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
use App\Models\{LogsModel, TermsPrivacy};

class Notification extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = NotificationModel::query()->orderBy("id", "DESC");
                return Datatables::of($data)
                ->addColumn('index', function ($row) {
                    static $index = 0;
                    return ++$index;
                })
                /* ->editColumn('description', function ($row) {
                    $formattedDescription = strlen($row->description) > 10 ? substr($row->description, 0, 10) . '...' : $row->description;
                    return $formattedDescription;
                }) */
                ->editColumn('create_type', function ($row) {
                    return $row->create_type ?? '';
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
                ->rawColumns(['action'])
                ->make(true); 
        }
        return view('notification.notify');
    }

    public function addnewNotification(Request $request)
    {
        return view('notification.notify-add');
    }

    public function addUpdateNotify(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description_txt' => 'required',
        ]);
        $notifyData = ['title' => $request->title, 'status' => $request->status, 
        'school_admin_noty' => 'admin', 
        'create_type' => 'superadmin', 
        'teacher_noty' => 'teacher', 
        'student_noty' => 'student', 
        'create_type' => $request->whatnew_type, 
        'description' => $request->description_txt];
        NotificationModel::create($notifyData);
        return redirect(route('notify.list'))->with(['message' => 'What\'s New added successfully!', 'status' => 'success']);
    }

    public function destroy(Request $request)
    {
        $notifyId = $request->notifyId;
        $user = Auth::user();
        if (($user) && $user->usertype == "superadmin") {
            NotificationModel::where('id', $notifyId)->delete();
            echo "removed";
        } else {
            echo "Something went wrong.";
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

    public function viewNotify(Request $request)
    {
        /* if ($request->ajax()) {
            $data = NotificationModel::query();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('description', function ($row) {
                    $created_at = date('d/m/Y', strtotime($row->created_at));
                    $title = $row->title;
                    $text_desc = $row->description;
                    $view_notify = '<div class="media align-items-center">
						  <div class="media-body">
							<p class="fs-16"><a class="hover-primary" href="#">' . $title . '</a></p>
							  <span class="text-fade fs-12">' . $created_at . '</span>
						  </div>
						</div><div class="media pt-0"><p class="text-mute">' . $text_desc . '</p></div>';
                    return $view_notify;
                })
                ->rawColumns(['description'])
                ->make(true);
        } */
       // $datas = NotificationModel::get();
        $datas = DB::table('notification')->whereIn('create_type', ['Classroom', 'Both'])->orderBy('id','desc')->get();
        return view('notification.view_notify', compact('datas'));
    }
    public function viewteacherNotify(Request $request)
    {
        $datas = DB::table('notification')->whereIn('create_type', ['Classroom', 'Both'])->orderBy('id','desc')->get();
        return view('notification.teacher-view-notify-list', compact('datas'));
    }
    public function viewstudentNotify(Request $request)
    {
        $user = Auth::user();
        $datas = DB::table('notification')
            ->orderBy('id', 'desc')
            ->whereIn('create_type', ['Student', 'Both'])
            ->get();

        
        return view('notification.student-view-notify-list', compact('datas'));
    }

    public function detailNotification(Request $request)
    {
      if(!empty($request->school_admin_noty))
      {
        $notification_data = NotificationModel::where('id', $request->id)->where('school_admin_noty', $request->school_admin_noty)->first();
        $notification_data->update([
          'school_admin_noty' => 0,
        ]);
      }else{
        $notification_data = NotificationModel::where('id', $request->id)->first();
      }
      if(!empty($request->teacher_noty))
      {
        $notification_data = NotificationModel::where('id', $request->id)->where('teacher_noty', $request->teacher_noty)->first();
        $notification_data->update([
          'teacher_noty' => 0,
        ]);
      }else{
        $notification_data = NotificationModel::where('id', $request->id)->first();
      }
      if(!empty($request->student_noty))
      {
        $notification_data = NotificationModel::where('id', $request->id)->where('student_noty', $request->student_noty)->first();
        $notification_data->update([
          'student_noty' => 0,
        ]);
      }else{
        $notification_data = NotificationModel::where('id', $request->id)->first();
      }
      
      return view('notification.notify-detail',compact('notification_data'));
    }

   /*  public function viewNotifyList(Request $request)
    {
        $user = Auth::user();
        $user_notify = DB::table('notify_logs')->where(['uid' => $user->id])->first();
        $last_notify_id = ($user_notify) ? $user_notify->nid : 0;
        $notify_data = NotificationModel::latest()->where('id', '>', $last_notify_id)->take(5)->get();
        $nList = '';
        if ($request->type == 'list') {
            if (count($notify_data) > 0) {
                foreach ($notify_data as $k => $nd) {
                    $nList .= "<li id='nid-" . $k . "' data-id='" . $nd->id . "'><a href='" . route('notify.schoolview') . "'>" . $nd->title . "</a></li>";
                }
            } else {
                $nList .= "<li class='text-center' id='nid-0' data-id='0'><p>No Notification Found</p></li>";
            }
            echo ($nList);
        }

        if ($request->type == 'clear') {
            if ($request->nid > 0) {
                DB::table('notify_logs')->updateOrInsert(['uid' => $user->id], ['nid' => $request->nid, 'updated_at' => date('Y-m-d H:i:s')]);
            }
            echo "update notify status";
        }
    } */

    public function viewNotifyList(Request $request)
    {
        $user = Auth::user();
        $nList = '';
        if ($request->type == 'list') {
              if($request->user_type == 'student'){
                $notify_data = NotificationModel::where('student_noty', $request->user_type)->get();
              }
              if($request->user_type == 'teacher'){
                $notify_data = NotificationModel::where('teacher_noty', $request->user_type)->get();
              }
              if($request->user_type == 'admin'){
                $notify_data = NotificationModel::where('school_admin_noty', $request->user_type)->get();
              }
            if (count($notify_data) > 0) {
                foreach ($notify_data as $k => $nd) {
                    $nList .= "<li id='nid-" . $k . "' data-id='" . $nd->id . "'><a href='" . route('notify.schoolview') . "'>" . $nd->title . "</a></li>";
                }
            } else {
                $nList .= "<li class='text-center' id='nid-0' data-id='0'><p>No Notification Found</p></li>";
            }
            echo ($nList);
        }

        if ($request->type == 'clear') {
            if($request->user_type == 'student'){
               // $notify_data = NotificationModel::where('student_noty', $request->user_type)->get();
                $notify_data = NotificationModel::where('student_noty', $request->user_type)->update(['student_noty' => 0]);
                $nList .= "<li class='text-center' id='nid-0' data-id='0'><p>No Notification Found</p></li>";
                echo ($nList);
              }
              if($request->user_type == 'teacher'){
                $notify_data = NotificationModel::where('teacher_noty', $request->user_type)->update(['teacher_noty' => 0]);
                $nList .= "<li class='text-center' id='nid-0' data-id='0'><p>No Notification Found</p></li>";
                echo ($nList);
            }
              if($request->user_type == 'admin'){
                $notify_data = NotificationModel::where('school_admin_noty', $request->user_type)->update(['school_admin_noty' => 0]);
                $nList .= "<li class='text-center' id='nid-0' data-id='0'><p>No Notification Found</p></li>";
                echo ($nList);
            }
            
        }
    }

    public function viewNotifyIcon(Request $request)
    {
     $user = Auth::user();
     $mainQuery = DB::table('notification');
     if($user->usertype == 'admin')
     {
        $mainQuery->where('school_admin_noty', $user->usertype)->whereIn('create_type', ['Classroom', 'Both']);
        
     }
     if($user->usertype == 'teacher')
     {
        $mainQuery->where('teacher_noty', $user->usertype)->whereIn('create_type', ['Classroom', 'Both']);;
        
     }
     if($user->usertype == 'student')
     {
       $mainQuery->where('student_noty', $user->usertype)->whereIn('create_type', ['Student', 'Both']);;
     } 
     $noty_count  = $mainQuery->count();
         return response()->json([
            'status' => true,
            'notifacation' => $noty_count,
            'message' => "Notification successfully",
        ]);  
    }

    public function viewNotifyStudentSubcription(Request $request)
    {
     $user = Auth::user();
     
     $student_date = DB::table('students')->where('id',  $user->student_id)->first();
        if (!empty($student_date->student_noty_subcrition)) {
            $jsonString = $student_date->student_noty_subcrition;
            $datas = json_decode($jsonString, true);
            $show_student_subcrition  = $datas['show_data_header'];
        
         return response()->json([
            'status' => true,
            'show_student_subcrition' => $show_student_subcrition,
            'message' => "Student Notification successfully",
        ]); 
    } 
    }
    /* terms privacys */
    public function termsIndex(Request $request)
    {
        if ($request->ajax()) {
            $data = TermsPrivacy::query()->orderBy("id", "DESC");
                return Datatables::of($data)
                ->addColumn('index', function ($row) {
                    static $index = 0;
                    return ++$index;
                })
                ->editColumn('type', function ($row) {
                    return $row->type ?? '';
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
                    $editLink = '<a href="' . route('terms-privacy.edit', ['id' => $row->id]) . '"
                                    class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Edit</a>';
                    $deleteLink = '<a href="javascript:void(0);" data-id="' . $row->id . '"
                                    class="waves-effect waves-light remove_school_data btn btn-sm btn-outline btn-danger mb-5">Delete</a>';

                    //$deleteLink = '<a href="javascript:void(0)" data-id=' . $row->id . ' class="edit btn btn-danger btn-sm remove_school_data">Delete</a>';

                    return $editLink . ' ' . $deleteLink;
                })



                ->rawColumns(['action'])
                ->make(true); 
        }
        return view('terms_privacy.index');
    }
    public function addTermsPrivacy(Request $request)
    {
        return view('terms_privacy.create');
    }
    public function EditTermsPrivacy(Request $request)
    {
        //dd($request->id);
        $data = TermsPrivacy::select('*')->where('id',$request->id)->first();
        return view('terms_privacy.update', compact('data'));
    }


    public function createTermsPrivacy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'type' => 'required',
        ]);
        if($validator->passes()){
            $TermsPrivacyCreate = new TermsPrivacy();
            $TermsPrivacyCreate->type = $request->type;
            $TermsPrivacyCreate->title = $request->title;
            $TermsPrivacyCreate->description = isset($request->description) ? $request->description : '';
            $TermsPrivacyCreate->status = 1;
            $TermsPrivacyCreate->save();

        return response()->json([
            'status' => true,
            'message' => "Terms & Privacy create successfully!",
        ], 201);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
             ]);
        
         }
    }

    public function updateTermsPrivacy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            //'description' => 'required',
            'type' => 'required',
        ]);
        
        if($validator->passes()){
            $TermsPrivacy_update = TermsPrivacy::findOrFail($request->terms_privacy_id);
            $TermsPrivacy_update->type = $request->type;
            $TermsPrivacy_update->title = $request->title;
            $TermsPrivacy_update->description = isset($request->description) ? $request->description : '';
            $TermsPrivacy_update->save();

        return response()->json([
            'status' => true,
            'message' => "Terms & Privacy create successfully!",
        ], 201);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
             ]);
        
         }
    }

    public function VerifyTermsPrivacy(Request $request)
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

    public function destroyTermsPrivacy(Request $request)
    {
        $notifyId = $request->notifyId;
        $user = Auth::user();
        if (($user) && $user->usertype == "superadmin") {
            TermsPrivacy::where('id', $notifyId)->delete();
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
    

}
