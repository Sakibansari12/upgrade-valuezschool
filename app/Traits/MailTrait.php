<?php

namespace App\Traits;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use ESolution\DBEncryption\Encrypter;
use Illuminate\Http\Request;
use App\Models\{StudentOtp,Student};

trait MailTrait
{
    protected static function mailSentSchool(int $payment_id, string $template)
    {

        $email_template = DB::table('email_templates')->where('module', $template)->where('activated', 1)->first();
        if(!$email_template){
            return false;
        }
        $payment = DB::table('schools_payments')->where('id', $payment_id)->first();
        $schooldata = DB::table('school')->where('id', $payment->school_id)->first();
        if(!$payment){
            return false;
        }
        $from = [
            "email" => config('app.mail_from_address'),
            "name" => $payment->school_name_billing
        ];
        $signaturelink = "<a href=\"{$payment->payment_url}\" target=\"_blank\"><strong>Pay here</strong></a>";
       $email_subject = str_replace("{{SCHOOL_NAME}}", $schooldata->school_name, $email_template->subject);
        $email_school_body = str_replace("{{SCHOOL_NAME}}", $schooldata->school_name, $email_template->school_body);
        $email_school_body = str_replace("{{NUMBER_OF_SUBSCRIPTION}}", $schooldata->licence, $email_school_body);
        $email_school_body = str_replace("{{SIGNATURE_LINK}}", $signaturelink, $email_school_body);
        $email_school_body = str_replace("{{COMPANY_NAME}}", $payment->school_name_billing, $email_school_body);
         
          $emailsent = json_decode($payment->email_sent);
        foreach ($emailsent as $value) {

            if (!empty($value)) {
                Mail::send([], [], function ($message) use ($value, $email_subject,  $email_school_body) {
                    $message->to($value)
                        //->from($from['email'], $from['name'])
                        ->subject($email_subject)
                        ->setBody($email_school_body, 'text/html');
                }); 
            }
          }
    }


    protected static function mailSentSRSchool(int $payment_id, string $template)
    {

        $email_template = DB::table('email_templates')->where('module', $template)->where('activated', 1)->first();
        if(!$email_template){
            return false;
        }
        $payment = DB::table('schools_payments')->where('id', $payment_id)->first();
        $schooldata = DB::table('school')->where('id', $payment->school_id)->first();
        if(!$payment){
            return false;
        }
        $from = [
            "email" => config('app.mail_from_address'),
            "name" => $payment->school_name_billing
        ];
        $signaturelink = "<a href=\"{$payment->payment_url}\" target=\"_blank\"><strong>Pay here</strong></a>";
       $email_subject = str_replace("{{SCHOOL_NAME}}", $schooldata->school_name, $email_template->subject);
        $email_school_body = str_replace("{{SCHOOL_NAME}}", $schooldata->school_name, $email_template->school_body);
        $email_school_body = str_replace("{{NUMBER_OF_SUBSCRIPTION}}", $schooldata->licence, $email_school_body);
        $email_school_body = str_replace("{{SIGNATURE_LINK}}", $signaturelink, $email_school_body);
        $email_school_body = str_replace("{{COMPANY_NAME}}", $payment->school_name_billing, $email_school_body);
         
          $emailsent = json_decode($payment->email_sent);
        foreach ($emailsent as $value) {

            if (!empty($value)) {
                Mail::send([], [], function ($message) use ($value, $email_subject,  $email_school_body) {
                    $message->to($value)
                        //->from($from['email'], $from['name'])
                        ->subject($email_subject)
                        ->setBody($email_school_body, 'text/html');
                }); 
            }
          }
    }




    protected static function mailSentSupport($support_id, string $template)
    {
        
        $email_template = DB::table('email_templates')->where('module', $template)->where('activated', 1)->first();
       /// dd($email_template);
        if(!$email_template){
            return false;
        }
        
        $support_data = DB::table('supports')->where('id', $support_id)->first();
        $user_data = DB::table('users')->where('id', $support_data->user_id)->first();
        $schooldata = DB::table('school')->where('id', $user_data->school_id)->first();


        $email_subject = str_replace("{{SCHOOL_NAME}}", $schooldata->school_name, $email_template->subject);
        $email_school_body = str_replace("{{SCHOOL_NAME}}", $schooldata->school_name, $email_template->school_body);
        $email_school_body = str_replace("{{TEACHER_NAME}}", $support_data->name, $email_school_body);
        $email_school_body = str_replace("{{TEACHER_EMAIL}}", $user_data->email, $email_school_body);
        $email_school_body = str_replace("{{TEACHER_PHONE}}", $support_data->phone_number, $email_school_body);
        $email_school_body = str_replace("{{TEACHER_DESCRIPTION}}", $support_data->query, $email_school_body);

        $support_email = 'support@valuezschool.com';
       // $support_email = 'sakib@thehutcafe.com';
        Mail::send([], [], function ($message) use ($support_email, $email_subject, $email_school_body) {
            $message->to($support_email)
                //->from($from['email'], $from['name'])
                ->subject($email_subject)
                ->setBody($email_school_body, 'text/html');
        }); 
    }    


    protected static function mailSentTeacherFeedback($feedback_id, string $template)
    {
        
        $email_template = DB::table('email_templates')->where('module', $template)->where('activated', 1)->first();
       /// dd($email_template);
        if(!$email_template){
            return false;
        }

        $feedback_data = DB::table('feedbacks')->where('id', $feedback_id)->first();

        $teachers_data = DB::table('users')->where('usertype', 'teacher')->where('id', $feedback_data->teacher_id)->first();

        $schooldata = DB::table('school')->where('id', $teachers_data->school_id)->first();


        $email_subject = str_replace("{{SCHOOL_NAME}}", $schooldata->school_name, $email_template->subject);
        $email_school_body = str_replace("{{SCHOOL_NAME}}", $schooldata->school_name, $email_template->school_body);
        $email_school_body = str_replace("{{TEACHER_NAME}}", $feedback_data->name, $email_school_body);
        //$email_school_body = str_replace("{{TEACHER_EMAIL}}", $$feedback_data->feedback_description, $email_school_body);
       // $email_school_body = str_replace("{{TEACHER_PHONE}}", $$feedback_data->phone_number, $email_school_body);
        $email_school_body = str_replace("{{TEACHER_DESCRIPTION}}", $feedback_data->feedback_description, $email_school_body);
        $support_email = 'support@valuezschool.com';
        Mail::send([], [], function ($message) use ($support_email, $email_subject, $email_school_body) {
            $message->to($support_email)
                //->from($from['email'], $from['name'])
                ->subject($email_subject)
                ->setBody($email_school_body, 'text/html');
        }); 
    }


    protected static function reset_student_otp(int $student_id)
    {
        $register_student = StudentOtp::where('id', $student_id)->first();

       
       // dd($register_student);
    }
}