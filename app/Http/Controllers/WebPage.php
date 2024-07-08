<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\{Auth, Validator};
use App\Models\{LessonPlan, Reports, Program, School, Package, Student, Aimodules, Identifier, PlanSortingModel};
use Mail;
class WebPage extends Controller
{
    public function courselist(Request $req)
    {
        /* $user = Auth::user();
        $userId = $user->id;
        $schoolId = $user->school_id;
        $classId = $req->class;

        $courseIds = Package::where(['school_id'=>$schoolId,'grade'=>$classId])->first();
        $course_ids = !empty($courseIds->course) ? explode(",", $courseIds->course) : [];
        $class_name = Program::find($classId);
        $course = LessonPlan::join("master_course as mc", "mc.id", "=", "lesson_plan.course_id")
            ->whereRaw('FIND_IN_SET("' . $classId . '", class_id)')
            ->whereIn('course_id', $course_ids)
            ->where(['lesson_plan.status' => 1])
            ->groupBy('lesson_plan.course_id')
            ->orderBy('lesson_plan.id')
            ->selectRaw('count(lesson_plan.id) as total_plan,mc.course_name,mc.course_image,class_id,course_id')
            ->get();

        $aicourses = DB::table('master_course')->where('ai_status',1)->first();
        return view('webpages.course', compact('course', 'aicourses', 'classId', 'userId', 'class_name')); */
        $user = Auth::user();
        $userId = $user->id;
        $schoolId = $user->school_id;
        $classId = $req->class;

        $courseIds = Package::where(['school_id' => $schoolId, 'grade' => $classId])->first();
        $course_ids = !empty($courseIds->course) ? explode(",", $courseIds->course) : [];
       // dd($course_ids);
        $class_name = Program::find($classId);
        $course = LessonPlan::join("master_course as mc", "mc.id", "=", "lesson_plan.course_id")
            ->whereRaw('FIND_IN_SET("' . $classId . '", class_id)')
            ->whereIn('course_id', $course_ids)
            ->where(['lesson_plan.status' => 1])
            ->groupBy('lesson_plan.course_id')
            ->orderBy('lesson_plan.id')
            ->selectRaw('count(lesson_plan.id) as total_plan,mc.course_name,mc.course_image,class_id,course_id')
            ->get();

        $aicourse = Identifier::join("master_course as mc", "mc.id", "=", "ai_identifiers.course_id")
            ->whereRaw('FIND_IN_SET("' . $classId . '", grade_id)')
            ->whereIn('course_id', $course_ids)
            ->where(['ai_identifiers.status' => 1])
            ->groupBy('ai_identifiers.course_id')
            ->orderBy('ai_identifiers.id')
            ->selectRaw('count(ai_identifiers.id) as total_plan,mc.course_name,mc.course_image,grade_id,course_id')
            ->get();
        // $aicourses = DB::table('master_course')->where('ai_status',1)->first();
        //   $aicount = DB::table('ai_identifiers')->whereNull('deleted_at')->count();

           $aicount = DB::table('ai_identifiers')
           ->join('aimodules', 'ai_identifiers.id', '=', 'aimodules.identifier_id')
           ->whereNull('ai_identifiers.deleted_at')
           ->count();
         //  dd($aicount);
        return view('webpages.course', compact('course', 'aicourse', 'classId', 'userId', 'class_name','aicount'));
    }

    public function studentcourselist(Request $req)
    {
        $user = Auth::user();
        $userId = $user->id;
        $schoolId = $user->school_id;
        $classId = $req->class;
        $courseIds = Package::where(['school_id' => $schoolId, 'student_grade_id' => $classId])->first();
        $course_ids = !empty($courseIds->student_course) ? explode(",", $courseIds->student_course) : [];
      // dd($course_ids);
        $class_name = Program::find($classId);
        $course = LessonPlan::join("master_course as mc", "mc.id", "=", "lesson_plan.course_id")
            ->whereRaw('FIND_IN_SET("' . $classId . '", class_id)')
            ->whereIn('course_id', $course_ids)
            ->where(['lesson_plan.status' => 1])
            ->groupBy('lesson_plan.course_id')
            ->orderBy('lesson_plan.id')
            ->selectRaw('count(lesson_plan.id) as total_plan,mc.course_name,mc.course_image,class_id,course_id')
            ->get();
           // dd($course);
        return view('student-login.student-course', compact('course', 'classId', 'userId', 'class_name'));
    }

    public function lessonPlan(Request $req)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $userId = $user->id;
            $schoolId = $user->school_id;
            $class_id = $req->classid;

            $lessonPlanItems = LessonPlan::with('program', 'course')
                ->groupBy('lesson_plan.id')
                ->whereRaw('FIND_IN_SET("' . $class_id . '", lesson_plan.class_id)')
                ->where(['lesson_plan.course_id' => $req->course, 'lesson_plan.status' => 1])
                ->selectRaw('lesson_plan.*')->orderBy('lesson_plan.id')->get();
            $lpIds = $lessonPlanItems->pluck('id');
            $planSorting = PlanSortingModel::whereIn('lesson_id', $lpIds)->where(['class_id' => $class_id, 'course_id' => intval($req->course)])->get()->pluck('position_order', 'lesson_id');

            $lessonPlan = $lessonPlanItems->map(function ($item) use ($planSorting) {
                $item['position'] = $planSorting->has($item->id) ? $planSorting[$item->id] : 0;
                return $item;
            })->sortBy('position');

            $report = Reports::where(['userid' => $userId, 'school' => $schoolId, 'classId' => $class_id])->get('lesson_plan')->toArray();
            $complete_lesson = array_column($report, 'lesson_plan');
            $class_name = Program::find($class_id);
            $check_premium = School::find($schoolId);

            return view('webpages.lessonplan', compact('lessonPlan', 'complete_lesson', 'class_id', 'class_name', 'check_premium'));
        }
    }


    public function TeacherLoginInstructionModule(Request $request)
    {
        $query = $request->input('query');
        if (Auth::check()) {
            $user = Auth::user();
            $userId = $user->id;
            $schoolId = $user->school_id;
            $lessonPlan = DB::table('lesson_plan')
                ->where('status', 1)
                ->when($query, function ($queryBuilder) use ($query) {
                    return $queryBuilder->where('title', 'LIKE', '%' . $query . '%');
                })
                ->select([
                    'lesson_plan.id as lesson_plan_id',
                    'lesson_plan.title as lesson_plan_name',
                    'lesson_plan.class_id as grade_id',
                    'lesson_plan.course_id'
                ])
                ->get();
        }
    }

    public function SearchlessonPlan(Request $request)
    {
     $query = $request->input('query');
    if (Auth::check()) {
    $user = Auth::user();
    $userId = $user->id;
    $schoolId = $user->school_id;
    $lesson_id = $request->lesson_id;
    $mainQuery = DB::table('lesson_plan')
        ->select([
            'lesson_plan.id',
            'lesson_plan.title',
            'lesson_plan.video_url',
            'lesson_plan.lesson_no',
            'lesson_plan.class_id',
            'lesson_plan.class_id as grade_name',
            'lesson_plan.course_id',
            'lesson_plan.lesson_desc',
            'lesson_plan.lesson_image',
            'lesson_plan.video_info_url',
            'lesson_plan.is_demo',
            'lesson_plan.myra_video_url',
        ])->distinct('lesson_plan.id')
        ->join('package', function ($join) use ($query, $schoolId) {
            $join->on(DB::raw("FIND_IN_SET(lesson_plan.course_id, package.course)"), '<>', DB::raw('0'))
                 ->where('lesson_plan.title', 'LIKE', '%' . $query . '%')
                 ->where('package.school_id', $schoolId);
        });
            $lessonPlan = $mainQuery->get()->map(function ($row) {
                $grade_id = isset($row->class_id) ? $row->class_id : '';
                $gradeIds = explode(',', $grade_id);

                $row->grade_name = Program::whereIn('id', $gradeIds)->get();
                return $row;
            })->all();
            $report = Reports::where(['userid' => $userId, 'school' => $schoolId])->get('classId')->toArray();
            $complete_lesson = array_column($report, 'classId');
            $check_premium = School::find($schoolId);
            return view('webpages.lessonplan-search', compact('lessonPlan', 'complete_lesson', 'check_premium'));
        }
    }


    public function StudentlessonPlan(Request $req)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $userId = $user->id;
            $schoolId = $user->school_id;
            $class_id = $req->classid;
            $lessonPlan = LessonPlan::with('program', 'course')
                ->leftJoin('plan_sorting', 'plan_sorting.lesson_id', '=', 'lesson_plan.id')
                ->groupBy('lesson_plan.id')
                ->whereRaw('FIND_IN_SET("' . $class_id . '", lesson_plan.class_id)')->where(['lesson_plan.course_id' => $req->course, 'lesson_plan.status' => 1])->selectRaw('lesson_plan.*,plan_sorting.position_order')->orderBy('plan_sorting.position_order')->get();
            $report = Reports::where(['userid' => $userId, 'school' => $schoolId, 'classId' => $class_id])->get('lesson_plan')->toArray();
            $complete_lesson = array_column($report, 'lesson_plan');
            $class_name = Program::find($class_id);
            $check_premium = School::find($schoolId);
            $student_check_premium = Student::where('id', $user->student_id)->first();


            /* $mainQuery = DB::table('student_packages')->whereNull('deleted_at')
                ->select([
                    'student_packages.id',
                    'student_packages.packages',
                    'student_packages.packages as package_data',
                    'student_packages.deal_code_per',
                    'student_packages.deal_code',
                    'student_packages.name_of_package',
                    'student_packages.set_pricing',
                    'student_packages.total_price',
                    'student_packages.duration_of_package',
                    'student_packages.invoice_id',
                    'student_packages.msg_discount',
                    'student_packages.this_package_includes',
                    'student_packages.package_status',
                ]);
            $student_packages = $mainQuery->get()->map(function ($row) {
                $row->package_data = isset($row->packages) ? json_decode($row->packages) : '';
                return $row;
            })->all(); */

            /* $mainQuery = DB::table('invoices')
    ->whereNull('invoices.deleted_at')
    ->select([
        'invoices.id as invoice_id',
        'invoices.invoice_number',
        'invoices.invoice_date',
        'invoices.address',
        'invoices.hsn_code',
        'invoices.cgst',
        'invoices.sgst',
        'invoices.igst',
        'invoices.description',
        'invoices.status',
        'student_packages.id as student_package_id',
        'student_packages.packages',
        'student_packages.packages as package_data',
        'student_packages.deal_code_per',
        'student_packages.deal_code',
        'student_packages.name_of_package',
        'student_packages.set_pricing',
        'student_packages.total_price',
        'student_packages.duration_of_package',
        'student_packages.invoice_id',
        'student_packages.msg_discount',
        'student_packages.this_package_includes',
        'student_packages.package_status',
    ])
    ->leftJoin('student_packages', 'invoices.id', '=', 'student_packages.invoice_id');

$student_packages = $mainQuery->get()->map(function ($row) {
    $row->package_data = isset($row->packages) ? json_decode($row->packages) : '';
    return $row;
})->all(); */



$mainQuery = DB::table('invoices')
    ->whereNull('invoices.deleted_at')
    ->select([
        'invoices.id as invoice_id',
        'invoices.invoice_number',
        'invoices.invoice_date',
        'invoices.address',
        'invoices.hsn_code',
        'invoices.cgst',
        'invoices.sgst',
        'invoices.igst',
        'invoices.description',
        'invoices.status',
        'student_packages.id as student_package_id',
        'student_packages.packages',
        'student_packages.deal_code_per',
        'student_packages.deal_code',
        'student_packages.name_of_package',
        'student_packages.set_pricing',
        'student_packages.total_price',
        'student_packages.duration_of_package',
        'student_packages.invoice_id',
        'student_packages.msg_discount',
        'student_packages.this_package_includes',
        'student_packages.package_status',
    ])
    ->leftJoin('student_packages', 'invoices.id', '=', 'student_packages.invoice_id')
    ->get();



// Grouping student_packages by invoice_id
$groupedData = $mainQuery->groupBy('invoice_id')->map(function ($invoiceGroup) {
    // Get the first invoice record to get the invoice details
    $invoice = $invoiceGroup->first();

    // Extract the student_packages for this invoice
    $studentPackages = $invoiceGroup->map(function ($item) {
        return [
            'student_package_id' => $item->student_package_id,
            'packages' => $item->packages,
            'package_data' => isset($item->packages) ? json_decode($item->packages) : '',
            'deal_code_per' => $item->deal_code_per,
            'deal_code' => $item->deal_code,
            'name_of_package' => $item->name_of_package,
            'set_pricing' => $item->set_pricing,
            'total_price' => $item->total_price,
            'duration_of_package' => $item->duration_of_package,
            'invoice_id' => $item->invoice_id,
            'msg_discount' => $item->msg_discount,
            'this_package_includes' => $item->this_package_includes,
            'package_status' => $item->package_status,
        ];
    })->values();

    // Return the invoice details along with its related student_packages
    return [
        'invoice_id' => $invoice->invoice_id,
        'invoice_number' => $invoice->invoice_number,
        'invoice_date' => $invoice->invoice_date,
        'address' => $invoice->address,
        'hsn_code' => $invoice->hsn_code,
        'cgst' => $invoice->cgst,
        'sgst' => $invoice->sgst,
        'igst' => $invoice->igst,
        'description' => $invoice->description,
        'status' => $invoice->status,
        'student_packages' => $studentPackages,
    ];
})->values();

//dd($groupedData);




//dd($student_packages);




            return view('student-login.student-lessonplan', compact('lessonPlan', 'groupedData', 'student_check_premium', 'complete_lesson', 'class_id', 'class_name', 'check_premium'));
        }
    }

    public function setUserReport(Request $req)
    {

        if (Auth::check()) {
            $user = Auth::user();
            $userId = $user->id;
            $schoolId = $user->school_id;
            $userType = $user->usertype;
            $classId = $req->gradeId;
            if ($userType == 'teacher') {
                if ($req->buttonText == 'Completed') {
                    //$addReport = ['userid' => $userId, 'complesion_status' => 0, 'school' => $schoolId, 'lesson_plan' => $req->planId, 'classId' => $classId];
                    $reportdata = Reports::where(['userid' => $userId, 'complesion_status' => 1, 'school' => $schoolId, 'classId' => $classId])->first();
                    $reportdata->delete();

                    //Reports::updateOrCreate($addReport);
                    return "status_changes";
                } else {
                    $addReport = ['userid' => $userId, 'complesion_status' => 1, 'school' => $schoolId, 'lesson_plan' => $req->planId, 'classId' => $classId];
                    Reports::updateOrCreate($addReport);
                    return "update";
                }

                /* $addReport = ['userid' => $userId, 'status' => 1, 'school' => $schoolId, 'lesson_plan' => $req->planId, 'classId' => $classId];
                Reports::updateOrCreate($addReport);
                return "update"; */
            } else {
                return "error";
            }
        }
    }


    public function StudentsetUserReport(Request $req)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $userId = $user->id;
            $schoolId = $user->school_id;
            $userType = $user->usertype;
            $classId = $req->gradeId;
            if ($userType == 'student') {
                if ($req->buttonText == 'Completed') {
                    //$addReport = ['userid' => $userId, 'complesion_status' => 0, 'school' => $schoolId, 'lesson_plan' => $req->planId, 'classId' => $classId];
                    $reportdata = Reports::where(['userid' => $userId, 'complesion_status' => 1, 'school' => $schoolId, 'classId' => $classId])->first();
                    $reportdata->delete();

                    //Reports::updateOrCreate($addReport);
                    return "status_changes";
                } else {
                    $addReport = ['userid' => $userId, 'complesion_status' => 1, 'school' => $schoolId, 'lesson_plan' => $req->planId, 'classId' => $classId];
                    Reports::updateOrCreate($addReport);
                    return "update";
                }

                /* $addReport = ['userid' => $userId, 'status' => 1, 'school' => $schoolId, 'lesson_plan' => $req->planId, 'classId' => $classId];
                Reports::updateOrCreate($addReport);
                return "update"; */
            } else {
                return "error";
            }
        }
    }

    public static function getVideoUrl($video_src = "")
    {
        $parsed = parse_url($video_src);
        $checkUrlHost = isset($parsed['host']) ? explode('.', $parsed['host']) : [];
        if (in_array('youtube', $checkUrlHost)) {
            $video_id = explode('?v=', $video_src);
            if (empty($video_id[1])) {
                $video_id = explode('/v/', $video_src);
            }
            /* $video_id = explode('&', $video_id[1]);
            $video_id = $video_id[0];
            $video_url = 'https://www.youtube.com/embed/' . $video_id; */
            $video_id = isset($video_id[1]) ? explode('&', $video_id[1]) : ['#'];
            $videoId = $video_id[0];
            $video_url = 'https://www.youtube.com/embed/' . $videoId;
        } elseif (in_array('vimeo', $checkUrlHost)) {
            $parse_vimeo = parse_url($video_src, PHP_URL_PATH);
            $video_id = array_values(array_filter(explode('/', $parse_vimeo)));
            $para_vimeo = count($video_id) > 1 ? '?h=' . $video_id[1] : '';
            $video_url = 'https://player.vimeo.com/video/' . $video_id[0] . $para_vimeo;
        } else {
            $video_url = isset($parsed['host']) ? $video_src : "";
        }
        return $video_url;
    }

    /**public page */
    public function getDemo(Request $req)
    {
        return view('webpages.get-demo');
    }

    public function workshopEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'mobile_no' => 'required',
            'emailid' => 'required',
            'school_name' => 'required',
            'city_name' => 'required',
            'form_type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()->all()]);
        }

        $details = [
            'view' => 'emails.workshop',
            'subject' => 'Free workshop from Valuez School', 
            'title' => 'Demo enquiry from ' . $request->school_name,// school name
            'formdata' => $request->all(),
        ];
     //   support@valuezschool.com
        try {
            Mail::to('support@valuezschool.com')->send(new \App\Mail\TestMail($details));
            return response()->json(['status' => true, 'msg' => 'Email sent successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'errors' => $e->getMessage()]);
        }
    }
    public function investorEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required',
            'email' => 'required',
            'comapny_name' => 'required',
            'message' => 'required',
           // 'role' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()->all()]);
        }

        $details = [
            'view' => 'emails.workshop',
            'subject' => 'Free workshop from Valuez School', 
            'title' => 'Investor enquiry from'. $request->first_name  . $request->last_name, // fisrt name and last name
            'formdata' => $request->all(),
        ];
        try {
             Mail::to('support@valuezschool.com')->send(new \App\Mail\TestMail($details));
            return response()->json(['status' => true, 'msg' => 'Email sent successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'errors' => $e->getMessage()]);
        }
    }

    public function cpdEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required',
            'email' => 'required',
            'school_name' => 'required',
            'city' => 'required',
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()->all()]);
        }

        $details = [
            'view' => 'emails.workshop',
            'subject' => 'Workshop enquiry from valuezschool.com', 
            'title' => 'Free workshop from ' . $request->school_name,// school name
            'formdata' => $request->all(),
        ];
        try {
             Mail::to('support@valuezschool.com')->send(new \App\Mail\TestMail($details));
            return response()->json(['status' => true, 'msg' => 'Email sent successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'errors' => $e->getMessage()]);
        }
    }
    public function packageEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required',
            'email' => 'required',
            'school_name' => 'required',
            'city' => 'required',
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()->all()]);
        }

        $details = [
            'view' => 'emails.workshop',
            'subject' => 'Free workshop from Valuez School', 
            'title' => 'Demo enquiry from' . $request->school_name,// school name
            'formdata' => $request->all(),
        ];
        try {
             Mail::to('support@valuezschool.com')->send(new \App\Mail\TestMail($details));
            return response()->json(['status' => true, 'msg' => 'Email sent successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'errors' => $e->getMessage()]);
        }
    }

    public function footerEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()->all()]);
        }

        $details = [
            'view' => 'emails.workshop',
            'subject' => 'Message from valuezschool.com website',
            'title' => 'Message from valuezschool.com website',
            'formdata' => $request->all(),
        ];
        try {
             Mail::to('support@valuezschool.com')->send(new \App\Mail\TestMail($details));
            return response()->json(['status' => true, 'msg' => 'Email sent successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'errors' => $e->getMessage()]);
        }
    }

    public function paymentRequestGet(Request $request)
    {  
       $payment_id = base64_decode($request->payment);
       $school_payment_data = DB::table('schools_payments')->where('id', $payment_id)->first();
       return view('payment.razorpayView', compact('school_payment_data'));
    }
    public function paymentSubscriptionRequestGet(Request $request)
    {  
       $payment_id = base64_decode($request->payment);
       $school_payment_data = DB::table('schools_payments')->where('id', $payment_id)->first();
       return view('payment.subscription-request-razorpay', compact('school_payment_data'));
    }
}
