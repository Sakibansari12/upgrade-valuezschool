<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reports extends Model
{
    use HasFactory;
    protected $table = 'reports';
    protected $primaryKey = 'id';

    protected $guarded = [];

    public function lessonplan()
    {
        return $this->hasOne(LessonPlan::class, 'id', 'lesson_plan');
    }

    public function userinfo()
    {
        return $this->hasOne(User::class, 'id', 'userid');
    }
}
