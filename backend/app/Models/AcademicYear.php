<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicYear extends Model
{
    /** @use HasFactory<Database\Factories\AcademicYearFactory> */
    use HasFactory;

    protected $table = 'academic_years';

    protected $fillable = [
        'name',
        'semester',
        'is_active',
    ];

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class, 'academic_year', 'name');
    }
}