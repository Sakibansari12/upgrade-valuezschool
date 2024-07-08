<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Reports, Program, Course,LessonPlan, VideoPlayReport};
use Illuminate\Support\Facades\Auth;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
class ReportController extends Controller
{
   
    public function index(Request $request)
    {
        $school = $request->school;
        $class_list = Program::where('status', 1)->get();
        $Course_list = Course::where('status', 1)->get();
        $LessonPlan_list = LessonPlan::where('status', 1)->get();
        if ($request->ajax()) {
           $grade = $request->input('grade');
           $course = $request->input('course');
           $lessonplan = $request->input('lessonplan');
           $mainQuery = DB::table('reports')
            ->where(['reports.school' => $request->school])
            ->select([
                'reports.id',
                'reports.lesson_plan',
                'reports.classId',
                'reports.userid',
                'reports.school',
                'reports.created_at',
                'reports.updated_at',
                'master_class.class_name as grade_class_name',
                'lesson_plan.title as lesson_plan_title',
                'lesson_plan.course_id as lesson_plan_course_id',
                'master_course.course_name as master_course_course_name',
                'school.school_name as school_name',
                'users.name as user_name',
                'users.section as user_section',
            ])
            ->leftJoin('master_class', 'master_class.id', 'reports.classId')
            ->leftJoin('lesson_plan', 'lesson_plan.id', 'reports.lesson_plan')
            ->leftJoin('master_course', 'master_course.id', 'lesson_plan.course_id')
            ->leftJoin('school', 'school.id', 'reports.school')
            ->leftJoin('users', 'users.id', 'reports.userid');
            if (isset($grade) && (int)$grade > 0) {
                $mainQuery->where('reports.classId', (int)$grade);
            }
            if (isset($course) && (int)$course > 0) {
                $mainQuery->where('lesson_plan.course_id', (int)$course);
            }
            if (isset($lessonplan) && (int)$lessonplan > 0) {
                $mainQuery->where('lesson_plan.id', (int)$lessonplan);
            }
            $data = $mainQuery->orderBy('id', 'desc');
            return Datatables::of($data)
            ->addColumn('index', function ($row) {
                static $index = 0;
                return ++$index;
            })
            ->editColumn('grade', function ($row) {
                return $row->grade_class_name;
            })
            ->editColumn('user_section', function ($row) {
                return $row->user_section;
            })
            ->editColumn('course', function ($row) {
                return $row->master_course_course_name;
            })
            ->editColumn('lesson_plan_title', function ($row) {
                return $row->lesson_plan_title;
            })
            ->editColumn('user_name', function ($row) {
                return $row->user_name;
            })
            ->editColumn('created_at', function ($row) {
                $formattedDate = date('d/m/Y', strtotime($row->created_at));
                $formattedTime = date('H:i', strtotime($row->created_at));
                return "$formattedDate | $formattedTime";
            })
            ->rawColumns(['index','grade', 'course', 'lesson_plan_title', 'user_name',   'created_at' ])
            ->toJson();
        } 
        return view('reports.metrics', compact('school','class_list', 'Course_list','LessonPlan_list'));
    }
    public function viewTeacherSummary(Request $request)
    {
        /* $user = Auth::user();
        if (($user) && $user->usertype == "teacher") {
            if ($request->ajax()) {
                $data = Reports::query()->with(['lessonplan', 'userinfo'])->where(['school' => $user->school_id, 'userid' => $user->id]);
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->editColumn('course', function ($row) {
                        $course = Course::find($row->lessonplan->course_id);
                        return $course->course_name;
                    })
                    ->editColumn('grade', function ($row) {
                        $program = Program::find($row->classId);                        
                        return $program->class_name;
                    })
                    ->editColumn('created_at', function ($row) {
                        return date('d-m-Y h:i A', strtotime($row->created_at));
                    })
                    ->make(true);
            }
        }
        return view('reports.lessonhistory'); */

        /* $user = Auth::user();
        $class_list = Program::where('status', 1)->get();
        $course_list = Course::where('status', 1)->get();
        $grade = $request->input('grade');
        $course = $request->input('course');

        $mainQuery = DB::table('reports')
        ->where(['reports.school' => $user->school_id])
        ->where(['reports.userid' => $user->id])
        ->select([
            'reports.id',
            'reports.lesson_plan',
            'reports.classId as class_id',
            'reports.userid',
            'reports.school',
            'reports.complesion_status',
            'reports.created_at',
            'reports.updated_at',
            'master_class.class_name as grade_class_name',
            'lesson_plan.title as lesson_plan_title',
            'lesson_plan.course_id as lesson_plan_course_id',
            'master_course.course_name as master_course_course_name',
            'school.school_name as school_name',
            'users.name as user_name',
        ])
        ->leftJoin('master_class', 'master_class.id', 'reports.classId')
        ->leftJoin('lesson_plan', 'lesson_plan.id', 'reports.lesson_plan')
        ->leftJoin('master_course', 'master_course.id', 'lesson_plan.course_id')
        ->leftJoin('school', 'school.id', 'reports.school')
        ->leftJoin('users', 'users.id', 'reports.userid');
        if(isset($grade) && (int)$grade>0){
            $mainQuery->where('reports.classId', (int)$grade);
        }
        if(isset($course) && (int)$course>0){
            $mainQuery->where('lesson_plan.course_id', (int)$course);
        }
        $mainQuery->orderBy('id','desc');
        $reportsdata = $mainQuery->get()->map(function ($row) {
            $row->created_at = isset($row->created_at) ? Carbon::parse($row->created_at)->format('d-m-Y h:i A') : '';
            return $row;
        })->all();
        $studentreportsdata = [
            'student' => $reportsdata,
        ];

        $response = Http::get('https://learn.valuezschool.com/lesson_plan');
        $data_lesson_plan = $response->json();
        $desiredIds = [86, 99, 89, 117, 90, 238, 122, 84, 81, 94,
                    100, 79, 5, 82, 96, 97, 80, 3, 83, 8, 
                    91, 87, 88, 234, 4, 98, 231, 17, 230, 1, 
                    113, 236, 85, 12];
        $filtered_lesson_plan = array_map(function ($item) use ($desiredIds, $reportsdata) {
            $item['complesion_status'] = 0;
            if (in_array($item['id'], $desiredIds)) {
                foreach ($reportsdata as $report) {
                    if ($report->lesson_plan == $item['id']) {
                        $item['complesion_status'] = 1;
                        break; 
                    }
                }
            }
            return $item;
        }, $data_lesson_plan);

        $filtered_lesson_plan = array_filter($filtered_lesson_plan, function ($item) use ($desiredIds) {
            return in_array($item['id'], $desiredIds);
        });
        return view('reports.lessonhistory',compact('studentreportsdata', 'filtered_lesson_plan','class_list','course_list','course','grade')); */

        $user = Auth::user();
        //dd($user);
    $class_list = Program::where('status', 1)->get();
    $course_list = Course::where('status', 1)->get();
    $grade = $request->input('grade');
    $course = $request->input('course');

    $mainQuery = DB::table('reports')
        ->where(['reports.school' => $user->school_id])
        ->where(['reports.userid' => $user->id])
        ->select([
            'reports.id',
            'reports.lesson_plan',
            'reports.classId as class_id',
            'reports.userid',
            'reports.school',
            'reports.complesion_status',
            'reports.created_at',
            'reports.updated_at',
            'master_class.class_name as grade_class_name',
            'lesson_plan.title as lesson_plan_title',
            'lesson_plan.course_id as lesson_plan_course_id',
            'master_course.course_name as master_course_course_name',
            'school.school_name as school_name',
            'users.name as user_name',
        ])
        ->leftJoin('master_class', 'master_class.id', 'reports.classId')
        ->leftJoin('lesson_plan', 'lesson_plan.id', 'reports.lesson_plan')
        ->leftJoin('master_course', 'master_course.id', 'lesson_plan.course_id')
        ->leftJoin('school', 'school.id', 'reports.school')
        ->leftJoin('users', 'users.id', 'reports.userid');

    if (isset($grade) && (int) $grade > 0) {
        $mainQuery->where('reports.classId', (int) $grade);
    }

    if (isset($course) && (int) $course > 0) {
        $mainQuery->where('lesson_plan.course_id', (int) $course);
    }

    $mainQuery->orderBy('id', 'desc');
    $reportsdata = $mainQuery->get()->map(function ($row) {
        $row->created_at = isset($row->created_at) ? Carbon::parse($row->created_at)->format('d-m-Y h:i A') : '';
        return $row;
    })->all();

    $studentreportsdata = [
        'student' => $reportsdata,
    ];

    $response = Http::get('https://learn.valuezschool.com/lesson_plan');
    $data_lesson_plan = $response->json();
    $desiredIds = [86, 99, 89, 117, 90, 238, 122, 84, 81, 94,
                   100, 79, 5, 82, 96, 97,  3, 83, 8, 
                   91, 87,80, 88, 234, 4, 98, 231, 17, 12, 230, 1, 
                   113, 236, 85, ];

                   /* [86, 99, 89, 117, 90, 238, 122, 84, 81, 94,
                   100, 79, 5, 82, 96, 97, 80, 3, 83, 8, 
                   91, 87, 88, 234, 4, 98, 231, 17, 230, 1, 
                   113, 236, 85, 12]; */

    $filtered_lesson_plan = array_map(function ($item) use ($desiredIds, $reportsdata) {
        $item['complesion_status'] = 0;
        if (in_array($item['id'], $desiredIds)) {
            foreach ($reportsdata as $report) {
                if ($report->lesson_plan == $item['id']) {
                    $item['complesion_status'] = 1;
                    break; 
                }
            }
        }
        return $item;
    }, $data_lesson_plan);

    $filtered_lesson_plan = array_filter($filtered_lesson_plan, function ($item) use ($desiredIds) {
        return in_array($item['id'], $desiredIds);
    });

    usort($filtered_lesson_plan, function ($a, $b) use ($desiredIds) {
        $posA = array_search($a['id'], $desiredIds);
        $posB = array_search($b['id'], $desiredIds);
        return $posA - $posB;
    });
    $filtered_lesson_plan = collect($filtered_lesson_plan);


    $response_second = Http::get('https://learn.valuezschool.com/lesson_plan');
    $data_lesson_plan_second = $response_second->json();
    $desiredIds_second = [105, 107, 124, 115, 103, 106, 18,  119, 121, 120,  112,  111, 110, 
    123, 116, 104, 109, 235, 102, 108, 113, 237,  85 , 101, ];

    $filtered_lesson_plan_second = array_map(function ($item) use ($desiredIds_second, $reportsdata) {
        $item['complesion_status'] = 0;
        if (in_array($item['id'], $desiredIds_second)) {
            foreach ($reportsdata as $report) {
                if ($report->lesson_plan == $item['id']) {
                    $item['complesion_status'] = 1;
                    break; 
                }
            }
        }
        return $item;
    }, $data_lesson_plan_second);

    $filtered_lesson_plan_second = array_filter($filtered_lesson_plan_second, function ($item) use ($desiredIds_second) {
        return in_array($item['id'], $desiredIds_second);
    });

    usort($filtered_lesson_plan_second, function ($a, $b) use ($desiredIds_second) {
        $posA = array_search($a['id'], $desiredIds_second);
        $posB = array_search($b['id'], $desiredIds_second);
        return $posA - $posB;
    });
    $filtered_lesson_plan_second = collect($filtered_lesson_plan_second);
    
    return view('reports.lessonhistory', compact('studentreportsdata', 'user', 'filtered_lesson_plan_second', 'filtered_lesson_plan', 'class_list', 'course_list', 'course', 'grade'));
    }


    public function viewStudentSummary (Request $request)
    {
        /* $user = Auth::user();
        if (($user) && $user->usertype == "student") {
            if ($request->ajax()) {
                $data = Reports::query()->with(['lessonplan', 'userinfo'])->where(['school' => $user->school_id, 'userid' => $user->id]);
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->editColumn('course', function ($row) {
                        $course = Course::find($row->lessonplan->course_id);
                        return $course->course_name;
                    })
                    ->editColumn('grade', function ($row) {
                        $program = Program::find($row->classId);                        
                        return $program->class_name;
                    })
                    ->editColumn('created_at', function ($row) {
                        return date('d-m-Y h:i A', strtotime($row->created_at));
                    })
                    ->make(true);
            }
        } */
        $user = Auth::user();
        $class_list = Program::where('status', 1)->get();
        $course_list = Course::where('status', 1)->get();
        $grade = $request->input('grade');
        $course = $request->input('course');

        $mainQuery = DB::table('reports')
        ->where(['reports.school' => $user->school_id])
        ->where(['reports.userid' => $user->id])
        ->select([
            'reports.id',
            'reports.lesson_plan',
            'reports.classId as class_id',
            'reports.userid',
            'reports.school',
            'reports.created_at',
            'reports.updated_at',

            'master_class.class_name as grade_class_name',

            'lesson_plan.title as lesson_plan_title',
            'lesson_plan.course_id as lesson_plan_course_id',

            'master_course.course_name as master_course_course_name',

            'school.school_name as school_name',
            'users.name as user_name',
        ])
        ->leftJoin('master_class', 'master_class.id', 'reports.classId')
        ->leftJoin('lesson_plan', 'lesson_plan.id', 'reports.lesson_plan')
        ->leftJoin('master_course', 'master_course.id', 'lesson_plan.course_id')
        ->leftJoin('school', 'school.id', 'reports.school')
        ->leftJoin('users', 'users.id', 'reports.userid');
        if(isset($grade) && (int)$grade>0){
            $mainQuery->where('reports.classId', (int)$grade);
        }
        if(isset($course) && (int)$course>0){
            $mainQuery->where('lesson_plan.course_id', (int)$course);
        }
        $reportsdata = $mainQuery->get()->map(function ($row) {
            
            $row->created_at = isset($row->created_at) ? Carbon::parse($row->created_at)->format('d-m-Y h:i A') : '';
           // $row->log_created_time = isset($row->log_created_time) ? Carbon::parse($row->log_created_time)->format('H:i:s') : '';
            return $row;
        })->all();

        
        $studentreportsdata = [
            'student' => $reportsdata,
        ];
       
        $response = Http::get('https://learn.valuezschool.com/lesson_plan');
        $data_lesson_plan = $response->json();
        $desiredIds = [86, 99, 89, 117, 90, 238, 122, 84, 81, 94,
                       100, 79, 5, 82, 96, 97,  3, 83, 8, 
                       91, 87,80, 88, 234, 4, 98, 231, 17, 12, 230, 1, 
                       113, 236, 85, ];
    
                       /* [86, 99, 89, 117, 90, 238, 122, 84, 81, 94,
                       100, 79, 5, 82, 96, 97, 80, 3, 83, 8, 
                       91, 87, 88, 234, 4, 98, 231, 17, 230, 1, 
                       113, 236, 85, 12]; */
    
        $filtered_lesson_plan = array_map(function ($item) use ($desiredIds, $reportsdata) {
            $item['complesion_status'] = 0;
            if (in_array($item['id'], $desiredIds)) {
                foreach ($reportsdata as $report) {
                    if ($report->lesson_plan == $item['id']) {
                        $item['complesion_status'] = 1;
                        break; 
                    }
                }
            }
            return $item;
        }, $data_lesson_plan);
    
        $filtered_lesson_plan = array_filter($filtered_lesson_plan, function ($item) use ($desiredIds) {
            return in_array($item['id'], $desiredIds);
        });
    
        usort($filtered_lesson_plan, function ($a, $b) use ($desiredIds) {
            $posA = array_search($a['id'], $desiredIds);
            $posB = array_search($b['id'], $desiredIds);
            return $posA - $posB;
        });
        $filtered_lesson_plan = collect($filtered_lesson_plan);





    $response_second = Http::get('https://learn.valuezschool.com/lesson_plan');
    $data_lesson_plan_second = $response_second->json();
    $desiredIds_second = [105, 107, 124, 115, 103, 106, 18,  119, 121, 120,  112,  111, 110, 
    123, 116, 104, 109, 235, 102, 108, 113, 237,  85 , 101, ];

    $filtered_lesson_plan_second = array_map(function ($item) use ($desiredIds_second, $reportsdata) {
        $item['complesion_status'] = 0;
        if (in_array($item['id'], $desiredIds_second)) {
            foreach ($reportsdata as $report) {
                if ($report->lesson_plan == $item['id']) {
                    $item['complesion_status'] = 1;
                    break; 
                }
            }
        }
        return $item;
    }, $data_lesson_plan_second);

    $filtered_lesson_plan_second = array_filter($filtered_lesson_plan_second, function ($item) use ($desiredIds_second) {
        return in_array($item['id'], $desiredIds_second);
    });

    usort($filtered_lesson_plan_second, function ($a, $b) use ($desiredIds_second) {
        $posA = array_search($a['id'], $desiredIds_second);
        $posB = array_search($b['id'], $desiredIds_second);
        return $posA - $posB;
    });
    $filtered_lesson_plan_second = collect($filtered_lesson_plan_second);


        return view('student-login.student-view-history',compact('studentreportsdata', 'filtered_lesson_plan_second', 'filtered_lesson_plan', 'class_list','course_list','course','grade','user'));
    }


    public function viewTeacherGradeSummary(Request $request)
    {
        $user = Auth::user();
        if (($user) && $user->usertype == "teacher") {
            if ($request->ajax()) {
                $data = Reports::where(['school' => $user->school_id, 'userid' => $user->id, 'lesson_plan.course_id' => $request->courseId, 'classId' => $request->classId])->join('lesson_plan', 'lesson_plan.id', '=', 'reports.lesson_plan')->selectRaw('lesson_plan.title,DATE_FORMAT(reports.created_at, "%d-%m-%Y %h:%i %p") as created_report')->get()->toArray();
                return response()->json($data);
            }
        }
    }
    public function viewStudentGradeSummary(Request $request)
    {
        $user = Auth::user();
        if (($user) && $user->usertype == "student") {
            if ($request->ajax()) {
                $data = Reports::where(['school' => $user->school_id, 'userid' => $user->id, 'lesson_plan.course_id' => $request->courseId, 'classId' => $request->classId])->join('lesson_plan', 'lesson_plan.id', '=', 'reports.lesson_plan')->selectRaw('lesson_plan.title,DATE_FORMAT(reports.created_at, "%d-%m-%Y %h:%i %p") as created_report')->get()->toArray();
                return response()->json($data);
            }
        }
    }

    public function moduleAccessReport(Request $request)
    {

        $school = $request->school_id;
        $class_list = Program::where('status', 1)->get();
        $Course_list = Course::where('status', 1)->get();
        $LessonPlan_list = LessonPlan::where('status', 1)->get();
        if ($request->ajax()) {
           $grade = $request->input('grade');
           $course = $request->input('course');
           $lessonplan = $request->input('lessonplan');
           $school_id = $request->input('school_id');
           $mainQuery = DB::table('video_play_reports')
            ->where(['video_play_reports.video_play_status' => 1])
            ->where(['video_play_reports.school_id' => $school_id])
            ->select([
                'video_play_reports.id',
                'video_play_reports.lesson_plan',
                'video_play_reports.class_id',
                'video_play_reports.user_id',
                'video_play_reports.school_id',
                'video_play_reports.created_at',
                'video_play_reports.updated_at',
                'master_class.class_name as grade_class_name',
                'lesson_plan.title as lesson_plan_title',
                'lesson_plan.course_id as lesson_plan_course_id',
                'master_course.course_name as master_course_course_name',
                'school.school_name as school_name',
                'users.name as user_name',
                'users.section as user_section',
            ])
            ->leftJoin('master_class', 'master_class.id', 'video_play_reports.class_id')
            ->leftJoin('lesson_plan', 'lesson_plan.id', 'video_play_reports.lesson_plan')
            ->leftJoin('master_course', 'master_course.id', 'lesson_plan.course_id')
            ->leftJoin('school', 'school.id', 'video_play_reports.school_id')
            ->leftJoin('users', 'users.id', 'video_play_reports.user_id');
            if (isset($grade) && (int)$grade > 0) {
                $mainQuery->where('video_play_reports.class_id', (int)$grade);
            }
            if (isset($course) && (int)$course > 0) {
                $mainQuery->where('lesson_plan.course_id', (int)$course);
            }
            if (isset($lessonplan) && (int)$lessonplan > 0) {
                $mainQuery->where('lesson_plan.id', (int)$lessonplan);
            }
            $data = $mainQuery->orderBy('id', 'desc');
            return Datatables::of($data)
            ->addColumn('index', function ($row) {
                static $index = 0;
                return ++$index;
            })
            ->editColumn('grade', function ($row) {
                return $row->grade_class_name;
            })
            ->editColumn('user_section', function ($row) {
                return $row->user_section;
            })
            ->editColumn('course', function ($row) {
                return $row->master_course_course_name;
            })
            ->editColumn('lesson_plan_title', function ($row) {
                return $row->lesson_plan_title;
            })
            ->editColumn('user_name', function ($row) {
                return $row->user_name;
            })
            ->editColumn('created_at', function ($row) {
                $formattedDate = date('d/m/Y', strtotime($row->created_at));
                $formattedTime = date('H:i', strtotime($row->created_at));
                return "$formattedDate | $formattedTime";
            })
            ->rawColumns(['index','grade', 'course', 'lesson_plan_title', 'user_name',   'created_at' ])
            ->toJson();
        } 
      return view('module-access-report', compact('class_list', 'school', 'Course_list','LessonPlan_list'));
    }
    public function storePlay(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $userId = $user->id;
            $schoolId = $user->school_id;
            $userType = $user->usertype;
            if ($userType == 'teacher') {
                    $addReport = ['user_id' => $userId, 'video_play_status' => 1, 'school_id' => $schoolId, 'lesson_plan' => $request->plan_Id, 'class_id' => $request->grade_Id];
                    VideoPlayReport::updateOrCreate($addReport);
            } else {
                return "error";
            }
        }
    }
}
