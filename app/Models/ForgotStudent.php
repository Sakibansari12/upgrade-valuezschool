<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ForgotStudent extends Model
{
    use HasFactory, SoftDeletes;
    protected  $table = 'forgot_students';

    protected $fillable = [
       'name',
       'last_name',
       'teacher_name',
       'school_name',
       'forgot_student_status',
       'phone_number',
       'otp',
       'email',
       'username',
       'last_name',
       'studenttype',
       'student_status',
       'password',
       'grade',
       'otp_verified_till',
       'school_id',
       'otp_verified_at',
       'view_password',
       'status',
    ];
    public function studentschool(){
        return $this->belongsTo('App\Models\School', 'school_id');
    }
    public function studentgrade(){
        return $this->belongsTo('App\Models\Program', 'grade');
    }
}
