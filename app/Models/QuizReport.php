<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizReport extends Model
{
    use HasFactory;
    protected $table = 'quiz_report';

    public function getuser(){
        return $this->hasOne(User::class,'id', 'user_id');
    }

    public function getquiz(){
        return $this->hasMany(Quiz::class,'quiz_title_id', 'quiz_title_id');
    }
}
