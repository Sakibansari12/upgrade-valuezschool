<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassroomSubscription extends Model
{
    use HasFactory;
    protected $table = 'classrooms_subscriptions';
    protected $fillable = [
        'subscription_status',
        'notify_subscription_status',
        'share_login_credential',
        'change_payment_status',
        'change_classroom_status',
        'package_row_count',
        'sr_number',
        'subscriptions_payment_status',
    ];
}
