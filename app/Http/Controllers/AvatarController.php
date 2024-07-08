<?php

namespace App\Http\Controllers;

use App\Models\{User, School, Quiz, QuizCategory,Program,QuizTitle,HistoryQuiz,Avatar};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use DataTables;
use Illuminate\Support\Facades\DB;

class AvatarController extends Controller
{
    public function indexAvatar(Request $request)
    {

        if ($request->ajax()) {
            $mainQuery = Avatar::whereNull('deleted_at');
            $data = $mainQuery->orderBy('id', 'desc');
            return Datatables::of($data)
            ->addColumn('index', function ($row) {
                static $index = 0;
                return ++$index;
            })
                //->addIndexColumn()
                ->editColumn('avatar_title_image', function ($row) {
                    $ImagePath = $row->avatar_title_image ? $row->avatar_title_image : 'no_image.png';
                    return '<img src="' . url('uploads/avatar/' . $ImagePath) . '" width="32" height="32" class="bg-light my-n1"
                    alt="' . $row->title . '">';
                })
                ->editColumn('avatar_title', function ($row) {
                    return $row->avatar_title ?? '';
                })
                ->editColumn('status', function ($row) {                  
                    $status_btn = '<a href="javascript:void(0);"  id="status_' . $row->id . '" data-id="' . $row->id . '" data-status="' . $row->status . '" class="text-white badge bg-' . ($row->status == 1 ? 'success' : 'danger') . '">' . ($row->status == 1 ? 'Active' : 'Inactive') . '</a>';
                    return $status_btn;
                })
                
                 ->addColumn('action', function ($row) {
                   /*  $edit_btn = '<a href="' . route('quiz-category-edit', ['quiz_category_id' => $row->id]) . '"
                    class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Edit</a>'; */
                    $user = Auth::user();
                    $remove_btn = ($user->usertype == "superadmin") ? ' <a href="javascript:void(0);" data-id='.$row->id.' 
                    class="waves-effect waves-light btn btn-sm btn-outline btn-danger remove_school_data mb-5">Delete</a>' : '';
                    $action_btn =  $remove_btn ;
                    return $action_btn;
                }) 

                ->rawColumns(['action', 'avatar_title', 'avatar_title_image', 'status'])
                ->make(true);
        }
        return view('avatar.avatar-list');
    }

    public function addAvatar(Request $request)
    {
        return view('avatar.avatar-add');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'title' => 'required|string',
            'image' => 'required|image|mimes:png,jpg,webp|max:2048',
        ]);
        if($validator->passes()){
            if ($image = $request->file('image')) {
                $destinationPath = 'uploads/avatar/';
                $originalname = $image->hashName();
                $imageName = "avatar_" . date('Ymd') . '_' . $originalname;
                $image->move($destinationPath, $imageName);
            }
            $quizcreate = new Avatar();
            $quizcreate->avatar_title = $request->title;
            $quizcreate->avatar_title_image = $imageName;
            $quizcreate->status = 1;
            $quizcreate->save();
            return response()->json([
                'status' => true,
                'message' => "Avatar created successfully",
            ], 201);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
             ]);
        
         }
    }

    public function AvatarVerify(Request $request)
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

    public function destroyAvatar(Request $request)
    {
        
        $quiz_id = $request->input('quiz_id');
        $sr_data = Avatar::where('id', $quiz_id)->first();
            $sr_data->delete();
            echo "removed";
    }

}
