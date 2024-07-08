<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use App\Models\{LessonPlan, Program, Course};

class CourseController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('master_course')->orderBy('id', 'DESC');
            return Datatables::of($data)
                ->addColumn('index', function ($row) {
                    static $index = 0;
                    return ++$index;
                })
                ->editColumn('course_image', function ($row) {
                    $ImagePath = $row->course_image ? $row->course_image : 'no_image.png';
                    $imageUrl = url('uploads/course/' . $ImagePath);
                    return '<img src="' . $imageUrl . '" width="32" height="32" class="bg-light my-n1" alt="' . $row->course_name . '">';
                })
                ->editColumn('course_name', function ($row) {
                    return $row->course_name ?? '';
                })
                ->addColumn('status', function ($row) {
                    $statusClass = $row->status == 1 ? 'success' : 'danger';
                    $statusText = $row->status == 1 ? 'Active' : 'Inactive';
                    $btn = '<a href="javascript:void(0)" data-gradeid=' . $row->id . ' data-bs-target="#"
                        class="change_status text-white badge bg-' . $statusClass . '" data-status="' . $row->status . '">
                        ' . $statusText . '</a>';
                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $editBtn = '<a href="' . route('course.edit', ['course' => $row->id]) . '"
                    data-id=' . $row->id . '
                    class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Edit</a>';
                    $removeBtn = '<a href="javascript:void(0)" data-id=' . $row->id . '
                    class="waves-effect waves-light remove_school_data btn btn-sm btn-outline btn-danger mb-5">Delete</a>';
                    return $editBtn . ' ' . $removeBtn;
                })
                ->rawColumns(['action', 'course_image', 'course_name', 'status'])
                ->toJson();
        }
        return view('course.course');
        // $course = DB::table('master_course')->orderBy('id', 'desc')->get();
        // return view('course.course',compact('course'));



    }

    public function addcourse()
    {
        return view('course.course-add');
    }

    public function editcourse(Request $request)
    {
        $courseId = $request->input('course');
        $course = DB::table('master_course')->where('id', $courseId)->first();
        return view('course.course-edit', compact('course'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required',
            'image' => 'required',
        ]);


        if ($image = $request->file('image')) {
            $destinationPath = 'uploads/course/';
            $originalname = $image->hashName();
            $imageName = "course_" . date('Ymd') . '_' . $originalname;
            $image->move($destinationPath, $imageName);

            // REMOVE OLD IMAGE
            if ($request->old_image) {
                if (file_exists($destinationPath . $request->old_image)) {
                    unlink($destinationPath . $request->old_image);
                }
            }
        }

        $courseData = ['course_name' => $request->title, 'status' => $request->status, 'course_image' => $imageName];
        DB::table('master_course')->insert($courseData);
        return redirect(route('course.list'))->with(['message' => 'Course added successfully!', 'status' => 'success']);
    }

    public function edit(Request $request)
    {

        $request->validate([
            'title' => 'required',
            // 'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($image = $request->file('image')) {
            $destinationPath = 'uploads/course/';
            $originalname = $image->hashName();
            $imageName = "course_" . date('Ymd') . '_' . $originalname;
            $image->move($destinationPath, $imageName);
            $image_path = $destinationPath . $request->old_image;
            @unlink($image_path);
        } else {
            $imageName = $request->old_image;
        }
        $courseData = ['course_name' => $request->title, 'status' => $request->status, 'course_image' => $imageName, 'ai_status' => $request->ai_status];
        DB::table('master_course')->where('id', $request->id)->update($courseData);
        return redirect(route('course.list'))->with(['message' => 'Course updated successfully!', 'status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $courseId = $request->input('course');
        $course = LessonPlan::where(['course_id' => $courseId])->get()->toArray();
        if (empty($course)) {
            DB::table('master_course')->where('id', $courseId)->delete();
            echo "removed";
        } else {
            echo "Instructional Module linked with this course.";
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




    public function change_status(Request $request)
    {
        $statusId = $request->sts_id;
        $status = ($request->status == 1) ? 0 : 1;
        DB::table('master_course')->where('id', $statusId)->update(['status' => $status]);
        echo ($status == 1) ? 'Active' : 'Inactive';
    }
}
