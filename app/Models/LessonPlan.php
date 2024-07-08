<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonPlan extends Model
{
    use HasFactory;
    protected $table = 'lesson_plan';
    protected $primaryKey = 'id';
    protected $guarded = [];

    protected $fillable = [
        'view_assessment',
        'title',
        'video_url',
        'lesson_no',
        'class_id',
        'course_id',
        'lesson_desc',
        'status',
        'lesson_image',
        'video_info_url',
        'is_demo',
        'myra_video_url',
        'conversation',
     ];

    public function program()
    {
        return $this->hasOne(Program::class, 'id', 'class_id');
    }

    public function course()
    {
        return $this->hasOne(Course::class, 'id', 'course_id');
    }
}
