<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aimodules extends Model
{
    use HasFactory;
    protected  $table = 'aimodules';
    public function program()
    {
        return $this->hasOne(Program::class, 'id', 'grade_id');
    }
}

