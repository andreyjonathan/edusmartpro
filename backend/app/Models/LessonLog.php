<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonLog extends Model
{
    /** @use HasFactory<Database\Factories\LessonLogFactory> */
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'subject_id',
        'class_name',
        'date',
        'topic',
        'notes',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}