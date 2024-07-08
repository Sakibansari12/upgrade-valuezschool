<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory;
    protected  $table = 'students';

    protected $fillable = [
        'id',
       'name',
       'last_name',
       'phone_number',
       'otp',
       'email',
       'username',
       'last_name',
       'studenttype',
       'student_status',
       'student_image',
       'password',
       'grade',
       'otp_verified_till',
       'school_id',
       'otp_verified_at',
       'view_password',
       'status',
       'student_payment_sucess',
       'student_noty_subcrition',
       'section',
       'start_date',
       'end_date'
    ];
    protected $casts = [
        'student_noty_subcrition' => 'json',
    ];
    public function studentgrade(){
        return $this->belongsTo('App\Models\Program', 'grade');
    }
    public function studentschool(){
        return $this->belongsTo('App\Models\School', 'id');
    }
    
}

