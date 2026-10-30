<?php

namespace App\Console\Commands;

use App\Models\StudentRegistration;
use Illuminate\Console\Command;

/**
 * Audit / repair student registrations whose photo is missing or whose lock is stuck.
 *
 *   php artisan registrations:repair-photos            (preview only - changes nothing)
 *   php artisan registrations:repair-photos --run      (apply the safe repair)
 *
 * SAFE REPAIR (only with --run):
 *   locked + "Pending Photo Submission" / "Attached Image, Pending Submission"
 *      -> is_locked = false   (the school can edit, attach the photo and resubmit)
 *
 * REPORT ONLY (never changed here; the admin decides with the Unlock button):
 *   "Pending Admin Approval" / "Approved" students whose photo is not on the server.
 *   A CSV of them is written to storage/app/registrations-missing-photos.csv
 */
class RepairStudentRegistrationPhotos extends Command
{
    protected $signature = 'registrations:repair-photos {--run : Apply the repair (default is preview only)}';

    protected $description = 'Unlock stuck student registrations and report submitted/approved students with no photo on the server';

    public function handle(): int
    {
        $apply = (bool) $this->option('run');

        $stuckIds = [];
        $stuckRows = [];
        $missing = [];

        StudentRegistration::query()
            ->whereIn('status', [
                'Pending Photo Submission',
                'Attached Image, Pending Submission',
                'Pending Admin Approval',
                'Approved',
            ])
            ->select('id', 'school_id', 'student_id', 'student_name', 'status', 'is_locked')
            ->orderBy('id')
            ->chunkById(500, function ($rows) use (&$stuckIds, &$stuckRows, &$missing) {
                foreach ($rows as $r) {
                    $preSubmission = in_array($r->status, ['Pending Photo Submission', 'Attached Image, Pending Submission'], true);

                    if ($preSubmission && $r->is_locked) {
                        $stuckIds[] = $r->id;
                        $stuckRows[] = [$r->student_id, $r->student_name, $r->status];
                    }

                    if (!$preSubmission && !$this->photoExists($r->student_id)) {
                        $missing[] = [$r->school_id, $r->student_id, $r->student_name, $r->status, $r->is_locked ? 'locked' : 'unlocked'];
                    }
                }
            });

        $this->info('Locked but still before submission (dead-end records): ' . count($stuckIds));
        if ($stuckRows) {
            $this->table(['Student ID', 'Name', 'Status'], array_slice($stuckRows, 0, 100));
        }

        if ($apply && $stuckIds) {
            $done = 0;
            foreach (array_chunk($stuckIds, 500) as $chunk) {
                $done += StudentRegistration::whereIn('id', $chunk)->update(['is_locked' => false]);
            }
            $this->info("Unlocked $done record(s).");
        } elseif (!$apply && $stuckIds) {
            $this->warn('Preview only - run with --run to unlock them.');
        }

        $this->newLine();
        $this->info('Pending approval / Approved students with NO photo on the server: ' . count($missing));

        if ($missing) {
            $csv = storage_path('app/registrations-missing-photos.csv');
            $fh = fopen($csv, 'w');
            fputcsv($fh, ['school_id', 'student_id', 'name', 'status', 'lock']);
            foreach ($missing as $row) {
                fputcsv($fh, $row);
            }
            fclose($fh);

            $this->table(['School', 'Student ID', 'Name', 'Status', 'Lock'], array_slice($missing, 0, 50));
            $this->line("Full list written to $csv");
            $this->line('Use the Unlock button on the admin approval page for these so the school can re-attach the photo.');
        }

        return self::SUCCESS;
    }

    private function photoExists(?string $studentId): bool
    {
        if (!$studentId) {
            return false;
        }

        $file = public_path('assets/student_photos/' . basename($studentId) . '.jpg');

        return is_file($file) && filesize($file) > 0;
    }
}
