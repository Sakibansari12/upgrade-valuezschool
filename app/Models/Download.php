<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Download extends Model
{
    use HasFactory, SoftDeletes;

    protected  $table = 'downloads';

    protected $fillable = [
       'description',
       'image',
       'school_id',
       'status',
       'deleted_at',
    ];
}
