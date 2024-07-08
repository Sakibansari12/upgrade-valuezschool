<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CitiesModel extends Model
{
    use HasFactory;
    protected $table = 'cities';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function state()
    {
        return $this->hasOne(StateModel::class, 'id', 'state_id');
    }
}
