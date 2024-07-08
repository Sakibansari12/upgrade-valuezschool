<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TestPayments extends Model
{
    use HasFactory;
    protected $table = 'test_payments';
    protected $fillable = [
        'orderid',
        'payment_status',
        'payment_amount',
        'payment_link_id',
        'payment_url',
        'link_created_at',
        'phone_number',
        'email',
        'otp',
        'chat_gpt_status',
        'dally_status',
     ];
}
