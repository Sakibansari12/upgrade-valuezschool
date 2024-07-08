<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPayment extends Model
{
    use HasFactory;
    protected $table = 'student_payments';
    protected $fillable = [
        'school_id',
        'payment_failed_info',
        'amount',
        'upi_id',
        'payment_sucessfull_time',
        'invoice_upload',
        'start_date_sub',
        'start_end_sub',
        'duration_package',
        'discount_percentage',
        'package_deal_code',
    ];
}
