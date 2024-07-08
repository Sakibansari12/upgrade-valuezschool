<?php

namespace App\Http\Controllers;
use App\Models\{User, School, IpAddess, LogsModel, StudentOtp,Student,Program, NotificationModel, ForgotStudent,SchoolPayment,LessonPlan,Aimodules,StudentPayment};
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Cache;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use DB;
use PDF;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Http; // Import Laravel's HTTP client
class TestController extends Controller
{

    public function getAImodule(Request $request)
    {
        $mainQuery = DB::table('aimodules')
            ->select([
                'aimodules.content',
                'aimodules.id as aimodule_id',
                'aimodules.description',
                'aimodules.own_description',
                'aimodules.own_placeholder',
                'aimodules.hello_there_description',
                'aimodules.slider_video_data',
                'aimodules.vision_data',
                'aimodules.generate_hybrid',
            ]);
    
        $aimodules = $mainQuery->get()->map(function ($row) {
            $row->content = isset($row->content) ? json_decode($row->content) : null;
            $row->promptsdata = isset($row->content->prompts) ? $row->content->prompts : [];
            $row->activitiesdata = isset($row->content->activities) ? $row->content->activities : [];
            
            $row->generate_hybrid = isset($row->generate_hybrid) ? json_decode($row->generate_hybrid) : null;
            $row->hybridsdata = isset($row->generate_hybrid->hybrid) ? $row->generate_hybrid->hybrid : [];
    
            $row->slider_video_data = isset($row->slider_video_data) ? json_decode($row->slider_video_data) : null;
            $row->sliderData = isset($row->slider_video_data->slider) ? $row->slider_video_data->slider : []; 
    
            $row->vision_data = isset($row->vision_data) ? json_decode($row->vision_data) : null;
            $row->visionData = isset($row->vision_data->vision) ? $row->vision_data->vision : []; 
    
            $row->AddOwnactivitiesdata = isset($row->content->add_own_activities_prompts) ? $row->content->add_own_activities_prompts : [];
            return $row;
        });
    
        return $aimodules;
    }
    



    /* Milestone api data */

    public function getLessonPlan(Request $request)
    {
        $data_lesson_plan = DB::table('lesson_plan')
        ->select([
            'lesson_plan.id',
            'lesson_plan.title',
            'lesson_plan.lesson_image',
       ])->get();
        return $data_lesson_plan;
    }

    public function getLessonPlanView(Request $request)
    {
        /* $response = Http::get('https://learn.valuezschool.com/lesson_plan');
        $data_lesson_plan = $response->json();
        return view('ai-static.lesson_plan_view', compact('data_lesson_plan')); */
        $response = Http::get('https://learn.valuezschool.com/lesson_plan');
        $data_lesson_plan = $response->json();
        $desiredIds = [86, 99, 89, 117, 90, 238, 122, 84, 81, 94,
                       100, 79, 5, 82, 96, 97, 80, 3, 83, 8, 
                       91, 87, 88, 234, 4, 98, 231, 17, 230, 1, 
                       113, 236, 85, 12];
        $filtered_lesson_plan = array_filter($data_lesson_plan, function ($item) use ($desiredIds) {
            return in_array($item['id'], $desiredIds);
        });
        return view('ai-static.lesson_plan_view', ['data_lesson_plan' => $filtered_lesson_plan]);

    }

    public function sessionData(Request $request)
    {
       $data = session()->getId();
       dd($data);
    }
    public function testUser(Request $request)
    {
      $data_user = DB::table('users')->get();
      return $data_user;
    }
    public function testStudent(Request $request)
    {
      $data_student = DB::table('students')->get();
      return $data_student;
    }

    public function test(Request $request)
{
    
    $lastLoginLogs = LogsModel::where('userid', 32)
        ->where('action', 'login')
        ->whereNotNull('current_student_session_id')
        ->select('userid', 'action', 'current_student_session_id')
        ->latest()
        ->get();
    $sessionCount = 0;
    $nonEmptyStudentSessionCount = 0; // Counter for non-empty student_session_id
    $sessionDataList = [];
    foreach ($lastLoginLogs as $log) {
        $sessionId = $log->current_student_session_id;
        $sessionFilePath = storage_path("framework/sessions/{$sessionId}");
        if (!File::exists($sessionFilePath)) {
            continue;
        }
        $sessionFileContent = File::get($sessionFilePath);
        $sessionData = unserialize($sessionFileContent);
        $studentSessionId = $sessionData['student_session_id'] ?? null;

        if (!empty($studentSessionId)) {
            $nonEmptyStudentSessionCount++; 
        }

        $sessionCount++;
        $sessionDataList[] = [
            'session_id' => $sessionId,
            'student_session_id' => $studentSessionId,
            'full_session_data' => $sessionData,
        ];
    }

    return response()->json([
        'session_count' => $sessionCount,
        'non_empty_student_session_count' => $nonEmptyStudentSessionCount, // Include the count in the response
        'session_data' => $sessionDataList,
    ]);
}






    public function macaddress(Request $request)
    {
        //return Socialite::driver('google')->redirect();

        $healtymind_data = DB::table('healty_minds')->get();
        dd($healtymind_data);



      //  $macAddress = DB::table('schools_payments')->get();
      //  return $macAddress;
    }
    public function logintest(Request $request)
    {
        $mainQuery = DB::table('ipaddresss')
                    ->select([
                        'ipaddresss.id',
                        'ipaddresss.ip_address',
                        'ipaddresss.user_id',
                        'ipaddresss.browser',
                        'ipaddresss.created_at',
                        'ipaddresss.updated_at',
                        'users.name As user_name'
                    ])->groupBy('ipaddresss.ip_address');
                    $mainQuery->leftJoin('users', 'users.id', 'ipaddresss.user_id');
                 $userIpAddress = $mainQuery->get();
                 return $userIpAddress;
    }
   /*  public function generatePDF(Request $request)
    {
        $student_payment_id = $request->input('student_payment_id');
       $student_payment_data = DB::table('student_payments')->where('student_payments.id',6)
        ->select([
            'student_payments.id',
            'student_payments.orderid',
            'student_payments.payment_id',
            'student_payments.student_id',
            'student_payments.amount',
            'student_payments.payment_status',
            'student_payments.created_at',
            'students.student_image',
            'students.view_password',
            'students.phone_number',
            'students.email',
            'students.username',
            'students.school_id',
            'students.student_status',
            'school.school_name',
        ])
        ->leftJoin('students', 'students.id', '=', 'student_payments.student_id')
        ->leftJoin('school', 'school.id', '=', 'students.school_id')
        ->first();
       $data = [
           'orderid' => $student_payment_data->orderid,
           'amount' => $student_payment_data->amount,
           'payment_status' => $student_payment_data->payment_status,
           'created_at' => $student_payment_data->created_at,
           'phone_number' => $student_payment_data->phone_number,
           'email' => $student_payment_data->email,
           'username' => $student_payment_data->username,
           'student_status' => $student_payment_data->student_status,
           'student_image' => $student_payment_data->student_image,
           'school_name' => $student_payment_data->school_name,
       ];
       $pdf = PDF::loadView('pdf.student-sample', $data);
       return $pdf->download('student.pdf');
        
    } */

    
    
    
        public function generatePDF(Request $request)
        {

            LogsModel::where(['userid' => 32, 'action' => 'login'])
            ->whereNotNull('current_student_session_id')
            ->update(['current_student_session_id' => null]);
            $this->autologoutLogs($request);

            /* $student_payment_id = $request->input('student_payment_id');
            $invoices = DB::table('invoices')->first();
            $student_payment_data = DB::table('student_payments')
                ->where('student_payments.id', 12)
                ->select([
                    'student_payments.id',
                    'student_payments.orderid',
                    'student_payments.payment_id',
                    'student_payments.student_id',
                    'student_payments.amount',
                    'student_payments.payment_status',
                    'student_payments.created_at',
                    'students.student_image',
                    'students.view_password',
                    'students.phone_number',
                    'students.email',
                    'students.name',
                    'students.username',
                    'students.school_id',
                    'students.student_status',
                    'school.school_name',
                ])
                ->leftJoin('students', 'students.id', '=', 'student_payments.student_id')
                ->leftJoin('school', 'school.id', '=', 'students.school_id')
                ->first();
           $amount = $student_payment_data->amount;

           $gstAmount = $amount * 0.18;
           $totalAmount = $amount + $gstAmount;
           $formattedAmount = number_format($amount, 2);
           $formattedGSTAmount = number_format($gstAmount, 2);
           $formattedTotalAmount = number_format($totalAmount, 2);
           $amountInWords = $this->convertToWords($formattedTotalAmount);
            $data = [
                'orderid' => $student_payment_data->orderid,
                'amount' => isset($formattedAmount) ? $formattedAmount : '',
                'gstAmount' => $formattedGSTAmount,
                'totalAmount' => $formattedTotalAmount,
                'amount_in_words' => $amountInWords,
                'payment_status' => $student_payment_data->payment_status,
                'created_at' => $student_payment_data->created_at,
                'phone_number' => $student_payment_data->phone_number,
                'email' => $student_payment_data->email,
                'name' => $student_payment_data->name,
                'username' => $student_payment_data->username,
                'student_status' => $student_payment_data->student_status,
                'student_image' => $student_payment_data->student_image,
                'school_name' => $student_payment_data->school_name,
                'invoice_number' => isset($invoices->invoice_number) ? $invoices->invoice_number : '',
                'invoice_date' => isset($invoices->invoice_date) ? $invoices->invoice_date : '',
                'address' => isset($invoices->address) ? $invoices->address : '',
                'hsn_code' => isset($invoices->hsn_code) ? $invoices->hsn_code : '',
                'cgst' => isset($invoices->cgst) ? $invoices->cgst . '%' : '',  
                'sgst' => isset($invoices->sgst) ? $invoices->sgst . '%' : '',
                'igst' => isset($invoices->igst) ? $invoices->igst . '%' : '',
                'description' => isset($invoices->description) ? $invoices->description : '',
            ];
            $pdf = PDF::loadView('pdf.student-sample', $data);
           
            return $pdf->download('student.pdf'); */
        }
    
   /*  private function convertToWords($amount)
    {
            $ones = ['One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
            $teens = ['Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
            $tens = ['Ten', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
            if ($amount < 10) {
                return $ones[$amount - 1];
            } elseif ($amount < 20) {
                return $teens[$amount - 11];
            } else {
                $ten = floor($amount / 10);
                $one = $amount % 10;

                $tenWord = isset($tens[$ten - 1]) ? $tens[$ten - 1] : '';
                $oneWord = isset($ones[$one - 1]) ? $ones[$one - 1] : '';

                return $tenWord . ' ' . $oneWord;
        } 
         } 
        */

        /* private function convertToWords($amount)
        {
            $ones = ['One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
            $teens = ['Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
            $tens = ['Ten', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
        
            if ($amount < 1 || $amount > 999) {
                return "Amount out of range"; 
            }
        
            $hundreds = floor($amount / 100);
            $remainingAmount = $amount % 100;
        
            $hundredWord = $hundreds > 0 ? $ones[$hundreds - 1] . ' Hundred' : '';
        
            if ($remainingAmount < 10) {
                $onesWord = $ones[$remainingAmount - 1];
                return $hundredWord . ($hundredWord ? ' ' : '') . $onesWord;
            } elseif ($remainingAmount < 20) {
                $teensWord = isset($teens[$remainingAmount - 11]) ? $teens[$remainingAmount - 11] : '';
                return $hundredWord . ($hundredWord ? ' ' : '') . $teensWord;
            } else {
                $ten = floor($remainingAmount / 10);
                $one = $remainingAmount % 10;
        
                $tenWord = isset($tens[$ten - 1]) ? $tens[$ten - 1] : '';
                $oneWord = isset($ones[$one - 1]) ? $ones[$one - 1] : '';
        
                return $hundredWord . ($hundredWord ? ' ' : '') . $tenWord . ' ' . $oneWord;
            }
        } */
        private function convertToWords($amount)
{
    $ones = ['One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
    $teens = ['Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
    $tens = ['Ten', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

    if ($amount < 1 || $amount > 1999) {
        return "Amount out of range";
    }

    $words = [];

    $thousands = floor($amount / 1000);
    $remaining = $amount % 1000;

    if ($thousands > 0) {
        $words[] = $ones[$thousands - 1] . ' Thousand';
    }

    if ($remaining > 0) {
        if ($remaining < 10) {
            $words[] = $ones[$remaining - 1];
        } elseif ($remaining < 20) {
            $words[] = isset($teens[$remaining - 11]) ? $teens[$remaining - 11] : '';
        } else {
            $hundreds = floor($remaining / 100);
            $remainingTens = $remaining % 100;

            if ($hundreds > 0) {
                $words[] = $ones[$hundreds - 1] . ' Hundred';
            }

            $ten = floor($remainingTens / 10);
            $one = $remainingTens % 10;

            $tenWord = isset($tens[$ten - 1]) ? $tens[$ten - 1] : '';
            $oneWord = isset($ones[$one - 1]) ? $ones[$one - 1] : '';

            $words[] = $tenWord . ($tenWord && $oneWord ? ' ' : '') . $oneWord;
        }
    }

    return implode(' ', $words);
}

        


      
    

    public function testfdgf(Request $request){

        /* $mainQuery = DB::table('notification');
        $query1 = $mainQuery->where('student_noty', 'student')->where('student_id',0);
        $query2 = $mainQuery->where('student_noty', 'student')->where('student_id', 8);
        
        $noty_count = $query1->count() + $query2->count(); */
        $student = Student::where('id', 8)->first();
        $notifyData = [
            'title' => 'Hi ' . $student->name . ' ' . $student->last_name,
            'status' => 1,
            'student_id' => $student->id,
            'subcrition_noty_type' => 'student',
            'description' => "Hope we are able to serve you well! It would be our honor to continue bringing you new content, new values, and new age technology updates. Your subscription expires in 15 days on ($end_date). Please use deal code ...... to renew now."
        ];
        $student->update([
            'student_noty_subcrition' => json_encode($notifyData)
        ]);
        return "suceesfully ";


        /* $mainQuery = DB::table('notification');
        $noty_count = $mainQuery
            ->where(function ($query) {
                $query->where('student_noty', 'student')->where('student_id', 0);
            })
            ->orWhere(function ($query) {
                $query->where('student_noty', 'student')->where('student_id', 8);
            })
            ->count();
        dd($noty_count); */
        // Now $noty_count contains the total count of records that satisfy the conditions in $query1 and $query2
        




        // Fetch data from 15 days ago based on 'end_date'
       /*  $studentsFrom15DaysAgo = Student::where('student_status', 'Paid')
            ->whereNotNull('end_date') // Ensure end_date is not null
            ->whereDate('end_date', '>=', Carbon::now()->subDays(15)->format('Y-m-d'))
            ->get(); */
      

            // Fetch data from 15 days ago based on 'end_date'
            /* $students = Student::where('student_status', 'Paid')->whereNotNull('end_date') 
                ->whereDate('end_date', '>', Carbon::now()->format('Y-m-d'))
                ->get();
                foreach ($students as $student) {
                    $end_date = isset($student->end_date) ? Carbon::parse($student->end_date)->format('Y-m-d') : '';
                    $current_date = now()->format('Y-m-d');
                    if (!empty($end_date)) {
                        $daysDifference = Carbon::parse($end_date)->diffInDays($current_date);
                        if($daysDifference == 15 || $daysDifference == 10 || $daysDifference == 5 || $daysDifference == 1){
                            $notifyData = [
                            'title' => 'title sakib', 
                            'status' => 1, 
                            'student_noty' => 'student', 
                            'description' => 'sakib description'
                        ];
                            NotificationModel::create($notifyData);
                        }
                    }
        } */
    /* foreach ($students as $student) {
       
        if (Carbon::now()->gte($student->start_date)) {
            $student->update([
                'student_status' => 'Pending',
            ]);
        }
    } */


       // $student_data = Student::get();
       /*  $students = Student::where('student_status', 'Paid')->get();

        foreach ($students as $student) {
            dd($student);
            $student->update([
                'student_status' => 'Pending',
            ]);
        } */



     //dd($student_data);
        /* $Usernumber = $this->generateUniqueStudentPayment(new StudentPayment, 3);
        dd($Usernumber);
      $data = SchoolPayment::get();
      return $data; */
      //dd($data);
         /*  $validator = Validator::make($request->all(),[
              'display_name' => 'required',
              'content' => 'required',
              'content' => 'nullable|array',
              'content.*' => 'nullable|string',
              'user_id' => 'required|exists:users,id',
              'thumbnail' => 'required',
            ]);
            $requestArr = $request->validated();    
            dd($requestArr);
            if($validator->passes()){
              $AimodulesCreate = new Aimodules;
              $AimodulesCreate->display_name = $request->display_name;
              $AimodulesCreate->user_id = $request->user_id;
              $AimodulesCreate->content = $request->content;
              $AimodulesCreate->thumbnail = $request->thumbnail;
              $AimodulesCreate->save();
              return response()->json([
                  'status' => true,
                  'message' => "Chat-Gpt Login Successfully",
              ]);
            }else{
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors(),
                    ]);
                } */
       }
    

       public function payment_webhook_style_1(Request $request)
    {
        //++++++++++++++++++++++++++++++++++++++++++++++++
        try {
            $webhookSecret    = 'wdBgs2CPHRM2hYCJxr6mXFLd';
            $webhookSignature = $request->header('X-Razorpay-Signature');
            //++++++++++++++++++++++++++++++++++++++++++++++++
            $api = new Api(env('RAZERPAY_KEY_ID'), env('RAZERPAY_KEY_SECRET'));
            $api->utility->verifyWebhookSignature($request->all(), $webhookSignature, $webhookSecret);
            //++++++++++++++++++++++++++++++++++++++++++++++++
            if (!empty($request['event'])) {
                $payment_ev = $request['event'];
                $payment_ba = $request['payload']['payment']['entity'];
                //++++++++++++++++++++++++++++++++++++++++++++++++
                if ($payment_ev == 'payment.captured') 
                {
                    $payment_id = $payment_ba['id'];
                    $order_id   = $payment_ba['order_id'];
                }
                //++++++++++++++++++++++++++++++++++++++++++++++++
                //++++++++++++++++++++++++++++++++++++++++++++++++
                if ($payment_ev == 'payment.failed') 
                {
                    $payment_id = $payment_ba['id'];
                    $order_id   = $payment_ba['order_id'];
                }
                //++++++++++++++++++++++++++++++++++++++++++++++++
                if ($payment_ev && $payment_ba) {

                    DB::table('schools_payments')->insert([
                        //'orderid'         => $id,
                       // 'payment_id'       => $payment_id,
                       // 'payment_order_id' => $order_id,
                      //  'amount'           => $payment_ba['amount'],
                      //  'currency'         => $payment_ba['currency'],
                      //  'email'            => $payment_ba['email'],
                      //  'contact'          => $payment_ba['contact'],
                      //  'event'            => $payment_ev,
                      //  'status'           => $payment_ba['status'],
                        'link_updated_at'       => $request['created_at'],
                    ]);
                }
                //++++++++++++++++++++++++++++++++++++++++++++++++
            }
            //++++++++++++++++++++++++++++++++++++++++++++++++
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
        }
       //  $getdata = Response::json(true);
        // dd($getdata);
       // return Response::json(true);
    }
}