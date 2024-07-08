<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentOtp extends Model
{
    use HasFactory;

    protected $table = 'student_otps';

    protected $fillable = [
        'name',
        'last_name',
        'otp',
        'username',
        'phone_number',
        'otp_verified_at',
        'otp_verified_till',
    ];
}
