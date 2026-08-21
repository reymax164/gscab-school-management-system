<?php

namespace App\Models\Enrollments;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class EnrollmentPayment extends Model
{
    protected $fillable = [
        'enrollment_id', 'payment_scheme', 'tuition_fee', 'misc_fee', 
        'discount_amount', 'total_amount', 'payment_status', 'or_number', 'verified_by'
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
