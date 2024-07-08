<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;
    protected $table = 'school';
    protected $primaryKey = 'id';

    public function teacher()
    {
        return $this->hasMany(User::class, 'school_id', 'id');
    }
    public function student()
    {
        return $this->hasMany(Student::class, 'school_id', 'id');
    }
    public function payment_links()
    {
        return $this->hasMany(SchoolPayment::class, 'school_id', 'id');
    }
    protected $fillable = [
        'student_status_view',
        'school_student_status',
        'school_username',
        'licence',
    ];
   
}
