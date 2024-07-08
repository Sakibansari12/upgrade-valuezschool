<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;
    protected  $table = 'email_templates';
    protected $fillable = [
        'template',
        'module',
        'category',
        'description',
        'type',
        'admin_body',
        'school_body',
        'student_body',
        'subject',
        'school_id',
        'student_id',
        'admin',
        'activated',
        'sequence',
    ];

    public function getVariablesAttribute()
    {
        switch ($this->category) {
            case 'school':
                return [
                    "{{SCHOOL_NAME}}",
                    "{{SIGNATURE_LINK}}",
                    "{{SCHOOL_PAYMENT_ID}}",
                    "{{COMPANY_NAME}}",
                    "{{NUMBER_OF_SUBSCRIPTION}}",
                ];
                break;
                case 'support':
                    return [
                        "{{SCHOOL_NAME}}",
                        "{{TEACHER_NAME}}",
                        "{{TEACHER_EMAIL}}",
                        "{{TEACHER_PHONE}}",
                        "{{TEACHER_DESCRIPTION}}",
                    ];
                    break; 
                case 'support':
                    return [
                        "{{SCHOOL_NAME}}",
                        "{{TEACHER_NAME}}",
                        "{{TEACHER_DESCRIPTION}}",
                    ];
                    break;   
            }
    }
}
