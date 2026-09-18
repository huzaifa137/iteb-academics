<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BackfillSubmittedAtOnStudentRegistrations extends Migration
{
    /**
     * Run the migrations.
     *
     * submitted_at was added by a later migration with no backfill, so any
     * registration that was already submitted (status Pending Admin Approval /
     * Approved / Returned) before that migration ran was left with
     * submitted_at = NULL. That silently hid them from the school's
     * "Submitted Students" list and PDF export, which filtered on
     * whereNotNull('submitted_at'). Backfill it from updated_at (falling back
     * to created_at) so existing data is consistent going forward.
     */
    public function up(): void
    {
        if (!Schema::hasTable('student_registrations') || !Schema::hasColumn('student_registrations', 'submitted_at')) {
            return;
        }

        DB::table('student_registrations')
            ->whereIn('status', ['Pending Admin Approval', 'Approved', 'Returned'])
            ->whereNull('submitted_at')
            ->update([
                'submitted_at' => DB::raw('COALESCE(updated_at, created_at)'),
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * Intentionally a no-op: we don't want to re-null out submitted_at values
     * that legitimate submissions may have set since this ran.
     */
    public function down(): void
    {
        //
    }
}
