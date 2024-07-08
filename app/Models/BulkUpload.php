<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulkUpload extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = 'bulk_uploads';
    protected  $fillable = ['school_id','bulk_upload_file','status','deleted_at'];
}
