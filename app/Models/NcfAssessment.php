<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NcfAssessment extends Model
{
    use HasFactory;

    protected $table = 'ncf_assessments'; 
    protected $fillable = [
        'title',
        'description',
        'ncf_assessment_image',
        'ncf_assessment_file',
        'status',
     ];
}
