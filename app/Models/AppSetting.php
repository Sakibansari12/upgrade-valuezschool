<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'field',
        'entity',
        'value',
        'autoload'
    ];
    protected $table = 'app_settings';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
}
