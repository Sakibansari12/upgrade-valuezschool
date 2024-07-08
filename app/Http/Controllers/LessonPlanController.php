<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\{Course, LessonPlan, Program};
use DataTables;

class LessonPlanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $lessonplan = LessonPlan::query()->orderBy('id','desc')->join('master_class', 'master_class.id', '=', 'lesson_plan.class_id')
                ->join('master_course', 'master_course.id', '=', 'lesson_plan.course_id')
                ->select('lesson_plan.*', 'master_class.class_name', 'master_course.course_name');
            return Datatables::of($lessonplan)
                ->addIndexColumn()
                ->editColumn('lesson_image', function ($row) {
                    $ImagePath = $row->lesson_image ? $row->lesson_image : 'no_image.png';
                    return '<img src="' . url('uploads/lessonplan/' . $ImagePath) . '" width="32" height="32" class="bg-light my-n1"
                    alt="' . $row->title . '">';
                })
                ->addColumn('class_name', function ($row) {
                    $class_list_name = Program::whereIn('id', explode(",", $row->class_id))->get(['class_name'])->toArray();
                    return array_column($class_list_name, 'class_name');
                })

                ->editColumn('view_assessment', function ($row) {
                    $file = $row->view_assessment;
                    $view_assessment = ''; // Initialize $view_assessment variable

                    if (!empty($file)) {
                      //  $view_assessment .= '<a href="' . url("uploads/lessonplan/{$file}") . '" class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5" download>Download</a>';
                      $view_assessment .= '<a href="javascript:void(0);"    class="change_status text-white badge bg-success">Uploded</a>';
                      return $view_assessment;
                    } else {
                        return '';
                    }
                })
                ->editColumn('status', function ($row) {
                    $status_btn = '<a href="javascript:void(0);"  id="status_' . $row->id . '" data-id="' . $row->id . '" data-status="' . $row->status . '" class="change_status text-white badge bg-' . ($row->status == 1 ? 'success' : 'danger') . '">' . ($row->status == 1 ? 'Active' : 'Inactive') . '</a>';
                    return $status_btn;
                })
                ->editColumn('is_demo', function ($row) {
                    $demo_btn = '<a href="javascript:void(0);"  id="status_demo_' . $row->id . '" data-id="' . $row->id . '" data-status="' . $row->is_demo . '" class="change_demo_status text-white badge bg-' . ($row->is_demo == 1 ? 'success' : 'danger') . '">' . ($row->is_demo == 1 ? 'Demo' : 'Paid') . '</a>';
                    return $demo_btn;
                })
                ->addColumn('action', function ($row) {
                    $edit_btn = '<a href="' . route('lesson.plan.edit', ['lessonplan' => $row->id]) . '"
                    class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Edit</a>';
                    $user = Auth::user();
                    $remove_btn = ($user->usertype == "superadmin") ? ' <a href="javascript:void(0);" data-id='.$row->id.'
                    class="waves-effect waves-light btn btn-sm btn-outline btn-danger remove_school_data mb-5">Delete</a>' : '';
                    $action_btn = $edit_btn . $remove_btn;
                    return $action_btn;
                })->filter(function ($query) {
                    if (request()->has('class_id')) {
                        $class_id = request('class_id');
                        $query->whereRaw('FIND_IN_SET("' . $class_id . '", lesson_plan.class_id)');
                    }
                },true)
                ->rawColumns(['action', 'is_demo', 'view_assessment', 'lesson_image', 'status'])
                ->make(true);
        }
        $class_list = Program::where('status', 1)->get();
        $course_list = Course::where('status', 1)->get();
        return view('lessonplan.lessonplan', compact('class_list', 'course_list'));
    }

    public function addlessonplan()
    {
        $program_list = DB::table('master_class')->where('status', 1)->get();
        $course_list = DB::table('master_course')->where('status', 1)->where('ai_status', 0)->get();
        return view('lessonplan.lessonplan-add', compact('program_list', 'course_list'));
    }

    public function editlessonplan(Request $request)
    {
        $lessonId = $request->input('lessonplan');
        $lessonplan = DB::table('lesson_plan')->where('id', $lessonId)->first();
        $program_list = DB::table('master_class')->where('status', 1)->get();
        $course_list = DB::table('master_course')->where('status', 1)->get();

        return view('lessonplan.lessonplan-edit', compact('program_list', 'course_list', 'lessonplan'));
    }

    public function store(Request $request)
    {
        $valid_rule = [
            'title' => 'required',
            'video_url' => ['required','regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i'],
            'class_id' => 'required',
            'course_id' => 'required',
            'image' => 'required',
            'upload_file' => 'nullable|mimes:pdf',
            // 'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];

        if(!empty($request->video_info_url)){
            $valid_rule['video_info_url'] = ['regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i'];
        }
        $request->validate($valid_rule);
        if ($image = $request->file('image')) {
            $destinationPath = 'uploads/lessonplan/';
            $originalname = $image->hashName();
            $imageName = "plan_" . date('Ymd') . '_' . $originalname;
            $image->move($destinationPath, $imageName);

            // REMOVE OLD IMAGE
            if ($request->old_image) {
                if (file_exists($destinationPath . $request->old_image)) {
                    unlink($destinationPath . $request->old_image);
                }
            }
        }
        if ($upload_file = $request->file('upload_file')) {
            $destinationPath = 'uploads/lessonplan/';
            $originalname = $upload_file->hashName();
            $upload_fileName = "plan_" . date('Ymd') . '_' . $originalname;
            $upload_file->move($destinationPath, $upload_fileName);

            // REMOVE OLD FILE
            if ($request->old_upload_file) {
                if (file_exists($destinationPath . $request->old_upload_file)) {
                    unlink($destinationPath . $request->old_upload_file);
                }
            }
        }else{
            $upload_fileName = '';
        }

        $courseData = [
            'title' => $request->title,
            'video_url' => $request->video_url,
            'myra_video_url' => $request->myra_video_url,
            'video_info_url' => $request->video_info_url,
            'lesson_no' => 0,
            'lesson_desc' => $request->lesson_desc,
            'conversation' => $request->conversation,
            'class_id' => implode(",", $request->class_id),
            'course_id' => $request->course_id,
            'status' => $request->status,
            'lesson_image' => $imageName,
            'view_assessment' => $upload_fileName,
        ];
        LessonPlan::create($courseData);
        return redirect(route('lesson.plan.list'))->with(['message' => 'Lesson Plan added successfully!', 'status' => 'success']);
    }

    public function edit(Request $request)
    {
        $valid_rule = [
            'title' => 'required',
            'video_url' => ['required','regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i'],
            'class_id' => 'required',
            'course_id' => 'required',
        ];
        if(!empty($request->video_info_url)){
            $valid_rule['video_info_url'] = ['regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i'];
        }
        $request->validate($valid_rule);
       
      
        if ($image = $request->file('image')) {
            $destinationPath = 'uploads/lessonplan/';
            $originalname = $image->hashName();
            $imageName = "plan_" . date('Ymd') . '_' . $originalname;
            $image->move($destinationPath, $imageName);
            $image_path = $destinationPath . $request->old_image;
            @unlink($image_path);

            // REMOVE OLD IMAGE
            if ($request->old_image) {
                if (file_exists($destinationPath . $request->old_image)) {
                    unlink($destinationPath . $request->old_image);
                }
            }


        } else {
            $imageName = $request->old_image;
        }
        if ($upload_file = $request->file('upload_file')) {
            $destinationPath = 'uploads/lessonplan/';
            $originalname = $upload_file->hashName();
            $upload_file_Name = "plan_" . date('Ymd') . '_' . $originalname;
            $upload_file->move($destinationPath, $upload_file_Name);
            $upload_file_path = $destinationPath . $request->old_upload_file;
            @unlink($upload_file_path);
            // REMOVE OLD FILE
            if ($request->old_upload_file) {
                if (file_exists($destinationPath . $request->old_upload_file)) {
                    unlink($destinationPath . $request->old_upload_file);
                }
            }
            
        } else {
            if($request->checkbox == 'checkbox_remove'){
                $upload_file_Name ='';
            }else{
                $upload_file_Name = $request->old_upload_file;
            }

            
        }

        $courseData = [
            'title' => $request->title,
            'video_url' => $request->video_url,
            'myra_video_url' => $request->myra_video_url,
            'video_info_url' => $request->video_info_url,
            'lesson_no' => 0,
            'lesson_desc' => $request->lesson_desc,
            'conversation' => $request->conversation,
            'class_id' => implode(",", $request->class_id),
            'course_id' => $request->course_id,
            'status' => $request->status,
            'lesson_image' => $imageName,
            'view_assessment' => $upload_file_Name,
        ];
        // print_r($courseData); die;
        LessonPlan::where('id', $request->id)->update($courseData);
        return redirect(route('lesson.plan.list'))->with(['message' => 'Lesson Plan updated successfully!', 'status' => 'success']);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $lessonId = $request->input('lessonplan');
        $user = Auth::user();
        if (($user) && $user->usertype == "superadmin") {
            DB::table('lesson_plan')->where('id', $lessonId)->delete();
            echo "removed";
        }else {
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



    public function sortLessonPlan(Request $request)
    {
        if ($request->ajax()) {
            $type = $request->type;
            if ($type == 'grade') {
                $courseId = $request->courseid;
                $class_list = LessonPlan::where(['course_id' => $courseId])->selectRaw('GROUP_CONCAT(class_id) as grade')->groupBy('course_id')->first();
                $grades = array_unique(explode(",", $class_list->grade));
                if (!empty($grades)) {
                    $program_list = Program::where('status', 1)->whereIn('id', $grades)->get(['id', 'class_name']);
                }
                return $program_list;
            }

            if ($type == 'lessonplan') {
                $grade = $request->grade;
                $courseid = $request->courseid;
                $lessonplan = LessonPlan::where(['course_id' => $courseid])->whereRaw('FIND_IN_SET("' . $grade . '",class_id)')->get(['id', 'title']);
                $lesson_html = '';
                if ($lessonplan->first()) {
                    $lessonplan_sort_list = DB::table('plan_sorting')->where(['course_id' => $courseid, 'class_id' => $grade])->get(['lesson_id', 'position_order'])->toArray();
                    $postionId = 0;
                    $sortedList = [];
                    foreach ($lessonplan as $lessondata) {
                        if (!empty($lessonplan_sort_list)) {
                            $sortKey = array_search($lessondata->id, array_column($lessonplan_sort_list, 'lesson_id'));
                            $postionId = $lessonplan_sort_list[$sortKey]->position_order;
                        }
                        $sortedList[] = ['id' => $lessondata->id, 'title' => $lessondata->title, 'position' => $postionId];
                    }
                    // print_r($sortedList);
                    $orderedItems = collect($sortedList)->sortBy('position');
                    foreach ($orderedItems as $pos => $ldata) {
                        $lesson_html .= '<a class="media media-single" href="#" id="' . $ldata['id'] . '">
                                        <span class="title text-mute">' . $ldata['title'] . '</span>
                                        <span class="badge badge-pill badge-primary">' . $pos . '</span>
                                        </a>';
                    }
                }
                return $lesson_html;
            }
            exit;
        }

        $course_list = DB::table('master_course')->where('status', 1)->get();
        return view('lessonplan.lessonplan-sorting', compact('course_list'));
    }

    public function updateSortingNumber(Request $request)
    {
        $position = $request->position;
        $classId = $request->grade;
        $courseId = $request->courseid;
        $i = 1;
        if (!empty($position)) {
            foreach ($position as $k => $v) {
                DB::table('plan_sorting')->updateOrInsert(
                    [
                        'course_id' => (int)$courseId,
                        'class_id' => (int)$classId,
                        'lesson_id' => (int)$v
                    ],
                    ['position_order' => $i]
                );
                $i++;
            }
        }
    }

    public function change_demo_status(Request $request)
    {
        $lessonId = $request->lessonid;
        $status = ($request->status == 1) ? 0 : 1;
        LessonPlan::where('id', $lessonId)->update(['is_demo' => $status]);
        echo ($status == 1) ? 'Demo' : 'Paid';
    }

    public function change_status(Request $request)
    {
        $statusId = $request->sts_id;
        $status = ($request->status == 1) ? 0 : 1;
        LessonPlan::where('id', $statusId)->update(['status' => $status]);
        echo ($status == 1) ? 'Active' : 'Inactive';
    }

}
