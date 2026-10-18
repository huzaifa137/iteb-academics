<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_timetables', function (Blueprint $table) {
            $table->id();
            $table->string('year', 50);                       // matches academic_years.year_en
            $table->enum('category', ['idaad', 'thanawi']);   // ع = idaad, ث = thanawi
            $table->string('class_level', 20)->nullable();    // e.g. 3
            $table->string('subject_ar', 150);
            $table->string('paper_ar', 150)->nullable();
            $table->date('exam_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();

            $table->index(['year', 'category']);
            $table->index('exam_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_timetables');
    }
};
