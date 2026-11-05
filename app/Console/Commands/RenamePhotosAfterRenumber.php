<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

/**
 * Make student photos follow their student after the index numbers were renumbered.
 *
 * Photos live in public/assets/student_photos/<student_id>.jpg, so when a student_id changes
 * (e.g. IT-004-TH-021-2026 -> IT-004-TH-020-2026) the file must be renamed too.
 *
 *   php artisan photos:follow-renumber            (preview only - changes nothing)
 *   php artisan photos:follow-renumber --run      (rename the files)
 *
 * HOW IT KNOWS OLD -> NEW
 *   The backup table (default student_registrations_bak_it004) holds the registrations as they
 *   were BEFORE the renumbering. Rows are matched to student_registrations by the numeric `id`,
 *   which never changed, so old student_id -> new student_id is exact (no guessing).
 *
 * SAFETY
 *   - Two-pass rename (old -> __tmp__<new> -> new), so 021 -> 020 can never overwrite 020.
 *   - A leftover photo that already sits on a NEW name but belongs to a deleted student
 *     (e.g. the old IT-004-TH-019-2026.jpg) is MOVED to storage/app/orphaned-photos-it004/,
 *     never overwritten or deleted.
 *   - Renamed files are touch()ed and the exam-card thumbnail cache for the affected IDs is
 *     cleared, so no card / browser keeps showing the previous student's picture.
 *   - A manifest CSV of every move is written to storage/app/.
 */
class RenamePhotosAfterRenumber extends Command
{
    protected $signature = 'photos:follow-renumber
        {--backup-table=student_registrations_bak_it004 : Table with the registrations as they were BEFORE renumbering}
        {--run : Apply the renames (default is preview only)}';

    protected $description = 'Rename student photo files so they follow students whose student_id was renumbered';

    public function handle(): int
    {
        $apply = (bool) $this->option('run');
        $bak = (string) $this->option('backup-table');

        if (!preg_match('/^[A-Za-z0-9_]+$/', $bak) || !Schema::hasTable($bak)) {
            $this->error("Backup table '$bak' was not found. It must hold student_registrations as it was before the renumbering.");

            return self::FAILURE;
        }

        $dir = public_path('assets/student_photos');
        if (!is_dir($dir)) {
            $this->error("Photo folder not found: $dir");

            return self::FAILURE;
        }

        // old student_id -> new student_id, matched on the unchanged numeric id
        $pairs = DB::table($bak . ' as b')
            ->join('student_registrations as r', 'r.id', '=', 'b.id')
            ->whereColumn('b.student_id', '<>', 'r.student_id')
            ->orderBy('r.student_id')
            ->get(['b.student_id as old_id', 'r.student_id as new_id'])
            ->map(fn ($p) => ['old' => (string) $p->old_id, 'new' => (string) $p->new_id])
            ->all();

        $this->info('Students whose student_id changed: ' . count($pairs));

        if (!$pairs) {
            $this->line('Nothing to do.');

            return self::SUCCESS;
        }

        $path = fn (string $id) => $dir . '/' . basename($id) . '.jpg';
        $tmpPath = fn (string $id) => $dir . '/__tmp__' . basename($id) . '.jpg';

        $oldIds = array_column($pairs, 'old');

        $moves = [];       // photo exists under the old id -> will be renamed
        $noPhoto = [];     // no photo under the old id (nothing to rename)
        $quarantine = [];  // stale file sitting on a NEW name, owned by a deleted student

        foreach ($pairs as $p) {
            if (is_file($path($p['old']))) {
                $moves[] = $p;
            } else {
                $noPhoto[] = $p;
            }

            if (is_file($path($p['new'])) && !in_array($p['new'], $oldIds, true)) {
                $quarantine[] = $p['new'];
            }
        }

        $this->table(
            ['Old ID', 'New ID', 'Photo on server'],
            array_map(fn ($p) => [$p['old'], $p['new'], is_file($path($p['old'])) ? 'yes - will rename' : 'NO photo'], $pairs)
        );

        $this->info('Photos to rename: ' . count($moves));
        if ($noPhoto) {
            $this->warn('No photo file under the old ID (nothing to rename): ' . count($noPhoto));
        }
        if ($quarantine) {
            $this->warn('Stale photos of deleted students sitting on a new name (will be moved aside, not deleted): '
                . implode(', ', $quarantine));
        }

        if (!$apply) {
            $this->newLine();
            $this->warn('Preview only - run with --run to rename the files.');

            return self::SUCCESS;
        }

        // 0) manifest first, so the run can always be traced / undone by hand
        $stamp = date('Ymd-His');
        $manifest = storage_path("app/photo-renumber-manifest-$stamp.csv");
        $fh = fopen($manifest, 'w');
        fputcsv($fh, ['action', 'from', 'to']);
        foreach ($quarantine as $id) {
            fputcsv($fh, ['quarantine', $id . '.jpg', 'storage/app/orphaned-photos-it004/']);
        }
        foreach ($moves as $p) {
            fputcsv($fh, ['rename', $p['old'] . '.jpg', $p['new'] . '.jpg']);
        }
        fclose($fh);

        // 1) move stale leftovers out of the way (kept, never overwritten)
        if ($quarantine) {
            $qdir = storage_path('app/orphaned-photos-it004');
            File::ensureDirectoryExists($qdir);
            foreach ($quarantine as $id) {
                File::move($path($id), $qdir . '/' . basename($id) . '-' . $stamp . '.jpg');
            }
            $this->info('Moved aside ' . count($quarantine) . ' stale photo(s) to ' . $qdir);
        }

        // 2) pass 1: old -> temporary name (rolls back if anything fails)
        $done = [];
        foreach ($moves as $p) {
            if (!@rename($path($p['old']), $tmpPath($p['new']))) {
                $this->error('Could not rename ' . $p['old'] . '.jpg - rolling back this run. Check folder permissions.');
                foreach (array_reverse($done) as $d) {
                    @rename($tmpPath($d['new']), $path($d['old']));
                }

                return self::FAILURE;
            }
            $done[] = $p;
        }

        // 3) pass 2: temporary name -> final name
        $failed = 0;
        foreach ($moves as $p) {
            if (@rename($tmpPath($p['new']), $path($p['new']))) {
                @touch($path($p['new'])); // new mtime => caches and ?v= URLs refresh
            } else {
                $failed++;
                $this->error('Left as __tmp__' . basename($p['new']) . '.jpg (rename to final name failed)');
            }
        }

        // 4) clear the exam-card thumbnail cache for every affected ID
        $cleared = 0;
        foreach (array_merge($oldIds, array_column($pairs, 'new'), $quarantine) as $id) {
            $cache = storage_path('app/exam-card-photos/' . preg_replace('/[^A-Za-z0-9_.-]/', '_', 'student-' . $id) . '.jpg');
            if (is_file($cache) && @unlink($cache)) {
                $cleared++;
            }
        }

        $this->newLine();
        $this->info('Renamed ' . (count($moves) - $failed) . ' photo(s). Cleared ' . $cleared . ' cached thumbnail(s).');
        $this->line("Manifest: $manifest");

        return $failed ? self::FAILURE : self::SUCCESS;
    }
}
