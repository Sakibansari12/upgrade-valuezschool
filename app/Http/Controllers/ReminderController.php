<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NotificationModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DataTables;
use Illuminate\Support\Carbon;
use DB;
use Illuminate\Support\Facades\Validator;
use App\Models\{LogsModel, TermsPrivacy,Reminder,Invoice};

class ReminderController extends Controller
{
    

    /* terms privacys */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Reminder::query()->orderBy("id", "DESC");
                return Datatables::of($data)
                ->addColumn('index', function ($row) {
                    static $index = 0;
                    return ++$index;
                })
                ->editColumn('title', function ($row) {
                    return $row->title ?? '';
                })
                ->editColumn('description', function ($row) {
                    $strippedDescription = strip_tags($row->description); // Remove HTML tags
                    $formattedDescription = strlen($strippedDescription) > 10 ? substr($strippedDescription, 0, 10) . '...' : $strippedDescription;
                    return $formattedDescription;
                })
                
                ->editColumn('created_at', function ($row) {
                    $formattedDate = date('d/m/Y', strtotime($row->created_at));
                    $formattedTime = date('H:i', strtotime($row->created_at));
                    return "$formattedDate | $formattedTime";
                })

                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-id=' . $row->id . ' class="edit btn btn-danger btn-sm remove_school_data">Delete</a>';
                    return $btn;
                })


                ->addColumn('action', function ($row) {
                    $editLink = '<a href="' . route('reminder.edit', ['id' => $row->id]) . '"
                                    class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Edit</a>';
                    $deleteLink = '<a href="javascript:void(0);" data-id="' . $row->id . '"
                                    class="waves-effect waves-light remove_school_data btn btn-sm btn-outline btn-danger mb-5">Delete</a>';

                    //$deleteLink = '<a href="javascript:void(0)" data-id=' . $row->id . ' class="edit btn btn-danger btn-sm remove_school_data">Delete</a>';

                    return $editLink . ' ' . $deleteLink;
                })



                ->rawColumns(['action'])
                ->make(true); 
        }
        return view('reminder.index');
    }

    public function indexLists(Request $request)
    {
        $datas = DB::table('reminders')
        ->orderBy('id', 'desc')
        ->get();
        return view('reminder.student-index', compact('datas'));
    }

    /* public function indexLists(Request $request)
    {
        if ($request->ajax()) {
            $data = Reminder::query()->orderBy("id", "DESC");
                return Datatables::of($data)
                ->addColumn('index', function ($row) {
                    static $index = 0;
                    return ++$index;
                })
                ->editColumn('title', function ($row) {
                    return $row->title ?? '';
                })
                ->editColumn('description', function ($row) {
                    $strippedDescription = strip_tags($row->description); // Remove HTML tags
                    $formattedDescription = strlen($strippedDescription) > 10 ? substr($strippedDescription, 0, 10) . '...' : $strippedDescription;
                    return $formattedDescription;
                })
                
                ->editColumn('created_at', function ($row) {
                    $formattedDate = date('d/m/Y', strtotime($row->created_at));
                    $formattedTime = date('H:i', strtotime($row->created_at));
                    return "$formattedDate | $formattedTime";
                })

                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-id=' . $row->id . ' class="edit btn btn-danger btn-sm remove_school_data">Delete</a>';
                    return $btn;
                })


                ->addColumn('action', function ($row) {
                    $reminderDetail = '<a class="btn btn-small btn-info btn-sm" title="Reminder Detail" href="' . route("reminder.detail", ["reminder_id" => $row->id]) . '">View</a>';

                    return $reminderDetail;
                })

                ->rawColumns(['action'])
                ->make(true); 
        }
        return view('reminder.student-index');
    } */

    public function reminderDetail(Request $request)
    {
      //  $data = Reminder::select('*')->where('id',$request->reminder_id)->first();
        $data = Reminder::where('id', $request->id)->where('student_reminder_noty', $request->student_reminder_noty)->first();
        $data->update([
          'student_reminder_noty' => 0,
        ]);

        return view('reminder.detail', compact('data'));
    }


    public function addReminder(Request $request)
    {
        return view('reminder.create');
    }
    public function EditReminder(Request $request)
    {
        $data = Reminder::select('*')->where('id',$request->id)->first();
        return view('reminder.edit', compact('data'));
    }


    public function reminderCreate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            //'type' => 'required',
        ]);
        if($validator->passes()){
            $Remindercreate = new Reminder();
            $Remindercreate->title = $request->title;
            $Remindercreate->description = isset($request->description) ? $request->description : '';
            $Remindercreate->status = 1;
           // $Remindercreate->school_reminder_noty = 'student';
           // $Remindercreate->teacher_reminder_noty = 1;
            $Remindercreate->student_reminder_noty = 'student';
            $Remindercreate->save();
        return response()->json([
            'status' => true,
            'message' => "Reminder create successfully!",
        ], 201);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
             ]);
        
         }
    }

    public function updateReminder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);
        
        if($validator->passes()){
            $Reminder_update = Reminder::findOrFail($request->reminder_id);
            $Reminder_update->title = $request->title;
            $Reminder_update->description = isset($request->description) ? $request->description : '';
            $Reminder_update->save();

        return response()->json([
            'status' => true,
            'message' => "Reminder update successfully!",
        ], 201);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
             ]);
        
         }
    }

    public function VerifyReminder(Request $request)
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

    public function destroyReminder(Request $request)
    {
        $reminder_id = $request->reminder_id;
        $user = Auth::user();
        if (($user) && $user->usertype == "superadmin") {
            Reminder::where('id', $reminder_id)->delete();
            echo "removed";
        } else {
            echo "Something went wrong.";
        }
    }

    public function getTermsConditions(Request $request)
    {
        $TermsConditions = TermsPrivacy::where('type', 'VALUEZ TERMS & CONDITIONS')->get();
        return view('terms_privacy.terms_conditions', compact('TermsConditions'));
    }
    public function getPrivacyPolicy(Request $request)
    {
        $PrivacyPolicy = TermsPrivacy::where('type', 'PRIVACY POLICY')->get();
        return view('terms_privacy.privacy_policy', compact('PrivacyPolicy'));
    }

    public function viewReminderNotifyIcon(Request $request)
    {
     $user = Auth::user();
     $mainQuery = DB::table('reminders');
    /*  if($user->usertype == 'admin')
     {
        $mainQuery->where('school_admin_noty', $user->usertype)->whereIn('create_type', ['Classroom', 'Both']);
        
     } */
    /*  if($user->usertype == 'teacher')
     {
        $mainQuery->where('teacher_noty', $user->usertype)->whereIn('create_type', ['Classroom', 'Both']);;
        
     } */
     if($user->usertype == 'student')
     {
       $mainQuery->where('student_reminder_noty', $user->usertype);
     } 
     $reminder_count  = $mainQuery->count();
    // dd($reminder_count);
         return response()->json([
            'status' => true,
            'ReminderNotifacation' => $reminder_count,
            'message' => "Reminder successfully",
        ]);  
    }


}
