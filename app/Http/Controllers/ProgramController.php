<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use App\Models\{Package, LessonPlan, Program};

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Program::query()->orderBy("id", "DESC");
            return Datatables::of($data)
                ->addColumn('index', function ($row) {
                    static $index = 0;
                    return ++$index;
                })
                ->editColumn('class_image', function ($row) {
                    $ImagePath = $row->class_image ? $row->class_image : 'no_image.png';
                    $imageUrl = url('uploads/program/' . $ImagePath);
                    return '<img src="' . $imageUrl . '" width="32" height="32" class="bg-light my-n1" alt="' . $row->class_name . '">';
                })
                ->editColumn('class_name', function ($row) {
                    return $row->class_name ?? '';
                })
                ->addColumn('status', function ($row) {
                    $statusClass = $row->status == 1 ? 'success' : 'danger';
                    $statusText = $row->status == 1 ? 'Active' : 'Inactive';
                    $btn = '<a href="javascript:void(0)" data-gradeid=' . $row->id . ' data-bs-target="#" 
                        class="change_status text-white badge bg-' . $statusClass . '" data-status="' . $row->status . '">
                        ' . $statusText . '</a>';
                    return $btn;
                })
                ->editColumn('sort_num', function ($row) {
                    return $row->sort_num ?? '';
                })
                ->addColumn('action', function ($row) {
                    $editBtn = '<a href="' . route('program.edit', ['program' => $row->id]) . '"
                    data-id=' . $row->id . '
                    class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Edit</a>';
                    $removeBtn = '<a href="javascript:void(0)" data-gradeid=' . $row->id . ' data-bs-target="#"
                    class="waves-effect waves-light remove_school_data btn btn-sm btn-outline btn-danger mb-5">Delete</a>';
                    return $editBtn . ' ' . $removeBtn;
                })
                ->rawColumns(['action', 'sort_num', 'class_name', 'status', 'class_image'])
                ->toJson();
        }
      
        
        /* $class_list = DB::table('master_class')->orderBy('id','desc')->get();
        return view('program.program', compact('class_list')); */
        return view('program.program');
    }

    public function addprogram()
    {
        return view('program.program-add');
    }

    public function editprogram(Request $request)
    {
        $classId = $request->input('program');
        $class = DB::table('master_class')->where('id', $classId)->first();
        return view('program.program-edit', compact('class'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'required',
        ]);


        if ($image = $request->file('image')) {
            $destinationPath = 'uploads/program/';
            $originalname = $image->hashName();
            $imageName = "class_" . date('Ymd') . '_' . $originalname;
            $image->move($destinationPath, $imageName);
        }

        $classData = ['class_name' => $request->title, 'status' => $request->status, 'class_image' => $imageName];
        DB::table('master_class')->insert($classData);
        return redirect(route('program.list'))->with(['message' => 'Program added successfully!', 'status' => 'success']);
    }

    public function edit(Request $request)
    {
        $request->validate([
            'title' => 'required',
        ]);
        if ($image = $request->file('image')) {
            $destinationPath = 'uploads/program/';
            $originalname = $image->hashName();
            $imageName = "class_" . date('Ymd') . '_' . $originalname;
            $image->move($destinationPath, $imageName);

            $image_path = $destinationPath . $request->old_image;
            @unlink($image_path);
        } else {
            $imageName = $request->old_image;
        }
        $classData = ['class_name' => $request->title, 'status' => $request->status, 'class_image' => $imageName, 'sort_num' => $request->sort_num];
        DB::table('master_class')->where('id', $request->id)->update($classData);
        return redirect(route('program.list'))->with(['message' => 'Program updated successfully!', 'status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $classId = $request->input('program');
       // dd($classId);
        $class_data = LessonPlan::join("master_course as mc", "mc.id", "=", "lesson_plan.course_id")
            ->whereRaw('FIND_IN_SET("' . $classId . '", class_id)')->get()->toArray();
      
        if (empty($class_data)) {
            DB::table('master_class')->where('id', $classId)->delete();
            echo "removed";
        } else {
            echo "Instructional Module linked with this class.";
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




    public function TeacherClasslist()
    {
        $user = Auth::user();
        $grades = Package::where(['school_id' => $user->school_id])->get(['grade', 'course'])->toArray();
        $class_list = [];
        if (!empty($grades)) {
            $grades_list = array_column($grades, 'grade');
            $class_list = DB::table('master_class')->whereIn('id', $grades_list)->where('status', 1)->orderBy('id')->get();
        }
        return view('webpages.classlist', compact('class_list'));
    }
    public function StudentGradelist()
    {

        $user = Auth::user();
        $grades = Package::where(['school_id' => $user->school_id])->get(['student_grade_id', 'student_course'])->toArray();
        //dd($grades);
        $student_class_list = [];
        if (!empty($grades)) {
            $grades_list = array_column($grades, 'student_grade_id');
            $student_class_list = DB::table('master_class')->whereIn('id', $grades_list)->where('status', 1)->orderBy('id')->get();
        }
       // dd($student_class_list);
        return view('student-login.grade-list', compact('student_class_list'));
    }
    public function change_status(Request $request)
    {
        $statusId = $request->sts_id;
        $status = ($request->status == 1) ? 0 : 1;
        DB::table('master_class')->where('id', $statusId)->update(['status' => $status]);
        echo ($status == 1) ? 'Active' : 'Inactive';
    }
}
