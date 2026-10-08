<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * students_basic was created with varchar(45) for several text columns, while
 * student_registrations (and houses.House) allow much longer values. When a
 * registration was approved, any school name longer than 45 characters made the
 * INSERT fail with "Data too long for column 'House'" (22 students across 3
 * schools in the last "Approve all pending" run). Widen the columns that
 * approveRegistrationRecord() copies so they can hold whatever the registration
 * table can.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('students_basic', function (Blueprint $table) {
            $table->string('House', 255)->nullable()->change();
            $table->string('Student_Name', 255)->change();
            $table->string('Student_Name_AR', 255)->nullable()->change();
            $table->string('Birth_Place', 255)->nullable()->change();
            $table->string('Birth_Place_AR', 255)->nullable()->change();
            $table->string('District', 255)->nullable()->change();
            $table->string('StudentsNationality', 255)->nullable()->change();
        });
    }

    public function down(): void
    {
        // Intentionally left empty: shrinking these columns again could truncate
        // or reject data that was saved after they were widened.
    }
};
