<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Jenssegers\Agent\Facades\Agent;
use App\Models\{User, LogsModel};
use DataTables;

class UserController extends Controller
{
    public  function index(Request $request)
    {
        if ($request->ajax()) {
            $adminuserlist = User::query()->orderBy('id', 'desc')->where(['users.is_deleted' => 0])->whereIn('usertype', ['contentadmin']);
            return Datatables::of($adminuserlist)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return date('d/m/Y', strtotime($row->created_at));
                })
                ->editColumn('status', function ($row) {
                    $span_btn = '<span class="badge bg-' . ($row->status == 1 ? 'success' : 'danger') . '">' . ($row->status == 1 ? 'Active' : 'Inactive') . '</span>';
                    return $span_btn;
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('users.admin.edit', ['userid' => $row->id]) . '"
                            class="btn btn-sm btn-outline btn-info mb-5">Edit</a>
                    <a href="javascript:void(0);" data-id="' . $row->id . '"
                    class="edit btn btn-outline btn-danger remove_school_data btn-sm mb-5"
                    data-id="' . $row->id . '"
                    >Delete</a>';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('users.masteruser.manage-user');
    }

    public function AddAdminUser(Request $request)
    {
        return view('users.masteruser.master-user-add');
    }

    public function editAdminUser(Request $request)
    {
        $userId = $request->input('userid');

        $adminuser = User::where(['id' => $userId])->first();
        return view('users.masteruser.master-user-edit', compact('adminuser'));
    }

    public function AddUpdateAdminUser(Request $request)
    {
        $data = $request->all();
        $updateuser = ['name' => $data['name'], 'email' => $data['email'], 'usertype' => $data['usertype'], 'status' => $data['status'], 'status' => $data['status']];
        $validate = ['name' => 'required', 'email' => 'required|email|unique:users,email,' . $data['id']];
        if (!empty($data['password'])) {
            $validate['password'] = ['required', Password::min(6)];
            $updateuser['password'] = Hash::make($data['password']);
            $updateuser['view_pass'] = $data['password'];
        }
        $request->validate($validate);
        if ($data['id'] > 0) {
            User::where('id', $data['id'])->update($updateuser);
        } else {
            $updateuser['created_at'] = date('Y-m-d H:i:s');
            User::insert($updateuser);
        }

        return redirect(route('users.admin.list'))->with('success', 'User Updated successfully');
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

    public function destroy(Request $request)
    {
        $userid = $request->input('userid');
        $user = Auth::user();
        if (($user) && $user->usertype == "superadmin") {
            DB::table('users')->where('id', $userid)->where('usertype', 'contentadmin')->update(['is_deleted' => 1]);
            echo "removed";
        } else {
            echo "Something went wrong.";
        }
    }

    public function logoutUserOtherDevices(Request $request)
    {
        $userId = $request->userid;
        $userLogs = \App\Models\LogsModel::where(['userid' => $userId, 'action' => 'login'])->get()->pluck('session_id');
        // dd($userLogs);
        if (!empty($userLogs)) {
            foreach ($userLogs as $uLog) {
                if (file_exists(storage_path('framework/sessions/' . $uLog))) {
                    // echo "The file $uLog exists.<br/>";
                    @unlink(storage_path('framework/sessions/' . $uLog));
                }
            }
            $this->autologoutLogs($request);
            echo "removed";
        } else {
            echo "All session removed";
        }
    }
    public function logoutStudentOtherDevices(Request $request)
    {
        $userId = $request->userid;
       $userLogs = LogsModel::where(['userid' => $userId, 'action' => 'login'])
       ->whereNotNull('current_student_session_id')
       ->get();
       
            // Update all matching records to set 'current_student_session_id' to an empty string
            LogsModel::where(['userid' => $userId, 'action' => 'login'])
                ->whereNotNull('current_student_session_id')
                ->update(['current_student_session_id' => null]);
                $this->autologoutLogs($request);
                echo "removed";       
   
        /* if (!empty($userLogs)) {
            foreach ($userLogs as $uLog) {
                if (file_exists(storage_path('framework/sessions/' . $uLog))) {
                    @unlink(storage_path('framework/sessions/' . $uLog));
                }
            }
            $this->autologoutLogs($request);
            echo "removed";
        } else {
            echo "All session removed";
        } */
    }
    public function autologoutLogs($request)
    {
        $user = Auth::user();
        $browser = Agent::browser();
        $version = Agent::version($browser);
        $session_id = session()->getId();

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
            'info' => 'User logout by ' . $user->name,
            'session' => $session_id,            
        ];
        LogsModel::create(['userid' => $request->userid, 'session_id' => $session_id, 'ip_address' => $request->ip(), 'action' => 'logout', 'logs_info' => json_encode($log_arr)]);
    }
}
