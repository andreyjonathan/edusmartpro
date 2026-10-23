<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    /** @use HasFactory<Database\Factories\GradeFactory> */
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject_id',
        'semester',
        'academic_year',
        'task_score',
        'mid_exam_score',
        'final_exam_score',
        'final_score',
        'letter_grade',
        'notes',
    ];

    protected $casts = [
        'task_score' => 'float',
        'mid_exam_score' => 'float',
        'final_exam_score' => 'float',
        'final_score' => 'float',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}