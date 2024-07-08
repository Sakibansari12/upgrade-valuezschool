<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use ESolution\DBEncryption\Encrypter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Traits\OtpVerifyTraits;
use Illuminate\Support\Facades\URL;
use App\Imports\StudetsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\{User, School, LogsModel, BulkUpload, StudentPackage, Avatar, Package, ForgotPasswordSchoolAdmin, StudentOtp,Student,Program,Course, LessonPlan,ForgotStudent,AccessCodes,StudentPayment};
use DataTables;
use Mail;
use Session;
use Redirect;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Jenssegers\Agent\Facades\Agent;
use App\Jobs\ResetOtpStudent;
use PDF;
class StudentController extends Controller
{

    use OtpVerifyTraits;

    public function indexOne(Request $request){
          $program_list = Program::get();
        return view('auth.register', compact('program_list'));
    }

    public function DashboardStudent(Request $request){
       return view('dashboard-student');
    } 

    public function authstudentotp(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required',
        ]);
        if($validator->passes()){
            $studentdata = Student::where('phone_number', $request->phone_number)->first();
             if(!empty($studentdata)){
                return response()->json([
                    'status' => true,
                    'student_allexixts' => "Number exists. Try with a different number or login",
                ]);
             }
             $studentotpupdate = StudentOtp::where('phone_number', $request->phone_number)->first(); 
             $otpnumberupdate = random_int(1000, 9999);
             if(!empty($studentotpupdate)){
                $studentotpupdate->update([
                    'otp_verified_at' => null,
                    'otp' => $otpnumberupdate,
                    'otp_verified_till' => Carbon::now()->addSeconds(60)->format('Y-m-d H:i:s'),
                ]);
                $timereset = Carbon::parse($studentotpupdate->otp_verified_till)->format(30,'s');
                $this->OtpSentMessage($studentotpupdate->phone_number,$studentotpupdate->otp);
                return response()->json([
                    'status' => true,
                    'timeResetOtpUpdatetime' => $timereset,
                    'message_update' => "OTP sent successfully",
                ]);
             }
             /* $username = $request->name . '_'. substr($request->last_name,  0, 1);
             $lastname =  '\_'. substr($request->last_name,  0, 1);
             $usernameCount = DB::table('student_otps')->select([
                'id',
                'username',
             ])->where('username', 'like', "%{$lastname}%")->count();
            $singlenumber  = str_pad((int) $usernameCount + 1, 2,  '0', STR_PAD_LEFT); 
            $username = strtolower($username . $singlenumber); */
            
            $otpnumber = random_int(1000, 9999);
            $studentOtp = new StudentOtp;
            $studentOtp->name = $request->name;
            $studentOtp->last_name = $request->last_name;
            $studentOtp->phone_number = $request->phone_number;
            $studentOtp->otp = $otpnumber;
            $studentOtp->otp_verified_till = Carbon::now()->addSeconds(60)->format('Y-m-d H:i:s');
            $studentOtp->save();
           $timereset = Carbon::parse($studentOtp->otp_verified_till)->format(30,'s');
            $this->OtpSentMessage($studentOtp->phone_number,$studentOtp->otp);
                return response()->json([
                    'status' => true,
                    'timeResetOtp' => $timereset,
                    'message' => "OTP sent successfully",
                ]);
             }else{
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors(),
                 ]);
    
             }
    }
    public function  studentVerifyOtp(Request $request)
    {
         $validator = Validator::make($request->all(),[
            'otp' => 'required',
            'phone_number' => 'nullable',
            'name' => 'nullable',
            'last_name' => 'nullable',
         ]);
        if($validator->passes()){
            $otpdata = StudentOtp::where('otp', $request->otp)
               ->where('phone_number', $request->phone_number)->first();
            if(empty($otpdata)){
                return response()->json([
                    'status' => false,
                    'queryfaild' => "OTP Invalid",
                    ]);
            }else{
               $username = $request->name . '_'. substr($request->last_name,  0, 1);
               $lastname =  '\_'. substr($request->last_name,  0, 1);
               /* $usernameCount = DB::table('students')->select([
                  'id',
                  'username',
               ])->where('username', 'like', "%{$lastname}%")->count(); */
               $usernameCount = DB::table('users')->select([
                'id',
                'username',
               ])->where('username', 'like', "%{$lastname}%")->count();
               if($usernameCount == 0){
                   $username = $username;
               }else{
                 $singlenumber  = str_pad((int) $usernameCount + 1, 2,  '0', STR_PAD_LEFT);
                 $username = strtolower($username . $singlenumber);
               }


               $databaseTimestamp = $otpdata->updated_at; 
               $currentTimestamp = now();
               $timeDifference = $currentTimestamp->diffInSeconds($databaseTimestamp);
               $allowedTimeDifference = 90;
               if ($timeDifference <= $allowedTimeDifference) {
                   // echo "Verification successful!";
                    $otpdata->update([
                        'otp_verified_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                        'username' => $username,
                    ]);
                    return response()->json([
                        'status' => true,
                        'otpdata' => $otpdata,
                        'message' => "OTP verified successfully",
                    ]);

                } else {
                   // echo "Verification failed!";
                    return response()->json([
                        'status' => false,
                        'queryfaild' => "OTP Expire",
                    ]);
                    
                } 
            }
            
        }else{
        return response()->json([
            'status' => false,
            'errors' => $validator->errors(),
            ]);

        }
        
    }

    public function  studentLoginVerifyOtp(Request $request)
    {
         $validator = Validator::make($request->all(),[
            'otp' => 'required',
            'phone_number' => 'nullable',
         ]);
        if($validator->passes()){
            $otpdata = Student::where('otp', $request->otp)
                       ->where('phone_number', $request->phone_number)->first();
            if(empty($otpdata)){
                return response()->json([
                    'status' => false,
                    'queryfaild' => "OTP Invalid",
                    ]);
            }else{
                if($otpdata->school_id>0){
                    $databaseTimestamp = $otpdata->updated_at; 
                    $currentTimestamp = now();
                    $timeDifference = $currentTimestamp->diffInSeconds($databaseTimestamp);
                    $allowedTimeDifference = 90;
                    if ($timeDifference <= $allowedTimeDifference) {
                        $otpdata->update([
                            'otp_verified_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                        ]);
                        return response()->json([
                            'status' => true,
                            'otpdata' => $otpdata,
                            'message' => "OTP verified successfully",
                        ]);
     
                     } else {
                        // echo "Verification failed!";
                         return response()->json([
                             'status' => false,
                             'queryfaild' => "OTP Expire",
                         ]);
                         
                     } 


                }else{
                    $otpdata->update([
                        'otp_verified_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                    ]);
                    return response()->json([
                        'status' => true,
                        'otpdata' => $otpdata,
                        'student_id_accesscode_time' => base64_encode($otpdata->id),
                        'message' => "OTP verified successfully",
                        'access_code' => "access_code Process pending task",
                    ]);
                }

            }
        }else{
        return response()->json([
            'status' => false,
            'errors' => $validator->errors(),
            ]);
        }
        
    }

    public function AccessCodeLogin(Request $request){
        $validator = Validator::make($request->all(),[
            'otp' => 'nullable',
            'phone_number' => 'nullable',
            'password' => 'nullable',
            'username' => 'nullable',
            'access_codes' => 'required|exists:school,access_code',
         ]);
        // dd($request->access_code);
         if($validator->passes()){
            if(!empty($request->username)){
                $otpdata = Student::where('username', $request->username)->where('password', $request->password)->first();
            }else{
                $otpdata = Student::where('phone_number', $request->phone_number)->where('otp', $request->otp)->first();
            }
            
            if(empty($otpdata)){
                return response()->json([
                    'status' => false,
                    'message' => "Number not registered. Kindly register.",
                    ]);
            }else{
                $school_data = School::where('access_code', $request->access_codes)->first();
               $check_school_user = School::with(['student' => function ($query) {
                $query->where('student_status', '=', 'Paid')->whereNull('deleted_at');
            }])->where(['is_deleted' => 0, 'id' => $school_data->id])->orderBy('id')->first();
            $total_student = $check_school_user->student->count();
            
                if ($check_school_user->student_licence > $total_student) {
                    $otpdata->update([
                        'school_id'  => $school_data->id,
                        'student_status'  => $school_data->school_student_status,
                    ]);
                }else{
                    return response()->json([
                        'status' => false,
                        'student_licence' => "error",
                    ]);
                }
                $UserUpdate = User::where('student_id', $otpdata->id)->where('usertype', 'student')->first();
                $UserUpdate->update([
                    'school_id'  => $otpdata->school_id,
                ]);

                return response()->json([
                    'status' => true,
                    'otpdata' => $otpdata,
                    'message' => "Student Login successfully",
                ]);
                
            }
            
        }else{
        return response()->json([
            'status' => false,
            'errors' => $validator->errors(),
            ]);

        }    
    }

    public function  VerifyOtpLoginPage(Request $request)
    {
         $validator = Validator::make($request->all(),[
            'otp' => 'required',
            'phone_number' => 'nullable',
         ]);
        if($validator->passes()){
            $otpdata = Student::where('otp', $request->otp)
               ->where('phone_number', $request->phone_number)->first();
            if(empty($otpdata)){
                return response()->json([
                    'status' => false,
                    'queryfaild' => "OTP Invalid",
                    ]);
            }else{
                $otpdata->update([
                    'otp_verified_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                ]);
                if($otpdata->school_id>0){
                     return response()->json([
                        'status' => true,
                        'otpdata' => $otpdata,
                        'message' => "OTP verified successfully, exists School ID",
                    ]); 
                }else{
                    return response()->json([
                        'status' => false,
                        'otpdata' => $otpdata,
                        'school_id_not_exists' => "OTP verified successfully, Does't exists School ID",
                    ]);
                }
                
            }
            
        }else{
        return response()->json([
            'status' => false,
            'errors' => $validator->errors(),
            ]);

        }
        
    }

/* otp_verify_at Null Case */
public function OtpVerifyAtNullCase(Request $request){
    $validator = Validator::make($request->all(),[
         'phone_number_null_case' => 'required',
         'username' => 'required',
         'password' => 'required',
    ]);
    if($validator->passes()){
       $studentOtp = Student::where('username', $request->username)
                     ->where('password', $request->password)->first();

         if(empty($studentOtp)){
            return response()->json([
               'status'  => false,
               'message' => "Number not registered. Kindly register.",
            ]);
         }else{
             if($request->phone_number_null_case == $studentOtp->phone_number){
                $mobilenumber = $request->phone_number_null_case;
             }else{
                 $studentdata = Student::where('phone_number', $request->phone_number_null_case)->first();
                 if(empty($studentdata)){
                    $mobilenumber = $request->phone_number_null_case;
                 }else{
                    return response()->json([
                        'status' => false,
                        'Phone_number_exists' => "The phone number has already been taken.",
                    ]);
                 }
             }
            $otpnumber = random_int(1000, 9999);
            $studentOtp->update([
                 'phone_number'  => $mobilenumber,
                 'otp'  => $otpnumber,
            ]);
            $this->OtpSentMessage($studentOtp->phone_number,$studentOtp->otp);
            return response()->json([
                'status' => true,
                'message' => "OTP sent successfully",
            ]);
         }
        
    }else{
        return response()->json([
           'status' => false,
           'errors' => $validator->errors(),
        ]);
    }
}

    


    public function authstudent(Request $request){
        $validator = Validator::make($request->all(),[
            'grade'            => 'required',
            'username'         => 'required',
            'name'             => 'required',
            'last_name'             => 'required',
            'phone_number'            => 'required|unique:students',
            'otp'              => 'required',
            'password'         => 'required',
            'confirm_password' => 'required',
         ]);
            $otpdata = StudentOtp::where('phone_number', $request->phone_number)
                                   ->where('otp', $request->otp)
                                    ->first();
            if(empty($otpdata)){
                return response()->json([
                    'status' => false,
                    'phonenumber' => "Phone Numbr is invalid",
                ]);
            }
            if($request->password != $request->confirm_password){
                return response()->json([
                    'status' => false,
                    'conform_password' => "“Passwords don’t match. (Please enter again)”",
                    'errors' => $validator->errors(),
                ]);
            }
            if($validator->passes()){
                $username = $request->name . '_'. substr($request->last_name,  0, 1);
                $lastname =  '\_'. substr($request->last_name,  0, 1);
                $usernameCount = DB::table('users')->select([
                    'id',
                    'username',
                   ])->where('username', 'like', "%{$lastname}%")->count();
                if($usernameCount == 0){
                    $username = $username;
                }else{
                  $singlenumber  = str_pad((int) $usernameCount + 1, 2,  '0', STR_PAD_LEFT);
                  $username = strtolower($username . $singlenumber);
                }
                $StudentCreate = new Student;
                $StudentCreate->grade = $request->grade;
                $StudentCreate->name = $request->name;
                $StudentCreate->phone_number = $request->phone_number;
                $StudentCreate->otp = $request->otp;
                $StudentCreate->status = 1;
                $StudentCreate->username = $username;
                $StudentCreate->last_name = $request->last_name;
                $StudentCreate->password = Hash::make($request->password);
                $StudentCreate->otp_verified_at = Carbon::now()->format('Y-m-d H:i:s');   
                $StudentCreate->save();
                $UserCreate = new User;
                $UserCreate->name = $StudentCreate->name;
                $UserCreate->usertype = 'student';
                $UserCreate->student_id = $StudentCreate->id;
                $UserCreate->status = $StudentCreate->status;
                $UserCreate->username = $StudentCreate->username;
                $UserCreate->grade = $StudentCreate->grade;
                $UserCreate->password = $StudentCreate->password;
              //  $UserCreate->view_pass = $StudentCreate->password;
                $UserCreate->save();


                $otpdata->delete();
                    return response()->json([
                        'status' => true,
                        'register_student_id' => base64_encode($StudentCreate->id),
                        'message' => "Create Student successfully",
                    ]);
                 }else{
                    return response()->json([
                        'status' => false,
                        'errors' => $validator->errors(),
                     ]);
        
                 }
  
  
     }

     /* Student Login*/
     public function StudentIndex(Request $request){
           // return view('auth.student_login');
            return view('student_auth.login-with-username');
     }
/* Start new */
     public function StudentPhoneVerify(Request $request){
        $student_id = base64_decode($request->input('student'));
         return view('student_auth.login-with-phone', compact('student_id'));
     }

     public function StudentLoginAllProcess(Request $request){
        $validator = Validator::make($request->all(),[
            'username' => 'required|exists:students,username',
            'password'  => 'required',
        ]); 
        if($validator->passes()){
            $studenlogin = Student::where('username', $request->username)
            ->first();
            if(empty($studenlogin)){
                return response()->json([
                    'status' => false,
                    'username_not_exists' => "Don't have an account ? Resgister here",
                ]);
            }else{
                    if(empty($studenlogin->phone_number)){
                        return response()->json([
                            'status' => false,
                            'student_id' => base64_encode($studenlogin->id),
                            'phonenumber_null' => "Your Phone Number is doesn't verified",
                        ]);
                    }else{
                        if(empty($studenlogin->otp_verified_at)){
                            return response()->json([
                                'status' => false,
                                'student_id' => base64_encode($studenlogin->id),
                                'otp_verify_at_null' => "This Student is not verified",
                            ]);
                        }else{
                            if($studenlogin->school_id>0){
                                 
                                 if (filter_var($request->username, FILTER_VALIDATE_EMAIL)) {
                                    Auth::attempt(['username' => $request->username, 'password' => $request->password]);
                                    
                                } else {
                                   
                                    Auth::attempt(['username' => $request->username, 'password' => $request->password]);
                                } 
                                $user = Auth::user(); 
                               if (Auth::check()) {
                               // $last_logged_session = LogsModel::where(['userid' => $user->id, 'action' => 'login'])->whereNotNull('current_student_session_id')->count();
                               $lastLoginLogs = LogsModel::where('userid', $user->id)
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
                                }

                                if ($nonEmptyStudentSessionCount > 1) {
                                    Auth::logout();
                                    return response()->json([
                                        'status' => true,
                                        'student_session' => "Sorry! You have reached the maximum device login limit.",
                                    ]);
                                    
                                } else {
                                    //$session_id = session()->getId();
                                   // session(['usertype' => $user->usertype]);
                                   // $session_id = session()->getId();
                                    session(['usertype' => $user->usertype]);
                                    $browser = Agent::browser();
                                    $version = Agent::version($browser);
                                    $session_id = session()->getId();
                                    session(['student_session_id' => $session_id]);
                                    $device_id = $request->deviceid;
                                    $log_arr = [
                                        'curr_user_id' => $user->id,
                                        'curr_name' => $user->name,
                                        'username' => $user->username,
                                        'usertype' => $user->usertype,
                                        'ip' => $request->ip(),
                                        'device' => Agent::device(),
                                        'platform' => Agent::platform(),
                                        'browser' => $browser,
                                        'version' => $version,
                                        'info' => 'Student Login',
                                        'session' => $session_id,
                                        'device_id' => $device_id,
                                    ];
                                    LogsModel::create(['userid' => $user->id, 'action' => 'login', 'current_student_session_id' => $session_id, 'logs_info' => json_encode($log_arr)]);

                                   // LogsModel::create(['userid' => $user->id, 'action' => 'login', 'current_student_session_id' => $session_id, 'logs_info' => json_encode(['info' => 'Student Login', 'usertype' => $user->usertype])]);
                                    return response()->json([
                                        'status' => true,
                                        'message' => "Student Login Successfully",
                                    ]);
                                }
                            }else{
                                
                                return response()->json([
                                    'status' => false,
                                    'credentials_do_not' => "The provided credentials do not match our records.",
                                ]);
                            }
                                
                                
                                
                            }else{
                                return response()->json([
                                    'status' => false,
                                    'student_id_accesscode_time' => base64_encode($studenlogin->id),
                                    'access_code' => "access_code Process pending task",
                                ]);
                            }            
                        }
                    }
                }
            }else{
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors(),
                    ]);
                } 
     
    }

    public function StudentGetOtp(Request $request){
        /* $validator = Validator::make($request->all(),[
             'phone_number' => 'required',
        ]);
        */
        $rules = [
            'phone_number' => 'required',
        ];
        $studentOtp = Student::where('id', $request->student_id)->first();
        if ($request->has('phone_number') && $request->phone_number != $studentOtp->phone_number) {
            $rules['phone_number'] = 'required|unique:students,phone_number';
        }
        $validator = Validator::make($request->all(), $rules);

        if($validator->passes()){
           $studentOtp = Student::where('id', $request->student_id)->first();
             if(empty($studentOtp)){
                return response()->json([
                   'status'  => false,
                   'phone_number_not_exists' => "Number not registered. Kindly register.",
                ]);
             }else{
                $otpnumber = random_int(1000, 9999);
                $studentOtp->update([
                     'phone_number'  => $request->phone_number,
                     'otp'  => $otpnumber,
                     'otp_verified_at' => null,
                     'otp_verified_till' => Carbon::now()->addSeconds(60)->format('Y-m-d H:i:s'),
                ]);
                $timereset = Carbon::parse($studentOtp->otp_verified_till)->format(30,'s');
                $this->OtpSentMessage($studentOtp->phone_number,$studentOtp->otp);
                return response()->json([
                    'status' => true,
                    'student_id' => base64_encode($studentOtp->id),
                    'timeResetOtpUpdatetime' => $timereset,
                    'message' => "OTP sent successfully",
                ]);
             }
            
        }else{
            return response()->json([
               'status' => false,
               'errors' => $validator->errors(),
            ]);
        }
    }

/* Login With Otp */
public function StudentLoginWithOpt(Request $request){
    return view('student_auth.login-with-otp');
}


    public function StudentOtpVerify(Request $request)
    {
        $student_id = base64_decode($request->input('student'));

        $studentOtp = Student::where('id', $student_id)->first();

        $timereset = Carbon::parse($studentOtp->otp_verified_till)->format(30,'s');
        //dd($timereset);
        return view('student_auth.otp-verify', compact('student_id','timereset'));
    }

    public function  LoginVerifyOtp(Request $request)
    {
         $validator = Validator::make($request->all(),[
            'otp' => 'required',
         ]);
        if($validator->passes()){
            $otpdata = Student::where('otp', $request->otp)->where('id', $request->student_id)->first();
           // $otpdata = Student::where('id', $request->student_id)->first();
            if(empty($otpdata)){
                return response()->json([
                    'status' => false,
                    'queryfaild' => "OTP Invalid",
                    ]);
            }else{
                if($otpdata->school_id>0){
                    $databaseTimestamp = $otpdata->updated_at; 
                    $currentTimestamp = now();
                    $timeDifference = $currentTimestamp->diffInSeconds($databaseTimestamp);
                    $allowedTimeDifference = 90;
                    if ($timeDifference <= $allowedTimeDifference) {
                        $otpdata->update([
                            'otp_verified_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                        ]);
                        return response()->json([
                            'status' => true,
                            'student_id' => $otpdata->id,
                            'student' => base64_encode($otpdata->id),
                            'message' => "OTP verified successfully",
                        ]);
     
                     } else {
                        // echo "Verification failed!";
                         return response()->json([
                             'status' => false,
                             'queryfaild' => "OTP Expire",
                         ]);
                         
                     } 
                }else{
                    $otpdata->update([
                        'otp_verified_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                    ]);
                    return response()->json([
                        'status' => true,
                        'otpdata' => $otpdata,
                        'student_id_accesscode_time' => base64_encode($otpdata->id),
                        'message' => "OTP verified successfully",
                        'access_code' => "access_code Process pending task",
                    ]);
                }

            }
        }else{
        return response()->json([
            'status' => false,
            'errors' => $validator->errors(),
            ]);
        }
        
    }

public function StudentLoginWithVerifyOtp(Request $request){

    $user_data = User::where('student_id', $request->student_id)->first();
    Auth::login($user_data);
    $user = Auth::user();
 
    if (Auth::check()) {
           // $last_logged_session = LogsModel::where(['userid' => $user->id, 'action' => 'login'])->whereNotNull('current_student_session_id')->count();
            $lastLoginLogs = LogsModel::where('userid', $user->id)
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
            }
            
        
        if ($nonEmptyStudentSessionCount > 1) {
            Auth::logout();
            return response()->json([
                'status' => true,
                'student_session' => "Sorry! You have reached the maximum device login limit.",
            ]);
            
        } else {
            $session_id = session()->getId();
            session(['usertype' => $user->usertype]);
            
            $browser = Agent::browser();
            $version = Agent::version($browser);
            $session_id = session()->getId();
            session(['student_session_id' => $session_id]);
            $device_id = $request->deviceid;
            $log_arr = [
                'curr_user_id' => $user->id,
                'curr_name' => $user->name,
                'username' => $user->username,
                'usertype' => $user->usertype,
                'ip' => $request->ip(),
                'device' => Agent::device(),
                'platform' => Agent::platform(),
                'browser' => $browser,
                'version' => $version,
                'info' => 'Student Login',
                'session' => $session_id,
                'device_id' => $device_id,
            ];
            LogsModel::create(['userid' => $user->id, 'action' => 'login', 'current_student_session_id' => $session_id, 'logs_info' => json_encode($log_arr)]);
            return response()->json([
                'status' => true,
                'message' => "Student Login Successfully",
            ]);
        }
    }else{
        return response()->json([
            'status' => false,
            'credentials_do_not' => "The provided credentials do not match our records.",
        ]);
    }
}


public function StudentLoginWithGetOtp(Request $request){
    $validator = Validator::make($request->all(),[
         'phone_number' => 'required|exists:students,phone_number',
    ]);
   
    if($validator->passes()){
       $studentOtp = Student::where('phone_number', $request->phone_number)->first();
         if(empty($studentOtp)){
            return response()->json([
               'status'  => false,
               'phone_number_not_exists' => "Number not registered. Kindly register.",
            ]);
         }else{
            $otpnumber = random_int(1000, 9999);
            $studentOtp->update([
                 //'phone_number'  => $request->phone_number,
                 'otp'  => $otpnumber,
                 'otp_verified_at' => null,
                 'otp_verified_till' => Carbon::now()->addSeconds(60)->format('Y-m-d H:i:s'),
            ]);
            $timereset = Carbon::parse($studentOtp->otp_verified_till)->format(30,'s');
            $this->OtpSentMessage($studentOtp->phone_number,$studentOtp->otp);
            return response()->json([
                'status' => true,
                'student_id' => base64_encode($studentOtp->id),
                'timeResetOtpUpdatetime' => $timereset,
                'message' => "OTP sent successfully",
            ]);
         }
        
    }else{
        return response()->json([
           'status' => false,
           'errors' => $validator->errors(),
        ]);
    }
}

/* Access code */
    public function SchoolAccessCode(Request $request)
    {   
        $student_id = base64_decode($request->input('student'));
        $studentid = $request->input('student');

        if(!empty($student_id)){
            //$student_data = Student::where('id', $decryptedStudentId)->first();
            return view('student_auth.access_code', compact('student_id','studentid'));
       }
    }

    public function studentNoAccessCode(Request $request)
    {   
        $decryptedStudentId = base64_decode($request->input('student_id'));
        if(!empty($decryptedStudentId)){
            $student_data = Student::where('id', $decryptedStudentId)->first();
            return view('student_auth.student-no-access-code', compact('student_data'));
       }
    }

    public function SchoolAccessCodeLogin(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'access_code' => 'required|exists:school,access_code',
        ]);
        if($validator->passes()){
              $studentdata = Student::where('id', $request->student_id)->first();
              $school_data = School::where('access_code', $request->access_code)->first();
              $check_school_user = School::with(['student' => function ($query) {
              $query->where('student_status', '=', 'Paid')->whereNull('deleted_at');
              }])->where(['is_deleted' => 0, 'id' => $school_data->id])->orderBy('id')->first();

             $total_student = $check_school_user->student->count();
             if ($check_school_user->student_licence > $total_student) {
                 $studentdata->update([
                     'school_id'  => $school_data->id,
                     'student_status'  => $school_data->school_student_status,
                 ]);
                 $UserUpdate = User::where('student_id', $studentdata->id)->where('usertype', 'student')->first();
                 $UserUpdate->update([
                     'school_id'  => $school_data->id,
                 ]);
                 return response()->json([
                    'status' => true,
                    'student_id' => $studentdata->id,
                    'message' => "Student Login successfully",
                ]);
             }else{
                 return response()->json([
                     'status' => false,
                     'student_licence' => "Maximum Subscription limit reached",
                 ]);
             }
             
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        } 
        
    }

/* End new */


    public function StudentIndexAccessCode(Request $request)
    {
        if(!empty($request->student_id)){
              $studentid = base64_decode($request->student_id);
            $student_data = Student::where('id', $studentid)->first();
            $student_id = base64_encode($student_data->id);
           return view('auth.access-code-login', compact('student_data','student_id'));
       }
    }

    public function StudentAccessCodeLogin(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'access_codes' => 'required|exists:school,access_code',
        ]);
        if($validator->passes()){
              $studentdata = Student::where('id', $request->student_id)->first();
              $school_data = School::where('access_code', $request->access_codes)->first();
              $check_school_user = School::with(['student' => function ($query) {
              $query->where('student_status', '=', 'Paid')->whereNull('deleted_at');
              }])->where(['is_deleted' => 0, 'id' => $school_data->id])->orderBy('id')->first();

             $total_student = $check_school_user->student->count();
             if ($check_school_user->student_licence > $total_student) {
                 $studentdata->update([
                     'school_id'  => $school_data->id,
                     'student_status'  => $school_data->school_student_status,
                 ]);
                 $UserUpdate = User::where('student_id', $studentdata->id)->where('usertype', 'student')->first();
                 $UserUpdate->update([
                     'school_id'  => $school_data->id,
                 ]);
                 return response()->json([
                    'status' => true,
                    'student_id' => $studentdata->id,
                    'message' => "Student Login successfully",
                ]);
             }else{
                 return response()->json([
                     'status' => false,
                     'student_licence' => "Maximum Subscription limit reached",
                 ]);
             }
             
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        } 
        
    }

     public function StudentLogin(Request $request){
        $validator = Validator::make($request->all(),[
            'username' => 'required|exists:students,username',
            'password'  => 'required|exists:students,password',
        ]); 
        if($validator->passes()){
            $studenlogin = Student::where('username', $request->username)->where('password', $request->password)
            ->first();
            if(empty($studenlogin)){
                return response()->json([
                    'status' => false,
                    'username_notexists_invalid' => "Don't have an account ? Resgister here",
                ]);
            }else{
                    if(empty($studenlogin->phone_number)){
                        return response()->json([
                            'status' => false,
                            'phonenumber_null' => "Your Phone Number is doesn't verified",
                        ]);
                    }else{
                        if(empty($studenlogin->otp_verified_at)){
                            return response()->json([
                                'status' => false,
                                'studentdata' =>$studenlogin, 
                                'otp_verify_at_null' => "This Student is not verified",
                            ]);
                        }else{
                            if($studenlogin->school_id>0){
                                 
                                if (filter_var($studenlogin->username, FILTER_VALIDATE_EMAIL)) {
                                    Auth::attempt(['username' => $studenlogin->username, 'password' => $studenlogin->password]);
                                    
                                } else {
                                   
                                    Auth::attempt(['username' => $studenlogin->username, 'password' => $studenlogin->password]);
                                }
                                $user = Auth::user();
                               // $last_logged_session = LogsModel::where(['userid' => $user->id, 'action' => 'login'])->whereNotNull('current_student_session_id')->count();
                             
                               $lastLoginLogs = LogsModel::where('userid', $user->id)
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
                               }

                                if ($nonEmptyStudentSessionCount > 1) {
                                    Auth::logout();
                                    return response()->json([
                                        'status' => true,
                                        'student_session' => "Sorry! You have reached the maximum device login limit.",
                                    ]);
                                    
                                } else {
                                   // $session_id = session()->getId();
                                   // session(['usertype' => $user->usertype]);
                                    $session_id = session()->getId();
                                    session(['usertype' => $user->usertype]);
                                    session(['student_session_id' => $session_id]);
                                    $browser = Agent::browser();
                                    $version = Agent::version($browser);
                                    $session_id = session()->getId();
                                    $device_id = $request->deviceid;
                                    $log_arr = [
                                        'curr_user_id' => $user->id,
                                        'curr_name' => $user->name,
                                        'username' => $user->username,
                                        'usertype' => $user->usertype,
                                        'ip' => $request->ip(),
                                        'device' => Agent::device(),
                                        'platform' => Agent::platform(),
                                        'browser' => $browser,
                                        'version' => $version,
                                        'info' => 'Student Login',
                                        'session' => $session_id,
                                        'device_id' => $device_id,
                                    ];
                                    LogsModel::create(['userid' => $user->id, 'action' => 'login', 'current_student_session_id' => $session_id, 'logs_info' => json_encode($log_arr)]);
                                   // LogsModel::create(['userid' => $user->id, 'action' => 'login', 'current_student_session_id' => $session_id, 'logs_info' => json_encode(['info' => 'Student Login', 'usertype' => $user->usertype])]);

                                    return response()->json([
                                        'status' => true,
                                        'message' => "Student Login Successfully",
                                    ]);
                                }

                                
                                
                                
                            }else{
                                return response()->json([
                                    'status' => false,
                                    'student_id_accesscode_time' => base64_encode($studenlogin->id),
                                    'access_code' => "access_code Process pending task",
                                ]);
                            }            
                        }
                    }
                }
            }else{
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors(),
                    ]);
                } 
     
    }

    public function StudentSessionLimit(Request $request)
    {
        return view('auth.student-deny');
    }

    public function StudentOtpLogin(Request $request){
        $validator = Validator::make($request->all(),[
             'phone_number' => 'required',
             'username' => 'required|exists:students,username',
             'password'  => 'required|exists:students,password',
        ]);
        if($validator->passes()){
           $studentOtp = Student::where('username', $request->username)
           ->where('password', $request->password)->first();
             if(empty($studentOtp)){
                return response()->json([
                   'status'  => false,
                   'username_password__not_exists' => "Number not registered. Kindly register.",
                ]);
             }else{
                $otpnumber = random_int(1000, 9999);
                if(empty($studentOtp->phone_number)){
                    $studentdata = Student::where('phone_number', $request->phone_number)->first();
                    if(empty($studentdata)){
                        $studentOtp->update([
                            'phone_number'  => $request->phone_number,
                            'otp'  => $otpnumber,
                            'otp_verified_at'  => null,
                            'otp_verified_till' => Carbon::now()->addSeconds(60)->format('Y-m-d H:i:s'),
                       ]);
                    }else{
                        return response()->json([
                            'status'  => false,
                            'phone_number_all_exists' => "The phone number has already been taken.",
                         ]);
                    }
                }else{
                    if($studentOtp->phone_number == $request->phone_number){
                        $studentOtp->update([
                            'phone_number'  => $request->phone_number,
                            'otp'  => $otpnumber,
                            'otp_verified_at'  => null,
                            'otp_verified_till' => Carbon::now()->addSeconds(60)->format('Y-m-d H:i:s'),
                       ]);
                    }else{
                        $studentdata = Student::where('phone_number', $request->phone_number)->first();
                        if(empty($studentdata)){
                            $studentOtp->update([
                                'phone_number'  => $request->phone_number,
                                'otp'  => $otpnumber,
                                'otp_verified_at'  => null,
                                'otp_verified_till' => Carbon::now()->addSeconds(60)->format('Y-m-d H:i:s'),
                           ]);
                        }else{
                            return response()->json([
                                'status'  => false,
                                'phone_number_all_exists' => "The phone number has already been taken.",
                             ]);
                        }
                    }
                    
                }
                $timereset = Carbon::parse($studentOtp->otp_verified_till)->format(30,'s');
                $this->OtpSentMessage($studentOtp->phone_number,$studentOtp->otp);
                return response()->json([
                    'status' => true,
                    'timeResetOtpUpdatetime' => $timereset,
                    'message' => "OTP sent successfully",
                ]);
             }
            
        }else{
            return response()->json([
               'status' => false,
               'errors' => $validator->errors(),
            ]);
        }
    }

    


    public function StudentLoginWithOtp(Request $request){
        $validator = Validator::make($request->all(),[
             'phone_number' => 'required',
        ]);
        if($validator->passes()){
           $studentOtp = Student::where('phone_number', $request->phone_number)->first();
             if(empty($studentOtp)){
                return response()->json([
                   'status'  => false,
                   'phone_number_not_exists' => "Number not registered. Kindly register.",
                ]);
             }else{
                $otpnumber = random_int(1000, 9999);
                $studentOtp->update([
                     'phone_number'  => $request->phone_number,
                     'otp'  => $otpnumber,
                     'otp_verified_at' => null,
                     'otp_verified_till' => Carbon::now()->addSeconds(60)->format('Y-m-d H:i:s'),
                ]);
                $timereset = Carbon::parse($studentOtp->otp_verified_till)->format(30,'s');
                $this->OtpSentMessage($studentOtp->phone_number,$studentOtp->otp);
                return response()->json([
                    'status' => true,
                    'timeResetOtpUpdatetime' => $timereset,
                    'message' => "OTP sent successfully",
                ]);
             }
            
        }else{
            return response()->json([
               'status' => false,
               'errors' => $validator->errors(),
            ]);
        }
    }

    public function StudentResetOTp(Request $request){
               $studentOtp = Student::where('id', $request->student_id )->first();
                $otpnumber = random_int(1000, 9999);
                $studentOtp->update([
                    // 'phone_number'  => $request->phone_number,
                     'otp'  => $otpnumber,
                     'otp_verified_at' => null,
                     'otp_verified_till' => Carbon::now()->addSeconds(60)->format('Y-m-d H:i:s'),
                ]);
                $timereset = Carbon::parse($studentOtp->otp_verified_till)->format(30,'s');
                $this->OtpSentMessage($studentOtp->phone_number,$studentOtp->otp);
                return response()->json([
                    'status' => true,
                    'timeResetOtpUpdatetime' => $timereset,
                    'message' => "OTP sent successfully",
                ]);
             }
    






      public function studentlist(Request $request)
     {
        $user = Auth::user();
       
        $schoolid = $request->input('school_id');
        $student_licence_error = $request->input('amp;student_licence_error');
        $grade = $request->input('grade');
        $student_status = $request->input('student_status');
        $student_update_status = $request->input('student_update_status');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $convert_select = $request->input('convert_select');
        $student_id_select = $request->input('student_id_select');
        
       // dd($convert_select);
        $school_id_update = $request->input('school_id_update');
        $class_list = Program::where('status', 1)->get();
        $schooldata = School::where('id', $schoolid)->first();
        $school_all_data = School::get();
         $mainQuery = DB::table('students')
         ->whereNull('deleted_at')
         ->select([
                'students.id',
                'students.name',
                'students.last_name',
                'students.phone_number',
                'students.otp',
                'students.start_date',
                'students.end_date',
                'students.email',
                'students.username',
                'students.last_name',
                'students.studenttype',
                'students.student_status',
                'students.password',
                'students.grade',
                'students.otp_verified_till',
                'students.school_id',
                'students.otp_verified_at',
                'students.view_password',
                'students.status',
                'students.section',
                'master_class.class_name As grade_class_name',
                'users.id As user_id',
         ])
         ->leftJoin('users', 'users.student_id', 'students.id')
         ->orderBy('id', 'desc');
            if(isset($grade) && (int)$grade>0){
              $mainQuery->where('students.grade', (int)$grade);
            }
            if(isset($student_status) && $student_status){
              $mainQuery->where('students.student_status', $student_status);
            }
            if(isset($schoolid) && (int)$schoolid>0){
                $mainQuery->where('students.school_id', $schoolid);
            }
            $mainQuery->leftJoin('master_class', 'master_class.id', 'students.grade'); 
           // dd($student_update_status);
            if(isset($student_update_status) && $student_update_status){
                  
                if($student_update_status == 'paid'){
                    $all_id = isset($convert_select[0]) ? $convert_select[0] : '';
                    $student_update_id = explode(',' , $all_id);
                    foreach ($student_update_id as $stu_id){
                         $student_data = Student::where('id', $stu_id)->first();
                         if(!empty($student_data)){
                            $student_data->update([
                              'student_status' => $student_update_status,
                              'start_date' => $start_date, //isset($start_date) ? $start_date : '',
                              'end_date' => $end_date,//isset($end_date) ? $end_date : '',
                            ]);
                         }
                    } 
                }else{
                    $all_id = isset($convert_select[0]) ? $convert_select[0] : '';
                    $student_update_id = explode(',' , $all_id);
                        foreach ($student_update_id as $stu_id){
                            $student_data = Student::where('id', $stu_id)->first();
                            if(!empty($student_data)){
                                $student_data->update([
                                'student_status' => $student_update_status,
                                ]);
                            }
                        } 
                }
                
                
                
               } 
               if(isset($school_id_update) && $school_id_update){
                $all_id = isset($student_id_select[0]) ? $student_id_select[0] : '';
                $student_id = explode(',' , $all_id);
               
                foreach ($student_id as $stu_id){
                     $student_data = Student::where('id', $stu_id)->first();
                     if(!empty($student_data)){
                        $student_data->update([
                          'school_id' => $school_id_update,
                        ]);
                     }
                }    
               } 
         $student = $mainQuery->get();
         $statusCounts = $student->groupBy('student_status')->map->count();
         $demoCount = $statusCounts->get('Demo', 0);
         $paidCount = $statusCounts->get('Paid', 0);
         $pendingCount = $statusCounts->get('Pending', 0);
         $total_student = $student->count();
         if($student_licence_error == 'error'){
            $redirect =  route('student.list', ['school_id' => $schoolid]);
            return redirect($redirect)->with('error', 'Maximum Student licences limit reached.');
         }else{
            return view('student.student-list',compact('user', 'student', 'demoCount','paidCount','pendingCount', 'total_student', 'class_list','schoolid','schooldata','student_status','grade','school_all_data'));
         }
         
     } 



     public function studentlistSchooladmin(Request $request)
     {
        $user = Auth::user();
       // dd($user->usertype);
        $schoolid = $request->input('school_id');
        $student_licence_error = $request->input('amp;student_licence_error');
        $grade = $request->input('grade');
        $student_status = $request->input('student_status');
        $student_update_status = $request->input('student_update_status');
        $convert_select = $request->input('convert_select');
        $student_id_select = $request->input('student_id_select');
        $school_id_update = $request->input('school_id_update');
        $class_list = Program::where('status', 1)->get();
        $schooldata = School::where('id', $schoolid)->first();
        $school_all_data = School::get();
         $mainQuery = DB::table('students')
         ->whereNull('deleted_at')
         ->select([
                'students.id',
                'students.name',
                'students.last_name',
                'students.phone_number',
                'students.otp',
                'students.email',
                'students.username',
                'students.last_name',
                'students.studenttype',
                'students.student_status',
                'students.password',
                'students.grade',
                'students.otp_verified_till',
                'students.school_id',
                'students.otp_verified_at',
                'students.view_password',
                'students.status',
                'students.section',
                'master_class.class_name As grade_class_name'
         ])->orderBy('id', 'desc');
            if(isset($grade) && (int)$grade>0){
              $mainQuery->where('students.grade', (int)$grade);
            }
            if(isset($student_status) && $student_status){
              $mainQuery->where('students.student_status', $student_status);
            }
            if(isset($schoolid) && (int)$schoolid>0){
                $mainQuery->where('students.school_id', $schoolid);
            }
            $mainQuery->leftJoin('master_class', 'master_class.id', 'students.grade'); 
            if(isset($student_update_status) && $student_update_status){
                $all_id = isset($convert_select[0]) ? $convert_select[0] : '';
                $student_update_id = explode(',' , $all_id);
               
                foreach ($student_update_id as $stu_id){
                     $student_data = Student::where('id', $stu_id)->first();
                     if(!empty($student_data)){
                        $student_data->update([
                          'student_status' => $student_update_status,
                        ]);
                     }
                }    
               } 
               if(isset($school_id_update) && $school_id_update){
                $all_id = isset($student_id_select[0]) ? $student_id_select[0] : '';
                $student_id = explode(',' , $all_id);
               
                foreach ($student_id as $stu_id){
                     $student_data = Student::where('id', $stu_id)->first();
                     if(!empty($student_data)){
                        $student_data->update([
                          'school_id' => $school_id_update,
                        ]);
                     }
                }    
               } 
         $student = $mainQuery->get();
         $statusCounts = $student->groupBy('student_status')->map->count();
         $demoCount = $statusCounts->get('Demo', 0);
         $paidCount = $statusCounts->get('Paid', 0);
         $pendingCount = $statusCounts->get('Pending', 0);
         $total_student = $student->count();
         if($student_licence_error == 'error'){
            $redirect =  route('student.list', ['school_id' => $schoolid]);
            return redirect($redirect)->with('error', 'Maximum Student licences limit reached.');
         }else{
            return view('student.student-list-school-admin',compact('user', 'student', 'demoCount','paidCount','pendingCount', 'total_student', 'class_list','schoolid','schooldata','student_status','grade','school_all_data'));
         }
         
     }


    /*  public function studentlist(Request $request)
     {

        $schoolid = $request->input('school_id');
        $student_id_select = $request->input('student_id_select');
        $school_id_update = $request->input('school_id_update');
        $student_update_status = $request->input('student_update_status');
        $class_list = Program::where('status', 1)->get();
        $schooldata = School::where('id', $schoolid)->first();
        $school_all_data = School::get();
        $mainQuery_count = Student::where('school_id', $schoolid)->whereNull('deleted_at');
        if ($request->ajax()) {
            $schoolid = $request->input('school');
            $checked_variable = $request->input('checked_variable');
            $grade = $request->input('grade');
            $student_status = $request->input('student_status');
            $convert_select = $request->input('convert_select');

            $student_id_select = $request->input('student_id_select');
            $student_id_filter = $request->input('student_id_filter');


            $student_update_status_filter = $request->input('student_update_status_filter');
            if(isset($student_update_status_filter) && $student_update_status_filter){
                $student_update_id = explode(',' , $convert_select);
                foreach ($student_update_id as $stu_id){
                     $student_data = Student::where('id', $stu_id)->first();
                     if(!empty($student_data)){
                        $student_data->update([
                          'student_status' => $student_update_status_filter,
                        ]);
                     }
                }    
               } 

               if(isset($student_id_filter) && $student_id_filter){
                $all_id = isset($student_id_select[0]) ? $student_id_select[0] : '';
                $student_id = explode(',' , $all_id);
               
                foreach ($student_id as $stu_id){
                     $student_data = Student::where('id', $stu_id)->first();
                     if(!empty($student_data)){
                        $student_data->update([
                          'school_id' => $student_id_filter,
                        ]);
                     }
                }    
               
               } 



            $student_mainQuery = Student::where('school_id', $schoolid)->whereNull('deleted_at')->orderBy('id', 'desc')
            ->leftJoin('master_class', 'master_class.id', 'students.grade')
            ->select('students.*', 'master_class.class_name');
            if (isset($grade) && (int)$grade > 0) {
                $student_mainQuery->where('grade', (int)$grade);
            }
            if (isset($student_status) && $student_status) {
                $student_mainQuery->where('student_status', $student_status);
            }
            $data = $student_mainQuery->orderBy('id', 'desc');


            return Datatables::of($data)
                ->addColumn('index', function ($row) {
                    static $index = 0;
                    return ++$index;
                })
                ->editColumn('id', function ($row) use ($checked_variable) {
                    $checked = ($checked_variable == 'checked') ? 'checked' : '';
                    return '<input class="custom-control-input" type="checkbox" value="' . $row->id . '" id="checkboxCheck" ' . $checked . '>';
                })
                ->editColumn('name', function ($row) {
                    $html = '<div class="engineer-listing">' .
                        '<p class="m-0 custom-nowrap-ellipsis"><b>Name: </b>' . $row->name . '</p>' .
                        '<p class="m-0 custom-nowrap-ellipsis"><b>Last Name: </b>' . $row->name . '</p>' .
                        '<p class="m-0 custom-nowrap-ellipsis"><b>Username: </b>' . $row->username . '</p>' .
                        '<p class="m-0 custom-nowrap-ellipsis"><b>Grade: </b>' . $row->class_name . '</p>' .
                        '<p class="m-0 custom-nowrap-ellipsis"><b>Phone: </b>' . $row->phone_number;
                
                    if ($row->otp_verified_at && $row->school_id > 0) {
                        $html .= '<i class="fas fa-check verified-icon"></i>';
                    }
                
                    $html .= '</p>' .
                        '<p class="m-0 custom-nowrap-ellipsis"><b>Email: </b>' . $row->email . '</p>' .
                        '<p class="m-0 custom-nowrap-ellipsis"><b>Password: </b>' . $row->view_password . '</p>' .
                    '</div>';
                    return $html;
                })
                
                ->editColumn('section', function ($row) {
                    if ($row->section) {
                        return substr($row->section, 0, 8) . '<a href="javascript:void(0);" data-description="' . $row->section . '" class="section_popup">....</a>';
                    }
                    return null; 
                })
                ->editColumn('student_status', function ($row) {
                    $statusClass = '';
                    switch ($row->student_status) {
                        case 'Demo':
                            $statusClass = 'text-white badge bg-success';
                            break;
                        case 'Paid':
                            $statusClass = 'text-white badge bg-danger';
                            break;
                        case 'Pending':
                            $statusClass = 'text-white badge bg-warning';
                            break;
                        default:
                            break;
                    }
                    $status = '<a href="#" class="text-white ' . $statusClass . '" data-status="' . $row->status . '">'
                                . $row->student_status .
                              '</a>';
                    return $status;
                })
                ->editColumn('status', function ($row) {
                    $statusClass = $row->status == 1 ? 'success' : 'danger';
                    $statusText = $row->status == 1 ? 'Active' : 'Inactive';
                    $btn = '<a href="javascript:void(0)" id="status_' . $row->id . '" data-id="' . $row->id . '" 
                        class="change_status text-white badge bg-' . $statusClass . '" data-status="' . $row->status . '">
                        ' . $statusText . '</a>';
                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $editBtn = '<a href="' . route('student.edit', ['studentid' => $row->id]) . '"
                    data-id=' . $row->id . '
                    class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Edit</a>';

                    $removeBtn = '<a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#bs-password-modal" 
                    class="remove_user_data waves-effect waves-light btn btn-sm btn-outline btn-danger mb-5"
                    data-id=' . $row->id . '
                    data-userid="' . $row->id . '"
                    >Delete</a>';

                  $history = '<a href="' . route('student.logs.list', ['userid' => $row->id]) . '"
                    data-id=' . $row->id . '
                    class="waves-effect waves-light btn btn-sm btn-outline btn-warning mb-5">Logs</a>';

                    return $editBtn . ' ' . $removeBtn . ' ' .$history;
                })



                ->rawColumns(['id','name', 'student_status', 'status', 'section',   'action' ])
                ->toJson();
        }
        $student = $mainQuery_count->get();
        $statusCounts = $student->groupBy('student_status')->map->count();
        $demoCount = $statusCounts->get('Demo', 0);
        $paidCount = $statusCounts->get('Paid', 0);
        $pendingCount = $statusCounts->get('Pending', 0);
        $total_student = $student->count();
        return view('student.student-list',compact('demoCount','paidCount','pendingCount', 'total_student', 'class_list','schoolid','schooldata','school_all_data'));
     } */



    public function SingleStudent(Request $request){
        
         $validator = Validator::make($request->all(),[
            'forgot_student_id' => 'required',
         ]);
         $single_student = ForgotStudent::where('id', $request->forgot_student_id)
         ->with('studentgrade')->first();
         if(!empty($single_student)){
            return response()->json([
                'status' => true,
                'single_student' => $single_student,
                'message' => "Forgot Student Retrive successfully",
                ]);
            }    
    }
     public function StudentStatusChange(Request $request){
        $schoolid = $request->input('school_id');
        $student_status = $request->input('student_status');
         if(!empty($student_status)){
            $studentstatus_update = Student::where('student_status', $student_status)
                                             ->where('school_id', $schoolid)->get();
            foreach($studentstatus_update as $studentstatus){
                
            }                                 
         }
     }

     public function change_student_status (Request $request)
    {
        $studentId = $request->studentid;
        $status = ($request->status == 1) ? 0 : 1;
        DB::table('students')->where('id', $studentId)->update(['status' => $status]);
        echo ($status == 1) ? 'Active' : 'Inactive';
    }

     public function addStudent(Request $request){
        $schoolid = $request->input('school_id');
        $schooldata = School::where('id', $schoolid)->first();
        $programs = Program::get();
        return view('student.student-add', compact('schoolid','programs','schooldata'));
     }
     public function CreateStudent(Request $request)
     {
            
       
        $validator = Validator::make($request->all(), [
            'grade' => 'required',
            'name' => 'required',
            'last_name' => 'required',
            'set_password' => 'required',
            'confirm_password' => 'required|same:set_password',
            'student_status' => 'required',
            'section' => 'nullable',
            'phone_number' => 'nullable|unique:students',
            'email' => 'nullable|email|unique:users',
            'school_id' => 'required|exists:school,id',
        ]);


        if($validator->passes()) {
               $username = $request->name . '_'. substr($request->last_name,  0, 1);
                $lastname =  '\_'. substr($request->last_name,  0, 1);

                /* $usernameCount = DB::table('students')->select([
                    'id',
                    'username',
                ])->where('username', 'like', "%{$lastname}%")->count(); */
                $usernameCount = DB::table('users')->select([
                    'id',
                    'username',
                   ])->where('username', 'like', "%{$lastname}%")->count();

                if($usernameCount == 0){
                    $username = $username;
                }else{
                    $singlenumber  = str_pad((int) $usernameCount + 1, 2,  '0', STR_PAD_LEFT);
                    $username = strtolower($username . $singlenumber);
                }
                $check_school_user = School::with(['student' => function ($query) {
                    $query->where('student_status', '=', 'Paid')->whereNull('deleted_at');
                }])->where(['is_deleted' => 0, 'id' => $request->school_id])->orderBy('id')->first();
                $total_student = $check_school_user->student->count();
                
            if ($check_school_user->student_licence > $total_student) {

           // $passwordgenrate = Str::random(5);
            $StudentCreate = new Student;
            $StudentCreate->grade = $request->grade;
            $StudentCreate->name = $request->name;
            $StudentCreate->last_name = $request->last_name;
            $StudentCreate->student_status = $request->student_status;
            $StudentCreate->phone_number = $request->phone_number;
            $StudentCreate->email = $request->email;
            $StudentCreate->section = $request->section;
            $StudentCreate->school_id = $request->school_id;
            $StudentCreate->username = $username;
            $StudentCreate->status = 1;
            $StudentCreate->password = Hash::make($request->set_password);
           // $StudentCreate->confirm_password = $passwordgenrate;
           // $StudentCreate->view_password = $passwordgenrate;
            $StudentCreate->save();

            $UserCreate = new User;
            $UserCreate->name = $StudentCreate->name;
            $UserCreate->usertype = 'student';
            $UserCreate->school_id = $StudentCreate->school_id;
            $UserCreate->student_id = $StudentCreate->id;
            $UserCreate->status = $StudentCreate->status;
            $UserCreate->username = $StudentCreate->username;
            $UserCreate->grade = $StudentCreate->grade;
            $UserCreate->password = $StudentCreate->password;
          //  $UserCreate->view_pass = $StudentCreate->password;
            $UserCreate->save();
            
            return response()->json([
                'status' => true,
                'message' => "Create Student successfully",
            ]);

        }else{
            return response()->json([
                'status' => false,
                'student_licence' => "error",
            ]);
        }

    }else{
        return response()->json([
            'status' => false,
            'errors' => $validator->errors(),
            ]);
        }
     }


     public function updateStudent(Request $request){
        $studentId = $request->input('studentid');
        //$studentdata = DB::table('students')->where('id', $studentId)->first();


        $mainQuery = DB::table('students')->where('students.id', $studentId)
        ->whereNull('students.deleted_at')
           ->select([
            'students.id',
            'students.name',
            'students.last_name',
            'students.phone_number',
            'students.otp',
            'students.email',
            'students.username',
            'students.student_image',
            'students.last_name',
            'students.studenttype',
            'students.student_status',
            'students.start_date',
            'students.end_date',
            'students.password',
            'students.grade',
            'students.otp_verified_till',
            'students.school_id',
            'students.otp_verified_at',
            'students.view_password',
            'students.status',
            'students.section',
        ])->orderBy('id', 'desc');
        $studentdata = $mainQuery->get()->map(function ($row) {
             $row->start_date = isset($row->start_date) ? Carbon::createFromFormat('Y-m-d H:i:s', $row->start_date)->format('Y-m-d') : '';
            $row->end_date = isset($row->end_date) ? Carbon::createFromFormat('Y-m-d H:i:s', $row->end_date)->format('Y-m-d') : '';
          return $row;
      })->first();

      //dd($studentdata);



        $programs = Program::get();

        return view('student.student-edit', compact('studentdata','programs')); 
     } 

     public function editstudent($studentid, Request $request){
        $studentupdate = Student::find($studentid);
        if(empty($studentupdate)){
                return response()->json([
                    'status' => false,
                    'message' => "Student Not Found",
                ]);
        }
      //  dd($request);
        $validator = Validator::make($request->all(),[
            'grade' => 'required',
            'name' => 'required',
            //'password' => 'nullable',
            'set_password' => 'nullable',
            'confirm_password' => 'nullable|required_with:set_password|same:set_password',
            
            'section' => 'nullable',
            //'confirm_password' => 'required',
            'last_name' => 'required',
            'student_status' => 'required',
            'phone_number' => 'nullable',
            //'email' => 'nullable|email',
            'email' => 'nullable|email|unique:users',
         ]);
            if($studentupdate->phone_number == $request->phone_number){
                $studentnumber = $request->phone_number;
            }else{
               $studentdata = Student::where('phone_number',$request->phone_number)->first();  
                 if(!empty($studentdata)){
                    return response()->json([
                        'status' => false,
                        'phone_number_exists' => "The phone number has already been taken.",
                    ]);
                 }
            }
           $username = $request->name . '_'. substr($request->last_name,  0, 1);
           $lastname =  '\_'. substr($request->last_name,  0, 1);
           $usernameCount = DB::table('users')->select([
            'id',
            'username',
           ])->where('username', 'like', "%{$lastname}%")->count();

           if($usernameCount == 0){
               $username = $username;
           }else{
             $singlenumber  = str_pad((int) $usernameCount + 1, 2,  '0', STR_PAD_LEFT);
             $username = strtolower($username . $singlenumber);
           }
            if($validator->passes()){

                if(!empty($request->set_password)){
                    //$studentupdate->password = $request->password;
                    $studentupdate->password = Hash::make($request->set_password);
                  //  $studentupdate->view_password = $request->password;
                }

                $check_school_user = School::with(['student' => function ($query) {
                    $query->where('student_status', '=', 'Paid')->whereNull('deleted_at');
                }])->where(['is_deleted' => 0, 'id' => $studentupdate->school_id])->orderBy('id')->first();
                $total_student = $check_school_user->student->count();
                if(($check_school_user->student_licence == $total_student && $request->student_status != 'Paid') ||  ($studentupdate->student_status == $request->student_status) || ($check_school_user->student_licence > $total_student) ){

                $studentupdate->grade = $request->grade;
                $studentupdate->name = $request->name;
                $studentupdate->section = $request->section;
                $studentupdate->last_name = $request->last_name;
                $studentupdate->username = $username;
                $studentupdate->student_status = $request->student_status;
                $studentupdate->phone_number = $request->phone_number;
                $studentupdate->start_date = $request->start_date;
                $studentupdate->end_date = $request->end_date;
                $studentupdate->email = $request->email;
                $studentupdate->save();
                $UserCreate = User::where('student_id', $studentupdate->id)->where('usertype', 'student')->first();
        
                $UserCreate->name = $studentupdate->name;
                $UserCreate->usertype = 'student';
                $UserCreate->school_id = $studentupdate->school_id;
                $UserCreate->student_id = $studentupdate->id;
                $UserCreate->status = $studentupdate->status;
                $UserCreate->username = $studentupdate->username;
                $UserCreate->grade = $studentupdate->grade;
                $UserCreate->password = $studentupdate->password;
               // $UserCreate->view_pass = $studentupdate->password;
                $UserCreate->save();

                    return response()->json([
                        'status' => true,
                        'message' => "Student Update successfully",
                    ]);
                }else{


                    //dd("ansari no update");
                    return response()->json([
                        'status' => false,
                        'student_licence' => "error",
                    ]);
                }


                 }else{
                    return response()->json([
                        'status' => false,
                        'errors' => $validator->errors(),
                     ]);
        
                 }
     }

    public function destroy(Request $request)
    {
        $studentid = $request->input('studentid');
        $studentpass = $request->input('studentpass');
            $user = Auth::user();
            if (($user) && $user->usertype == "superadmin") {
                $studentdelete = Student::where('id', $studentid)->first();
                DB::table('users')->where('student_id', $studentid)->delete();
                $studentdelete->delete();
                
                echo "removed";
            } else {
                echo "Something went wrong.";
            }
    }


    public function resetPassword(Request $request)
    {
        $passWord = Str::random(5);
            $resetPass = Student::where('id', $request->studentid)
            ->update(['view_password' => $passWord, 'password' => $passWord]);
        if ($resetPass) {
            $student_email = Student::where('id', $request->studentid)->first();
            $details = [
                'view' => 'emails.reset_password',
                'subject' => 'User Account Password Reset - Valuez',
                'title' => $student_email->name,
                'email' => $student_email->email,
                'pass' => $passWord
            ];
            Mail::to($student_email->email)->send(new \App\Mail\TestMail($details));
        }
    }




    public function uploadStudent(Request $request)
    {
        $school_id = $request->input('school_id');
        $schooldata = School::where('id', $school_id)->first();
        $BulkUpload_data = BulkUpload::where('school_id', $school_id)->get();

        return view('student.bulkUploadForm', compact('school_id','schooldata','BulkUpload_data'));
    }

    

    public function import(Request $request)
    {
        $school_id = $request->input('school_id');
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|mimes:csv,xls,xlsx',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $requestArr = $validator->validated();

        $dataArr = Excel::toArray(new StudetsImport, $requestArr['csv_file'])[0];
        $studentArr = ['student' => $dataArr];
        $validator = Validator::make($studentArr, [
            'student.*.grade' => 'required',
            'student.*.name' => 'required',
            'student.*.password' => 'required',
            'student.*.last_name' => 'required',
            'student.*.student_status' => 'required',
           // 'student.*.phone_number' => 'nullable|unique:students',
            'student.*.phone_number' => 'nullable',
            'student.*.email' => 'nullable|email',
            'student.*.section' => 'nullable',
            'student.*.school_id' =>  'required|exists:school,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                ]);
        }
        $requestArr = $validator->validated();
        if($validator->passes()){
        foreach ($requestArr['student'] as $student) {
             if($school_id == $student['school_id']){
                 $sutdentid = $student['school_id'];
             }else{
                return response()->json([
                    'status' => false,
                    'school_id_not_exists' => "school_id does't exists",
                    ]);
             }
             $username = $student['name'] . '_'. substr($student['last_name'],  0, 1);
                $lastname =  '\_'. substr($student['last_name'],  0, 1);


                /* $usernameCount = DB::table('students')->select([
                   'id',
                   'username',
                ])->where('username', 'like', "%{$lastname}%")->count(); */

                $usernameCount = DB::table('users')->select([
                    'id',
                    'username',
                   ])->where('username', 'like', "%{$lastname}%")->count();

               $singlenumber  = str_pad((int) $usernameCount + 1, 2,  '0', STR_PAD_LEFT); 

            $username = strtolower($username . $singlenumber);

        $gradedata = Program::where('class_name', $student['grade'])->first();
       // $passwordgenrate = Str::random(5);

        $check_school_user = School::with(['student' => function ($query) {
            $query->where('student_status', '=', 'Paid')->whereNull('deleted_at');
        }])->where(['is_deleted' => 0, 'id' => $sutdentid])->orderBy('id')->first();
        $total_student = $check_school_user->student->count();
        if ($check_school_user->student_licence > $total_student) {

        $createStudent = [
        'grade' => isset($gradedata->id) ? $gradedata->id : null,
        'name' => isset($student['name']) ? $student['name'] : null,
        'last_name' => isset($student['last_name']) ? $student['last_name'] : null,
        'username' => isset($username) ? $username : null,
        'password' => isset($student['password']) ? Hash::make($student['password']) : null,
        'phone_number' => isset($student['phone_number']) ? $student['phone_number'] : null,
        'section' => isset($student['section']) ? $student['section'] : null,
        'email' => isset($student['email']) ? $student['email'] : null,
        'status' => 1,
        'school_id' => isset($sutdentid) ? $sutdentid : 0,
        'student_status' => isset($student['student_status']) ? $student['student_status'] : null,
        ];
           $StudentArr = Student::create($createStudent);

           $UserCreate = new User;
            $UserCreate->name = $StudentArr->name;
            $UserCreate->usertype = 'student';
            $UserCreate->school_id = $StudentArr->school_id;
            $UserCreate->student_id = $StudentArr->id;
            $UserCreate->status = 1;
            $UserCreate->username = $StudentArr->username;
            $UserCreate->grade = $StudentArr->grade;
            $UserCreate->password = $StudentArr->password;
            $UserCreate->save();

        }else{
            return response()->json([
                'status' => false,
                'student_licence' => "error",
            ]);
        }
        }

        
        if ($requestArr['csv_file']) { 
            $file = $requestArr['csv_file']; 
            $destinationPath = 'uploads/bulk_upload_file/';
            $fileName = time() . $file->getClientOriginalName();
            $file->move($destinationPath, $fileName);
            BulkUpload::create([
                'school_id' => $school_id,
                'bulk_upload_file' => $fileName, 
                'status' => 1, 
               
            ]);
        }


             return response()->json([
                 'status' => true,
                 'message' => "Bulk Upload  successfully",
             ]);

            

             }else{
                 return response()->json([
                     'status' => false,
                     'errors' => $validator->errors(),
                     ]);
                 }
    }

    public function download(){
        $StudentExcelFile = Storage_path("ImportFile\StudentSample.xlsx");
        return response()->download($StudentExcelFile);
       
    }
    /* public function StudentForgot(Request $request){
        $program_list = Program::get();
        return view('auth.student_forgot', compact('program_list'));
    } */

/* Access Code */    
    public function studentaccesscode(Request $request)
    {   
       // dd($request->input('student_id'));
        $decryptedStudentId = base64_decode($request->input('student_id'));
      //  dd($decryptedStudentId);
        if(!empty($decryptedStudentId)){
            $student_data = Student::where('id', $decryptedStudentId)->first();
            
            return view('auth.student-access-code', compact('student_data'));
       }
    }
    public function AccessCodeGet(Request $request){
        $validator = Validator::make($request->all(),[
            //'otp' => 'nullable',
           // 'phone_number' => 'nullable',
            'school_name' => 'required',
            'teacher_name' => 'required',
            'roll_no' => 'required',
            //'passwords' => 'required',
            //'username' => 'required',
            'student_id' => 'required|exists:students,id',
         ]);
        // dd($request->student_id);
         if($validator->passes()){
            //$otpdata = Student::where('username', $request->username)->where('password', $request->passwords)->first();
            $otpdata = Student::where('id', $request->student_id)->first();
            if(empty($otpdata)){
                return response()->json([
                    'status' => false,
                    'message' => "Number not registered. Kindly register.",
                    ]);
            }else{
                $school_data = School::where('access_code', 'vz1234')->first();
               $check_school_user = School::with(['student' => function ($query) {
                $query->where('student_status', '=', 'Paid')->whereNull('deleted_at');
                }])->where(['is_deleted' => 0, 'id' => $school_data->id])->orderBy('id')->first();
                $total_student = $check_school_user->student->count();
            
               if ($check_school_user->student_licence > $total_student) {
                $otpdata->update([
                    'school_id'  => $school_data->id,
                    'student_status'  => $school_data->school_student_status,
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'student_licence' => "Maximum Subscription limit reached",
                ]);
            }
                /* $school_data->update([
                    'student_status_view'  => $school_data->is_demo,
                ]); */
                $UserUpdate = User::where('student_id', $otpdata->id)->where('usertype', 'student')->first();

                $UserUpdate->update([
                    'school_id'  => $otpdata->school_id,
                ]);

            $accesscodeCreate = new AccessCodes;
            $accesscodeCreate->school_name = $request->school_name;
            $accesscodeCreate->teacher_name = $request->teacher_name;
            $accesscodeCreate->roll_no = $request->roll_no;
            $accesscodeCreate->student_id = $otpdata->id;
            $accesscodeCreate->school_id = $school_data->id;
            $accesscodeCreate->save();

                return response()->json([
                    'status' => true,
                    'student_id' => $otpdata->id,
                    'message' => "School access code  successfully",
                ]); 
            }
            
        }else{
        return response()->json([
            'status' => false,
            'errors' => $validator->errors(),
            ]);

        }    
    }





/* Forgot Password Api start */
    public function StudentGetOtpForgot(Request $request){
        
        $validator = Validator::make($request->all(),[
            'username' => 'required|exists:students',
            'phone_number' => 'required',
        ]);
       
        if($validator->passes()){
            $studentdata = Student::where('username', $request->username)->first();
              if(empty($studentdata->phone_number)){
                $otpnumber = random_int(1000, 9999);
                $ForgotStudentCreate = new ForgotStudent;
                $ForgotStudentCreate->school_id = $studentdata->school_id;
                $ForgotStudentCreate->name = $studentdata->name;
                $ForgotStudentCreate->last_name = $studentdata->last_name;
                $ForgotStudentCreate->username = $request->username;
                $ForgotStudentCreate->phone_number = $request->phone_number;
                $ForgotStudentCreate->status = 1;
                $ForgotStudentCreate->forgot_student_status = 'Pending';
                $ForgotStudentCreate->otp_verified_at = null;
                $ForgotStudentCreate->otp = $otpnumber;
                $ForgotStudentCreate->otp_verified_till = Carbon::now()->addSeconds(60)->format('Y-m-d H:i:s');
                $ForgotStudentCreate->save();
                /* $studentdata->update([
                     'otp'  => $otpnumber,
                     'phone_number' => $request->phone_number,
                     'otp_verified_at' => null,
                     'otp_verified_till' => Carbon::now()->addSeconds(60)->format('Y-m-d H:i:s'),
                ]); */
                $timereset = Carbon::parse($ForgotStudentCreate->otp_verified_till)->format(30,'s');
                $this->OtpSentMessage($ForgotStudentCreate->phone_number,$ForgotStudentCreate->otp);
                return response()->json([
                    'status' => true,
                    'timeResetOtp' => $timereset,
                    'message' => "OTP sent successfully",
                ]);
                /* return response()->json([
                    'status' => false,
                    'student_not_store' => "Number not registered. Kindly register.",
                ]); */

              }else{
                $otpnumber = random_int(1000, 9999);
                $studentdata->update([
                     'otp'  => $otpnumber,
                    // 'phone_number' => $request->phone_number,
                     'otp_verified_at' => null,
                     'otp_verified_till' => Carbon::now()->addSeconds(60)->format('Y-m-d H:i:s'),
                ]);
                $timereset = Carbon::parse($studentdata->otp_verified_till)->format(30,'s');
                $this->OtpSentMessage($studentdata->phone_number,$studentdata->otp);
                return response()->json([
                    'status' => true,
                    'timeResetOtp' => $timereset,
                    'message' => "OTP sent successfully",
                ]);
              } 
            }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);

        }
    }

    public function StudentForgot(Request $request){
        $program_list = Program::get();
        return view('student_auth.forgot_student', compact('program_list'));
    }


    public function ForgotUsername(Request $request){
        $validator = Validator::make($request->all(),[
            'username' => 'required|exists:students',
         ]);
        if($validator->passes()){
            $otpdata = Student::where('username', $request->username)->first();
            if(empty($otpdata->phone_number)){
                return response()->json([
                    'status' => false,
                    'student_id' => base64_encode($otpdata->id),
                    'phone_number_not_store' => "The phone number is not store",
                    ]);
            }else{
                return response()->json([
                    'status' => true,
                    'student_id' => base64_encode($otpdata->id),
                    //'otpdata' => $otpdata,
                    'message' => "Username is selected",
                ]);
            }
        }else{
        return response()->json([
            'status' => false,
            'errors' => $validator->errors(),
            ]);
        }
    }
    public function ForgotPhoneVerify(Request $request){
        $student_id = base64_decode($request->input('student'));
         return view('student_auth.forgot-phone-number', compact('student_id'));
     }
     public function ForgotOtpVerify(Request $request)
     {
         $student_id = base64_decode($request->input('student'));
         $studentOtp = Student::where('id', $student_id)->first();
         $timereset = Carbon::parse($studentOtp->otp_verified_till)->format(30,'s');
         return view('student_auth.forgot-otp-verify', compact('student_id','timereset'));
     }


    public function PasswordChange(Request $request){
          $validator = Validator::make($request->all(),[
              'password' => 'required',
              'confirm_password' => 'required|same:password',
           ]);
          if($validator->passes()){
              $student_id = $request->input('student_id');
              $otpdata = Student::where('id', $student_id)->first();
              if(!empty($otpdata)){
                    // $UserCreate = User::where('student_id', $otpdata->id)->where('usertype', 'student')->first();
                     $otpdata->password = Hash::make($otpdata->password);
                     $otpdata->save();

                    $UserCreate = User::where('student_id', $otpdata->id)->where('usertype', 'student')->first();
                    $UserCreate->password = $otpdata->password;
                    $UserCreate->save();

                    return response()->json([
                        'status' => true,
                        'message' => "Password update successfully",
                    ]);
                 }
                  
              
          }else{
          return response()->json([
              'status' => false,
              'errors' => $validator->errors(),
              ]);
          }
      }


    public function StudentSetPassword(Request $request)
    {
        $student_id = base64_decode($request->input('student'));
        return view('student_auth.set_password', compact('student_id'));
    }

    /* public function ForgotUsername(Request $request){
        $validator = Validator::make($request->all(),[
            //'otp' => 'required',
            'username' => 'required|exists:students',
         ]);
        if($validator->passes()){
            $otpdata = Student::where('username', $request->username)->first();
            if(empty($otpdata->phone_number)){
                return response()->json([
                    'status' => false,
                    'phone_number_not_store' => "The phone number is not store",
                    ]);
            }else{
                    return response()->json([
                        'status' => true,
                        'otpdata' => $otpdata,
                        'message' => "Username is selected",
                    ]);
            }
        }else{
        return response()->json([
            'status' => false,
            'errors' => $validator->errors(),
            ]);
        }
    } */

    public function  StudentVerifiedOtpForgot(Request $request)
    {
         $validator = Validator::make($request->all(),[
            'username' => 'required',
            'otp' => 'required',
            'phone_number' => 'required',
            'mobile' => 'nullable',
         ]);
        if($validator->passes()){
            $otpdata = Student::where('otp', $request->otp)
               ->where('phone_number', $request->mobile)->first();
            if(empty($otpdata)){
                if(empty($otpdata->phone_number)){
                   $forgotStudentdata = ForgotStudent::where('otp', $request->otp)
                    ->where('phone_number', $request->phone_number)->with('studentschool')->first();
                    if(empty($forgotStudentdata)){
                        return response()->json([
                            'status' => false,
                            'queryfaild' => "OTP Invalid",
                        ]);
                    }else{
                        
                        $forgotStudentdata->update([
                            'otp_verified_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                        ]);
                        return response()->json([
                            'status' => true,
                            'otpdata' => $forgotStudentdata,
                            'forgot_password_table_message' => "OTP verified successfully, ",
                        ]);
                    }
                }else{
                    return response()->json([
                        'status' => false,
                        'queryfaild' => "OTP Invalid",
                    ]);
                }
                
            }else{
                
                $databaseTimestamp = $otpdata->updated_at; 
                $currentTimestamp = now();
                $timeDifference = $currentTimestamp->diffInSeconds($databaseTimestamp);
                $allowedTimeDifference = 90;
                if ($timeDifference <= $allowedTimeDifference) {
                    $otpdata->update([
                        'otp_verified_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                    ]);
                    return response()->json([
                        'status' => true,
                        'otpdata' => $otpdata,
                        'message' => "OTP verified successfully, ",
                    ]); 
 
                 } else {
                    // echo "Verification failed!";
                     return response()->json([
                         'status' => false,
                         'queryfaild' => "OTP Expire",
                     ]);
                     
                 } 

            }
            
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                ]);
        }
        
    }




/* Email Ke liye Otp */
    public function StudentEmailVerifiedOtpForgot(Request $request){
        $validator = Validator::make($request->all(),[
            'otp' => 'nullable',
            'phone_number' => 'required',
            'email' => 'required|email',
         ]);
        if($validator->passes()){
            $otpdata = Student::where('phone_number', $request->phone_number)->first();
            $otpnumber = random_int(1000, 9999);
            if(empty($otpdata->email)){
                $otpdata->update([
                    'email'  => $request->email,
                    'otp' => $otpnumber,
                ]);
                
                if (!empty($otpdata->email)) {
                    $details = [
                        'view' => 'emails.reset_password',
                        'subject' => $otpdata->name . ' Your Account Password Reset by Student - Valuez',
                        'title' => $otpdata->name,
                        'email' => $otpdata->email,
                        'pass' => $otpnumber,
                    ];
                    Mail::to($otpdata->email)->send(new \App\Mail\TestMail($details));
                }
            }else{
                $otpdata = Student::where('phone_number', $request->phone_number)->first();
                if($otpdata->email == $request->email){
                    $otpdata->update([
                        'email'  => $request->email,
                        'otp' => $otpnumber,
                    ]);
                    
                    if (!empty($otpdata->email)) {
                        $details = [
                            'view' => 'emails.reset_password',
                            'subject' => $otpdata->name . ' Your Account Password Reset by Student - Valuez',
                            'title' => $otpdata->name,
                            'email' => $otpdata->email,
                            'pass' => $otpnumber,
                        ];
                        Mail::to($otpdata->email)->send(new \App\Mail\TestMail($details));
                    }
                }else{
                    return response()->json([
                        'status' => false,
                        'student_email_store' => "Number not registered. Kindly register.",
                    ]);
                }
            }

            /* Mail::send([], [], function ($message) use ($otpdata) {
                $message->to($otpdata->email)
                    ->from($otpdata->email, $otpdata->name)
                    ->subject($otpdata->name);
                    
            }); */



            return response()->json([
                'status' => true,
                'message' => "Email Verified successfully",
            ]);
        }else{
        return response()->json([
            'status' => false,
            'errors' => $validator->errors(),
            ]);
        }
    }

    public function StudentForgotPassword(Request $request){
        $forgot_student = $request->input('forgot_student');
       // dd($forgot_student);
         $rules = [
            'email' => 'nullable|email',
            'username' => 'required',
            'phone_number' => 'required',
            'mobile' => 'nullable',
        ];
        
        if (isset($forgot_student) && $forgot_student == 'forgot_student_table') {
            $rules['grade'] = 'required';
            $rules['teacher_name'] = 'required';
            $rules['otp'] = 'required';
            $rules['school_name'] = 'required';
        }else {
            $rules['password'] = 'required';
            $rules['confirm_password'] = 'required';
            $rules['otp'] = 'nullable';
        }
        $validator = Validator::make($request->all(), $rules);
        if($validator->passes()){
            $otpdata = Student::where('username', $request->username)
                                ->where('phone_number', $request->mobile)->first();
            if(empty($otpdata->phone_number)){
                $forgotstudentdata = ForgotStudent::where('phone_number', $request->phone_number)
                               ->where('username', $request->username)
                               ->where('otp', $request->otp)->first();
                if(empty($forgotstudentdata)){
                    return response()->json([
                        'status' => false,
                        'queryfaild' => "Number not registered. Kindly register.",
                        ]);
                }else{
                    $forgotstudentdata->update([
                        'grade' =>  $request->grade,
                        'teacher_name' =>  $request->teacher_name,
                        'email' =>  $request->email,
                        'school_name' =>  $request->school_name,
                    ]);
                    return response()->json([
                        'status' => true,
                        'otpdata' => $forgotstudentdata,
                        'message' => "Forgot Password successfully",
                    ]);
                }               
            }else{
                if($request->password != $request->confirm_password){
                    return response()->json([
                        'status' => false,
                        'conform_password' => "“Passwords don’t match. (Please enter again)”",
                    ]);
                }
                    $otpdata->update([
                        'password' =>  Hash::make($request->password),
                        //'conform_password' => $request->confirm_password, 
                        //'view_password' => $request->password, 
                    ]);

                    $UserCreate = User::where('student_id', $otpdata->id)->where('usertype', 'student')->first();
                    $UserCreate->update([
                        'password' => Hash::make($request->password),
                        //'view_pass' => $request->password,
                    ]);
                    return response()->json([
                        'status' => true,
                        'otpdata' => $otpdata,
                        'message' => "Forgot Password successfully",
                    ]);
            }
        }else{
        return response()->json([
            'status' => false,
            'errors' => $validator->errors(),
            ]);
        }
    }
    public function ForgotPasswordList(Request $request){
        $schoolid = $request->input('school_id');
        $schooldata = School::where('id', $schoolid)->first();
        $student = ForgotStudent::with('studentgrade')->get();
        $class_list = Program::where('status', 1)->get();
        return view('student.student-forgot-list',compact('student','class_list','schoolid','schooldata'));
   }

   public function ForgotPasswordSchoolAdmin(Request $request)
   {
       //$schooladminlist = ForgotPasswordSchoolAdmin::where('status', 1)->get();
       $schooladminlist = DB::table('forgot_password_school_admins')
       ->whereNull('deleted_at')
       ->select([
           'forgot_password_school_admins.id',
           'forgot_password_school_admins.name',
           'forgot_password_school_admins.email',
           'forgot_password_school_admins.user_id',
           'forgot_password_school_admins.forgot_noty_password',
           'forgot_password_school_admins.status',
           'forgot_password_school_admins.created_at',
           'forgot_password_school_admins.updated_at',
           'school.school_name',
           'users.view_pass',
       ])->leftJoin('users', 'users.id', 'forgot_password_school_admins.user_id')
       ->leftJoin('school', 'school.id', 'users.school_id')
       ->orderBy("forgot_password_school_admins.id", "DESC")->get();

//dd($schooladminlist);

       return view('school.school-forgot-list',compact('schooladminlist'));
   }


   public function forgotPasswordNotify()
   {
        $forgot_noty_password = DB::table('forgot_password_school_admins')
        ->where('forgot_noty_password', 1)->count();
       
       return response()->json([
           'status' => true,
           'forgot_noty_password' => $forgot_noty_password,
           'message' => "Forgot Password notification count successfully",
       ]);
   }



    public function getUpdateForgotStudent(Request $request){
        $student_id = $request->input('student_id');
        $studentdata = DB::table('forgot_students')->where('id', $student_id)->first();
        $programs = Program::get();
        return view('student.student-forgot-edit', compact('studentdata','programs')); 
    }
    public function UpdateForgotStudent(Request $request){
        $student_id = $request->input('student_id');
        $studentupdate = ForgotStudent::find($student_id);
        if(empty($studentupdate)){
                return response()->json([
                    'status' => false,
                    'message' => "Student Not Found",
                ]);
        }
        $validator = Validator::make($request->all(), [
            'grade' => 'required',
            'name' => 'required',
            'last_name' => 'required',
            'student_status' => 'required',
            'password' => 'required',
            'phone_number' => 'nullable|unique:students',
            'email' => 'nullable|email|unique:students',
            //'school_id' => 'required|exists:school,id',
        ]);
        //$otpnumber = random_int(1000, 9999);
        if($validator->passes()) {
               $username = $request->name . '_'. substr($request->last_name,  0, 1);
               $lastname =  '\_'. substr($request->last_name,  0, 1);

               /* $usernameCount = DB::table('students')->select([
                  'id',
                  'username',
               ])->where('username', 'like', "%{$lastname}%")->count(); */

               $usernameCount = DB::table('users')->select([
                'id',
                'username',
               ])->where('username', 'like', "%{$lastname}%")->count();


               if($usernameCount == 0){
                   $username = $username;
               }else{
                 $singlenumber  = str_pad((int) $usernameCount + 1, 2,  '0', STR_PAD_LEFT);
                 $username = strtolower($username . $singlenumber);
               }
            $check_school_user = School::with(['student' => function ($query) {
                $query->where('student_status', '=', 'Paid')->whereNull('deleted_at');
            }])->where(['is_deleted' => 0, 'id' => $studentupdate->school_id])->orderBy('id')->first();
            $total_student = $check_school_user->student->count();
            
        if ($check_school_user->student_licence > $total_student) {



            $StudentCreate = new Student;
            $StudentCreate->grade = $request->grade;
            $StudentCreate->name = $request->name;
            $StudentCreate->last_name = $request->last_name;
            $StudentCreate->student_status = $request->student_status;
            $StudentCreate->phone_number = $request->phone_number;
            $StudentCreate->email = $request->email;
           // $StudentCreate->otp = $otpnumber;
            $StudentCreate->school_id = $studentupdate->school_id;
            $StudentCreate->username = $username;
            $StudentCreate->status = 1;
            $StudentCreate->password = Hash::make($request->password);
           // $StudentCreate->confirm_password = $request->password;
          //  $StudentCreate->view_password = $request->password;
            $StudentCreate->save();
            $studentupdate->delete();

            $UserCreate = new User;
            $UserCreate->name = $StudentCreate->name;
            $UserCreate->usertype = 'student';
            $UserCreate->school_id = $StudentCreate->school_id;
            $UserCreate->student_id = $StudentCreate->id;
            $UserCreate->status = $StudentCreate->status;
            $UserCreate->username = $StudentCreate->username;
            $UserCreate->grade = $StudentCreate->grade;
            $UserCreate->password = $StudentCreate->password;
            $UserCreate->save();
            
            return response()->json([
                'status' => true,
                'message' => "Create Student successfully",
            ]);
        }else{
            return response()->json([
                'status' => false,
                'student_licence' => "error",
            ]);
        }

            }else{
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors(),
                    ]);
            }
       
    }
    public function StatusUpdateForgot(Request $request){
        $student_id = $request->input('student_id');
        $student_status = $request->input('student_status');
        $student_delete = $request->input('student_delete');
        $studentupdate = ForgotStudent::find($student_id);
        if(empty($studentupdate)){
                return response()->json([
                    'status' => false,
                    'message' => "Student Not Found",
                ]);
        }
        if(!empty($student_status)){
            $studentupdate->update([
                'forgot_student_status' => $student_status,
              ]);
              return redirect()->intended(route('student.forgot.list'))->withSuccess('Student Status successfully');
        }
        if(!empty($student_delete)){
               $studentupdate->delete();
              return redirect()->intended(route('student.forgot.list'))->withSuccess('Student Delete successfully');
        }

    }
     /* filter data */
     public function indexfilter(Request $request)
     {
         $validator = Validator::make($request->all(), [
             'grade' => 'nullable',
         ]);
        // dd($request);
         //$keyword = $request->input('keyword');
 
         $filteredData = Student::where('grade', $request->grade)->get();
         
        dd($filteredData);
 
        // return response()->json($filteredData);        
         $class_list = Program::where('status', 1)->get();
         $course_list = Course::where('status', 1)->get();

         /* return response()->json([
            'status' => true,
            'message' => "Create Student successfully",
        ]); */
       //  return view('student.student-list', compact('class_list', 'course_list'));
     }
    
/* Register From */
     public function RegisterStudentList(Request $request){
           $school_all_data = School::get();
           $student_id_select = $request->input('student_id_select');
           $school_id_update = $request->input('school_id_update');
          // dd($request);
           $mainQuery = DB::table('students')->where('school_id', 0)
           ->whereNull('deleted_at')
           ->select([
                  'students.id',
                  'students.name',
                  'students.last_name',
                  'students.phone_number',
                  'students.otp',
                  'students.email',
                  'students.username',
                  'students.last_name',
                  'students.studenttype',
                  'students.student_status',
                  'students.password',
                  'students.grade',
                  'students.otp_verified_till',
                  'students.school_id',
                  'students.otp_verified_at',
                  'students.view_password',
                  'students.status',
                  'master_class.class_name As grade_class_name'
              ]);
              
              $mainQuery->leftJoin('master_class', 'master_class.id', 'students.grade');
           $student = $mainQuery->get();


           if(isset($school_id_update) && $school_id_update){
            $all_id = isset($student_id_select[0]) ? $student_id_select[0] : '';
            $student_id = explode(',' , $all_id);
            foreach ($student_id as $stu_id){
                 $student_data = Student::where('id', $stu_id)->first();
                 if(!empty($student_data)){
                    $student_data->update([
                      'school_id' => $school_id_update,
                    ]);
                 }
            }    
           
           } 




  
           return view('student.register-student-list',compact('student','school_all_data'));
     }
     public function getRegisterStudent(Request $request){
        $student_id = $request->input('student_id');
        $studentdata = DB::table('students')->where('id', $student_id)->first();
       // dd($studentdata);
        $programs = Program::get();
        return view('student.register-studetn-edit', compact('studentdata','programs')); 
     }

     public function UpdateRegisterStudent(Request $request){
        $student_id = $request->input('student_id');
        $studentupdate = Student::find($student_id);
        if(empty($studentupdate)){
                return response()->json([
                    'status' => false,
                    'message' => "Register Student Not Found",
                ]);
        }
        $validator = Validator::make($request->all(),[
            'grade' => 'required',
            'name' => 'required',
            'password' => 'nullable',
            //'confirm_password' => 'required',
            'last_name' => 'required',
            'student_status' => 'required',
            'phone_number' => 'nullable',
           // 'email' => 'nullable|email',
            'email' => 'nullable|email|unique:users',
         ]);
            if($studentupdate->phone_number == $request->phone_number){
                $studentnumber = $request->phone_number;
            }else{
               $studentdata = Student::where('phone_number',$request->phone_number)->first();  
                 if(!empty($studentdata)){
                    return response()->json([
                        'status' => false,
                        'phone_number_exists' => "The phone number has already been taken.",
                    ]);
                 }
            }
           $username = $request->name . '_'. substr($request->last_name,  0, 1);
           $lastname =  '\_'. substr($request->last_name,  0, 1);

           /* $usernameCount = DB::table('students')->select([
              'id',
              'username',
           ])->where('username', 'like', "%{$lastname}%")->count(); */

           $usernameCount = DB::table('users')->select([
            'id',
            'username',
           ])->where('username', 'like', "%{$lastname}%")->count();


           if($usernameCount == 0){
               $username = $username;
           }else{
             $singlenumber  = str_pad((int) $usernameCount + 1, 2,  '0', STR_PAD_LEFT);
             $username = strtolower($username . $singlenumber);
           }
           /* if($request->password != $request->confirm_password){
             return response()->json([
                 'status' => false,
                 'conform_password' => "“Passwords don’t match. (Please enter again)”",
                 'errors' => $validator->errors(),
             ]);
         } */
           //$otpnumber = random_int(1000, 9999)
            if($validator->passes()){
                if(!empty($request->password)){
                    $studentupdate->password = Hash::make($request->password);
                    //$studentupdate->view_password = $request->password;
                   // $studentupdate->confirm_password = $request->password;
                }
                $studentupdate->grade = $request->grade;
                $studentupdate->name = $request->name;
                /* $studentupdate->password = $request->password;
                $studentupdate->view_password = $request->password;
                $studentupdate->confirm_password = $request->password; */
                $studentupdate->last_name = $request->last_name;
                $studentupdate->username = $username;
                $studentupdate->student_status = $request->student_status;
                $studentupdate->phone_number = $request->phone_number;
                $studentupdate->email = $request->email;
                $studentupdate->save();
                
                 $UserUpdate = User::where('student_id', $studentupdate->id)->first();
                $UserUpdate->name = $studentupdate->name;
                //$UserUpdate->usertype = 'student';
                //$UserUpdate->school_id = $studentupdate->school_id;
                //$UserUpdate->student_id = $studentupdate->id;
                //$UserUpdate->status = $studentupdate->status;
                $UserUpdate->username = $studentupdate->username;
                $UserUpdate->grade = $studentupdate->grade;
                $UserUpdate->password = $studentupdate->password;
                //$UserUpdate->view_pass = $studentupdate->password;
                $UserUpdate->save();




                    return response()->json([
                        'status' => true,
                        'message' => "Register Student Update successfully",
                    ]);
                 }else{
                    return response()->json([
                        'status' => false,
                        'errors' => $validator->errors(),
                     ]);
        
                 }
        }

        public function RegisterStudentRemove(Request $request){
            $student_id = $request->input('student_id');
            $registerstudent = Student::where('id', $student_id)->first();
            if(!empty($registerstudent)){
                $registerstudent->delete();
               return redirect()->intended(route('register-list'))->withSuccess('Student Delete successfully');
            }
        }



        public function StudentviewLogs(Request $request)
        {
            $user = Auth::user();
            $userId = $request->userid;
            
             if($userId>0){
                $userdata = DB::table('users')->where('id', $userId)->where('usertype', 'student')->first();
                $userId = $userdata->id;
                $schoolId = $userdata->school_id;
            } 
            //schoolId
            if (($user) && $user->usertype == "superadmin") {
                $whercond = ['userid' => $userId];
            } else {
                $school_id = $user->school_id;
                $whercond = ['userid' => $userId, 'school_id' => $school_id];
            }
                if ($request->ajax()) {
                    //dd($whercond);
                    $userLogs = LogsModel::query()->join('users as u', 'u.id', '=', 'userid')->where($whercond)->selectRaw("logs.*");
                    return Datatables::of($userLogs)
                        ->addIndexColumn()
                        ->editColumn('logs_info', function ($row) {
                            $log_html = '';
                            $loginfo = ['platform' => "OS", 'browser' => "Browser", 'username' => "User",'version'=>"Version",'info'=>'action','device_id'=>'Device id'];
                            foreach (json_decode($row->logs_info) as $log_k => $logdata) {
                                if (in_array($log_k, array_keys($loginfo))) {
                                    $log_html .= '<p class="mb-0"><b>' . $loginfo[$log_k] . '</b>: ' . $logdata . '</p>';
                                }
                            }
        
                            return $log_html;
                        })
                        ->editColumn('created_at', function ($row) {
                            $formattedDate = date('d-m-Y H:i a', strtotime($row->created_at));
                            return $formattedDate;
                        })
                        ->rawColumns(['logs_info'])
                        ->make(true);
                }

                $mainQueryreport = DB::table('reports')
                ->where('reports.userid',$whercond)
                ->select([
                    'reports.id AS reports_id',
                    'reports.lesson_plan AS reports_lesson_plan',
                    'reports.classId AS reports_classId',
                    'reports.userid AS reports_user_id',
                    'reports.school AS reports_school_id',
                    'reports.created_at AS reports_created_date',
                    'reports.created_at AS reports_created_at',
                    'reports.created_at AS report_compelet_created_at',
                    'reports.created_at AS duration_time',
                    'reports.updated_at AS reports_updated_at',
                    'master_class.class_name as grade_class_name',
                    'lesson_plan.title as lesson_plan_title',
                ])
                ->leftJoin('master_class', 'master_class.id', 'reports.classId')
                ->leftJoin('lesson_plan', 'lesson_plan.id', 'reports.lesson_plan')
            ->orderBy("reports.id", "DESC");
            $teacherReportArr = $mainQueryreport->get()->map(function ($row) {
                  $row->reports_created_date = isset($row->reports_created_date) ? Carbon::parse($row->reports_created_date)->format('d-m-Y') : '';
                  $repor_date = isset($row->reports_created_date) ? Carbon::parse($row->reports_created_date)->format('Y-m-d') : '';
                  $logsdata = LogsModel::where('userid', $row->reports_user_id)->whereDate('created_at', $repor_date)->first();
                  $date_formate =  isset($logsdata->created_at) ?  $logsdata->created_at->format('d-m-Y H:i:s') : '';
                  $row->log_created_time = isset($row->log_created_time) ? Carbon::parse($row->log_created_time)->format('H:i:s') : '';
    
                    $row->report_compelet_created_at = isset($row->report_compelet_created_at) ? Carbon::parse($row->report_compelet_created_at)->format('d-m-Y H:i') : '';
                    $startTime = Carbon::parse($date_formate);
                    $endTime = Carbon::parse($row->report_compelet_created_at);
                    $row->duration_time = $startTime->diff($endTime);
                
                return $row;
            })->all();
    
    
                $userLogs = [
                   // 'teachers' => $teacherArr,
                    'teacherReportArr' => $teacherReportArr,
                ];
              
            return view('student-login.student-logs', compact('userLogs','userId', 'user','schoolId'));
        }
        
        public function StudentDetails(Request $request)
        {
            $userdata = Auth::user();
            if($userdata->student_id > 0){
                $mainQuery = DB::table('students')->where('students.id', $userdata->student_id)
           ->whereNull('students.deleted_at')
           ->select([
                  'students.id',
                  'students.name',
                  'students.last_name',
                  'students.phone_number',
                  'students.otp',
                  'students.email',
                  'students.username',
                  'students.student_image',
                  'students.last_name',
                  'students.studenttype',
                  'students.student_status',
                  'students.start_date',
                  'students.end_date',
                  'students.password',
                  'students.grade',
                  'students.otp_verified_till',
                  'students.school_id',
                  'students.otp_verified_at',
                  'students.view_password',
                  'students.status',
                  'students.section',
                  'master_class.class_name As grade_class_name',
                  'school.school_name As student_school_name',
                  'school.student_package_start',
                  'school.student_package_end',
                  'student_payments.duration_package',
              ]);
              
              $mainQuery->leftJoin('master_class', 'master_class.id', 'students.grade');
              $mainQuery->leftJoin('student_payments', 'student_payments.student_id', 'students.id');
              $mainQuery->leftJoin('school', 'school.id', 'students.school_id');
           $studentdata = $mainQuery->first(); 
            }
         //  dd($studentdata);
          $avatar_images = Avatar::whereNull('deleted_at')->get();

          $studentId = $userdata->student_id;
          $student_data = DB::table('students')->where('id',$studentId)->where('student_payment_sucess', 1)->first();
          if(!empty($student_data)){
              $datas = DB::table('student_payments')->where('student_id', $student_data->id)->get();
          }else{
              $datas = '';
          }
        // return view('student-login.student-billing', compact('datas'));



            return view('student-login.student-details', compact('studentdata','avatar_images','datas'));
        }

        public function studentLoginEdit(Request $request)
        {
            $student_id = $request->input('student_id');
            $studentdata = DB::table('students')->where('students.id', $student_id)->whereNull('deleted_at')->first();
            $mainQuery = DB::table('package')->where('school_id', $studentdata->school_id)
            ->select([
                'package.id',
                'package.grade',
                'package.course',
                'package.student_course',
                'package.school_id',
                'package.status',
                'package.package_start',
                'package.package_end',
                'master_class.id as grade_id',
                'master_class.class_name',
            ])->leftJoin('master_class', 'master_class.id', 'package.grade');
              //$programs = $mainQuery->get();
              $programs = $mainQuery->groupBy('package.grade')->get();
            return view('student-login.student-login-edit', compact('studentdata','programs'));
            
        }

        public function studentDetailLoginOtp(Request $request)
        {
            
            $studentdata = Student::where('id', $request->student_id)->first();
            $rules = [
                'phone_number' => 'required',
            ];
            
            if (isset($studentdata->phone_number) && $studentdata->phone_number == $request->phone_number) {
                $rules['phone_number'] = 'required';
            }else {
                $rules['phone_number'] = 'required|unique:students';
            }
            $validator = Validator::make($request->all(), $rules);
          // dd($request->phone_number);
             if($validator->passes()){
                $studentotp = Student::where('id', $request->student_id)->first();
                $otpnumber = random_int(1000, 9999);
                $this->OtpSentMessage($request->phone_number,$otpnumber);
                $timereset = Carbon::parse($studentotp->otp_verified_till)->format(30,'s');
                if(!empty($studentotp)){
                    $studentotp->update([
                        //'otp_verified_at' => null,
                       // 'phone_number'    => $request->phone_number,
                        'otp' => $otpnumber,
                        'otp_verified_till' => Carbon::now()->addSeconds(60)->format('Y-m-d H:i:s'),
                    ]);
                }
                return response()->json([
                    'status' => true,
                    'timeResetOtp' => $timereset,
                    'message' => "OTP sent successfully",
                ]);   
                }else{
                    return response()->json([
                       'status' => false,
                       'errors' => $validator->errors(),
                    ]);
                }

        }

        public function studentLoginUpdateVerifyOtp(Request $request)
        {
            $validator = Validator::make($request->all(),[
                'otp' => 'required',
                'phone_number' => 'nullable',
             ]);

             if($validator->passes()){
                $otpdata = Student::where('otp', $request->otp)
                   ->where('phone_number', $request->phone_number)->first();
                if(empty($otpdata)){
                    return response()->json([
                        'status' => false,
                        'queryfaild' => "OTP Invalid",
                        ]);
                }else{
                   $databaseTimestamp = $otpdata->updated_at; 
                   $currentTimestamp = now();
                   $timeDifference = $currentTimestamp->diffInSeconds($databaseTimestamp);
                   $allowedTimeDifference = 90;
                   if ($timeDifference <= $allowedTimeDifference) {
                       // echo "Verification successful!";
                        $otpdata->update([
                            'otp_verified_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                        ]);
                        return response()->json([
                            'status' => true,
                            'otpdata' => $otpdata,
                            'message' => "OTP verified successfully",
                        ]);
                    } else {
                       // echo "Verification failed!";
                        return response()->json([
                            'status' => false,
                            'queryfaild' => "OTP Expire",
                        ]);
                        
                    } 
                }
            }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                ]);
    
            }
        }

        public function studentLoginUpdate(Request $request)
        {
             $studentupdate = Student::where('id', $request->student_id)->first();
             //dd($studentupdate);
            if(empty($studentupdate)){
                    return response()->json([
                        'status' => false,
                        'message' => "Student Not Found",
                    ]);
            }
            if(empty($studentupdate->otp_verified_at)){
                return response()->json([
                    'status' => false,
                    'student_verified' => "Your Phone Number is doesn't verified",
                ]);
            }
            $validator = Validator::make($request->all(),[
                'grade' => 'required',
               // 'email' => 'nullable',
                 'email' => 'nullable|email|unique:users',
                'section' => 'nullable',
                'phone_number' => 'nullable',
             ]);

             if($validator->passes()){
                $studentupdate->grade = $request->grade;
                $studentupdate->section = $request->section;
                $studentupdate->phone_number = $request->phone_number;
                $studentupdate->email = $request->email;
                $studentupdate->save();


                $UserCreate = User::where('student_id', $studentupdate->id)->where('usertype', 'student')->first();
                $UserCreate->grade = $studentupdate->grade;
                $UserCreate->save();


                    return response()->json([
                        'status' => true,
                        'message' => "Student Update successfully",
                    ]);
                 }else{
                    return response()->json([
                        'status' => false,
                        'errors' => $validator->errors(),
                     ]);
        
                 }
        }

        public function StudentResetPassword(Request $request){
            $validator = Validator::make($request->all(),[
               // 'old_password' => 'required',
                'set_new_password' => 'required',
                'confirm_password' => 'required',
                //'email' => 'nullable|email',
             ]);
             
             if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors(),
                ]);
            }
            if ($request->set_new_password != $request->confirm_password) {
                return response()->json([
                    'status' => false,
                    'password_match_error' => "“Passwords don’t match. (Please enter again)”",
                ]);
            }

            $student_data = Student::where('id', $request->student_id)
            //->where('password', $request->old_password)
            ->first();
                if(empty($student_data)){
                    return response()->json([
                        'status' => false,
                        'old_password_incorrect' => "Old Password Incorrect",
                    ]);
                }else{
                    $student_data->password = Hash::make($request->set_new_password);
                    $student_data->save();
                    $UserCreate = User::where('student_id', $student_data->id)->where('usertype', 'student')->first();
                    $UserCreate->password = $student_data->password;
                   // $UserCreate->view_pass = $student_data->password;
                    $UserCreate->save();
                    return response()->json([
                        'status' => true,
                        'message' => "Reset Password successfully",
                    ]);
                }
             
            
        }

public function UploadStudentImage(Request $request)
{
    $student_id = $request->input('student_id');
    $rules = [
        'student' => 'required|image|mimes:png,jpg,jpeg|max:2048', 
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors(),
        ]);
    }

    if ($request->hasFile('student')) {
        $upload_student = $request->file('student');
        $destinationPath = 'uploads/avatar/';
        $originalname = $upload_student->hashName();
        $imageName = "upload_student_" . date('Ymd') . '_' . $originalname;
        $upload_student->move($destinationPath, $imageName);

        $Studentdata = Student::where('id', $student_id)->first();
       // $fileUrl = URL::to('/') . '/' . $destinationPath . $imageName;

        $Studentdata->update([
            'student_image' => $imageName,
        ]);

        return response()->json([
            'status' => true,
            'message' => "Upload Student successfully",
        ]);
    } else {
        return response()->json([
            'status' => false,
            'message' => "File upload failed",
        ]);
    }
}

public function UploadAvatar(Request $request)
{
    $student_id = $request->input('student_id');
        $Studentdata = Student::where('id', $student_id)->first();
        $Studentdata->update([
            'student_image' => $request->avatar_image,
        ]);
        return response()->json([
            'status' => true,
            'message' => "Upload Student successfully",
        ]);
}
    public function VerifyadminPassword(Request $request)
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


     public function studentPaymentInsert(Request $request)
    {

        $keyId = 'rzp_live_nvVMrBwg0kJRIT';
        $keySecret = 'wdBgs2CPHRM2hYCJxr6mXFLd';
        $api = new Api($keyId, $keySecret);
        $paymentId = $request->razorpay_payment_id; 
        $payment = $api->payment->fetch($paymentId);

        if ($request->duration_package === '3 years') {
            $startDate = now();
            $endDate = $startDate->copy()->addYears(3);
        } elseif ($request->duration_package === '12 months') {
            $startDate = now();
            $endDate = $startDate->copy()->addMonths(12);
        } elseif ($request->duration_package === '6 months') {
            $startDate = now();
            $endDate = $startDate->copy()->addMonths(6);
        } else {
            // Handle other cases if needed
            $startDate = '';
            $endDate = '';  
        }


        $UserCreate = new StudentPayment;
        $UserCreate->orderid = $this->generateUniqueStudentPayment(new StudentPayment, 3);
        $UserCreate->payment_id = $request->razorpay_payment_id;
        $UserCreate->amount = $request->amount;
        $UserCreate->student_id = $request->student_id;
        $UserCreate->school_id = $request->school_id;
        $UserCreate->payment_sucessfull_time = date('Y-m-d H:i:s', $payment->created_at);
        $UserCreate->upi_id = $payment->vpa;
        $UserCreate->start_date_sub = $startDate;
        $UserCreate->start_end_sub = $endDate;
        $UserCreate->payment_status = 1;
        $UserCreate->duration_package = $request->duration_package;
        $UserCreate->discount_percentage = $request->discount_percentage;
        $UserCreate->package_deal_code = $request->package_deal_code;
        $UserCreate->save();
        $studentdata = Student::where('id', $request->student_id)->first();

        $studentdata->update([
            'student_status' => 'Paid',
            'start_date' => $startDate,
            'end_date' => $endDate,
            'student_payment_sucess' => 1,
        ]);
        return response()->json(['success' => 'Payment successful']);
    } 


    public function studentPaymentFailed(Request $request)
    {
        if ($request->razorpay_payment_id) {
        $paymentFailedInfo = [
            'payment_failed' => $request->failed_data,
            'amount' => $request->amount,
        ];

       /*  $keyId = 'rzp_live_nvVMrBwg0kJRIT';
        $keySecret = 'wdBgs2CPHRM2hYCJxr6mXFLd';
        $api = new Api($keyId, $keySecret);
        $paymentId = $request->razorpay_payment_id; 
        $payment = $api->payment->fetch($paymentId); */

        $UserCreate = new StudentPayment;
        $UserCreate->orderid = $this->generateUniqueStudentPayment(new StudentPayment, 3);
        $UserCreate->payment_id = $request->razorpay_payment_id;
        $UserCreate->amount = $request->amount;
        $UserCreate->student_id = $request->student_id;
        $UserCreate->school_id = $request->school_id;
        $UserCreate->payment_status = 0;
       // $UserCreate->upi_id = $payment->vpa;
        $UserCreate->duration_package = $request->duration_package;
        $UserCreate->payment_sucessfull_time = Carbon::now();
        $UserCreate->payment_failed_info = json_encode($paymentFailedInfo);
        $UserCreate->save();
        return response()->json(['success' => 'Payment failed']);
    }
    }

    public function generateStudentPDF(Request $request)
    {
        $student_payment_id = $request->input('student_payment_id');
        
            $student_payment_data = DB::table('student_payments')
                ->where('student_payments.id', $student_payment_id)
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
                'username' => $student_payment_data->username,
                'student_status' => $student_payment_data->student_status,
                'student_image' => $student_payment_data->student_image,
                'school_name' => $student_payment_data->school_name,
            ];
            $pdf = PDF::loadView('pdf.student-sample', $data);
            return $pdf->download('student.pdf');
    }
    
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


    public function studentBilling(Request $request)
    {
        $user = Auth::user();
        $studentId = $user->student_id;
        $student_data = DB::table('students')->where('id',$studentId)->where('student_payment_sucess', 1)->first();
        if(!empty($student_data)){
            $datas = DB::table('student_payments')->where('student_id', $student_data->id)->get();
        }else{
            $datas = '';
        }
       return view('student-login.student-billing', compact('datas'));
    }
    public function packageList(Request $request)
    {
      $student_package_count = DB::table('student_packages')->whereNull('deleted_at')->count();
      //dd($student_package_count);
                if ($request->ajax()) {
                    $mainQuery = DB::table('student_packages')->whereNull('deleted_at')
                    ->select([
                        'student_packages.id',
                        'student_packages.packages',
                        'student_packages.packages as package_data',
                        'student_packages.deal_code_per',
                        'student_packages.name_of_package',
                        'student_packages.set_pricing',
                        'student_packages.total_price',
                        'student_packages.duration_of_package',
                        'student_packages.deal_code',
                        'student_packages.package_status',
                    ])
                    ->orderBy("student_packages.id", "DESC");
                    $student_packages = $mainQuery->get()->map(function ($row) {
                        $row->package_data = isset($row->packages) ? json_decode($row->packages) : '';
                        return $row;
                    });
                     $data = $student_packages;
                     return Datatables::of($data)
                     ->addColumn('index', function ($row) {
                         static $index = 0;
                         return ++$index;
                     })
                     ->editColumn('package_data', function ($row) {
                        $packageNames = '';
                        foreach ($row->package_data->package as $key => $packagedata) {
                            $packageNames .= isset($packagedata->deal_code) ? $packagedata->deal_code : '';
                        }
                        return $packageNames;
                    })
                    ->editColumn('name_of_package', function ($row) {
                        return $row->name_of_package;
                    })
                    ->editColumn('set_pricing', function ($row) {
                        return $row->set_pricing;
                    })
                    ->editColumn('total_price', function ($row) {
                        return $row->total_price;
                    })
                    ->editColumn('duration_of_package', function ($row) {
                        return $row->duration_of_package;
                    })
                    ->editColumn('package_status', function ($row) {
                        $statusBadge = '<a href="javascript:void(0);"
                            class="text-white badge bg-' . ($row->package_status == 1 ? 'success' : 'danger') . '"
                            id="status_' . $row->id . '" data-id="' . $row->id . '"
                            data-package_status="' . $row->package_status . '">'
                            . ($row->package_status == 1 ? 'Active' : 'Inactive') . '</a>';
                        return $statusBadge;
                    })
                    ->editColumn('action', function ($row) {
                        return '<a href="' . route('package.edit', ['package_id' => $row->id]) . '"
                            class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Edit</a>
                            <a href="' . route('package.view', ['package_id' => $row->id]) . '"
                            class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">View</a>    
                        <a href="javascript:void(0);" data-id="' . $row->id . '"
                            class="waves-effect waves-light remove_school_data btn btn-sm btn-outline btn-danger mb-5">Delete</a>';
                    })
                     ->rawColumns(['index','package_data', 'set_pricing', 'total_price', 'duration_of_package', 'name_of_package', 'package_status', 'action'])
                     ->toJson();
                 }
       return view('package.package-list',compact('student_package_count'));
    }


    public function addPackage(Request $request)
    {
       $invoice_data = DB::table('invoices')->whereNull('deleted_at')->first();
       
       //$totalPrice = setPricing + ($request->set_pricing * (taxRate / 100));

       return view('package.package-add', compact('invoice_data'));
    }
    public function createPackage(Request $request)
    {

        

        $validator = Validator::make($request->all(), [
            'msg_discount' => 'nullable|string',
            'this_package_includes' => 'nullable|string',
            'set_pricing' => 'required',
            'invoice' => 'required',
           // 'total_price' => 'required',
            'name_of_package' => 'required',
            'duration_of_package' => 'required',
            'packages' => 'required|array',
            'packages.package.*.deal_code' => 'required|string',
            'packages.package.*.deal_code_per' => 'required|string',
        ]);
        $aiModuleMsgRule = [];
        foreach ($request->packages['package'] as $cindex => $competence_data) {
            $aiModuleMsgRule['packages.package.'.$cindex.'.deal_code'] = 'Row' .($cindex+1). '-'. 'Deal Code';
            $aiModuleMsgRule['packages.package.'.$cindex.'.deal_code_per'] = 'Row' .($cindex+1). '-'. 'Discount Percentage';
          
        }
        $validator->setAttributeNames($aiModuleMsgRule);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                ]);
        }

       

        if($validator->passes()){
          $data = $request->all();
          

          $invoice = DB::table('invoices')->where('id', $request->invoice)->first();
          $discount_percentage = 0;
          $totalCgst = ($invoice->cgst * ($request->set_pricing - $discount_percentage)) / 100;
          $totalSgst = ($invoice->sgst * ($request->set_pricing - $discount_percentage)) / 100;
          $totalIgst = ($invoice->igst * ($request->set_pricing - $discount_percentage)) / 100;
          $total = $request->set_pricing - $discount_percentage + $totalCgst + $totalSgst + $totalIgst;
          $totalAmount = round($total);

          $studentPackage = new StudentPackage();
          $studentPackage->name_of_package = isset($data['name_of_package']) ? $data['name_of_package'] : '';
          $studentPackage->duration_of_package = isset($data['duration_of_package']) ? $data['duration_of_package'] : '';
          $studentPackage->set_pricing = isset($data['set_pricing']) ? $data['set_pricing'] : '';

          $studentPackage->total_price = isset($totalAmount) ? $totalAmount : '';
          
          $studentPackage->msg_discount = isset($data['msg_discount']) ? $data['msg_discount'] : '';
          $studentPackage->package_status = 1;
          $studentPackage->invoice_id = $data['invoice'];
          $studentPackage->this_package_includes = isset($data['this_package_includes']) ? $data['this_package_includes'] : '';;
          
            $studentPackage->packages = json_encode([
                'package' => $request->packages['package']
                ?? []]);
          $studentPackage->save();
          return response()->json([
              'status' => true,
              'message' => "Package created successfully",
          ], 201);
          
          
      }else{
          return response()->json([
              'status' => false,
              'errors' => $validator->errors(),
           ]);
      
       }



    }

    public function VerifyadminPasswordPackage(Request $request)
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

    public function destroyPackage(Request $request)
    {
       $package_id = $request->input('package_id');
       $package_data = StudentPackage::where('id', $package_id)->first();
        if(!empty($package_data)){
            $package_data->delete();
            echo "removed";
        }
    }
    public function editPackage(Request $request)
    {
        $package_id = $request->input('package_id');

        $mainQuery = DB::table('student_packages')->whereNull('deleted_at')
        ->select([
            'student_packages.id',
            'student_packages.packages',
            'student_packages.packages as package_data',
            'student_packages.deal_code_per',
            'student_packages.this_package_includes',
            'student_packages.name_of_package',
            'student_packages.set_pricing',
            'student_packages.total_price',
            'student_packages.duration_of_package',
            'student_packages.invoice_id',
            'student_packages.msg_discount',
            'student_packages.deal_code',
            'student_packages.package_status',
        ])->where('id', $package_id);
        $packages_single = $mainQuery->get()->map(function ($row) {
            $row->package_data = isset($row->packages) ? json_decode($row->packages) : '';
            return $row;
        })->first();

        $invoice_data = DB::table('invoices')->whereNull('deleted_at')->first();
        return view('package.package-edit',compact('packages_single','invoice_data'));
    }

    public function ViewPackage(Request $request)
    {
        $package_id = $request->input('package_id');

        $mainQuery = DB::table('student_packages')->whereNull('deleted_at')
        ->select([
            'student_packages.id',
            'student_packages.packages',
            'student_packages.packages as package_data',
            'student_packages.deal_code_per',
            'student_packages.this_package_includes',
            'student_packages.name_of_package',
            'student_packages.set_pricing',
            'student_packages.total_price',
            'student_packages.duration_of_package',
            'student_packages.msg_discount',
            'student_packages.deal_code',
            'student_packages.package_status',
        ])->where('id', $package_id);
        $packages_single = $mainQuery->get()->map(function ($row) {
            $row->package_data = isset($row->packages) ? json_decode($row->packages) : '';
            return $row;
        })->first();
        return view('package.package-view',compact('packages_single'));
    }


    /* public function packageUpdate(Request $request)
    {
        $updatepackage = StudentPackage::where('id', $request->package_id)->first();
        $validator = Validator::make($request->all(), [
            'deal_code' => 'nullable|string',
            'msg_discount' => 'nullable|string',
            'deal_code_per' => 'nullable|string',
            'this_package_includes' => 'nullable|string',
            'packages' => 'required|array',
            'packages.package.*.name_of_package' => 'required|string',
            'packages.package.*.duration_of_package' => 'required|string',
            'packages.package.*.set_pricing' => 'required|string',
            'packages.package.*.tax_rate' => 'required|string',
            'packages.package.*.total_price' => 'required|string',
        ]);
        $aiModuleMsgRule = [];
        foreach ($request->packages['package'] as $cindex => $competence_data) {
            $aiModuleMsgRule['packages.package.'.$cindex.'.name_of_package'] = 'Row' .($cindex+1). '-'. 'Name of package';
            $aiModuleMsgRule['packages.package.'.$cindex.'.duration_of_package'] = 'Row' .($cindex+1). '-'. 'Duration of package';
            $aiModuleMsgRule['packages.package.'.$cindex.'.set_pricing'] = 'Row' .($cindex+1). '-'. 'Set pricing';
            $aiModuleMsgRule['packages.package.'.$cindex.'.tax_rate'] = 'Row' .($cindex+1). '-'. 'Tax rate';
            $aiModuleMsgRule['packages.package.'.$cindex.'.total_price'] = 'Row' .($cindex+1). '-'. 'Total price';
        }
        $validator->setAttributeNames($aiModuleMsgRule);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                ]);
        }
        if($validator->passes()){
            $data = $request->all();
            $updatepackage->deal_code_per = isset($data['deal_code_per']) ? $data['deal_code_per'] : '';
            $updatepackage->deal_code = isset($data['deal_code']) ? $data['deal_code'] : '';
            $updatepackage->msg_discount = isset($data['msg_discount']) ? $data['msg_discount'] : '';
            $updatepackage->package_status = 1;
            $updatepackage->this_package_includes = isset($data['this_package_includes']) ? $data['this_package_includes'] : '';
              $updatepackage->packages = json_encode([
                  'package' => $request->packages['package']
                  ?? []]);
            $updatepackage->save();
            return response()->json([
                'status' => true,
                'message' => "Package Update successfully",
            ], 201);
            
            
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
             ]);
        
         }
    } */


    public function packageUpdate(Request $request)
    {
        $updatepackage = StudentPackage::where('id', $request->package_id)->first();
        $validator = Validator::make($request->all(), [
            'msg_discount' => 'nullable|string',
            'this_package_includes' => 'nullable|string',
            'set_pricing' => 'required',
            'invoice' => 'required',
           // 'total_price' => 'required',
            'name_of_package' => 'required',
            'duration_of_package' => 'required',
            'packages' => 'required|array',
            'packages.package.*.deal_code' => 'required|string',
            'packages.package.*.deal_code_per' => 'required|string',
        ]);
        $aiModuleMsgRule = [];
        foreach ($request->packages['package'] as $cindex => $competence_data) {
            $aiModuleMsgRule['packages.package.'.$cindex.'.deal_code'] = 'Row' .($cindex+1). '-'. 'Deal Code';
            $aiModuleMsgRule['packages.package.'.$cindex.'.deal_code_per'] = 'Row' .($cindex+1). '-'. 'Discount Percentage';
          
        }
        $validator->setAttributeNames($aiModuleMsgRule);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                ]);
        }

       

        if($validator->passes()){
          $data = $request->all();
          $invoice = DB::table('invoices')->where('id', $request->invoice)->first();
          $discount_percentage = 0;
          $totalCgst = ($invoice->cgst * ($request->set_pricing - $discount_percentage)) / 100;
          $totalSgst = ($invoice->sgst * ($request->set_pricing - $discount_percentage)) / 100;
          $totalIgst = ($invoice->igst * ($request->set_pricing - $discount_percentage)) / 100;
          $total = $request->set_pricing - $discount_percentage + $totalCgst + $totalSgst + $totalIgst;
          $totalAmount = round($total);
         // $studentPackage = new StudentPackage();
          $updatepackage->name_of_package = isset($data['name_of_package']) ? $data['name_of_package'] : '';
          $updatepackage->duration_of_package = isset($data['duration_of_package']) ? $data['duration_of_package'] : '';
          $updatepackage->set_pricing = isset($data['set_pricing']) ? $data['set_pricing'] : '';
          $updatepackage->total_price = isset($totalAmount) ? $totalAmount : '';
          $updatepackage->msg_discount = isset($data['msg_discount']) ? $data['msg_discount'] : '';
          $updatepackage->package_status = 1;
          $updatepackage->invoice_id = $data['invoice'];
          $updatepackage->this_package_includes = isset($data['this_package_includes']) ? $data['this_package_includes'] : '';;
            $updatepackage->packages = json_encode([
                'package' => $request->packages['package']
                ?? []]);
          $updatepackage->save();
          return response()->json([
              'status' => true,
              'message' => "Package update successfully",
          ], 201);
      }else{
          return response()->json([
              'status' => false,
              'errors' => $validator->errors(),
           ]);
      
       }



    }








    public function StudentPaymentHistory(Request $request)
    {
        $user = Auth::user();
        $school_id = $user->school_id;
        $student_id = $request->input('student_id');
        $mainQuery = DB::table('student_payments')->where('student_payments.student_id', $student_id);
        $student_payment_data = $mainQuery->get()->map(function ($row) {
        $row->payment_failed_info = isset($row->payment_failed_info) ? json_decode($row->payment_failed_info) : '';
            return $row;
        });
       // dd($student_payment_data);

        return view('student.student-payment-school-admin', compact('school_id', 'student_payment_data'));
    }



    public function allSchoolStudentList(Request $request)
     {

        
        
        if ($request->ajax()) {
            $student_mainQuery = Student::
            leftJoin('master_class', 'master_class.id', 'students.grade')
            ->leftJoin('school', 'school.id', 'students.school_id')
            ->leftJoin('users', 'users.student_id', 'students.id')
            ->select('students.*', 'master_class.class_name','school.school_name','users.id as user_id');
            $data = $student_mainQuery->orderBy('id', 'desc')->get();
            //dd($data);
            return Datatables::of($data)
                ->addColumn('index', function ($row) {
                    static $index = 0;
                    return ++$index;
                })
                /* ->editColumn('school_name', function ($row) {
                    return $row->school_name ?? '';
                }) */

                ->editColumn('school_name', function ($row) {
                    $school_name = '<a href="' . route('student.list', ['school_id' => $row->school_id]) . '"
                                    class="fw-bold"
                                    title="Student List"
                                    >' . $row->school_name . '</a>';
                    return $school_name;
                })
                
                



                ->editColumn('name', function ($row) {
                    $html = $row->name . ' ' . $row->last_name;
                    return $html;
                })                
                ->editColumn('phone_number', function ($row) {
                    return $row->phone_number ?? '';
                })
                ->editColumn('student_status', function ($row) {
                    $statusClass = '';
                    switch ($row->student_status) {
                        case 'Demo':
                            $statusClass = 'text-white badge bg-success';
                            break;
                        case 'Paid':
                            $statusClass = 'text-white badge bg-danger';
                            break;
                        case 'Pending':
                            $statusClass = 'text-white badge bg-warning';
                            break;
                        default:
                            break;
                    }
                    $status = '<a href="#" class="text-white ' . $statusClass . '" data-status="' . $row->status . '">'
                                . $row->student_status .
                              '</a>';
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    
                    $removeBtn = '<a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#bs-password-modal" 
                    class="remove_school_data waves-effect waves-light btn btn-sm btn-outline btn-danger mb-5"
                    data-id=' . $row->id . '
                    data-userid="' . $row->id . '"
                    >Delete</a>';
                  $removeBtn .= '<a href="' . route('student-login-history', ['user_id' => $row->user_id]) . '"
                    data-id=' . $row->id . '
                    class="waves-effect waves-light btn btn-sm btn-outline btn-warning mb-5">Student Logs</a>';

                    

                    return    $removeBtn;
                })



                ->rawColumns(['name', 'school_name', 'student_status', 'phone_number', 'action' ])
                ->toJson();
        }
        
        return view('student.all-student-list');
     }

     public function StudentLoginHis(Request $request)
     {
        $user = Auth::user();
        $userId = $request->user_id;
        if (($user) && $user->usertype == "superadmin") {
            $whercond = ['userid' => $userId];
        }
        //dd($whercond);
            if ($request->ajax()) {
               // dd($whercond);
                $userLogs = LogsModel::query()->join('users as u', 'u.id', '=', 'userid')->where($whercond)->selectRaw("logs.*");
                return Datatables::of($userLogs)
                    ->addIndexColumn()
                    ->editColumn('logs_info', function ($row) {
                        $log_html = '';
                        $loginfo = ['platform' => "OS", 'browser' => "Browser", 'username' => "User",'version'=>"Version",'info'=>'action','device_id'=>'Device id'];
                        foreach (json_decode($row->logs_info) as $log_k => $logdata) {
                            if (in_array($log_k, array_keys($loginfo))) {
                                $log_html .= '<p class="mb-0"><b>' . $loginfo[$log_k] . '</b>: ' . $logdata . '</p>';
                            }
                        }
    
                        return $log_html;
                    })
                    ->editColumn('created_at', function ($row) {
                        $formattedDate = date('d-m-Y H:i a', strtotime($row->created_at));
                        return $formattedDate;
                    })
                    ->rawColumns(['logs_info'])
                    ->make(true);
            }
            return view('student-login.login-student-list', compact('userId', 'user'));
     }

     
}
