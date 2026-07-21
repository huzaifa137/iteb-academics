<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddStatusToRegistrationPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('registration_periods') && !Schema::hasColumn('registration_periods', 'status')) {
            Schema::table('registration_periods', function (Blueprint $table) {
                // active   -> currently open period (only one at a time)
                // closed   -> was active before, now closed but kept for history / re-activation
                // archived -> older / superseded period, hidden from the default list
                $table->string('status', 20)->default('closed')->after('is_active');
            });

            // Backfill existing rows so nothing disappears from the list
            DB::table('registration_periods')->where('is_active', true)->update(['status' => 'active']);
            DB::table('registration_periods')->where('is_active', false)->update(['status' => 'closed']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('registration_periods') && Schema::hasColumn('registration_periods', 'status')) {
            Schema::table('registration_periods', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
}