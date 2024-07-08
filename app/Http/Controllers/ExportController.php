<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Export;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Mail;
class ExportController extends Controller
{
    public function ExportUser(Request $request) {
        $validator = Validator::make($request->all(), [
            'school' => 'required|exists:school,id',
            'all_teacher_id' => 'nullable',
        ]);
         
        if(!empty($request->all_teacher_id)) {
        $teacherIds = explode(',', $request->all_teacher_id);
        $teacherArr = DB::table('users')
            ->where('users.usertype', 'teacher')
            ->where('users.school_id', $request->school)
            ->whereIn('users.id', $teacherIds)
            ->select([
                'users.name',
                'users.grade',
                'users.section',
                'users.username',
                'users.email',
                'users.view_pass',
                'master_class.class_name',
            ])
            ->leftJoin('master_class', 'master_class.id', 'users.grade')
            ->get();
        $param['header'] = [
            'Grade',
            'Section',
            'Username',
            'Password',
            'Teacher Name',
            //'Email',
        ];
        foreach ($teacherArr as $key => $value) {
            $param['rows'][$key] =
                [
                    'Grade' => isset($teacherArr[$key]->class_name) ? $teacherArr[$key]->class_name : null,
                    'Section' => isset($teacherArr[$key]->section) ? $teacherArr[$key]->section : null,
                    'Username' => isset($teacherArr[$key]->username) ? $teacherArr[$key]->username : null,
                    'Password : ' => isset($teacherArr[$key]->view_pass) ? $teacherArr[$key]->view_pass : null,
                    'Teacher Name' => isset($teacherArr[$key]->name) ? $teacherArr[$key]->name : null,
                ];
        }
       
       $today = Carbon::now()->format('Y-m-d');
       $fileName = "Classroom_credentials_{$today}.xlsx";
       return Excel::download(new Export($param), $fileName);
      }else{
        return back()->withErrors(['Please select at least one checkbox before exporting.']);
    }
    }



    public function ExportStudent(Request $request) {
        $validator = Validator::make($request->all(), [
            'school_id' => 'required|exists:school,id',
            'all_teacher_id' => 'nullable',
        ]);
         
        if(!empty($request->all_teacher_id)) {
                $teacherIds = explode(',', $request->all_teacher_id);
                
                $teacherArr = DB::table('students')
                    ->where('school_id', $request->school_id)
                    ->whereIn('id', $teacherIds)
                    ->select([
                        'name',
                        'last_name',
                        'username',
                        'view_password',
                    ]) ->get();
                    
                $param['header'] = [
                    'Name',
                    'Last Name',
                    'UserName',
                    'View Password',
                ];
                 
                    foreach ($teacherArr as $key => $value) {
                        $param['rows'][$key] =
                            [
                                'Name' => isset($teacherArr[$key]->name) ? $teacherArr[$key]->name : null,
                                'Last Name : ' => isset($teacherArr[$key]->last_name) ? $teacherArr[$key]->last_name : null,
                                'UserName : ' => isset($teacherArr[$key]->username) ? $teacherArr[$key]->username : null,
                                'View Password : ' => isset($teacherArr[$key]->view_password) ? $teacherArr[$key]->view_password : null,
                            ];
                    }
                    return Excel::download(new Export($param), 'student.xlsx');
                }else{
                    return back()->withErrors(['Please select at least one checkbox before exporting.']);
                }
    }
    public function SchoolTeacher(Request $request) {
        $query = $request->input('query');

        $schooldata = DB::table('school')
            ->where('status', 1)
            ->where('is_deleted', 0)
            ->when($query, function ($queryBuilder) use ($query) {
                return $queryBuilder->where('school_name', 'LIKE', '%' . $query . '%');
            })
            ->select(['id as school_id','school_name as common_name'])
            ->get();

        $teacherdata = DB::table('users')
            ->where('usertype', 'teacher')
            ->where('is_deleted', 0)
            ->where('school_id', '!=', 0)
            ->when($query, function ($queryBuilder) use ($query) {
                return $queryBuilder->where('name', 'LIKE', '%' . $query . '%');
            })
            ->select(['school_id as school_id','name as common_name'])
            ->get();
        $school_teacher_Data = $schooldata->merge($teacherdata);

        return response()->json([
            'status' => true,
            'school_teacher_Data' => $school_teacher_Data,
            'message' => "Data retrieved successfully",
        ]);


    }

    public function ExportSchoolPayment(Request $request)
    {
      $validator = Validator::make($request->all(),[
        'school_id' => 'required|exists:school,id',
      ]);

      if(!empty($request->school_id)){
            $mainQuery = DB::table('schools_payments')
                ->where('school_id', $request->school_id)
                ->whereNull('deleted_at')
                ->select([
                    'schools_payments.id',
                    'schools_payments.orderid',
                    'schools_payments.school_name_billing',
                    'schools_payments.payment_amount',
                    'schools_payments.link_expiry_at',
                    'schools_payments.link_created_at',
                    'schools_payments.email_sent_at',
                    'schools_payments.email_sent_at as payment_due',
                    'schools_payments.sms_sent_at',
                    'schools_payments.payment_made_at',
                    'schools_payments.payment_status',
                    'schools_payments.payment_link_id',
                    'schools_payments.description',
                    'schools_payments.school_id',
                    'schools_payments.email',
                    'schools_payments.payment_url',
                    'schools_payments.phone_number',
                    'school.school_name As school_name_text'
                ]);
                $mainQuery->leftJoin('school', 'school.id', 'schools_payments.school_id');
                $schoolPayment = $mainQuery->get()->map(function ($row) {
                    $paymentDueDate = isset($row->payment_due) ? $row->payment_due : '';
                    $currentDate = Carbon::now();
                    $row->payment_status =  $row->payment_status == 1 ? 'Activate' : 'Deactivate';
                    $row->payment_due = $currentDate->diffInDays($paymentDueDate);
                    return $row;
                    })->all();
            $param['header'] = [
                'School Name',
                'Amount',
                'School Name Billing',
                'Description',
                'Payment Link',
                'Link_Create_at',
                'Link_Expiry_at',
                'Payment_Made_at',
                'Email_Sent_at',
                'Payment Due',
                'Status',
            ];
            foreach ($schoolPayment as $key => $value) {
                $param['rows'][$key] =
                    [
                        'School Name' => isset($schoolPayment[$key]->school_name_text) ? $schoolPayment[$key]->school_name_text : null,
                        'Amount' => isset($schoolPayment[$key]->payment_amount) ? $schoolPayment[$key]->payment_amount : null,
                        'School Name Billing : ' => isset($schoolPayment[$key]->school_name_billing) ? $schoolPayment[$key]->school_name_billing : null,
                        'Description : ' => isset($schoolPayment[$key]->description) ? $schoolPayment[$key]->description : null,
                        'Payment Link : ' => isset($schoolPayment[$key]->payment_url) ? $schoolPayment[$key]->payment_url : null,
                        'Link_Create_at : ' => isset($schoolPayment[$key]->link_created_at) ? $schoolPayment[$key]->link_created_at : null,
                        'Link_Expiry_at : ' => isset($schoolPayment[$key]->link_expiry_at) ? $schoolPayment[$key]->link_expiry_at : null,
                        'Payment_Made_at : ' => isset($schoolPayment[$key]->payment_made_at) ? $schoolPayment[$key]->payment_made_at : null,
                        'Email_Sent_at : ' => isset($schoolPayment[$key]->email_sent_at) ? $schoolPayment[$key]->email_sent_at : null,
                        'Payment Due : ' => isset($schoolPayment[$key]->payment_due) ? ($schoolPayment[$key]->payment_due . 'D') : null,
                        'Status : ' => isset($schoolPayment[$key]->payment_status) ? $schoolPayment[$key]->payment_status : null,
                    ];
            }
            return Excel::download(new Export($param), 'school-payment.xlsx');
      }else{
        return back()->withErrors(['Please select at least one checkbox before exporting.']);
    }
}
    



public function ExportSRSchoolPayment(Request $request)
    {
      $validator = Validator::make($request->all(),[
        'subscription_id' => 'required|exists:classrooms_subscriptions,id',
      ]);

      if(!empty($request->subscription_id)){
            $mainQuery = DB::table('schools_payments')
                ->where('classrooms_subscriptions_id', $request->subscription_id)
                ->whereNull('deleted_at')
                ->select([
                    'schools_payments.id',
                    'schools_payments.orderid',
                    'schools_payments.school_name_billing',
                    'schools_payments.payment_amount',
                    'schools_payments.link_expiry_at',
                    'schools_payments.link_created_at',
                    'schools_payments.email_sent_at',
                    'schools_payments.email_sent_at as payment_due',
                    'schools_payments.sms_sent_at',
                    'schools_payments.payment_made_at',
                    'schools_payments.payment_status',
                    'schools_payments.payment_link_id',
                    'schools_payments.description',
                    'schools_payments.school_id',
                    'schools_payments.email',
                    'schools_payments.payment_url',
                    'schools_payments.phone_number',
                    'school.school_name As school_name_text'
                ]);
                $mainQuery->leftJoin('school', 'school.id', 'schools_payments.school_id');
                $schoolPayment = $mainQuery->get()->map(function ($row) {
                    $paymentDueDate = isset($row->payment_due) ? $row->payment_due : '';
                    $currentDate = Carbon::now();
                    $row->payment_status =  $row->payment_status == 1 ? 'Activate' : 'Deactivate';
                    $row->payment_due = $currentDate->diffInDays($paymentDueDate);
                    return $row;
                    })->all();
            $param['header'] = [
                'School Name',
                'Amount',
                'School Name Billing',
                'Description',
                'Payment Link',
                'Link_Create_at',
                'Link_Expiry_at',
                'Payment_Made_at',
                'Email_Sent_at',
                'Payment Due',
                'Status',
            ];
            foreach ($schoolPayment as $key => $value) {
                $param['rows'][$key] =
                    [
                        'School Name' => isset($schoolPayment[$key]->school_name_text) ? $schoolPayment[$key]->school_name_text : null,
                        'Amount' => isset($schoolPayment[$key]->payment_amount) ? $schoolPayment[$key]->payment_amount : null,
                        'School Name Billing : ' => isset($schoolPayment[$key]->school_name_billing) ? $schoolPayment[$key]->school_name_billing : null,
                        'Description : ' => isset($schoolPayment[$key]->description) ? $schoolPayment[$key]->description : null,
                        'Payment Link : ' => isset($schoolPayment[$key]->payment_url) ? $schoolPayment[$key]->payment_url : null,
                        'Link_Create_at : ' => isset($schoolPayment[$key]->link_created_at) ? $schoolPayment[$key]->link_created_at : null,
                        'Link_Expiry_at : ' => isset($schoolPayment[$key]->link_expiry_at) ? $schoolPayment[$key]->link_expiry_at : null,
                        'Payment_Made_at : ' => isset($schoolPayment[$key]->payment_made_at) ? $schoolPayment[$key]->payment_made_at : null,
                        'Email_Sent_at : ' => isset($schoolPayment[$key]->email_sent_at) ? $schoolPayment[$key]->email_sent_at : null,
                        'Payment Due : ' => isset($schoolPayment[$key]->payment_due) ? ($schoolPayment[$key]->payment_due . 'D') : null,
                        'Status : ' => isset($schoolPayment[$key]->payment_status) ? $schoolPayment[$key]->payment_status : null,
                    ];
            }
            return Excel::download(new Export($param), 'subscription-request-payment.xlsx');
      }else{
        return back()->withErrors(['Please select at least one checkbox before exporting.']);
    }
}


public function ExportSubscriptionRequest(Request $request)
    {
      $validator = Validator::make($request->all(),[
        'subscription_id' => 'required|exists:classrooms_subscriptions,id',
      ]);

      if(!empty($request->subscription_id)){
            $mainQuery = DB::table('classrooms_subscriptions')
                ->where('classrooms_subscriptions.id', $request->subscription_id)
                ->whereNull('classrooms_subscriptions.deleted_at')
                ->select([
                    'classrooms_subscriptions.id',
                    'classrooms_subscriptions.classrooms_subscription',
                    'classrooms_subscriptions.classrooms_subscription as subscription_data',
                    'classrooms_subscriptions.school_id',
                    'classrooms_subscriptions.subscriptions_payment_status',
                    'classrooms_subscriptions.package_row_count',
                    'classrooms_subscriptions.sr_number',
                    'classrooms_subscriptions.change_classroom_status',
                    'classrooms_subscriptions.change_payment_status',
                    'classrooms_subscriptions.school_admin_id',
                    'classrooms_subscriptions.share_login_credential',
                    'classrooms_subscriptions.subscription_status',
                    'classrooms_subscriptions.notify_subscription_status',
                    'classrooms_subscriptions.deleted_at',
                    'classrooms_subscriptions.created_at',
                    'classrooms_subscriptions.updated_at',
                    'school.school_name As school_name_text'
                ]);
                $mainQuery->leftJoin('school', 'school.id', 'classrooms_subscriptions.school_id');
                $schoolPayment = $mainQuery->get()->map(function ($row) {
                    $row->subscription_data = isset($row->classrooms_subscription) ? json_decode($row->classrooms_subscription) : '';
                    return $row;
                    })->first();
                  //  dd($schoolPayment->subscriptions_payment_status);
            $param['header'] = [
                'School Name',
                'Teacher Name',
                'Grade',
                'Section',
                //'Status',
            ];
            foreach ($schoolPayment->subscription_data->subscription as $key => $value) {
               // dd($value->teacher_name);
                $param['rows'][$key] =
                    [
                        'School Name' => isset($schoolPayment->school_name_text) ? $schoolPayment->school_name_text : null,
                        'Teacher Name' => isset($value->teacher_name) ? $value->teacher_name : null,
                        'Grade' => isset($value->garde) ? $value->garde : null,
                        'Section' => isset($value->section) ? $value->section : null,
                       // 'Status' => isset($schoolPayment->subscriptions_payment_status) && $schoolPayment->subscriptions_payment_status == 1 ? 'Complete' : 'Pending'
                    ]; 
            }
            return Excel::download(new Export($param), 'subscription-request.xlsx');
      }else{
        return back()->withErrors(['Please select at least one checkbox before exporting.']);
    }
}
    
     public function ExportAllSchoolPayment(Request $request)
      {
        $mainQuery = DB::table('schools_payments')
            ->whereNull('deleted_at')
            ->select([
                'schools_payments.id',
                'schools_payments.orderid',
                'schools_payments.school_name_billing',
                'schools_payments.payment_amount',
                'schools_payments.link_expiry_at',
                'schools_payments.link_created_at',
                'schools_payments.email_sent_at',
                'schools_payments.email_sent_at as payment_due',
                'schools_payments.sms_sent_at',
                'schools_payments.payment_made_at',
                'schools_payments.payment_status',
                'schools_payments.payment_link_id',
                'schools_payments.description',
                'schools_payments.school_id',
                'schools_payments.email',
                'schools_payments.payment_url',
                'schools_payments.phone_number',
                'school.school_name As school_name_text'
            ]);
            $mainQuery->leftJoin('school', 'school.id', 'schools_payments.school_id');
            $schoolPayment = $mainQuery->get()->map(function ($row) {
                $paymentDueDate = isset($row->payment_due) ? $row->payment_due : '';
                $currentDate = Carbon::now();
                $row->payment_status =  $row->payment_status == 1 ? 'Activate' : 'Deactivate';
                $row->payment_due = $currentDate->diffInDays($paymentDueDate);
                return $row;
                })->all();
            $param['header'] = [
                'School Name',
                'Amount',
                'School Name Billing',
                'Description',
                'Payment Link',
                'Link_Create_at',
                'Link_Expiry_at',
                'Payment_Made_at',
                'Email_Sent_at',
                'Payment Due',
                'Status',
            ];
            foreach ($schoolPayment as $key => $value) {
                $param['rows'][$key] =
                    [
                        'School Name' => isset($schoolPayment[$key]->school_name_text) ? $schoolPayment[$key]->school_name_text : null,
                        'Amount' => isset($schoolPayment[$key]->payment_amount) ? $schoolPayment[$key]->payment_amount : null,
                        'School Name Billing : ' => isset($schoolPayment[$key]->school_name_billing) ? $schoolPayment[$key]->school_name_billing : null,
                        'Description : ' => isset($schoolPayment[$key]->description) ? $schoolPayment[$key]->description : null,
                        'Payment Link : ' => isset($schoolPayment[$key]->payment_url) ? $schoolPayment[$key]->payment_url : null,
                        'Link_Create_at : ' => isset($schoolPayment[$key]->link_created_at) ? $schoolPayment[$key]->link_created_at : null,
                        'Link_Expiry_at : ' => isset($schoolPayment[$key]->link_expiry_at) ? $schoolPayment[$key]->link_expiry_at : null,
                        'Payment_Made_at : ' => isset($schoolPayment[$key]->payment_made_at) ? $schoolPayment[$key]->payment_made_at : null,
                        'Email_Sent_at : ' => isset($schoolPayment[$key]->email_sent_at) ? $schoolPayment[$key]->email_sent_at : null,
                        'Payment Due : ' => isset($schoolPayment[$key]->payment_due) ? ($schoolPayment[$key]->payment_due . 'D') : null,
                        'Status : ' => isset($schoolPayment[$key]->payment_status) ? $schoolPayment[$key]->payment_status : null,
                    ];
            }
            return Excel::download(new Export($param), 'all-school-payment.xlsx');
        }


        public function emailTeachers(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'school' => 'required|exists:school,id',
                'all_teacher_id' => 'nullable',
            ]);

            $user = Auth::user();
                if(!empty($request->all_teacher_id)) {
                    $teacherIds = explode(',', $request->all_teacher_id);
                    $teacherArr = DB::table('users')
                    ->where('users.usertype', 'teacher')
                    ->where('users.status', 1)
                    ->where('users.school_id', $request->school)
                    ->whereIn('users.id', $teacherIds)
                    ->select([
                        'users.name',
                        'users.email',
                        'users.view_pass',
                        'school.school_name',
                    ])
                    ->leftJoin('school', 'school.id', 'users.school_id')
                    ->get();
            $cc = 'support@valuezschool.com';
            $school = $request->school;
            foreach ($teacherArr as $value) {
                $details = [
                    'view' => 'emails.teacher-account-creation-email',
                    'subject' => 'Welcome! Your teacher account to access Valuez School 21st Century LMS has been created',
                    'title' => $value->name,
                    'school_name' => $value->school_name,
                    'email' => $value->email,
                    'pass' => $value->view_pass
                ];
                Mail::to($value->email)->cc($cc)->send(new \App\Mail\TestMail($details));
            }
            if($user->usertype == 'admin'){
                return redirect()->intended(route('school.teacher.list', compact('school')))->withSuccess('Email with user credentials sent to teacher');
            }else{
                return redirect()->intended(route('teacher.list', compact('school')))->withSuccess('Email with user credentials sent to teacher');
            }
            }else{
                return back()->withErrors(['Please select at least one checkbox before email sent to teacher.']);
            }
        }

    


}
