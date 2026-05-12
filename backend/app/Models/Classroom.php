<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'major',
        'homeroom_teacher_id',
        'academic_year',
    ];

    public function homeroomTeacher()
    {
        return $this->belongsTo(Teacher::class, 'homeroom_teacher_id');
    }

    public function students()
    {
        // Currently Student model has 'class' as string. 
        // In the future, we should migrate Student to have 'classroom_id'.
        // For now, we'll keep the current string relation if needed or plan migration.
        return $this->hasMany(Student::class, 'class', 'name');
    }
}
