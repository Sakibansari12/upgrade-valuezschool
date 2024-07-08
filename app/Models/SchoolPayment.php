<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolPayment extends Model
{
    use HasFactory, SoftDeletes;

    protected  $table = 'schools_payments';

    protected $fillable = [
       'orderid',
       'phone_number',
       'milestone',
       'payment_status',
       'payment_amount',
       'upi_id',
       'payment_sucessfull_time',
       'number_of_subscription',
       'link_expiry_at',
       'upload_invoice',
       'description',
       'school_id',
       'school_name_billing',
       'payment_link_id',
       'email_template',
       'payment_url',
       'email',
       'email_sent',
       'phone_number_sent',
       'email_sent_at',
       'sms_sent_at',
       'link_created_at',
       'link_updated_at',
       'classrooms_subscriptions_id',
       'payment_failed_info',
    ];
    /* public function school()
    {
        return $this->belongsTo(School::class, 'school_id', 'id');
    } */
}
