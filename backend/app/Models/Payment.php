<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'payment_category_id',
        'amount',
        'payment_date',
        'payment_method',
        'month',
        'year',
        'status',
        'reference_number',
        'notes',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function category()
    {
        return $this->belongsTo(PaymentCategory::class, 'payment_category_id');
    }
}
