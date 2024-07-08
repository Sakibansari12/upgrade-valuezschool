<?php

namespace App\Http\Controllers;

use App\Models\{User, School, CitiesModel, StudentPayment, ForgotPasswordSchoolAdmin, StateModel, LogsModel, Program, Course, Package, SchoolPayment, EmailTemplate};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use DataTables;
use Illuminate\Support\Facades\Validator;
use Mail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Razorpay\Api\Api;
use Session;
use Exception;
use GuzzleHttp\Client;
use App\Traits\OtpVerifyTraits;
use Illuminate\Support\Facades\URL;
use App\Traits\MailTrait;
use Razorpay\Api\Errors\SignatureVerificationError;
use Illuminate\Support\Facades\Storage;
use PDF;

class SchoolController extends Controller
{

    use OtpVerifyTraits, MailTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = School::with(['teacher' => function ($query) {
                $query->where('usertype', '=', 'teacher')->where('status', 1)->where(['is_deleted' => 0]);
            }, 'student' => function ($query) {
                $query->where('student_status', '=', 'Paid')->whereNull('deleted_at');
            }])->where(['is_deleted' => 0])->orderBy('id', 'desc');
            return Datatables::of($data)
                ->addColumn('index', function ($row) {
                    static $index = 0;
                    return ++$index;
                })
                ->editColumn('school_logo', function ($row) {
                    $ImagePath = $row->school_logo ? $row->school_logo : 'no_image.png';
                    $imageUrl = url('uploads/school/' . $ImagePath);
                    return '<img src="' . $imageUrl . '" width="32" height="32" class="bg-light my-n1">';
                })
                ->addColumn('school_name', function ($row) {
                    $school_name = '<a href="javascript:void(0)"
                    data-bs-toggle="modal" data-bs-target="#bs-school-modal"
                    class="fw-bold preview_school_data"
                    title="Preview School"
                    data-school=' . $row->id . '
                    >' . $row->school_name . '</a>';
                    return $school_name;
                })
                ->addColumn('teacher_licence', function ($row) {
                    $licence = '<span class="badge badge-pill" style="background-color: #00205c; color: #fff;">' .
                        $row->teacher->count() . ' / ' . $row->licence .
                        '</span>';
                    return $licence;
                })
                ->addColumn('student_licence', function ($row) {
                    $licence = '<span class="badge badge-pill" style="background-color: #00205c; color: #fff;">' .
                        $row->student->count() . ' / ' . $row->student_licence .
                        '</span>';
                    return $licence;
                })
                ->addColumn('is_demo', function ($row) {
                    $is_demo = '<a href="javascript:void(0)"
                                    class="change_school_demo_status text-white badge bg-' . ($row->is_demo == 1 ? 'success' : 'danger') . '"
                                    id="demo_status_' . $row->id . '" data-id="' . $row->id . '" data-status="' . $row->is_demo . '">
                                    ' . ($row->is_demo == 1 ? 'Demo' : 'Paid') . '</a>';
                    return $is_demo;
                })
                ->addColumn('student_status', function ($row) {
                    $statusClass = '';
                    switch ($row->school_student_status) {
                        case 'Demo':
                            $statusClass = 'badge bg-success';
                            break;
                        case 'Paid':
                            $statusClass = 'badge bg-danger';
                            break;
                        case 'Pending':
                            $statusClass = 'badge bg-warning';
                            break;
                        default:
                            break;
                    }
                    $status = '<a href="#" class="text-white ' . $statusClass . '" data-status="' . $row->status . '">'
                        . $row->school_student_status .
                        '</a>';
                    return $status;
                })

                ->addColumn('action', function ($row) {
                    return '<a href="' . route('school.edit', ['school' => $row->id]) . '"
                                class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5"
                                title="Edit School"><i class="fa fa-pencil-square-o"></i></a>

                            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#bs-password-modal"
                                title="Delete School"
                                class="remove_school_data waves-effect waves-light btn btn-sm btn-outline btn-danger mb-5"
                                data-schoolid="' . $row->id . '"><i class="fa fa-trash-o"></i></a>

                            <a href="' . route('report.school.view', ['school' => $row->id]) . '"
                                class="waves-effect waves-light btn btn-sm btn-outline btn-warning mb-5"
                                title="View Analytics"><i class="fa fa-bar-chart"></i></a>

                            <a href="' . route('school.admin', ['school' => $row->id]) . '"
                                class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5"
                                title="Manage Admin"><i class="fa fa-user-o"></i> Admin</a>

                            <a href="' . route('teacher.list', ['school' => $row->id]) . '"
                                class="waves-effect waves-light btn btn-sm btn-outline mb-5"
                                title="Manage Classroom"><i class="fa fa-user-o"></i> Classroom</a>

                            <a href="' . route('student.list', ['school_id' => $row->id]) . '"
                                class="waves-effect waves-light btn btn-sm btn-outline  mb-5"
                                title="Manage Student"><i class="fa fa-user-o"></i> Student</a>

                            <a href="' . route('school-payment', ['school_id' => $row->id]) . '"
                                class="waves-effect waves-light btn btn-sm btn-outline  mb-5"
                                title="Manage Payment"><i class="fa fa-user-o"></i> Payment</a>';
                })
                ->rawColumns(['action', 'student_status', 'is_demo', 'school_logo', 'school_name', 'student_licence', 'teacher_licence'])
                ->toJson();
        }
        return view('school.school-list');
    }
    public function addschool()
    {
        $states = StateModel::where("flag", 1)->get(["name", "id"]);
        $grades = Program::where("status", 1)->get(["class_name", "id"]);
        $courses = Course::where("status", 1)->get(["course_name", "id"]);
        return view('school.school-add', compact('states', 'grades', 'courses'));
    }

    public function editschool(Request $request)
    {
        $schoolId = $request->input('school');
        $school = DB::table('school')->where('id', $schoolId)->first();
        $states = StateModel::where("flag", 1)->get(["name", "id"]);
        $grades = Program::where("status", 1)->get(["class_name", "id"]);
        $courses = Course::where("status", 1)->get(["course_name", "id"]);
        $package = Package::where('school_id', $schoolId)->get(['grade', 'course', 'student_course','student_grade_id'])->toArray();

        $grade_ids = ($package) ? array_column($package, 'grade') : [];
        $student_grade_id = ($package) ? array_column($package, 'student_grade_id') : [];
        $course_ids = isset($package[0]) ? explode(",", $package[0]['course']) : [];
        $student_course_ids = isset($package[0]) ? explode(",", $package[0]['student_course']) : [];
        return view('school.school-edit', compact('school', 'states', 'grades', 'grade_ids', 'student_grade_id', 'courses', 'course_ids', 'student_course_ids'));
    }

    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'title' => 'required',
            'primary_email' => 'required|email|unique:school',
            'primary_person' => 'required',
            'school_username' => 'required|regex:/^[a-zA-Z0-9]+$/',
            'licence' => 'required',
            'mobile' => 'required|numeric|digits:10',
            'primary_mobile' => 'required|numeric|digits:10',
            'secondary_mobile' => 'nullable|numeric|digits:10',
            'address' => 'required',
            'pincode' => 'required',
            'student_licence' => 'required|numeric',
            'package_start' => 'required',
            'package_end' => 'required',
            'grade_ids' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
            'student_status' => 'required',
            'school_logo' => 'required|mimes:jpeg,png,jpg|max:2048'

            // 'image' => 'required',
        ]);


        if ($image = $request->file('school_logo')) {
            $destinationPath = 'uploads/school/';
            $originalname = $image->hashName();
            $imageName = "school_" . date('Ymd') . '_' . $originalname;
            $image->move($destinationPath, $imageName);
        } else {
            $imageName = "";
        }
        $access_code = random_int(1000, 9999);
        $schoolData = [
            'school_name' => $request->title,
            'primary_person' => $request->primary_person,
            'school_username' => $request->school_username,
            'primary_email' => $request->primary_email,
            'primary_mobile' => $request->primary_mobile,
            'second_email' => $request->secondary_email,
            'second_mobile' => $request->secondary_mobile,
            'mobile' => $request->mobile,
            'access_code' => $access_code,
            'address' => $request->address,
            'licence' => $request->licence,
            'student_licence' => $request->student_licence,
            'student_package_start' => $request->student_package_start,
            'student_package_end' => $request->student_package_end,
            'school_desc' => $request->school_desc,
            'school_logo' => $imageName,
            'package_start' => $request->package_start,
            'package_end' => $request->package_end,
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
            'school_student_status' => $request->student_status,
            'pincode' => $request->pincode,
            'is_deleted' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'status' => $request->status,
        ];

        $school_id =  DB::table('school')->insertGetId($schoolData);
        $auth_user = new AuthController();
        $user_pass = $auth_user->getToken();
        $user_email = strtolower($request->primary_email);
        $username = explode("@", $user_email);
        $userId = trim($username[0]) . date('Yims');
        if (!empty($request->grade_ids)) {
            $package_info = [];
            $course_ids = !empty($request->course_ids) ? implode(",", $request->course_ids) : 0;
            $student_course_ids = !empty($request->student_course_ids) ? implode(",", $request->student_course_ids) : 0;
            /* foreach ($request->grade_ids as $grade) {
                $package_info[] = ['grade' => $grade, 'school_id' => $school_id, 'status' => 1, 'package_start' => $request->package_start, 'package_end' => $request->package_end, 'course' => $course_ids, 'student_course' => $student_course_ids];
            } */
            $combined_grades = array_map(null, $request->grade_ids, $request->student_grade_id);
            foreach ($combined_grades as $combined_grade) {
                [$grade, $student_grade] = $combined_grade;
                $package_info[] = [
                    'grade' => isset($grade) ? $grade : 0,
                    'student_grade_id' => isset($student_grade) ? $student_grade : 0,
                    'school_id' => $school_id,
                    'status' => 1,
                    'package_start' => $request->package_start,
                    'package_end' => $request->package_end,
                    'course' => $course_ids,
                    'student_course' => $student_course_ids
                ];
            }
            Package::insert($package_info);
        }
        return redirect(route('school.list'))->with(['message' => 'School added successfully & access code generated!', 'status' => 'success']);
    }

    public function schoolAdminMail($data)
    {
        $details = [
            'view' => 'emails.account',
            'subject' => 'School Admin Account creation Mail from Valuez',
            'title' => $data['title'],
            'username' => $data['email'],
            'pass' => $data['pass'],
            'school_name' => $data['school_name'],
        ];
        Mail::to($data['email'])->send(new \App\Mail\TestMail($details));
    }

    public function edit(Request $request)
    {
        // dd($request);
        $data_valid = [
            'title' => 'required',
            'mobile' => 'required|numeric|digits:10',
            'primary_mobile' => 'required|numeric|digits:10',
            'secondary_mobile' => 'nullable|numeric|digits:10',
            'address' => 'required',
            'primary_person' => 'required',
            'school_username' => 'required|regex:/^[a-zA-Z0-9]+$/',
            'licence' => 'required|numeric',
            'student_licence' => 'required|numeric',
            'package_start' => 'required',
            'package_end' => 'required',
            'grade_ids' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
            'student_status' => 'required',

            // 'image' => 'required',
        ];
        if ($request->file('image')) {
            $data_valid['image'] = 'required|max:2048';
        }
        $school_admin = User::where(['school_id' => $request->id, 'usertype' => 'admin'])->first();
        if (isset($school_admin) && $request->primary_email != $school_admin->email) {
            $data_valid['primary_email'] = 'required|email|unique:users,email';
        }
        $request->validate($data_valid);
        if (!empty($request->licence)) {
        }

        if ($image = $request->file('image')) {
            $destinationPath = 'uploads/school/';
            $originalname = $image->hashName();
            $imageName = "plan_" . date('Ymd') . '_' . $originalname;
            $image->move($destinationPath, $imageName);
            $image_path = $destinationPath . $request->old_image;
            @unlink($image_path);
        } else {
            $imageName = $request->old_image;
        }
        $schoolData = [
            'school_name' => $request->title,
            'primary_person' => $request->primary_person,
            'school_username' => $request->school_username,
            'primary_email' => $request->primary_email,
            'primary_mobile' => $request->primary_mobile,
            'second_email' => $request->secondary_email,
            'second_mobile' => $request->secondary_mobile,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'licence' => $request->licence,
            'student_licence' => $request->student_licence,
            'student_package_start' => $request->student_package_start,
            'student_package_end' => $request->student_package_end,
            'school_desc' => $request->school_desc,
            'school_logo' => $imageName,
            'package_start' => $request->package_start,
            'package_end' => $request->package_end,
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
            'school_student_status' => $request->student_status,
            'pincode' => $request->pincode,
            'is_deleted' => 0,
            'updated_at' => date('Y-m-d H:i:s'),
            'status' => $request->status,
        ];

        $admin_user_data = [
            'name' => $request->primary_person,
            'email' => $request->primary_email,
            'status' => 1,
        ];

        if (!empty($request->primary_password)) {
            $admin_user_data['view_pass'] = $request->primary_password;
            $admin_user_data['password'] = Hash::make($request->primary_password);
        }
        User::where(['school_id' => $request->id, 'usertype' => 'admin'])->update($admin_user_data);

        if (!empty($request->grade_ids)) {
            $package_info = [];
            $package = Package::where('school_id', $request->id)->get(['grade'])->toArray();

            $grade_ids = ($package) ? array_column($package, 'grade') : [];
           // dd($grade_ids);
            $remove_grade = array_diff($grade_ids, $request->grade_ids);

            if (!empty($remove_grade)) {
                Package::whereIn('grade', $remove_grade)->where(['school_id' => $request->id])->delete();
            }
            $course_ids = !empty($request->course_ids) ? implode(",", $request->course_ids) : 0;
            $student_course_ids = !empty($request->student_course_ids) ? implode(",", $request->student_course_ids) : 0;

             /* foreach ($request->grade_ids as $grade) {
                $package_info = ['status' => 1, 'package_start' => $request->package_start, 'package_end' => $request->package_end, 'course' => $course_ids, 'student_course' => $student_course_ids];
                Package::updateOrCreate(['grade' => $grade,  'school_id' => $request->id], $package_info);
            }  */
            // Combine grade_ids and student_grade_id arrays element-wise
             $combined_grades = array_map(null, $request->grade_ids, $request->student_grade_id);
                foreach ($combined_grades as $combined_grade) {
                    [$grade, $student_grade] = $combined_grade;
                    $package_info = [
                        'status' => 1,
                        'package_start' => $request->package_start,
                        'package_end' => $request->package_end,
                        'course' => $course_ids,
                        'student_course' => $student_course_ids
                    ];
                    Package::updateOrCreate(
                        ['grade' => isset($grade) ? $grade : 0, 'student_grade_id' => isset($student_grade) ? $student_grade : 0, 'school_id' => $request->id],
                        $package_info
                    );
                }
              //  dd($schoolData);

        }

        DB::table('school')->where('id', $request->id)->update($schoolData);
        return redirect(route('school.list'))->with(['message' => 'School Updated successfully!', 'status' => 'success']);
    }

    public function destroy(Request $request)
    {
        $schoolId = $request->input('school');
        $userPass = $request->input('userpass');
        if (Auth::check()) {
            $user = Auth::user();
            $check_user = DB::table('users')->where('school_id', $schoolId)->count();
            if ($check_user >= 1) {
                return response()->json(['success' => false, 'msg' => 'School teacher exist please remove all account.']);
            } else if (Hash::check($userPass, $user->password)) {
                DB::table('school')->where('id', $schoolId)->update(['is_deleted' => 1]);
                DB::table('users')->where('school_id', $schoolId)->update(['is_deleted' => 1]);
                return response()->json(['success' => true, 'msg' => 'School deleted successfully!']);
            } else {
                return response()->json(['success' => false, 'msg' => 'Entered Password Incorrect.']);
            }
        } else {
            return response()->json(['success' => false, 'msg' => 'Somenthing Went Wrong!']);
        }
    }

    public function VerifyadminPasswordPayment(Request $request)
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

    public function VerifyadminPasswordStudentPayment(Request $request)
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
    public function StudentPaymentRemove(Request $request)
    {
        $payment_id = $request->input('payment_id');
        // $school_id = $request->input('school_id');
        $registerstudent = StudentPayment::where('id', $payment_id)->first();
        if (!empty($registerstudent)) {
            $registerstudent->delete();
            echo "removed";
        }
    }



    public function change_status(Request $request)
    {
        $schoolId = $request->school;
        $status = ($request->status == 1) ? 0 : 1;
        DB::table('school')->where('id', $schoolId)->update(['status' => $status]);
        echo ($status == 1) ? 'Active' : 'Inactive';
    }

    public function change_user_status(Request $request)
    {
        $userId = $request->userid;

        $user_data = DB::table('users')->where('id', $userId)->first();
        $check_school_user = School::with(['teacher' => function ($query) {
            $query->where('usertype', '=', 'teacher')->where('status', 1)->where(['is_deleted' => 0]);
        }])->where(['is_deleted' => 0, 'id' => $user_data->school_id])->orderBy('id')->first();
        $total_teacher = $check_school_user->teacher->count();
        if ($check_school_user->licence > $total_teacher) {
            $status = ($request->status == 1) ? 0 : 1;
            DB::table('users')->where('id', $userId)->update(['status' => $status]);
            echo ($status == 1) ? 'Active' : 'Inactive';
        } else {
            if ($request->status == 1) {
                $status = ($request->status == 1) ? 0 : 1;
                DB::table('users')->where('id', $userId)->update(['status' => $status]);
                echo ($status == 1) ? 'Active' : 'Inactive';
            } else {
                return "error";
            }
        }
    }

    public function change_school_demo_status(Request $request)
    {
        $schoolId = $request->school;
        $status = ($request->status == 1) ? 0 : 1;
        School::where('id', $schoolId)->update(['is_demo' => $status]);
        echo ($status == 1) ? 'Demo' : 'Paid';
    }
    public function CityList(Request $request)
    {
        $data['cities'] = CitiesModel::where("state_id", $request->state_id)->get(["city", "id"]);
        return response()->json($data);
    }

    public function viewLogs(Request $request)
    {
        $user = Auth::user();
        // dd($request->userid);
        $userId = $request->userid;
        $schoolId = $request->schol_id;
        if (($user) && $user->usertype == "superadmin") {
            $whercond = ['userid' => $userId];
        } else {
            $school_id = $user->school_id;
            $whercond = ['userid' => $userId, 'school_id' => $school_id];
        }

        if ($request->ajax()) {
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
            ->where('reports.userid', $whercond)
            ->select([
                'reports.id AS reports_id',
                'reports.lesson_plan AS reports_lesson_plan',
                'reports.classId AS reports_classId',
                'reports.userid AS reports_user_id',
                'reports.school AS reports_school_id',
                'reports.created_at',
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
        return view('users.userlogs', compact('userLogs', 'user', 'schoolId', 'userId'));
    }

    public function previewSchool(Request $request)
    {
        $school_id = $request->school;
        if ($school_id > 0) {
            $school_data = School::find($school_id);
            $package = Package::with('grade')->where('school_id', $school_id)->get()->toArray();

            $city_state = CitiesModel::with('state')->where(["state_id" => $school_data->state_id, "id" => $school_data->city_id])->first();
            $grade_name = [];
            if (!empty($package)) {
                foreach ($package as $grade) {
                    $grade_name[] = @$grade['grade']['class_name'];
                }
            }
            $grades = implode(",", $grade_name);
            return view('school.preview_school', compact('school_data', 'city_state', 'grades'));
        }
    }
    /* Payment Code */
    public function SchoolPayment(Request $request)
    {
        $school_id = $request->input('school_id');

        $schoodata = School::where('id', $school_id)->first();
        $mainQuery = DB::table('schools_payments')->where('schools_payments.school_id', $school_id)
            ->whereNull('schools_payments.deleted_at')
            ->select([
                'schools_payments.id',
                'schools_payments.orderid',
                'schools_payments.payment_amount',
                'schools_payments.link_expiry_at',

                'schools_payments.link_created_at',
                'schools_payments.email_sent_at',
                'schools_payments.email_sent_at as payment_due',
                'schools_payments.sms_sent_at',
                'schools_payments.payment_made_at',
                'schools_payments.payment_link_id',
                'schools_payments.payment_status',
                'schools_payments.upload_invoice',
                'schools_payments.link_updated_at',
                'schools_payments.classrooms_subscriptions_id',
                'schools_payments.json_response',
                'schools_payments.payment_failed_info',

                'schools_payments.description',
                'schools_payments.school_id',
                'schools_payments.email',
                'schools_payments.payment_url',
                'schools_payments.phone_number',
                'school.school_name As school_name_text',
                // 'classrooms_subscriptions.id as sr_id',
                'classrooms_subscriptions.sr_number'
            ]);
        $my_email = "support@valuezschool.com";
        $my_phone_number = "8826708801";
        $mainQuery->leftJoin('school', 'school.id', 'schools_payments.school_id');
        $mainQuery->leftJoin('classrooms_subscriptions', 'classrooms_subscriptions.id', 'schools_payments.classrooms_subscriptions_id');
        $paymentlists = $mainQuery->get()->map(function ($row) {
            $paymentDueDate = isset($row->payment_due) ? $row->payment_due : '';
            $currentDate = Carbon::now();
            $row->payment_due = $currentDate->diffInDays($paymentDueDate);
            return $row;
        })->all();
        $school_email_template = DB::table('email_templates')->where('module', 'school')->where('activated', 1)->first();
        // dd($school_email_template->school_body);
        return view('school.school-payment', compact('school_id', 'my_phone_number', 'school_email_template', 'my_email', 'schoodata', 'paymentlists'));
    }

    public function SchoolPaymentPay(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_amount' => 'required',
            'number_of_subscription' => 'required',
            'description' => 'required',
            'school_id' => 'required|exists:school,id',
            'emails' => 'required|email',
            'phone_numbers' => 'required',
            'school_name_billing' => 'required',
        ]);

        if ($validator->passes()) {
            $schoolpayment = new SchoolPayment;
            $schoolpayment->orderid = $this->generateUniqueBarcode(new SchoolPayment, 1);
            // $schoolpayment->orderid = uniqid();
            $schoolpayment->payment_amount = $request->payment_amount;
            $schoolpayment->description = $request->description;
            $schoolpayment->number_of_subscription = $request->number_of_subscription;
            // $schoolpayment->payment_url = $uniqueURL;
            $schoolpayment->school_id = $request->school_id;
            $schoolpayment->school_name_billing = $request->school_name_billing;
            $schoolpayment->email = isset($request->emails) ? $request->emails : '';
            $schoolpayment->phone_number = $request->phone_numbers;
            $schoolpayment->link_expiry_at = Carbon::now()->addYear()->format('Y-m-d H:i:s');
            $schoolpayment->link_created_at = now()->format('Y-m-d H:i:s');
            $schoolpayment->save();
            $school_payment_id = base64_encode($schoolpayment->id);
            $baseUrl = url('/');
            $uniqueURL = $baseUrl . '/' . 'api/payment-get?payment=' . $school_payment_id;
            $schoolpayment->update([
                'payment_url' => $uniqueURL
            ]);
            /* $schooldata = School::where('id', $schoolpayment->school_id)->first();
          $schooldata->update(['licence' => $request->number_of_subscription]); */

            return response()->json([
                'status' => true,
                'message' => "School Payment successfully",
            ]);
            /* $payment_request = $this->payment($schoolpayment->school_id, $schoolpayment->id);
            $shortUrls = collect($payment_request)
                ->map(function ($response) {
                    $data = json_decode($response, true);
                    $shortUrl = $data['short_url'] ?? null;
                    $id = $data['id'] ?? null;
                    return compact('shortUrl', 'id');
                })
                ->filter()
                ->toArray();
           if(!empty($shortUrls[0])){
                $schoolpayment->update([
                    'payment_url' => $shortUrls[0]['shortUrl'],
                    'payment_link_id' => $shortUrls[0]['id'],
                    'payment_status' => 1,

                ]);
                return response()->json([
                    'status' => true,
                    'message' => "School Payment successfully",
                ]);

           }else{
                return response()->json([
                    'status' => false,
                    'message' => "No Payment successfully",
                ]);
           } */
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }


    public function getsinglePayment(Request $request)
    {
        $payment_id = $request->input('payment_id');
        $paymentdata = SchoolPayment::where('id', $payment_id)->first();
        $schoodata  = School::where('id', $paymentdata->school_id)->first();
        return view('school.payment-edit', compact('paymentdata', 'schoodata'));
    }

    public function UpdatePayment(Request $request)
    {
        $payment_id = $request->input('payment_id');
        $paymentdata = SchoolPayment::where('id', $payment_id)->first();
        if (empty($paymentdata)) {
            return response()->json([
                'status' => false,
                'message' => "Payment Not Found",
            ]);
        }
        $validator = Validator::make($request->all(), [
            'payment_amount' => 'required',
            'description' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required',
            'school_name_billing' => 'required'
        ]);

        if ($validator->passes()) {
            $paymentdata->payment_amount = $request->payment_amount;
            $paymentdata->description = $request->description;
            $paymentdata->school_name_billing = $request->school_name_billing;
            $paymentdata->email = isset($request->email) ? $request->email : '';
            $paymentdata->phone_number = $request->phone_number;
            $paymentdata->link_expiry_at = Carbon::now()->addYear()->format('Y-m-d H:i:s');
            $paymentdata->link_updated_at = now()->format('Y-m-d H:i:s');
            $paymentdata->save();
            $payment_request =  $this->paymentupdate($paymentdata->school_id, $paymentdata->id);
            //  dd($payment_request);
            /* $shortUrls = collect($payment_request)
               ->map(function ($response) {
                   $data = json_decode($response, true);
                   return $data['short_url'] ?? null;
               })
               ->filter()
               ->toArray(); */

            $shortUrls = collect($payment_request)
                ->map(function ($response) {
                    $data = json_decode($response, true);
                    $shortUrl = $data['short_url'] ?? null;
                    $id = $data['id'] ?? null;
                    return compact('shortUrl', 'id');
                })
                ->filter()
                ->toArray();

            if (!empty($shortUrls[0])) {
                $paymentdata->update([
                    'payment_url' => $shortUrls[0]['shortUrl'],
                ]);
                return response()->json([
                    'status' => true,
                    'message' => "School Payment successfully",
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => "No Payment successfully",
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function SchoolPaymentList(Request $request)
    {

        if ($request->ajax()) {
            $mainQuery = DB::table('schools_payments')
                ->whereNull('deleted_at')
                ->select([
                    'schools_payments.id',
                    'schools_payments.orderid',
                    'schools_payments.payment_amount',
                    'schools_payments.link_expiry_at',
                    'schools_payments.json_response',
                    'schools_payments.payment_failed_info',
                    'schools_payments.link_created_at',
                    'schools_payments.email_sent_at',
                    'schools_payments.email_sent_at as payment_due',
                    'schools_payments.sms_sent_at',
                    'schools_payments.payment_made_at',
                    'schools_payments.payment_status',
                    'schools_payments.payment_link_id',
                    'schools_payments.upload_invoice',
                    'schools_payments.description',
                    'schools_payments.school_id',
                    'schools_payments.email',
                    'schools_payments.payment_url',
                    'schools_payments.phone_number',
                    'school.school_name As school_name_text'
                ]);
            $mainQuery->leftJoin('school', 'school.id', 'schools_payments.school_id');
            $paymentlists = $mainQuery->get()->map(function ($row) {
                $paymentDueDate = isset($row->payment_due) ? $row->payment_due : '';
                $currentDate = Carbon::now();
                $row->payment_due = $currentDate->diffInDays($paymentDueDate);
                return $row;
            });
            $data = $paymentlists;
            return Datatables::of($data)
                ->addColumn('index', function ($row) {
                    static $index = 0;
                    return ++$index;
                })
                ->editColumn('school_name_text', function ($row) {
                    return '<a href="#" class="fw-bold ">
                                ' . $row->school_name_text . '
                            </a>';
                })
                /* ->editColumn('payment_amount', function ($row) {
                    return $row->payment_amount;
                })
                ->editColumn('payment_url', function ($row) {
                    return $row->payment_url;
                })  */
                ->editColumn('payment_url', function ($data) {
                    return '<a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#bs-school-modal" class="fw-bold preview_school_data" data-school="' . $data->id . '" title="Payment Info"><i class="fas fa-info-circle info-icon-payment"></i></a>';
                })

                ->editColumn('payment_failed_info', function ($data) {
                    if ($data->payment_failed_info) {
                        return '<a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#bs-sr-modal" class="fw-bold sr_payment_data" data-payment-sr="' . $data->id . '" title="Payment Info"><i class="fas fa-info-circle info-icon-payment"></i></a>';
                    } else {
                        return '';
                    }
                })


                /* ->editColumn('engineer_details', function ($data) {
                    $linkCreated = '<p class="m-0 custom-nowrap-ellipsis"><b>Link Created: </b> ' . $data->link_created_at . ' </p>';
                    $linkExpiry = '<p class="m-0 custom-nowrap-ellipsis"><b>Link Expiry: </b> ' . $data->link_expiry_at . ' </p>';
                    $emailSent = '<p class="m-0 custom-nowrap-ellipsis"><b>Email Sent: </b> ' . $data->email_sent_at . ' </p>';
                    $paymentDue = '';
                    if ($data->payment_due > 0) {
                        $paymentDue = '<p class="m-0 custom-nowrap-ellipsis"><b>Payment Due: </b>
                                        <button type="button" class="waves-effect waves-circle btn btn-circle btn-primary-light">
                                            ' . $data->payment_due . 'D
                                        </button>
                                    </p>';
                    }
                    $paymentMade = '<p class="m-0 custom-nowrap-ellipsis"><b>Payment Made: </b> ' . $data->payment_made_at . ' </p>';
                    $description = '<p class="m-0 custom-nowrap-ellipsis"><b>Description: </b> ' . substr($data->description, 0, 15);
                    if (strlen($data->description) > 20) {
                        $description .= '<a href="javascript:void(0);" data-description="' . $data->description . '" class="description_popup btn btn-sm btn-success">i</a>';
                    }
                    $description .= '</p>';
                    return '<div class="engineer-listing">' . $linkCreated . $linkExpiry . $emailSent . $paymentDue . $paymentMade . $description . '</div>';
                }) */
                /* ->editColumn('payment_status', function ($data) {
                    $statusClass = $data->payment_status == 1 ? 'success' : 'danger';
                    $statusText = $data->payment_status == 1 ? 'Activate' : 'Deactivate';

                    return '<a href="#" class="change_school_demo_status text-white badge bg-' . $statusClass . '">'
                        . $statusText . '</a>';
                }) */
                ->editColumn('payment_status', function ($data) {
                    if ($data->payment_failed_info) {
                        return '<a href="#" class="text-white badge bg-danger">Failed</a>';
                    } else {
                        $status = $data->payment_status == 1 ? 'success' : 'danger';
                        $text = $data->payment_status == 1 ? 'Done' : 'Pending';
                        return '<a href="#" class="text-white badge bg-' . $status . '">' . $text . '</a>';
                    }
                })

                ->addColumn('action', function ($data) {
                    /* $deactivateLink = '<a href="javascript:void(0);"
                                        payment-link-id="' . $data->payment_link_id . '"
                                        payment-id="' . $data->id . '"
                                        class="deactivate_link waves-effect waves-light btn btn-sm btn-outline btn-info mb-5" id="DeactivateLink">Deactivate</a>'; */

                    $removeUserData = '<a href="' . route('school-payment-removie', ['payment_id' => $data->id]) . '"
                                        class="remove_user_data waves-effect waves-light btn btn-sm btn-outline btn-danger mb-5"
                                        onclick="return confirm(\'Are you sure you want to delete this item?\');"
                                        >Delete</a>';

                    return /* $deactivateLink . ' ' . */ $removeUserData;
                })
                ->rawColumns(['index', 'school_name_text', 'payment_url', 'payment_failed_info', /* 'payment_amount', 'payment_url',  'json_response', */ /* 'engineer_details', */ 'payment_status', 'action'])
                ->toJson();
        }
        return view('school.payments-list');
    }

    public function UploadInvoice(Request $request)
    {
        $payment_id = $request->input('payment_id');
        if ($request->hasFile('invoice')) {
            /*  $upload_invoice = $request->file('invoice');
            $destinationPath = 'uploads/upload_invoice/';
            $originalname = $upload_invoice->hashName();
            $imageName = "upload_invoice_" . date('Ymd') . '_' . $originalname;
            $upload_invoice->move($destinationPath, $imageName);

            $Paymentdata = SchoolPayment::where('id', $payment_id)->first();

            $Paymentdata->update([
              'upload_invoice' => $imageName,
            ]);
            return response()->json([
                'status' => true,
                'message' => "Upload Invoice successfully",
            ]); */

            $upload_invoice = $request->file('invoice');
            $destinationPath = 'uploads/upload_invoice/';
            $originalname = $upload_invoice->hashName();
            $imageName = "upload_invoice_" . date('Ymd') . '_' . $originalname;
            $upload_invoice->move($destinationPath, $imageName);

            $Paymentdata = SchoolPayment::where('id', $payment_id)->first();

            $fileUrl = URL::to('/') . '/' . $destinationPath . $imageName;

            $Paymentdata->update([
                'upload_invoice' => $fileUrl,
            ]);
            return response()->json([
                'status' => true,
                'message' => "Upload Invoice successfully",
            ]);
        } else {
            $imageName = "";
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }
    public function SchoolPaymentRemove(Request $request)
    {
        $payment_id = $request->input('payment_id');
        $registerstudent = SchoolPayment::where('id', $payment_id)->first();
        if (!empty($registerstudent)) {
            $registerstudent->delete();
            return redirect()->intended(route('payment-list'))->withSuccess('School Payment Delete successfully');
        }
    }
    public function CreatePagePaymentRemove(Request $request)
    {
        $payment_id = $request->input('payment_id');
        $school_id = $request->input('school_id');
        $registerstudent = SchoolPayment::where('id', $payment_id)->first();
        if (!empty($registerstudent)) {
            $registerstudent->delete();
            echo "removed";
            //return redirect()->intended(route('school-payment', compact('school_id')))->withSuccess('School Payment Delete successfully');
        }
    }
    public function PaymentInvoice(Request $request)
    {
        $payment_id = $request->input('payment_id');
        $paymentdata = SchoolPayment::where('id', $payment_id)->first();
        $StudentExcelFile = Storage_path($paymentdata->upload_invoice);
        return response()->download($StudentExcelFile);
    }


    /* payment link email sent code*/
    public function SentEmailPaymentLink(Request $request)
    {
        $payments_id = $request->input('payments_id');
        $paymentdata = SchoolPayment::where('id', $payments_id)->first();
        if (empty($paymentdata)) {
            return response()->json([
                'status' => false,
                'message' => "Payment Not Found",
            ]);
        }
        $validator = Validator::make($request->all(), [
            'email' => 'required|json',
            'email_template' => 'required',
            'email_template_id' => 'required|integer|exists:email_templates,id',

        ]);
        if ($validator->passes()) {

            $emailTemplate = EmailTemplate::where('id', (int)$request->email_template_id)->update([
                'school_body' => isset($request->email_template) ? $request->email_template : null,
            ]);
            $paymentdata->update([
                'email_sent' => $request->email,
                'email_sent_at' => now()->format('Y-m-d H:i:s'),
            ]);
            $this->mailSentSchool($paymentdata->id, "school");
            return response()->json([
                'status' => true,
                'message' => "Email Sent successfully",
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function DeactivatePaymentLink(Request $request)
    {
        $payment_id = $request->input('payment_id');
        $paymentdata = SchoolPayment::where('id', $payment_id)->first();
        if (empty($paymentdata)) {
            return response()->json([
                'status' => false,
                'message' => "Payment Not Found",
            ]);
        }
        $validator = Validator::make($request->all(), [
            'payment_id' => 'required|integer|exists:schools_payments,id',
        ]);
        if ($validator->passes()) {

            $payment_request =  $this->paymentdeactivate($paymentdata->school_id, $paymentdata->id);
            $upi_link = collect($payment_request)
                ->map(function ($response) {
                    $data = json_decode($response, true);
                    $upi_link = $data['upi_link'] ?? null;
                    return compact('upi_link');
                })
                ->filter()
                ->toArray();
            if (($upi_link[0] == true)) {
                $paymentdata->update([
                    'payment_status' => 0,
                ]);
                return response()->json([
                    'status' => true,
                    'message' => "Payment Deactivate successfully",
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => "No Payment Deactivate",
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function singleSchoolPayment(Request $request)
    {
        $payment_id = $request->schoolPaymentId;
        if ($payment_id > 0) {
            $payment_data = SchoolPayment::where('id', $payment_id)->first();
            $school_data  = School::where('id', $payment_data->school_id)->first();
            return view('school.info_payment_list', compact('payment_data', 'school_data'));
        }
    }



    public function payment_success(Request $request)
    {

        $razorpayResponse = $request->all();
        $jsonResponse = json_encode($razorpayResponse);
        $payment = new SchoolPayment();
        $payment->orderid = 'HM-100';
        $payment->json_response = $jsonResponse;
        $payment->save();
        return response()->json(['message' => 'Payment success.']);
    }


    public function change_student_view_status(Request $request)
    {
        $schoolId = $request->school;
        $status = ($request->status == 1) ? 0 : 1;
        School::where('id', $schoolId)->update(['student_status_view' => $status]);
        echo ($status == 1) ? 'Demo' : 'Paid';
    }

    public function StudentPaymentList(Request $request)
    {

        $school_id = $request->input('school_id');
        $student_payment_data = DB::table('student_payments')
            ->where('student_payments.school_id', $school_id)
            ->select([
                'student_payments.id',
                'student_payments.orderid',
                'student_payments.payment_id',
                'student_payments.student_id',
                'student_payments.amount',
                'student_payments.payment_status',
                'student_payments.school_id',
                'student_payments.payment_failed_info',
                //'student_payments.duration_package ',
                'student_payments.start_date_sub',
                'student_payments.start_end_sub',
                'student_payments.created_at',
                'students.student_payment_sucess',
                'students.name',
                'students.last_name',
                'students.grade',
                'students.section',
                'school.school_name',
                'master_class.class_name',
            ])
            ->leftJoin('students', 'students.id', '=', 'student_payments.student_id')
            ->leftJoin('school', 'school.id', '=', 'student_payments.school_id')
            ->leftJoin('master_class', 'master_class.id', '=', 'students.grade')
            ->get();
        // dd($student_payment_data);
        return view('school.student-payment-list', compact('school_id', 'student_payment_data'));
    }


    public function generateStudentInvoice(Request $request)
    {
        $student_payment_id = $request->input('student_payment_id');
        //  dd($student_payment_id);
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
        $pdf = PDF::loadView('pdf.student-invoice', $data);
        return $pdf->download('student-invoice.pdf');
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



    public function StudentPaymentListSchoolAdmin(Request $request)
    {
        $user = Auth::user();
        $school_id = $user->school_id;
        // dd($school_id);
        $student_payment_data = DB::table('student_payments')
            ->where('student_payments.school_id', $school_id)
            ->select([
                'student_payments.id',
                'student_payments.orderid',
                'student_payments.payment_id',
                'student_payments.student_id',
                'student_payments.amount',
                'student_payments.payment_status',
                'student_payments.school_id',
                'student_payments.payment_failed_info',
                'student_payments.duration_package',
                'student_payments.start_date_sub',
                'student_payments.start_end_sub',
                'student_payments.created_at',
                'students.student_payment_sucess',
                'students.name',
                'students.last_name',
                'students.grade',
                'students.section',
                'school.school_name',
                'master_class.class_name',
            ])
            ->leftJoin('students', 'students.id', '=', 'student_payments.student_id')
            ->leftJoin('school', 'school.id', '=', 'student_payments.school_id')
            ->leftJoin('master_class', 'master_class.id', '=', 'students.grade')
            ->get();
        // dd($student_payment_data);
        return view('school.student-payment-admin', compact('school_id', 'student_payment_data'));
    }



    public function singleStudentPayment(Request $request)
    {
        $payment_id = $request->schoolPaymentId;
        if ($payment_id > 0) {
            // $payment_data = StudentPayment::where('id', $payment_id)->first();
            $payment_data = StudentPayment::leftJoin('students', 'student_payments.student_id', '=', 'students.id')
                ->where('student_payments.id', $payment_id)
                ->first();
            $school_data  = School::where('id', $payment_data->school_id)->first();
            return view('student.student_info_payment', compact('payment_data', 'school_data'));
        }
    }

    public function studentPaymentfailedpopup(Request $request)
    {
        $payment_id = $request->schoolPaymentId;
        //$school_data  = School::where('id', $payment_data->school_id)->first();

        $mainQuery = DB::table('student_payments')->where('student_payments.id', $payment_id)
            ->whereNull('student_payments.deleted_at')
            ->select([
                'student_payments.id',
                'student_payments.orderid',
                'student_payments.payment_id',
                'student_payments.student_id',
                'student_payments.amount',
                'student_payments.payment_status',
                'student_payments.payment_sucessfull_time',
                'student_payments.school_id',
                'student_payments.payment_failed_info',
                'student_payments.created_at',
                // 'students.student_payment_sucess',
                // 'students.name',
                //  'students.last_name',
                //'school.school_name',
                'school.school_name As school_name_text',
                'school.school_logo'
            ]);
        // mainQuery->leftJoin('students', 'students.id', '=', 'student_payments.student_id')
        $mainQuery->leftJoin('school', 'school.id', 'student_payments.school_id');

        // $student_payments_data = $mainQuery->first();

        $student_payments_data = $mainQuery->get()->map(function ($row) {
            $row->payment_failed_info = isset($row->payment_failed_info) ? json_decode($row->payment_failed_info) : '';
            return $row;
        })->first();
        return view('student.student_payment_failed_info', compact('student_payments_data'));
    }
}
