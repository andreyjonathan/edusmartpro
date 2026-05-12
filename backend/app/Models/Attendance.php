<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    /** @use HasFactory<\Database\Factories\AttendanceFactory> */
    use HasFactory;

    protected $fillable = [
        'attendant_id',
        'attendant_type',
        'date',
        'status',
        'notes',
    ];

    /**
     * Get the parent attendant model (Student, Teacher, or Employee).
     */
    public function attendant()
    {
        return $this->morphTo();
    }
}
