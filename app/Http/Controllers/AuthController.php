<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Traits\MailTrait;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Validator;
use Jenssegers\Agent\Facades\Agent;
use App\Models\{User, School, LogsModel, IpAddess, Support, ForgotPasswordSchoolAdmin, TeacherEmail, ClassroomSubscription, Program, SchoolPayment, EmailTemplate};
use DataTables;
use Mail;


class AuthController extends Controller
{
    use MailTrait;
    public function index(Request $request)
    {
        return view('auth.login');
    }

    public function authuser(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $username = $request->email;
        $password = $request->password;

        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            Auth::attempt(['email' => $username, 'password' => $password]);
        } else {
            Auth::attempt(['username' => $username, 'password' => $password]);
        }

        $user = Auth::user();
        if (Auth::check()) {
            $user = Auth::user();
            if (in_array($user->usertype, ['teacher', 'admin'])) {
                $school_data = School::where(['id' => $user->school_id])->first();
                if ($school_data->status == 0) {
                    Auth::logout();
                    return back()->withErrors(['error' => 'Your school account is not yet active, plz contact on support@valuezschool.com']);
                }
                session(['usertype' => $user->usertype]);

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
                    'info' => 'User Login',
                    'session' => $session_id,
                    'device_id' => $device_id,
                ];

                LogsModel::create(['userid' => $user->id, 'session_id' => $session_id, 'ip_address' => $request->ip(), 'action' => 'login', 'logs_info' => json_encode($log_arr)]);
            }
            if ($user->usertype == 'superadmin' || $user->usertype == 'contentadmin') {
                session(['usertype' => $user->usertype]);
                return redirect()->intended(route('admin-dashboard'))->withSuccess('Signed in');
            } else if ($user->usertype == 'teacher' && $user->status == 1) {
                return redirect(route('teacher.class.list'))->withSuccess('Signed in');
            } else if ($user->usertype == 'admin') {
                return redirect(route('school.teacher.list'))->withSuccess('Signed in');
            } else if ($user->usertype == 'student') {
                Auth::logout();
                return back()->withErrors(['errors' => 'Your account is not yet active.']);
            } else {
                Auth::logout();
                return back()->withErrors(['error' => 'Your account is not yet active.']);
            }
        }
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function IpaddressRemove(Request $request)
    {
        $ipaddess_id = $request->input('ipaddess_id');
        $IpAddessdata = IpAddess::where('id', $ipaddess_id)->first();
        if (!empty($IpAddessdata)) {
            $IpAddessdata->delete();
            echo "removed";
        }
    }
    public function userlist(Request $request)
    {
        $user = Auth::user();
        $schoolid = $request->input('school');
        $class_list = Program::where('status', 1)->get();
        $grade = $request->input('grade');

        if ($request->ajax()) {
            $status_hide_school_admin = "";
            $schoolId = $request->input('school');
            $search_results_dashbord = $request->input('search_results_dashbord');
            $user_type = $request->input('user_type');
            $checked_variable = $request->input('checked_variable');
            $grade = $request->input('grade');
            $query = $request->input('query');
            if($search_results_dashbord){
               // dd($search_results_dashbord);
                $mainQuery = User::where([
                    'school_id' => $search_results_dashbord,
                    'usertype' => 'teacher',
                    'is_deleted' => 0,
                ]);
            }else{
               // dd($schoolId);
                $mainQuery = User::where([
                    'school_id' => $schoolid,
                    'usertype' => 'teacher',
                    'is_deleted' => 0,
                ]);
            }

            if (isset($grade) && (int)$grade > 0) {
                $mainQuery->where('grade', (int)$grade);
            }
            if (isset($query) && $query) {
                $mainQuery->where('name', 'LIKE', '%' . $query . '%');
            }
            $data = $mainQuery->orderBy('id', 'desc');
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
                    return '<div class="engineer-listing">
                                <p class="m-0 custom-nowrap-ellipsis"><b>Name: </b>' . ($row->name ?? '') . '</p>
                                <p class="m-0 custom-nowrap-ellipsis" id="copyContainer_' . $row->id . '">
                                    <b>Username: </b>
                                    <span id="usernameParagraph_' . $row->id . '">' . ($row->username ?? '') . '</span><br>
                                    <b>Password: </b>
                                    <span id="passwordParagraph_' . $row->id . '" data-password="' . ($row->view_pass ?? '') . '">xxxxx</span>
                                </p>
                            </div>';
                })
                ->editColumn('username', function ($row) {
                    return '<button class="btn btn-sm btn-outline-secondary copy-button" style="margin-left: 50px; background-color: #00205c; color: #fff;" onclick="copyToClipboard(' . $row->id . ')">Copy</button>';
                })

                ->editColumn('status', function ($row) use ($user_type) {
                   // dd($user_type);
                    if ($user_type != 'admin') {
                        return '<a href="javascript:void(0);"
                                    class="change_status text-white badge bg-' . ($row->status == 1 ? 'success' : 'danger') . '"
                                    id="status_' . $row->id . '" data-id="' . $row->id . '"
                                    data-status="' . $row->status . '">' . ($row->status == 1 ? 'Active' : 'Inactive') . '</a>';
                    }  else {
                        return '';
                    }
                })
                ->editColumn('section', function ($row) {
                    return '<div class="engineer-listing">
                                <p class="m-0 custom-nowrap-ellipsis"><b>Grade: </b>' . ($row->grade ?? '') . '</p>
                                <p class="m-0 custom-nowrap-ellipsis"><b>Section: </b>' . ($row->section ?? '') . '</p>
                            </div>';
                })

                ->addColumn('action', function ($row) use ($user_type) {
                    if ($user_type != 'admin') {
                        $deleteButton = '<a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#bs-password-modal"
                                            class="remove_user_data waves-effect waves-light btn btn-sm btn-outline btn-danger mb-5"
                                            data-userid="' . $row->id . '">Delete</a>';
                    } else {
                        $deleteButton = '';
                    }
                    $columnContent = '<div class="dropdown">
                                        <button class="btn btn-sm btn-outline btn-info mb-5 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="icon ti-settings"></i> Password
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="reset_password dropdown-item" href="javascript:void(0);" data-userid="' . $row->id . '">
                                                <i class="fa fa-refresh"></i> Reset
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="view_password dropdown-item" data-email="' . $row->username . '" data-pass="' . $row->view_pass . '" href="javascript:void(0);">
                                                <i class="fa fa-eye"></i> View
                                            </a>
                                        </div>
                                        <a href="' . route('teacher.edit', ['userid' => $row->id]) . '"
                                            class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Edit</a>'
                                            . $deleteButton . // Include the delete button here
                                        '<a href="' . route('user.logs.list', ['userid' => $row->id , 'schol_id' => $row->school_id]) . '"
                                            class="waves-effect waves-light btn btn-sm btn-outline btn-danger mb-5">Classroom Logs</a>
                                    </div>';

                    return $columnContent;
                })
                ->rawColumns(['username', 'name', 'id', 'status', 'section',   'action'])
                ->toJson();
        }
        $mainQuery = DB::table('users')->where(['school_id' => $schoolid, 'usertype' => 'teacher', 'is_deleted' => 0])->orderBy('id', 'desc')->get();
        $total_teacher = $mainQuery->where('status', 1)->count();

        $school_data = DB::table('school')->where('id', $schoolid)->where('status', 1)->where('is_deleted', 0)->first();
        $status_hide_school_admin = "";
        return view('users.teacher', compact('class_list', 'user', 'grade', 'total_teacher', 'school_data', 'schoolid', 'status_hide_school_admin'));
    }
    public function addUser(Request $request)
    {
        $user = Auth::user();
        $user_type = $user->usertype;
        // dd($user_type);
        $schoolid = $request->input('school');
        return view('users.teacher-add', compact('schoolid', 'user'));
    }
    public function addAdminUser(Request $request)
    {
        $schoolid = $request->schoolid;
        return view('users.schooladmin.admin-add', compact('schoolid'));
    }

    public function updateUser(Request $request)
    {
        $userId = $request->input('userid');
        $user_auth = Auth::user();
        $where_cond = ['usertype' => 'teacher', 'id' => $userId];
        if (session()->get('usertype') == 'admin') {
            $where_cond['school_id'] = $user_auth->school_id;
        }
        $user = DB::table('users')->where(['users.id' => $userId, 'users.usertype' => 'teacher', 'users.is_deleted' => 0])
            ->select([
                'users.id',
                'users.name',
                'users.email',
                'users.usertype',
                'users.email_verified_at',
                'users.grade',
                'users.section',
                'users.view_pass',
                'users.password',
                'users.remember_token',
                'users.created_at',
                'users.updated_at',
                'users.school_id',
                'users.status',
                'users.is_deleted',
                'users.username',
                'users.role_type',
            ])->first();
        return view('users.teacher-edit', compact('user', 'user_auth'));
    }

    public function updateAdminUser(Request $request)
    {
        $userId = $request->userid;
        $user = Auth::user();
        $where_cond = ['usertype' => 'admin', 'id' => $userId];
        if (session()->get('usertype') == 'admin') {
            $where_cond['school_id'] = $user->school_id;
        }
        $user = DB::table('users')->where($where_cond)->first();
        return view('users.schooladmin.admin-edit', compact('user'));
    }

    public function createuser(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
        ]);

        $data = $request->all();
        $check = $this->create($data);
        $redirect = (session()->get('usertype') == 'admin') ? route('school.teacher.list') : route('teacher.list', ['school' => $data['school']]);
        if ($check == "error") {
            return redirect($redirect)->with('error', 'Maximum licences limit reached.');
        } else {
            $pagetype = !empty($data['pagetype']) ? $data['pagetype'] : '';
            $redirect_url = ($pagetype == 'schooladmin') ? route('school.admin', ['school' => $data['school']]) : $redirect;
            return redirect($redirect_url)->withSuccess('User added successfully!');
        }
    }

    public function create(array $data)
    {

        $check_school_user = School::with(['teacher' => function ($query) {
            $query->where('usertype', '=', 'teacher')->where('status', 1)->where(['is_deleted' => 0]);
        }])->where(['is_deleted' => 0, 'id' => $data['school']])->orderBy('id')->first();
        $total_teacher = ($check_school_user) ? $check_school_user->teacher->count() : 0;
        //  dd($total_teacher);
        if ((isset($data['usertype']) && $data['usertype'] == "admin") || ($check_school_user->licence > $total_teacher)) {

            $passWord = isset($data['password']) ? $data['password'] : Str::random(5);
            $user_email = strtolower($data['email']);
            $username = explode("@", $user_email);
            $userId = trim($username[0]) . date('Yims');
            $add_user = [
                'name' => $data['name'],
                'email' => $data['email'],
                'grade' => isset($data['grade']) ? $data['grade'] : "",
                'section' => isset($data['section']) ? $data['section'] : "",
                'school_id' => $data['school'],
                'usertype' => isset($data['usertype']) ? $data['usertype'] : 'teacher',
                'status' => 1,
                'username' => $userId,
                'view_pass' => $passWord,
                'password' => Hash::make($passWord)
            ];
            return User::create($add_user);
        } else {
            return "error";
        }
    }



    public function createTeacher(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'grade' => 'required',
            'section' => 'required',
            'confirm_grade' => 'required',
            'confirm_section' => 'required',
        ]);
        $data = $request->all();

        if ($data['grade'] != $data['confirm_grade']) {
            return back()->withErrors(['confirm_grade' => 'grade and confirm_grade do not match or password is empty.']);
        }
        if ($data['section'] != $data['confirm_section']) {
            return back()->withErrors(['confirm_section' => 'section and confirm_section do not match or password is empty.']);
        }



        $check = $this->createTeach($data);
        $redirect = (session()->get('usertype') == 'admin') ? route('school.teacher.list') : route('teacher.list', ['school' => $data['school']]);
        if ($check == "error") {
            return redirect($redirect)->with('error', 'Maximum licences limit reached.');
        } else {
            $pagetype = !empty($data['pagetype']) ? $data['pagetype'] : '';
            $redirect_url = ($pagetype == 'schooladmin') ? route('school.admin', ['school' => $data['school']]) : $redirect;
            return redirect($redirect_url)->withSuccess('User added successfully!');
        }
    }

    public function createTeach(array $data)
    {

        $check_school_user = School::with(['teacher' => function ($query) {
            $query->where('usertype', '=', 'teacher')->where('status', 1)->where(['is_deleted' => 0]);
        }])->where(['is_deleted' => 0, 'id' => $data['school']])->orderBy('id')->first();
        $total_teacher = ($check_school_user) ? $check_school_user->teacher->count() : 0;
        if ($check_school_user->licence > $total_teacher) {

            $passWord = isset($data['password']) ? $data['password'] : Str::random(5);
            /* $username = $check_school_user->school_username . '_' . $data['grade'] . substr($data['section'],  0, 1);
            $lastcharacter = '\_' . $data['grade'] . substr($data['section'],  0, 1);
            $usernameCount = DB::table('users')->select([
                'id',
                'username',
            ])->where('usertype', 'teacher')->where('username', 'like', "%{$lastcharacter}%")->count();
            $singlenumber  = str_pad((int) $usernameCount + 1, 2,  '0', STR_PAD_LEFT);
            $username = ($username . $singlenumber); */

            $username = $check_school_user->school_username . '_' . $data['grade'] . substr($data['section'],  0, 1);
            $usernameCount = DB::table('users')->select([
                'id',
                'username',
            ])->where('username', 'like', "%{$username}%")->count();
            if($usernameCount == 0){
                $username = $username;
            }else{
                $userCount = $usernameCount - 1;
              $singlenumber  = str_pad((int) $userCount + 1, 2,  '0', STR_PAD_LEFT);
              $username = ($username . $singlenumber);
            }
           // dd($username);
            $add_user = [
                'name' => $data['name'],
                'grade' => isset($data['grade']) ? $data['grade'] : "",
                'section' => isset($data['section']) ? $data['section'] : "",
                'school_id' => $data['school'],
                'usertype' => 'teacher',
                'status' => 1,
                'username' => $username,
                'view_pass' => $passWord,
                'password' => Hash::make($passWord)
            ];
            return  $teachers_data = User::create($add_user);
        } else {
            return "error";
        }
    }



    public function edituser(Request $request)
    {
        $data = $request->all();
        $school = $data['school'];
        $pagetype = !empty($data['pagetype']) ? $data['pagetype'] : '';
        $updateuser = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];
        $validate = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'
        ];

        if (!empty($data['password'])) {
            if ($data['password'] != $data['confirm_password']) {
                return back()->withErrors(['confirm_password' => 'Password and confirm_password do not match or password is empty.']);
            }
            $validate['password'] = ['required', Password::min(6)];
            $updateuser['password'] = Hash::make($data['password']);
            $updateuser['view_pass'] = $data['password'];
        }

        $request->validate($validate);
        User::where('id', $data['id'])->update($updateuser);
        if (!empty($data['password'])) {
            $details = [
                'view' => 'emails.reset_password',
                'subject' => $data['name'] . ' Your Account Password Reset by admin - Valuez',
                'title' => $data['name'],
                'email' => $data['email'],
                'pass' => $data['password']
            ];
            Mail::to($data['email'])->send(new \App\Mail\TestMail($details));
        }
        $redirect = (session()->get('usertype') == 'admin') ? route('school.teacher.list') : route('teacher.list', ['school' => $school]);

        $redirect_url = ($pagetype == 'schooladmin') ? route('school.admin', ['school' => $school]) : $redirect;
        return redirect($redirect_url)->with('success', 'User Updated successfully');
    }




    public function editTeacher(Request $request)
    {
        $data = $request->all();
        $school = $data['school'];
        $pagetype = !empty($data['pagetype']) ? $data['pagetype'] : '';
        $updateuser = [
            'name' => $data['name'],
        ];
        $validate = [
            'name' => 'required',
        ];
        if (!empty($data['password'])) {
            if ($data['password'] != $data['confirm_password']) {
                return back()->withErrors(['confirm_password' => 'Password and confirm_password do not match or password is empty.']);
            }
            $validate['password'] = ['required', Password::min(6)];
            $updateuser['password'] = Hash::make($data['password']);
            $updateuser['view_pass'] = $data['password'];
        }

        $request->validate($validate);
        User::where('id', $data['id'])->update($updateuser);
        /* if (!empty($data['password'])) {
            $details = [
                'view' => 'emails.reset_password',
                'subject' => $data['name'] . ' Your Account Password Reset by admin - Valuez',
                'title' => $data['name'],
                'email' => $data['email'],
                'pass' => $data['password']
            ];
            Mail::to($data['email'])->send(new \App\Mail\TestMail($details));
        } */
        $redirect = (session()->get('usertype') == 'admin') ? route('school.teacher.list') : route('teacher.list', ['school' => $school]);

        $redirect_url = ($pagetype == 'schooladmin') ? route('school.admin', ['school' => $school]) : $redirect;
        return redirect($redirect_url)->with('success', 'User Updated successfully');
    }




    public function resetPassword(Request $request)
    {
        $passWord = $this->getToken();
        $resetPass = User::where('id', $request->userid)->update(['view_pass' => $passWord, 'password' => Hash::make($passWord)]);
        if ($resetPass) {
            $user_email = User::where('id', $request->userid)->first();
            $school_data = School::where('id', $user_email->school_id)->first();
            $cc = 'support@valuezschool.com';
            $userEmails = [
                [
                    'email' => $cc,
                ],
                [
                    'email' => $user_email->email,
                ]
            ];
            /* if ($user_email->usertype == 'admin') {
                foreach ($userEmails as $userData) {
                    $details = [
                        'view' => 'emails.school-admin-reset-password',
                        'subject' => 'Your school admin account password of Valuez school 21st Century LMS has been reset',
                        'title' => $user_email->name,
                        'school_name' => $school_data->school_name,
                        'email' => $user_email->email,
                        'pass' => $passWord
                    ];
                    Mail::to($userData['email'])->cc($cc)->send(new \App\Mail\TestMail($details));
                }
            } else {
                foreach ($userEmails as $userData) {
                    $details = [
                        'view' => 'emails.teacher-reset-password',
                        'subject' => 'Your teacher account password of Valuez school 21st Century LMS has been reset',
                        'title' => $user_email->name,
                        'school_name' => $school_data->school_name,
                        'email' => $user_email->email,
                        'pass' => $passWord
                    ];
                    Mail::to($userData['email'])->cc($cc)->send(new \App\Mail\TestMail($details));
                }
            } */
        }
    }

    public function destroy(Request $request)
    {
        $userId = $request->input('userid');
        $userPass = $request->input('userpass');
        if (Auth::check()) {
            $user = Auth::user();
            if (Hash::check($userPass, $user->password)) {
                DB::table('users')->where('id', $userId)->update(['is_deleted' => 1]);
                echo "removed";
            } else {
                return response()->json(['success' => false, 'msg' => 'Entered Password Incorrect.']);
            }
        } else {
            return response()->json(['success' => false, 'msg' => 'Somenthing Went Wrong!']);
        }
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


    public function AdminDash()
    {
        if (Auth::check()) {
            $school = $teacher = $program = $lessonplan = 0;

            $school = DB::table('school')->where('status', 1)->where('is_deleted', 0)->get()->count();
            $teacher = DB::table('users')->where('usertype', 'teacher')->where('is_deleted', 0)->get()->count();
            $total_teacher_active = DB::table('users')->where('usertype', 'teacher')->where('status', 1)
                ->where('is_deleted', 0)->get()->count();

            $total_teacher_compelte = DB::table('reports')->where('complesion_status', 1)->get()->count();
            $student = DB::table('students')->where('studenttype', 'student')->whereNull('deleted_at')->get()->count();
            $course = DB::table('master_course')->where('status', 1)->get()->count();
            $program = DB::table('master_class')->where('status', 1)->get()->count();
            $lessonplan = DB::table('lesson_plan')->where('status', 1)->get()->count();
            return view('dashboard-admin', compact('school', 'teacher', 'total_teacher_compelte', 'total_teacher_active', 'student', 'program', 'lessonplan', 'course'));
        } else {
            return redirect("login")->withSuccess('You are not allowed to access');
        }
    }

    public function dashboard()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $schoolid = $user->school_id;
            if (session()->get('usertype') == 'admin') {
                $school = School::with(['teacher' => function ($query) {
                    $query->where('usertype', '=', 'teacher');
                    $query->where('status', '=', 1);
                    $query->where('is_deleted', '=', 0);
                }])->where('id', $schoolid)->orderBy('id')->first();
                $package_end = $school->package_end;
                $currentDate = Carbon::now();
                $time_left = $currentDate->diffInDays($package_end);
                return view('dashboard', compact('school', 'time_left'));
            } else {
                return view('dashboard-teacher');
            }
        } else {
            return redirect("login")->withSuccess('You are not allowed to access');
        }
    }


    public function teacherList(Request $request)
    {
        $query = $request->input('query');
        $user = Auth::user();
        $schoolid = $user->school_id;
        $class_list = Program::where('status', 1)->get();
        $grade = $request->input('grade');

        if ($request->ajax()) {
            $status_hide_school_admin = "";
            $schoolId = $request->input('school');
            $checked_variable = $request->input('checked_variable');
            $grade = $request->input('grade');
            $data = User::where(['school_id' => $schoolid, 'usertype' => 'teacher', 'is_deleted' => 0])->orderBy('id', 'desc');
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
                    return '<div class="engineer-listing">
                                <p class="m-0 custom-nowrap-ellipsis"><b>Name: </b>' . ($row->name ?? '') . '</p>
                                <p class="m-0 custom-nowrap-ellipsis" id="copyContainer_' . $row->id . '">
                                    <b>Username: </b>
                                    <span id="usernameParagraph_' . $row->id . '">' . ($row->username ?? '') . '</span><br>
                                    <b>Password: </b>
                                    <span id="passwordParagraph_' . $row->id . '" data-password="' . ($row->view_pass ?? '') . '">xxxxx</span>
                                </p>
                            </div>';
                })
                ->editColumn('username', function ($row) {
                    return '<button class="btn btn-sm btn-outline-secondary copy-button" style="margin-left: 50px; background-color: #00205c; color: #fff;" onclick="copyToClipboard(' . $row->id . ')">Copy</button>';
                })
                ->editColumn('status', function ($row) use ($status_hide_school_admin) {
                    if ($status_hide_school_admin != 'school_admin') {
                        return '<a href="javascript:void(0);"
                                    class="change_status text-white badge bg-' . ($row->status == 1 ? 'success' : 'danger') . '"
                                    id="status_' . $row->id . '" data-id="' . $row->id . '"
                                    data-status="' . $row->status . '">' . ($row->status == 1 ? 'Active' : 'Inactive') . '</a>';
                    } else {
                        return '';
                    }
                })
                ->editColumn('section', function ($row) {
                    return '<div class="engineer-listing">
                                <p class="m-0 custom-nowrap-ellipsis"><b>Grade: </b>' . ($row->grade ?? '') . '</p>
                                <p class="m-0 custom-nowrap-ellipsis"><b>Section: </b>' . ($row->section ?? '') . '</p>
                            </div>';
                })
                ->addColumn('action', function ($row) {
                    return '<div class="dropdown">
                                <button class="btn btn-sm btn-outline btn-info mb-5 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="icon ti-settings"></i> Password
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="reset_password dropdown-item" href="javascript:void(0);" data-userid="' . $row->id . '">
                                        <i class="fa fa-refresh"></i> Reset
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="view_password dropdown-item" data-email="' . $row->email . '" data-pass="' . $row->view_pass . '" href="javascript:void(0);">
                                        <i class="fa fa-eye"></i> View
                                    </a>
                                </div>
                                <a href="' . route('teacher.edit', ['userid' => $row->id]) . '"
                                    class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Edit</a>
                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#bs-password-modal"
                                    class="remove_user_data waves-effect waves-light btn btn-sm btn-outline btn-danger mb-5"
                                    data-userid="' . $row->id . '">Delete</a>
                                <a href="' . route('user.logs.list', ['userid' => $row->id]) . '"
                                    class="waves-effect waves-light btn btn-sm btn-outline btn-warning mb-5">Session Log</a>
                            </div>';
                })
                ->rawColumns(['username', 'name', 'id', 'status', 'section',   'action'])
                ->toJson();
        }
        $mainQuery = DB::table('users')->where(['school_id' => $schoolid, 'usertype' => 'teacher', 'is_deleted' => 0])->orderBy('id', 'desc')->get();
        $total_teacher = $mainQuery->where('status', 1)->count();

        $school_data = DB::table('school')->where('id', $schoolid)->where('status', 1)->where('is_deleted', 0)->first();
        $status_hide_school_admin = "";
        return view('users.teacher', compact('class_list', 'user', 'grade', 'total_teacher', 'school_data', 'schoolid', 'status_hide_school_admin'));
    }




    public function ipaddressList(Request $request)
    {
        $user_id = $request->input('user_id');

        if ($user_id > 0) {
            $ipaddress_data = DB::table('ipaddresss')->where('user_id', $user_id)
                ->select([
                    'users.id as usersid',
                    'users.name',
                    'ipaddresss.id as ipaddresss_id',
                    'ipaddresss.ip_address',
                    'ipaddresss.deleted_at as ipaddresss_deleted_at',
                    'ipaddresss.user_id',
                ])->groupBy('ipaddresss.ip_address')
                ->leftJoin('users', 'users.id', 'ipaddresss.user_id')
                ->get();

            if (!empty($ipaddress_data)) {
                return response()->json([
                    'status' => true,
                    'ipaddress_data' => $ipaddress_data,
                    'message' => "IP Address Retrive successfully",
                ]);
            }
        }
    }
    public function SchoolAdmin(Request $request)
    {
        $schoolid = $request->school;
        if ($request->ajax()) {
            $schoolId = $request->input('school');
            $data = DB::table('users')->where(['school_id' => $schoolid, 'usertype' => 'admin', 'users.is_deleted' => 0])->orderBy('id', 'desc')->get();
            return Datatables::of($data)
                ->addColumn('index', function ($row) {
                    static $index = 0;
                    return ++$index;
                })
                ->editColumn('name', function ($row) {
                    return $row->name ?? '';
                })
                ->editColumn('email', function ($row) {
                    return $row->email ?? '';
                })
                ->addColumn('status', function ($row) {
                    return '<a href="javascript:void(0);"
                        class="change_status text-white badge bg-' . ($row->status == 1 ? 'success' : 'danger') . '"
                        id="status_' . $row->id . '" data-id="' . $row->id . '"
                        data-status="' . $row->status . '">' . ($row->status == 1 ? 'Active' : 'Inactive') . '</a>';
                })
                ->addColumn('action', function ($row) {
                    return '<div class="dropdown">
                                <button class="btn btn-sm btn-outline btn-primary mb-5 dropdown-toggle"
                                        type="button" data-bs-toggle="dropdown"><i class="icon ti-settings"></i>
                                        Password</button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="reset_password dropdown-item" href="javascript:void(0);"
                                        data-userid="' . $row->id . '"><i class="fa fa-refresh"></i> Reset</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="view_password dropdown-item" data-email="' . $row->email . '"
                                        data-pass="' . $row->view_pass . '" href="javascript:void(0);"><i class="fa fa-eye"></i> View</a>
                                </div>

                                <a href="' . route('school.admin.edit', ['userid' => $row->id]) . '"
                                    class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Edit</a>

                                <a href="javascript:void(0);" data-id="' . $row->id . '"
                                    class="waves-effect waves-light remove_school_data btn btn-sm btn-outline btn-danger mb-5">Delete</a>
                            </div>';
                })
                ->rawColumns(['action', 'name', 'email', 'status'])
                ->toJson();
        }
        return view('users.schooladmin.admin', compact('schoolid'));
    }

    public function change_admin_status(Request $request)
    {
        $user_id = $request->user_id;
        $status = ($request->status == 1) ? 0 : 1;
        User::where('id', $user_id)->update(['status' => $status]);
        echo ($status == 1) ? 'Active' : 'Inactive';
    }

    public function signOut(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $userId = $user->id;
            LogsModel::create(['userid' => $userId, 'action' => 'logout', 'logs_info' => json_encode(['info' => 'User logout', 'usertype' => $user->usertype])]);
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }
    public function StudentSignout(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $userId = $user->id;
            $last_logged_session = LogsModel::where(['userid' => $user->id, 'action' => 'login'])
                ->whereNotNull('current_student_session_id')
                ->latest() 
                ->first(); 
            if ($last_logged_session) {
                $last_logged_session->update(['current_student_session_id' => null]);
            }

            $session_id = session()->getId();
            session(['usertype' => $user->usertype]);
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

            LogsModel::create(['userid' => $user->id, 'action' => 'logout', 'current_student_session_id' => null, 'logs_info' => json_encode($log_arr)]);
          //  LogsModel::create(['userid' => $userId, 'action' => 'logout', 'logs_info' => json_encode(['info' => 'User logout', 'usertype' => $user->usertype])]);
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('student-login');
    }

    // Generate token
    public function getToken($length = 5)
    {
        return Str::random($length);
    }

    public function UserAccountMail($data)
    {
        $details = [
            'view' => 'emails.account',
            'subject' => 'User Account creation Mail from Valuez',
            'title' => $data['username'],
            'userid' => $data['userid'],
            'pass' => $data['pass'],
            'school_name' => $data['school_name'],
        ];
    }


    public function supporthistory()
    {
        $user = Auth::user();
        $schoolid = $user->school_id;
        $supports = DB::table('supports')
        ->where('user_id', $user->id)
        ->orderBy("supports.id", "DESC")
        ->get();
        return view('users.support', compact('user','supports'));
    }

    public function getSupportlist(Request $request)
    {

        $school_all_data = School::get();
            if ($request->ajax()) {
                $school_id_filter = $request->input('school_id_filter');
                $mainQuery = DB::table('supports')
                ->whereNull('deleted_at')
                    ->select([
                           'supports.id',
                           'supports.name',
                           'supports.email',
                           'supports.phone_number',
                           'supports.query',
                           'supports.support_reply',
                           'supports.support_reply_noty',
                           'supports.user_id',
                           'supports.status',
                           'supports.deleted_at',
                           'supports.created_at',
                           'supports.updated_at',
                           'users.name As teacher_name',
                           'users.school_id',
                           'school.school_name',
                       ])->orderBy('supports.id', 'desc');
                       $mainQuery->leftJoin('users', 'users.id', 'supports.user_id');
                       $mainQuery->leftJoin('school', 'school.id', 'users.school_id');
                       if(isset($school_id_filter) && (int)$school_id_filter>0){
                        $mainQuery->where('school.id', $school_id_filter);
                       }

                 $data = $mainQuery->orderBy('supports.id', 'desc');
                 return Datatables::of($data)
                 ->addColumn('index', function ($row) {
                     static $index = 0;
                     if ($row->support_reply_noty == 1) {
                        return '<b>' . ++$index . '</b>';
                    } else {
                        return ++$index;;
                    }
                 })
                 ->editColumn('school_name', function ($row) {
                    if ($row->support_reply_noty == 1) {
                        return '<b>' . $row->school_name . '</b>';
                    } else {
                        return $row->school_name;
                    }
                })
                 ->editColumn('name', function ($row) {
                    if ($row->support_reply_noty == 1) {
                        return '<b>' . $row->name . '</b>';
                    } else {
                        return $row->name;
                    }
                 })
                ->editColumn('query', function ($row) {
                    $description = substr($row->query, 0, 10);
                    $hasPopupLink = (strlen($row->query) > 10);
                    $output = $description;
                    if ($hasPopupLink) {
                        $output .= '<a href="javascript:void(0);" data-description="' . $row->query . '" class="description_popup btn btn-sm btn-success">i</a>';
                    }
                    if ($row->support_reply_noty == 1) {
                        return '<b>' . $output . '</b>';
                    } else {
                        return $output;
                    }
                })
                ->editColumn('support_reply', function ($row) {
                    $support_reply = strip_tags($row->support_reply);
                    $description = substr($support_reply, 0, 10);
                    $hasPopupLink = (strlen($support_reply) > 10);
                    $output = $description;
                    if ($hasPopupLink) {
                        $output .= '<a href="javascript:void(0);" data-description-reply="' . $support_reply . '" class="feedback_reply_popup btn btn-sm btn-success">i</a>';
                    }
                    if ($row->support_reply_noty == 1) {
                        return '<b>' . $output . '</b>';
                    } else {
                        return $output;
                    }
                })
                ->editColumn('status', function ($row) {
                         return '<a href="javascript:void(0);"
                                     class="change_status text-white badge bg-' . ($row->status == 1 ? 'success' : 'danger') . '"
                                     id="status_' . $row->id . '" data-id="' . $row->id . '"
                                     data-status="' . $row->status . '">' . ($row->status == 1 ? 'Closed' : 'Open') . '</a>';
                 })
                 ->editColumn('created_at', function ($row) {
                     $formattedDate = date('d/m/Y', strtotime($row->created_at));
                     $formattedTime = date('H:i', strtotime($row->created_at));
                     if ($row->support_reply_noty == 1) {
                        return '<b>' . "$formattedDate | $formattedTime" . '</b>';
                    } else {
                        return "$formattedDate | $formattedTime";
                    }
                    // return "$formattedDate | $formattedTime";
                 })
                 ->addColumn('action', function ($row) {
                    $replyBtn = '<a href="' . route('support-reply-add', ['support_id' => $row->id]) . '"
                    class="waves-effect waves-light btn btn-sm btn-outline btn-outline btn-info mb-5">Reply</a>';

                    $removeBtn = '<a href="javascript:void(0)" data-id=' . $row->id . '
                    class="waves-effect waves-light remove_school_data btn btn-sm btn-outline btn-danger mb-5">Delete</a>';
                  //  return  $replyBtn . ' ' . $removeBtn;
                    if ($row->support_reply_noty == 1) {
                        return '<b>' . $replyBtn . ' ' . $removeBtn . '</b>';
                    } else {
                        return $replyBtn . ' ' . $removeBtn;
                    }
                })

                 ->rawColumns(['index','school_name','status', 'query', 'name',  'support_reply',   'created_at','action' ])
                 ->toJson();
             }

        return view('users.support-list',compact('school_all_data'));
    }



    public function supportReply(Request $request)
    {
        $support_id = $request->input('support_id');
        return view('users.support-reply',compact('support_id'));
       // dd($feedback_id);
    }

    public function supportCreateReply(Request $request)
    {
        $support_id = $request->input('support_id');
        $validator = Validator::make($request->all(),[
            'support_reply' => 'required',
        ]);
        if($validator->passes()){
            $feedback_data = Support::where(['id' => $support_id])->first();

            if(!empty($feedback_data)){
                $feedback_data->update([
                  'support_reply' => $request->support_reply,
                  'support_reply_noty' => 0,
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

    public function supportNotify(Request $request)
    {
         $support_count_noty = DB::table('supports')
         ->where('support_reply_noty', 1)->count();
        // dd($support_count_noty);
        return response()->json([
            'status' => true,
            'support_count_noty' => $support_count_noty,
            'message' => "Support notification count successfully",
        ]);
    }

    public function contactSupport(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'email' => 'nullable|email',
        'phone_number' => 'required',
        'query' => 'required',
        'user_id' => 'required',
    ]);

    if ($validator->passes()) {
        $name = $request->input('name');
        $email = $request->input('email');
        $phone_number = $request->input('phone_number');
        $query = $request->input('query');
        $user_id = $request->input('user_id');
        $support = new Support;
        $support->name = $name;
        $support->email = $email;
        $support->phone_number = $phone_number;
        $support->query = $query;
        $support->support_reply_noty = 1;
        $support->user_id = $user_id;
        $support->save();
        $this->mailSentSupport($support->id, "support");
        return response()->json([
            'status' => true,
            'message' => "Thank you for your query! We will get back to you soon.",
        ]);
    } else {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors(),
        ]);
    }
}



    public function subscriptionCreate()
    {
        $user = Auth::user();
        $schoolid = $user->school_id;
        $school_data = DB::table('school')->where('id', $schoolid)->first();
        return view('users.subscription-create', compact('school_data'));
    }

    public function createSubscription(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'subscriptions' => 'required|array',
            'subscriptions.subscription.*.teacher_name' => 'nullable|string',
            'subscriptions.subscription.*.garde' => 'required|string',
            'subscriptions.subscription.*.section' => 'required|string',
            'subscriptions.subscription.*.confirm_section' => 'required|same:subscriptions.subscription.*.section',
        ]);
        $aiModuleMsgRule = [];

        foreach ($request->subscriptions['subscription'] as $cindex => $competence_data) {
            $gardeKey = 'subscriptions.subscription.' . $cindex . '.garde';
            $sectionKey = 'subscriptions.subscription.' . $cindex . '.section';
            $confirmSectionKey = 'subscriptions.subscription.' . $cindex . '.confirm_section';

            $aiModuleMsgRule[$gardeKey] = 'Row ' . ($cindex + 1) . '- ' . 'Garde';
            $aiModuleMsgRule[$sectionKey] = 'Row ' . ($cindex + 1) . '- ' . 'Section';
            $aiModuleMsgRule[$confirmSectionKey] = 'Row ' . ($cindex + 1) . '- ' . 'Confirm Section';

            if (isset($competence_data['section'], $competence_data['confirm_section']) && $competence_data['section'] !== $competence_data['confirm_section']) {
                $aiModuleMsgRule[$confirmSectionKey] = 'Row ' . ($cindex + 1) . '- ' . 'Section and Confirm Section must have the same value.';
            }
        }
        $validator->setAttributeNames($aiModuleMsgRule);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
        if ($validator->passes()) {

            $schooldata = School::where('id', $user->school_id)->first();
            $username = $schooldata->school_username . '_';
            $usernameCount = DB::table('classrooms_subscriptions')->select([
               'id',
               'sr_number',
            ])->where('sr_number', 'like', "%{$username}%")->count();
             $singlenumber  = str_pad((int) $usernameCount + 1, 2,  '0', STR_PAD_LEFT);
             $username = ($username . $singlenumber);
            $data = $request->all();
            $classroomsubscription = new ClassroomSubscription();
            $classroomsubscription->school_admin_id = $user->id;
            $classroomsubscription->school_id = $user->school_id;
            $classroomsubscription->sr_number = $username;
            $classroomsubscription->package_row_count = $request->package_row_count;
            $classroomsubscription->subscription_status = 0;
            $classroomsubscription->notify_subscription_status = 1;
            $classroomsubscription->classrooms_subscription = json_encode([
                'subscription' => $request->subscriptions['subscription']
                    ?? []
            ]);
            $classroomsubscription->save();
            return response()->json([
                'status' => true,
                'message' => "subscription created successfully",
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function updateSubscription(Request $request)
    {
        $user = Auth::user();
        $updatesubscription = ClassroomSubscription::where('id', $request->subscription_id)->first();
        $validator = Validator::make($request->all(), [
            'subscriptions' => 'required|array',
            'subscriptions.subscription.*.teacher_name' => 'nullable|string',
            'subscriptions.subscription.*.garde' => 'required|string',
            'subscriptions.subscription.*.section' => 'required|string',
            'subscriptions.subscription.*.confirm_section' => 'required|same:subscriptions.subscription.*.section',
        ]);
        $aiModuleMsgRule = [];
        foreach ($request->subscriptions['subscription'] as $cindex => $competence_data) {
            $gardeKey = 'subscriptions.subscription.' . $cindex . '.garde';
            $sectionKey = 'subscriptions.subscription.' . $cindex . '.section';
            $confirmSectionKey = 'subscriptions.subscription.' . $cindex . '.confirm_section';

            $aiModuleMsgRule[$gardeKey] = 'Row ' . ($cindex + 1) . '- ' . 'Garde';
            $aiModuleMsgRule[$sectionKey] = 'Row ' . ($cindex + 1) . '- ' . 'Section';
            $aiModuleMsgRule[$confirmSectionKey] = 'Row ' . ($cindex + 1) . '- ' . 'Confirm Section';

            if (isset($competence_data['section'], $competence_data['confirm_section']) && $competence_data['section'] !== $competence_data['confirm_section']) {
                $aiModuleMsgRule[$confirmSectionKey] = 'Row ' . ($cindex + 1) . '- ' . 'Section and Confirm Section must have the same value.';
            }
        }
        $validator->setAttributeNames($aiModuleMsgRule);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
        if ($validator->passes()) {
            $data = $request->all();

             $updatesubscription->package_row_count = $request->package_row_count;
             $updatesubscription->notify_subscription_status = 1;

            $updatesubscription->classrooms_subscription = json_encode([
                'subscription' => $request->subscriptions['subscription']
                    ?? []
            ]);
            $updatesubscription->save();
            return response()->json([
                'status' => true,
                'message' => "subscription created successfully",
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }



    public function classroomCreate(Request $request)
    {
        $user = Auth::user();
        // dd($user->school_id);
        $updatesubscription = ClassroomSubscription::where('id', $request->subscription_id)->first();
        $validator = Validator::make($request->all(), [
            'subscriptions' => 'required|array',
            'subscriptions.subscription.*.teacher_name' => 'nullable|string',
            'subscriptions.subscription.*.garde' => 'required|string',
            'subscriptions.subscription.*.section' => 'required|string',
            'subscriptions.subscription.*.confirm_section' => 'required|same:subscriptions.subscription.*.section',
        ]);
        $aiModuleMsgRule = [];

        foreach ($request->subscriptions['subscription'] as $cindex => $competence_data) {
            $gardeKey = 'subscriptions.subscription.' . $cindex . '.garde';
            $sectionKey = 'subscriptions.subscription.' . $cindex . '.section';
            $confirmSectionKey = 'subscriptions.subscription.' . $cindex . '.confirm_section';

            $aiModuleMsgRule[$gardeKey] = 'Row ' . ($cindex + 1) . '- ' . 'Garde';
            $aiModuleMsgRule[$sectionKey] = 'Row ' . ($cindex + 1) . '- ' . 'Section';
            $aiModuleMsgRule[$confirmSectionKey] = 'Row ' . ($cindex + 1) . '- ' . 'Confirm Section';

            if (isset($competence_data['section'], $competence_data['confirm_section']) && $competence_data['section'] !== $competence_data['confirm_section']) {
                $aiModuleMsgRule[$confirmSectionKey] = 'Row ' . ($cindex + 1) . '- ' . 'Section and Confirm Section must have the same value.';
            }
        }


        $validator->setAttributeNames($aiModuleMsgRule);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
        if ($validator->passes()) {
            $data = $request->all();
            $passWord = Str::random(5);
            foreach ($data['subscriptions']['subscription'] as $userData) {
                $check_school_user = School::with(['teacher' => function ($query) {
                    $query->where('usertype', '=', 'teacher')->where('status', 1)->where(['is_deleted' => 0]);
                }])->where(['is_deleted' => 0, 'id' => $updatesubscription->school_id])->orderBy('id')->first();
                $total_teacher = ($check_school_user) ? $check_school_user->teacher->count() : 0;

                if ($check_school_user->licence > $total_teacher) {
                    $username = $check_school_user->school_username . '_' . $userData['garde'] . substr($userData['section'],  0, 1);
                    $lastcharacter = '\_' . $userData['garde'] . substr($userData['section'],  0, 1);
                    $usernameCount = DB::table('users')->select([
                        'id',
                        'username',
                    ])->where('usertype', 'teacher')->where('username', 'like', "%{$lastcharacter}%")->count();
                    $singlenumber  = str_pad((int) $usernameCount + 1, 2,  '0', STR_PAD_LEFT);
                    $username = ($username . $singlenumber);

                    $userData['name'] = $userData['teacher_name'];
                    $userData['username'] = $username;
                    $userData['usertype'] = 'teacher';
                    $userData['status'] = 1;
                    $userData['school_id'] = $updatesubscription->school_id;
                    $userData['password'] = Hash::make($passWord);
                    $userData['view_pass'] = $passWord;
                    User::create($userData);
                    $updatesubscription = ClassroomSubscription::where('id', $request->subscription_id)->first();
                    $updatesubscription->update(['subscription_status' => 1]);
                } else {
                    return response()->json([
                        'status' => false,
                        'licence_limit' => "Maximum licences limit reached.",
                    ], 201);
                }
            }
            return response()->json([
                'status' => true,
                'message' => "Classroom created successfully",
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }


    public function getSubscription(Request $request)
    {
        $user = Auth::user();
        $school_id = $user->school_id;
        $school_all_data = School::get();
        if ($user->usertype == 'admin') {
            $mainQuery = DB::table('classrooms_subscriptions')->where('school_id', $school_id)
                ->whereNull('deleted_at')
                ->select([
                    'classrooms_subscriptions.id',
                    'classrooms_subscriptions.classrooms_subscription',
                    'classrooms_subscriptions.classrooms_subscription as subscription_data',
                    'classrooms_subscriptions.school_id',
                    'classrooms_subscriptions.sr_number',
                    'classrooms_subscriptions.school_admin_id',
                    'classrooms_subscriptions.subscription_status',
                    'classrooms_subscriptions.created_at',
                    'classrooms_subscriptions.updated_at',
                    'school.school_name',
                    'school.licence',
                    'school.is_demo',
                ])->leftJoin('school', 'school.id', 'classrooms_subscriptions.school_id')
                ->orderBy("classrooms_subscriptions.id", "DESC");
            $subscriptions = $mainQuery->get()->map(function ($row) {
                $row->subscription_data = isset($row->classrooms_subscription) ? json_decode($row->classrooms_subscription) : '';
                return $row;
            })->all();
        } else {

            $mainQuery = DB::table('classrooms_subscriptions')
                ->whereNull('deleted_at')
                ->select([
                    'classrooms_subscriptions.id',
                    'classrooms_subscriptions.classrooms_subscription',
                    'classrooms_subscriptions.classrooms_subscription as subscription_data',
                    'classrooms_subscriptions.school_id',
                    'classrooms_subscriptions.sr_number',
                    'classrooms_subscriptions.school_admin_id',
                    'classrooms_subscriptions.subscription_status',
                    'classrooms_subscriptions.created_at',
                    'classrooms_subscriptions.updated_at',
                    'school.school_name',
                    'school.licence',
                    'school.is_demo',
                ])->leftJoin('school', 'school.id', 'classrooms_subscriptions.school_id')
                ->orderBy("classrooms_subscriptions.id", "DESC");
            $subscriptions = $mainQuery->get()->map(function ($row) {
                $row->subscription_data = isset($row->classrooms_subscription) ? json_decode($row->classrooms_subscription) : '';
                return $row;
            })->all();
        }
        return view('users.subscription-list', compact('subscriptions', 'user', 'school_all_data'));
    }

    public function getSubscriptionRequest(Request $request)
    {
        $user = Auth::user();
        $school_id = $user->school_id;
        $school_all_data = School::get();
        if ($user->usertype == 'admin') {
            $mainQuery = DB::table('classrooms_subscriptions')->where('school_id', $school_id)
                ->whereNull('deleted_at')
                ->select([
                    'classrooms_subscriptions.id',
                    'classrooms_subscriptions.classrooms_subscription',
                    'classrooms_subscriptions.classrooms_subscription as subscription_data',
                    'classrooms_subscriptions.school_id',
                    'classrooms_subscriptions.notify_subscription_status',
                    'classrooms_subscriptions.change_classroom_status',
                    'classrooms_subscriptions.change_payment_status',
                    'classrooms_subscriptions.share_login_credential',
                    'classrooms_subscriptions.package_row_count',
                    'classrooms_subscriptions.sr_number',
                    'classrooms_subscriptions.school_admin_id',
                    'classrooms_subscriptions.subscription_status',
                    'classrooms_subscriptions.created_at',
                    'classrooms_subscriptions.updated_at',
                    'school.school_name',
                    'school.licence',
                    'school.is_demo',
                ])->leftJoin('school', 'school.id', 'classrooms_subscriptions.school_id')
                ->orderBy("classrooms_subscriptions.id", "DESC");
            $subscriptions = $mainQuery->get()->map(function ($row) {
                $row->subscription_data = isset($row->classrooms_subscription) ? json_decode($row->classrooms_subscription) : '';
                return $row;
            })->all();
        } else {
            $search = $request->search;
//dd($search);
            $mainQuery = DB::table('classrooms_subscriptions')
                ->whereNull('deleted_at')
                ->select([
                    'classrooms_subscriptions.id',
                    'classrooms_subscriptions.classrooms_subscription',
                    'classrooms_subscriptions.classrooms_subscription as subscription_data',
                    'classrooms_subscriptions.school_id',
                    'classrooms_subscriptions.notify_subscription_status',
                    'classrooms_subscriptions.change_classroom_status',
                    'classrooms_subscriptions.change_payment_status',
                    'classrooms_subscriptions.share_login_credential',
                    'classrooms_subscriptions.package_row_count',
                    'classrooms_subscriptions.sr_number',
                    'classrooms_subscriptions.school_admin_id',
                    'classrooms_subscriptions.subscription_status',
                    'classrooms_subscriptions.created_at',
                    'classrooms_subscriptions.updated_at',
                    'school.school_name',
                    'school.licence',
                    'school.is_demo',
                ])->leftJoin('school', 'school.id', 'classrooms_subscriptions.school_id')
                ->orderBy("classrooms_subscriptions.id", "DESC");

                 if (isset($search) && !empty($search)) {
                    $mainQuery->where(function ($query) use ($search) {
                        $query->where('classrooms_subscriptions.sr_number', $search)
                              ->orWhere('school.school_name', 'LIKE', "%$search%");
                    });
                }


                 $subscriptions = $mainQuery->get()->map(function ($row) {
                $row->subscription_data = isset($row->classrooms_subscription) ? json_decode($row->classrooms_subscription) : '';
                return $row;
            })->all();
        }
        return view('users.subscription-request', compact('subscriptions', 'user', 'school_all_data'));
    }

    public function getSubscriptionManage(Request $request)
    {
        $user = Auth::user();
        $subscription_id = $request->input('subscription_id');
        $mainQuery = DB::table('classrooms_subscriptions')->where('classrooms_subscriptions.id', $subscription_id)->whereNull('deleted_at')
            ->select([
                'classrooms_subscriptions.id',
                'classrooms_subscriptions.classrooms_subscription',
                'classrooms_subscriptions.classrooms_subscription as subscription_data',
                'classrooms_subscriptions.school_id',
                'classrooms_subscriptions.notify_subscription_status',
                'classrooms_subscriptions.change_classroom_status',
                'classrooms_subscriptions.change_payment_status',
                'classrooms_subscriptions.share_login_credential',
                'classrooms_subscriptions.school_admin_id',
                'classrooms_subscriptions.subscription_status',
                'classrooms_subscriptions.created_at',
                'classrooms_subscriptions.updated_at',
                'school.school_name',
            ])->where('classrooms_subscriptions.id', $subscription_id)
            ->leftJoin('school', 'school.id', 'classrooms_subscriptions.school_id');
        $subscriptions = $mainQuery->get()->map(function ($row) {
            $row->subscription_data = isset($row->classrooms_subscription) ? json_decode($row->classrooms_subscription) : '';
            return $row;
        })->first();
        $subscription = ClassroomSubscription::find($subscription_id);
        if ($subscription) {
            $subscription->update([
                'notify_subscription_status' => 0,
            ]);
        }
        return view('users.subscription-manage-list', compact('subscriptions', 'user'));
    }

    public function subscriptionrequestNotify(Request $request)
    {
        $mainQuery = DB::table('classrooms_subscriptions')->where('notify_subscription_status', 1);
        $noty_count  = $mainQuery->count();
        return response()->json([
            'status' => true,
            'subscription_request_count' => $noty_count,
            'message' => "Subscription request count successfully",
        ]);
    }


    public function schoolLicenceUpdate(Request $request)
    {
        $school_id = $request->input('school_id');
        $validator = Validator::make($request->all(), [
            'school_licence' => 'required|string',
        ]);

        if ($validator->passes()) {
           // DB::table('school')->where('id', $school_id)->update(['licence' => $request->school_licence]);

            $schooldata = School::where('id', $school_id)->first();
            $total_licence = $schooldata->licence + $request->school_licence;
            $schooldata->update(['licence' => $total_licence]);

            return response()->json([
                'status' => true,
                'message' => "licence Update successfully",
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }
    public function subscriptionEdit(Request $request)
    {

        $subscription_id = $request->input('subscription_id');

        $mainQuery = DB::table('classrooms_subscriptions')
            ->whereNull('deleted_at')
            ->select([
                'classrooms_subscriptions.id',
                'classrooms_subscriptions.classrooms_subscription',
                'classrooms_subscriptions.classrooms_subscription as subscription_data',
                'classrooms_subscriptions.school_id',
                'classrooms_subscriptions.school_admin_id',
                'classrooms_subscriptions.subscription_status',
                'classrooms_subscriptions.created_at',
                'classrooms_subscriptions.updated_at',
                'school.school_name',
            ])->where('classrooms_subscriptions.id', $subscription_id)
            ->leftJoin('school', 'school.id', 'classrooms_subscriptions.school_id');
        $subscriptions = $mainQuery->get()->map(function ($row) {
            $row->subscription_data = isset($row->classrooms_subscription) ? json_decode($row->classrooms_subscription) : '';
            return $row;
        })->first();
        //dd($subscriptions);
        return view('users.subscription-edit', compact('subscriptions'));
    }

    public function classroomEdit(Request $request)
    {

        $subscription_id = $request->input('subscription_id');

        $mainQuery = DB::table('classrooms_subscriptions')
            ->whereNull('deleted_at')
            ->select([
                'classrooms_subscriptions.id',
                'classrooms_subscriptions.classrooms_subscription',
                'classrooms_subscriptions.classrooms_subscription as subscription_data',
                'classrooms_subscriptions.school_id',
                'classrooms_subscriptions.school_admin_id',
                'classrooms_subscriptions.subscription_status',
                'classrooms_subscriptions.created_at',
                'classrooms_subscriptions.updated_at',
                'school.school_name',
            ])->where('classrooms_subscriptions.id', $subscription_id)
            ->leftJoin('school', 'school.id', 'classrooms_subscriptions.school_id');
        $subscriptions = $mainQuery->get()->map(function ($row) {
            $row->subscription_data = isset($row->classrooms_subscription) ? json_decode($row->classrooms_subscription) : '';
            return $row;
        })->first();
        return view('users.classroom-create', compact('subscriptions'));
    }

    public function VerifyschooladminPassword(Request $request)
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

    public function destroySchoolAdmin(Request $request)
    {
        $school_admin_id = $request->input('school_admin_id');
        $feedback_data = User::where(['id' => $school_admin_id])->first();
        if (!empty($feedback_data)) {
            $feedback_data->delete();
            echo "removed";
        }
    }


    public function subscriptionRequestPayment(Request $request){


        $subscription_id = $request->input('subscription_id');

        $subscription_data = ClassroomSubscription::where('id', $subscription_id)->first();

        $school_id = $subscription_data->school_id;

         $schoodata = School::where('id', $school_id)->first();
         $mainQuery = DB::table('schools_payments')->where('school_id', $school_id)->where('classrooms_subscriptions_id', $subscription_id)
            ->whereNull('deleted_at')
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
                   'schools_payments.json_response',
                   'schools_payments.payment_failed_info',

                   'schools_payments.description',
                   'schools_payments.school_id',
                   'schools_payments.email',
                   'schools_payments.payment_url',
                   'schools_payments.phone_number',
                   'school.school_name As school_name_text'
               ]);
               $my_email = "support@valuezschool.com";
               $my_phone_number = "8826708801";
               $mainQuery->leftJoin('school', 'school.id', 'schools_payments.school_id');
            $paymentlists = $mainQuery->get()->map(function ($row) {
                $paymentDueDate = isset($row->payment_due) ? $row->payment_due : '';
                $currentDate = Carbon::now();
                $row->payment_due = $currentDate->diffInDays($paymentDueDate);
                return $row;
             })->all();
            $school_email_template = DB::table('email_templates')->where('module', 'srequest')->where('activated', 1)->first();
            return view('users.subscription-request-payment', compact('school_id', 'subscription_data', 'my_phone_number', 'school_email_template', 'my_email', 'schoodata','paymentlists'));
    }


    public function SubscriptionPaymentPay(Request $request){
        $validator = Validator::make($request->all(),[
          'payment_amount' => 'required',
          'number_of_subscription' => 'required',
          'description' => 'required',
          'school_id' => 'required|exists:school,id',
          'emails' => 'required|email',
          'phone_numbers' => 'required',
          'school_name_billing' => 'required',
        ]);
        if($validator->passes()){
           $schoolpayment = new SchoolPayment;
           $schoolpayment->orderid = $this->generateUniqueBarcode(new SchoolPayment, 1);
           $schoolpayment->payment_amount = $request->payment_amount;
           $schoolpayment->description = $request->description;
           $schoolpayment->number_of_subscription = $request->number_of_subscription;
           $schoolpayment->school_id = $request->school_id;
           $schoolpayment->classrooms_subscriptions_id = $request->subscription_id;
           $schoolpayment->school_name_billing = $request->school_name_billing;
           $schoolpayment->email = isset($request->emails) ? $request->emails : '';
           $schoolpayment->phone_number = $request->phone_numbers;
           $schoolpayment->link_expiry_at = Carbon::now()->addYear()->format('Y-m-d H:i:s');
           $schoolpayment->link_created_at = now()->format('Y-m-d H:i:s');
           $schoolpayment->save();
           $school_payment_id = base64_encode($schoolpayment->id);
           $baseUrl = url('/');
           $uniqueURL = $baseUrl . '/' . 'api/subscription-request?payment=' . $school_payment_id;
           $schoolpayment->update([
             'payment_url' => $uniqueURL
           ]);
           return response()->json([
             'status' => true,
             'message' => "Subscription Request Payment successfully",
         ]);
         }else{
             return response()->json([
                 'status' => false,
                 'errors' => $validator->errors(),
                 ]);
             }
     }



public function srPaymentfailed(Request $request)
{
    $payment_id = $request->schoolPaymentId;
    $mainQuery = DB::table('schools_payments')->where('schools_payments.id', $payment_id)
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
            'schools_payments.json_response',
            'schools_payments.payment_failed_info',
            'schools_payments.description',
            'schools_payments.school_id',
            'schools_payments.email',
            'schools_payments.payment_url',
            'schools_payments.phone_number',
            'school.school_name As school_name_text',
            'school.school_logo'
        ]);
            $mainQuery->leftJoin('school', 'school.id', 'schools_payments.school_id');
            $schools_payments_data = $mainQuery->get()->map(function ($row) {
            $row->payment_failed_info = isset($row->payment_failed_info) ? json_decode($row->payment_failed_info) : '';
            return $row;
         })->first();
         return view('users.payment_failed_info', compact('schools_payments_data'));
}


public function changeSubscriptionRequestStatus(Request $request)
    {

        $subscriptionRequestId = $request->sr_id;
        $status_type = $request->status_type;
        $sr_status_value = $request->sr_status_value;

         if($status_type == 'change_classroom_status'){
            $status = ($sr_status_value == 1) ? 0 : 1;
            ClassroomSubscription::where('id', $subscriptionRequestId)->update(['change_classroom_status' => $status]);
            echo ($status == 1) ? 'Done' : 'Pending';
         }
         if($status_type == 'change_payment_status'){
            $status = ($sr_status_value == 1) ? 0 : 1;
            ClassroomSubscription::where('id', $subscriptionRequestId)->update(['change_payment_status' => $status]);
            echo ($status == 1) ? 'Done' : 'Pending';
         }
         if($status_type == 'share_login_credential'){
            $status = ($sr_status_value == 1) ? 0 : 1;
            ClassroomSubscription::where('id', $subscriptionRequestId)->update(['share_login_credential' => $status]);
            echo ($status == 1) ? 'Done' : 'Pending';
         }

    }


    /*sr payment link email sent code */
    public function SentEmailPaymentLinkSR(Request $request)
    {
        $payments_id = $request->input('payments_id');
        $paymentdata = SchoolPayment::where('id', $payments_id)->first();
        if(empty($paymentdata)){
            return response()->json([
                'status' => false,
                'message' => "Payment Not Found",
            ]);
        }
        $validator = Validator::make($request->all(),[
            'email' => 'required|json',
            'email_template' => 'required',
            'email_template_id' => 'required|integer|exists:email_templates,id',

          ]);
          if($validator->passes()){

            $emailTemplate = EmailTemplate::where('id', (int)$request->email_template_id)->update([
                'school_body' => isset($request->email_template) ? $request->email_template : null,
            ]);
            $paymentdata->update([
                 'email_sent' => $request->email,
                 'email_sent_at' => now()->format('Y-m-d H:i:s'),
            ]);
            $this->mailSentSRSchool($paymentdata->id, "srequest");
            return response()->json([
                'status' => true,
                'message' => "Email Sent successfully",
            ]);
            }else{
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors(),
                    ]);
                }
    }

    public function SRPaymentRemove(Request $request){
        $payment_id = $request->input('payment_id');
        $school_id = $request->input('school_id');
        $registerstudent = SchoolPayment::where('id', $payment_id)->first();
        if(!empty($registerstudent)){
            $registerstudent->delete();
            echo "removed";
        }
    }

    public function UploadInvoiceSR(Request $request)
    {
        $payment_id = $request->input('payment_id');
        if ($request->hasFile('invoice')) {
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
        }else {
            $imageName = "";
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

    }

    public function VerifySRPasswordPackage(Request $request)
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

    public function SRdestroyPackage(Request $request)
    {
       $sr_id = $request->input('sr_id');
       $sr_data = ClassroomSubscription::where('id', $sr_id)->first();
        if(!empty($sr_data)){
            $sr_data->delete();
            echo "removed";
        }
    }

    public function schoolAdminForgot(Request $request)
    {
       return view('auth.school-admin-forgot');
    }

    public function schoolAdminForgotPasswor(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'school_name' => 'required',
            'email'  => 'required|exists:users,email',
         ]);
         if($validator->passes()){
            $user_data = User::where('email', $request->email)->where('is_deleted', 0)->first();
            $SchoolAdminForgot = new ForgotPasswordSchoolAdmin;
            $SchoolAdminForgot->name = $request->name;
            $SchoolAdminForgot->school_name = $request->school_name;
            $SchoolAdminForgot->email = $request->email;
            $SchoolAdminForgot->user_id = $user_data->id;
            $SchoolAdminForgot->forgot_noty_password = 1;
            $SchoolAdminForgot->status = 1;
            $SchoolAdminForgot->save();
          //  support@valuezschool.com
            $cc = 'support@valuezschool.com';
             if (!empty($request->email)) {
                $details = [
                    'view' => 'emails.school-admin-forgot',
                    'subject' => $request->school_name . ' Your Account Password Reset by admin - Valuez',
                    'title' => $request->name,
                    'email' => $request->email,
                   // 'pass' => $data['password']
                ];
                Mail::to($cc)->send(new \App\Mail\TestMail($details));
            }


            return response()->json([
                'status' => true,
                'message' => "School Admin successfully",
            ]);
         }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                ]);

            }
    }


    public function getAdminUser(Request $request)
    {
        $forgot_password_id = $request->input('forgot_password_id');
        $forgot_password_data = DB::table('forgot_password_school_admins')->where('id', $forgot_password_id)->first();
        $user_data = DB::table('users')->where('id', $forgot_password_data->user_id)->first();
        return view('school.school-admin-forgot-edit', compact('user_data','forgot_password_data'));
    }



    public function UpdateSchoolAdmin(Request $request)
{

    $data = $request->all();
    $updateuser = [
        'email' => $data['email'],
    ];
    $validate = [
        'password' => 'nullable|min:8',
        'confirm_password' => 'nullable|same:password',
        'email'  => 'required|exists:users,email',
    ];

    if (!empty($data['password'])) {
        if ($data['password'] != $data['confirm_password']) {
            return back()->withErrors(['confirm_password' => 'Password and confirm_password do not match or password is empty.']);
        }
        $validate['password'] = ['required', Password::min(6)];
        $updateuser['password'] = Hash::make($data['password']);
        $updateuser['view_pass'] = $data['password'];
    }

    User::where('id', $data['user_id'])->update($updateuser);
    $redirect_url = route('school-admin-forgot-password-list');
    return redirect($redirect_url)->with('success', 'User Updated successfully');
}

public function VerifySAFPPassword(Request $request)
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

    public function destroyFPSchoolAdmin(Request $request)
    {
        $forgot_password_id = $request->input('forgot_password_id');
            $user = Auth::user();
            if (($user) && $user->usertype == "superadmin") {
                $forgotdelete = ForgotPasswordSchoolAdmin::where('id', $forgot_password_id)->first();
                $forgotdelete->delete();
                echo "removed";
            } else {
                echo "Something went wrong.";
            }
    }
    public function VerifySupport(Request $request)
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
    public function destroySupport(Request $request)
    {
        $support_id = $request->input('support_id');
            $user = Auth::user();
            if (($user) && $user->usertype == "superadmin") {
                $forgotdelete = Support::where('id', $support_id)->first();
                $forgotdelete->delete();
                echo "removed";
            } else {
                echo "Something went wrong.";
            }
    }

    public function supportchangestatus(Request $request)
    {
        $statusId = $request->sts_id;
        $status = ($request->status == 1) ? 0 : 1;
        DB::table('supports')->where('id', $statusId)->update(['status' => $status]);
        echo ($status == 1) ? 'Closed' : 'Open';
    }

}
