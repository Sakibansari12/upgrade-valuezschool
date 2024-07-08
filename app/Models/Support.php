<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    use HasFactory;
    protected $table = 'supports';
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'query',
        'support_reply',
        'support_reply_noty',
        'user_id',
        'status',
     ]; 
}
