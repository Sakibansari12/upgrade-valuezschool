<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use DataTables;
use App\Traits\MailTrait;
use App\Models\{User, School, Feedback, TestPayments, Program};
use Mail;
class FeedBackController extends Controller
{
    use MailTrait;
    public function getfeedback(Request $request)
    {    $user = Auth::user();
       // dd($user->id);
         $feedbacks = DB::table('feedbacks')
         ->leftJoin('master_class', 'master_class.id', 'feedbacks.grade')
         ->where('teacher_id', $user->id)
         ->orderBy("feedbacks.id", "DESC")
         ->get();
         //dd($feedbacks);
        return view('feedback.feedback-add', compact('feedbacks'));
    }
    public function feedbackTeacher(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(),[
            'your_name' => 'required',
            'grade' => 'required',
            'section' => 'required',
            'your_feedback' => 'required',
          ]);
          if($validator->passes()){
            $feedbackCreate = new Feedback;
            $feedbackCreate->name = $request->your_name;
            $feedbackCreate->grade = $request->grade;
            $feedbackCreate->section = $request->section;
            $feedbackCreate->feedback_type = 'teacher';
            $feedbackCreate->feedback_description = $request->your_feedback;
            $feedbackCreate->teacher_id = $user->id;
            $feedbackCreate->feedback_reply_noty = 1;
            $feedbackCreate->save();
            $feedback_data = DB::table('feedbacks')->where('id', $feedbackCreate->id)->first();
            $grade_data = DB::table('master_class')->where('id', $feedback_data->grade)->first();
            $teachers_data = DB::table('users')->where('usertype', 'teacher')->where('id', $feedback_data->teacher_id)->first();
            $schooldata = DB::table('school')->where('id', $teachers_data->school_id)->first();
            $support_email = 'support@valuezschool.com';
            $details = [
                'view' => 'emails.classroom-feedback',
                'subject' => '21st Century LMS -'. $schooldata->school_name,
                'title' => $feedback_data->name,
                'school_name' => $schooldata->school_name,
                'grade' => $grade_data->class_name,
                'section' => $feedback_data->section,
                'email' => $support_email,
                'Description' => $feedback_data->feedback_description
            ];
            Mail::to($support_email)->cc($support_email)->send(new \App\Mail\TestMail($details));
            return response()->json([
                'status' => true,
                'message' => "We value your feedback. Thanks for taking out time!",
            ]);

          }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
             ]);
         }
        
    }
    public function feedbackGet(Request $request)
    {
        
        $school_all_data = School::get();
        $class_list = Program::where('status', 1)->get();
        if($request->ajax()){
            $school_id_filter = $request->input('school_id_filter');
            $grade_filter = $request->input('grade_filter');
            if ($request->ajax()) {
                $mainQuery = DB::table('feedbacks')
                ->whereNull('deleted_at')
                    ->select([
                           'feedbacks.id',
                           'feedbacks.name',
                           'feedbacks.grade',
                           'feedbacks.feedback_type',
                           'feedbacks.feedback_reply_noty',
                           'feedbacks.section',
                           'feedbacks.feedback_description',
                           'feedbacks.feedback_reply',
                           'feedbacks.teacher_id',
                           'feedbacks.deleted_at',
                           'feedbacks.created_at',
                           'feedbacks.updated_at',
                           'users.name As teacher_name',
                           'users.school_id',
                           'school.school_name',
                           'master_class.class_name',
                       ])->orderBy('feedbacks.id', 'desc');
                       $mainQuery->leftJoin('master_class', 'master_class.id', 'feedbacks.grade');
                       $mainQuery->leftJoin('users', 'users.id', 'feedbacks.teacher_id');
                       $mainQuery->leftJoin('school', 'school.id', 'users.school_id');
                       if(isset($school_id_filter) && (int)$school_id_filter>0){
                        $mainQuery->where('school.id', $school_id_filter);
                    }
                    if(isset($grade_filter) && (int)$grade_filter>0){
                        $mainQuery->where('feedbacks.grade', $grade_filter);
                    }
                 $data = $mainQuery->orderBy('id', 'desc');
                 return Datatables::of($data)
                 ->addColumn('index', function ($row) {
                     static $index = 0;
                     if ($row->feedback_reply_noty == 1) {
                        return '<b>' . ++$index . '</b>';
                    } else {
                        return ++$index;;
                    }
                    
                 })
                 ->editColumn('school_name', function ($row) {
                    if ($row->feedback_reply_noty == 1) {
                        return '<b>' . $row->school_name . '</b>';
                    } else {
                        return $row->school_name;
                    }
                })
                 ->editColumn('name', function ($row) {
                    if ($row->feedback_reply_noty == 1) {
                        return '<b>' . $row->name . '</b>';
                    } else {
                        return $row->name;
                    }
                 })
                 ->editColumn('class_name', function ($row) {
                     if ($row->feedback_reply_noty == 1) {
                        return '<b>' . $row->class_name . '</b>';
                    } else {
                        return $row->class_name;
                    }

                 })
                 ->editColumn('section', function ($row) {
                     if ($row->feedback_reply_noty == 1) {
                        return '<b>' . $row->section . '</b>';
                    } else {
                        return $row->section;
                    }
                 })
                ->editColumn('feedback_description', function ($row) {
                    $description = substr($row->feedback_description, 0, 10);
                    $hasPopupLink = (strlen($row->feedback_description) > 10);
                    $output = $description;
                    if ($hasPopupLink) {
                        $output .= '<a href="javascript:void(0);" data-description="' . $row->feedback_description . '" class="description_popup btn btn-sm btn-success">i</a>';
                    }
                    if ($row->feedback_reply_noty == 1) {
                        return '<b>' . $output . '</b>';
                    } else {
                        return $output;
                    }
                })
                ->editColumn('feedback_reply', function ($row) {
                    $feedback_reply_new = strip_tags($row->feedback_reply);
                    $description = substr($feedback_reply_new, 0, 10);
                    $hasPopupLink = (strlen($feedback_reply_new) > 10);
                    $output = $description;
                    if ($hasPopupLink) {
                        $output .= '<a href="javascript:void(0);" data-description-reply="' . $feedback_reply_new . '" class="feedback_reply_popup btn btn-sm btn-success">i</a>';
                    }
                    if ($row->feedback_reply_noty == 1) {
                        return '<b>' . $output . '</b>';
                    } else {
                        return $output;
                    }
                })
                 ->editColumn('created_at', function ($row) {
                     $formattedDate = date('d/m/Y', strtotime($row->created_at));
                     $formattedTime = date('H:i', strtotime($row->created_at));
                     if ($row->feedback_reply_noty == 1) {
                        return '<b>' . "$formattedDate | $formattedTime" . '</b>';
                    } else {
                        return "$formattedDate | $formattedTime";
                    }
                    // return "$formattedDate | $formattedTime";
                 })
                 ->addColumn('action', function ($row) {
                    $replyBtn = '<a href="' . route('feedback-reply-add', ['feedback_id' => $row->id]) . '"
                    class="waves-effect waves-light btn btn-sm btn-outline btn-outline btn-info mb-5">Reply</a>';

                    $removeBtn = '<a href="javascript:void(0)" data-id=' . $row->id . '
                    class="waves-effect waves-light remove_school_data btn btn-sm btn-outline btn-danger mb-5">Delete</a>';
                  //  return  $replyBtn . ' ' . $removeBtn;
                    if ($row->feedback_reply_noty == 1) {
                        return '<b>' . $replyBtn . ' ' . $removeBtn . '</b>';
                    } else {
                        return $replyBtn . ' ' . $removeBtn;
                    }
                })

                 ->rawColumns(['index','school_name', 'feedback_reply', 'name', 'class_name', 'section', 'feedback_description',   'created_at','action' ])
                 ->toJson();
             } 
        }
        return view('feedback.feedback-list',compact('school_all_data','class_list'));

    }

    public function feedbackReply(Request $request)
    {
        $feedback_id = $request->input('feedback_id');
        return view('feedback.feedback-reply',compact('feedback_id'));
       // dd($feedback_id);
    }
    public function feedbackReplyCreate(Request $request)
    {
        $feedback_id = $request->input('feedback_id');
        $validator = Validator::make($request->all(),[
            'feedback_reply' => 'required',
        ]);
        if($validator->passes()){
            $feedback_data = Feedback::where(['id' => $feedback_id])->first();

            if(!empty($feedback_data)){
                $feedback_data->update([
                  'feedback_reply' => $request->feedback_reply,
                  'feedback_reply_noty' => 0,
                ]);
                return response()->json([
                    'status' => true,
                    'message' => "Valuez's response successfully ",
                ]);
            }
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
             ]);
         }    
    }
    public function VerifyadminPassword(Request $request)
    {
        $userPass = $request->input('userpass');
        //dd($userPass);
        if (Auth::check()) {
            $user = Auth::user();
             if (Hash::check($userPass, $user->password)) {
                return response()->json(['success' => true, 'msg' => 'Account Verify successfully!']);
            } else {
                return response()->json(['success' => false, 'msg' => 'Entered Password Incorrect.']);
            }
        } else {
            return response()->json(['success' => false, 'msg' => 'Somenthing Went Wrong!']);
        }
    }
    public function destroy(Request $request)
    {
        $feedback_id = $request->input('feedback_id');
        $feedback_data = Feedback::where(['id' => $feedback_id])->first();
        if(!empty($feedback_data)){
            $feedback_data->delete();
            echo "removed";
        }
    }
    public function getTesting(Request $request)
    {
              if ($request->ajax()) {
                $mainQuery = DB::table('test_payments')
                ->whereNull('deleted_at')
                    ->select([
                           'test_payments.id',
                           'test_payments.orderid',
                           'test_payments.payment_amount',
                           'test_payments.email',
                           'test_payments.payment_url',
                           'test_payments.otp',
                           'test_payments.payment_link_id',
                           'test_payments.link_created_at',
                           'test_payments.chat_gpt_status',
                           'test_payments.dally_status',
                           'test_payments.payment_status',
                           'test_payments.deleted_at',
                           'test_payments.created_at',
                           'test_payments.updated_at',
                           
                       ])->orderBy('test_payments.id', 'DESC');
                 
                 $data = $mainQuery->orderBy('id', 'desc');
                 return Datatables::of($data)
                 ->addColumn('index', function ($row) {
                     static $index = 0;
                     return ++$index;
                 })
                 ->editColumn('payment_url', function ($row) {
                    if ($row->payment_url) {
                        return '<a href="' . $row->payment_url . '" style="color: #00205c;" target="_blank">link</a>
                                <i style="color: #00205c;" class="fas fa-check verified-icon"></i>';
                    } else {
                        return '<i class="fas fa-times verified-icon"></i>';
                    }
                })
                ->editColumn('email', function ($row) {
                    if ($row->email) {
                        return '<b style="color: #00205c;">' . $row->email . '</b>
                                <i style="color: #00205c;" class="fas fa-check verified-icon"></i>';
                    } else {
                        return '<i class="fas fa-times verified-icon"></i>';
                    }
                })
                ->editColumn('otp', function ($row) {
                    if ($row->otp) {
                        return '<b style="color: #00205c;">' . $row->otp . '</b>
                                <i style="color: #00205c;" class="fas fa-check verified-icon"></i>';
                    } else {
                        return '<i class="fas fa-times verified-icon"></i>';
                    }
                })
                ->editColumn('chat_gpt_status', function ($row) {
                    if ($row->chat_gpt_status > 0) {
                        return '<button class="btn btn-success btn-sm">
                                    <i class="fas fa-check verified-icon"></i>
                                </button>';
                    } else {
                        return '<button class="btn btn-danger btn-sm">
                                    <i class="fas fa-times verified-icon"></i>
                                </button>';
                    }
                })
                ->editColumn('dally_status', function ($row) {
                    if ($row->dally_status > 0) {
                        return '<button class="btn btn-success btn-sm">
                                    <i class="fas fa-check verified-icon"></i>
                                </button>';
                    } else {
                        return '<button class="btn btn-danger btn-sm">
                                    <i class="fas fa-times verified-icon"></i>
                                </button>';
                    }
                })
                ->editColumn('created_at', function ($row) {
                    return isset($row->created_at) ? \Carbon\Carbon::parse($row->created_at)->format('Y-m-d') : '';
                })
                ->editColumn('action', function ($cdata) {
                    return '<a href="javascript:void(0);" data-id="' . $cdata->id . '"
                                class="waves-effect waves-light remove_school_data btn btn-sm btn-outline btn-danger mb-5">Delete</a>';
                })
                 ->rawColumns(['index','payment_url', 'email', 'otp', 'chat_gpt_status','dally_status', 'created_at','action'])
                 ->toJson();
             }


        return view('testing.testing-list');
    }


    public function VerifyadminTestingPassword(Request $request)
    {
        $userPass = $request->input('userpass');
        if (Auth::check()) {
            $user = Auth::user();
             if (Hash::check($userPass, $user->password)) {
                return response()->json(['success' => true, 'msg' => 'Account Verify successfully!']);
            } else {
                return response()->json(['success' => false, 'msg' => 'Entered Password Incorrect.']);
            }
        } else {
            return response()->json(['success' => false, 'msg' => 'Somenthing Went Wrong!']);
        }
    }

    public function destroyTesting(Request $request)
    {
        $feedback_id = $request->input('feedback_id');
        $feedback_data = TestPayments::where(['id' => $feedback_id])->first();
        if(!empty($feedback_data)){
            $feedback_data->delete();
            echo "removed";
        }
    }
    public function addTesting(Request $request)
    {
        $testing_type = $request->input('testing_type');
        if($testing_type == 'test_otp'){
          return view('testing.testing-otp');
        }
        if($testing_type == 'test_email'){
            return view('testing.testing-email');
        }
        if($testing_type == 'test_payment_link'){
            return view('testing.test-payment-link');
        }
        if($testing_type == 'test_chat_gpt'){
            return view('testing.test-chat-gpt');
        }
        if($testing_type == 'test_dally'){
            return view('testing.test-dally');
        }
    }

    public function otpTesting(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'phone_number' => 'required',
       ]);
       if($validator->passes()){
             $apiUrl = 'https://enterprise.smsgupshup.com/GatewayAPI/rest';
             $otpnumber = random_int(1000, 9999);
             $postData = [
                 'userid' => 2000215380,
                 'password' => 'D5cgrl4y4',
                 'method' => 'SendMessage',
                 'msg' => $otpnumber . ' is your one-time password (OTP) for Valuez Account. This will be valid for 2 mins.',
                 'format' => 'text',
                 'v' => '1.1',
                 'send_to' => $request->phone_number,
             ];
             $ch = curl_init($apiUrl);
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
             curl_setopt($ch, CURLOPT_POST, true);
             curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
             $response = curl_exec($ch);
             if (strpos($response, 'success') !== false) {
                    return response()->json([
                        'status' => true,
                        'message' => "OTP sent successfully",
                    ]);
             }else{
                    return response()->json([
                        'status' => false,
                        'otp_errors' => "Failed to send OTP",
                    ]);
             }
             curl_close($ch);
       }else{
           return response()->json([
              'status' => false,
              'errors' => $validator->errors(),
           ]);
       }
    }
    public function emailTesting(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
       ]);
       if($validator->passes()){
            $details = [
                'view' => 'emails.test-email',
                'subject' => 'Your Account Email Testing - Valuez',
                'title' => 'Email Testing',
                'email' => $request->email,
                'pass' => 'test123',
            ];
            Mail::to($request->email)->send(new \App\Mail\TestMail($details));
            return response()->json([
                'status' => true,
                'message' => "Email sent successfully",
            ]);
       }else{
           return response()->json([
              'status' => false,
              'errors' => $validator->errors(),
           ]);
       }
    }

    public function paymentTesting(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'phone_number' => 'required',
       ]);
       if($validator->passes()){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.razorpay.com/v1/payment_links',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode(array(
                "amount" => 1 * 100,
                "currency" => "INR",
                "accept_partial" => true,
                "first_min_partial_amount" => 100,
                "expire_by" => 1897043600,
                "reference_id" => $this->generateUniquetestId(new TestPayments, 4),
                "description" => 'Create Testing Payment Link',
                "customer" => array(
                    "name" => 'Create Testing Payment Link',
                    "contact" => $request->phone_number,
                    "email" => $request->email,
                ),
                "notify" => array(
                    "sms" => true,
                    "email" => true
                ),
                "reminder_enable" => true,
                "notes" => array(
                    "policy_name" => "Jeevan Bima"
                ),
                "callback_url" => "https://example-callback-url.com/",
                "callback_method" => "get"
            )),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Basic cnpwX2xpdmVfbnZWTXJCd2cwa0pSSVQ6d2RCZ3MyQ1BIUk0yaFlDSnhyNm1YRkxk'
               
            ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $shortUrls = collect($response)
        ->map(function ($response) {
            $data = json_decode($response, true);
            $shortUrl = $data['short_url'] ?? null;
            $id = $data['id'] ?? null; 
            return compact('shortUrl', 'id');
        })
        ->filter()
        ->toArray();
        if(!empty($shortUrls[0])){
            return response()->json([
                'status' => true,
                'message' => "Create Payment link successfully",
            ]);
        }else{
            return response()->json([
                'status' => false,
                'payment_link_errors' => "No Payment successfully",
            ]); 
        }

       }else{
           return response()->json([
              'status' => false,
              'errors' => $validator->errors(),
           ]);
       }
    }


    public function chatGptTesting(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'chat_gpt' => 'required',
       ]);
       if($validator->passes()){
        $apiKey = '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';
        $model = 'gpt-3.5-turbo';
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $apiKey,
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => $model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a helpful assistant.',
                ],
                [
                    'role' => 'user',
                    'content' => $request->chat_gpt,
                ],
            ],
        ]);
        if ($response->successful()) {
            $result = $response->json();
            return response()->json([
                'status' => true,
                'message' => "Chat GPT sent successfully",
            ]);
        }else{
            return response()->json([
                'status' => false,
                'chat_gpt_errors' => 'Failed to Chat GPT',
             ]);
        }
       }else{
           return response()->json([
              'status' => false,
              'errors' => $validator->errors(),
           ]);
       }
    }

    public function dallyTesting(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'dally' => 'required',
        ]);
        if($validator->passes()){
            $apiKey = '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';
            $model = 'dall-e-2';
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $apiKey,
            ])->post('https://api.openai.com/v1/images/generations', [
                "model" => $model,
                "prompt"=> $request->dally,
                "n"=> 1,
                "size"=> "512x512"
                
            ]);
            // dd($response);
            if ($response->successful()) {
                return response()->json([
                    'status' => true,
                    'message' => "Dally sent successfully",
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'dally_errors' => 'Failed to Dally',
                 ]);
            }
           }else{
               return response()->json([
                  'status' => false,
                  'errors' => $validator->errors(),
               ]);
           }
    }
    public function feedbackReplyNotify()
    {
       // $user = Auth::user();
         $feedback_noty_count = DB::table('feedbacks')
         ->leftJoin('master_class', 'master_class.id', 'feedbacks.grade')
         ->where('feedback_reply_noty', 1)->count();
        
        return response()->json([
            'status' => true,
            'feedback_reply_noty' => $feedback_noty_count,
            'message' => "Feedback reply notification count successfully",
        ]);
    }
}
