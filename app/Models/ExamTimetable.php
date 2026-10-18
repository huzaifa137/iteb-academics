<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamTimetable extends Model
{
    protected $table = 'exam_timetables';

    protected $fillable = [
        'year',
        'category',
        'class_level',
        'subject_ar',
        'paper_ar',
        'exam_date',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'exam_date' => 'date',
    ];

    public const DAYS_AR = [
        0 => 'الأحد',
        1 => 'الاثنين',
        2 => 'الثلاثاء',
        3 => 'الأربعاء',
        4 => 'الخميس',
        5 => 'الجمعة',
        6 => 'السبت',
    ];

    public function getStartHmAttribute(): string
    {
        return substr((string) $this->start_time, 0, 5);
    }

    public function getEndHmAttribute(): string
    {
        return substr((string) $this->end_time, 0, 5);
    }

    public function getDayArAttribute(): string
    {
        return self::DAYS_AR[$this->exam_date->dayOfWeek] ?? '';
    }

    /** Shape used by the index page (edit modal) and the attendance sheet dropdown. */
    public function toPayload(): array
    {
        return [
            'id'          => $this->id,
            'year'        => $this->year,
            'category'    => $this->category,
            'class_level' => $this->class_level,
            'subject_ar'  => $this->subject_ar,
            'paper_ar'    => $this->paper_ar,
            'exam_date_iso' => $this->exam_date->format('Y-m-d'),
            'exam_date'   => $this->exam_date->format('d/m/Y'),
            'day_ar'      => $this->day_ar,
            'start_time'  => $this->start_hm,
            'end_time'    => $this->end_hm,
            'label'       => $this->exam_date->format('D d/m/Y') . ' · '
                . $this->start_hm . '–' . $this->end_hm . ' · '
                . $this->subject_ar . ($this->paper_ar ? ' (' . $this->paper_ar . ')' : '')
                . ($this->class_level ? ' [' . $this->class_level . ']' : ''),
        ];
    }
}
