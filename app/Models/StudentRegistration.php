<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRegistration extends Model
{
    use HasFactory;

    protected $table = 'student_registrations';

    protected $fillable = [
        'school_id',
        'category',
        'admission_year',
        'student_id',
        'student_name',
        'student_name_ar',
        'date_of_birth',
        'student_sex',
        'student_nationality',
        'birth_place',
        'birth_place_ar',
        'class',
        'section',
        'house',
        'district',
        'district_ar',
        'entry_date',
        'submitted_at',
        'is_locked',
        'status',
        'admin_remarks',
    ];

    protected $casts = [
        'date_of_birth' => 'date:Y-m-d',
        'entry_date' => 'date:Y-m-d',
        'submitted_at' => 'datetime',
        'is_locked' => 'boolean',
    ];

    /**
     * INVARIANT: a record that goes (back) to a pre-submission status can never stay locked.
     * A locked record with no photo is a dead end: the school cannot edit it and the admin
     * had no Unlock button. Mass ->update() calls bypass this hook, so they set is_locked themselves.
     */
    protected static function booted()
    {
        static::saving(function (StudentRegistration $reg) {
            if (
                $reg->isDirty('status')
                && in_array($reg->status, ['Pending Photo Submission', 'Attached Image, Pending Submission'], true)
            ) {
                $reg->is_locked = false;
            }
        });
    }

    public function school()
    {
        return $this->belongsTo(House::class, 'school_id', 'ID');
    }

    public function submissionDocument()
    {
        return $this->belongsTo(SubmissionDocument::class, 'submission_document_id');
    }
    /**
     * Check if this registration has a submission document.
     */
    public function hasSubmissionDocument()
    {
        return $this->submission_document_id !== null;
    }
}