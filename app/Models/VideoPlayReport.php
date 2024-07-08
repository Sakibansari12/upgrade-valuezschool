<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoPlayReport extends Model
{
    use HasFactory;
    protected $table = 'video_play_reports';

    protected $fillable = [
        'lesson_plan',
        'user_id',
        'school_id',
        'class_id',
        'video_play_status',
     ];
}
