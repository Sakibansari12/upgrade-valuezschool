<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HealtyMind extends Model
{
    use HasFactory, SoftDeletes;

    protected  $table = 'healty_minds';

    protected $fillable = [
       'title',
       'video_url',
       'red_guidance_desc',
       'healty_mind_image',
       'healty_mind_file',
       'healty_mind_upload_file',
       'status',
       'deleted_at',
    ];
}
