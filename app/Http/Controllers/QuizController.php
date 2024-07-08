<?php

namespace App\Http\Controllers;

use App\Models\{User, School, Quiz, QuizCategory, Program, QuizTitle, HistoryQuiz, QuizReport};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use DataTables;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{

    public function indexCategoryList(Request $request)
    {

        if ($request->ajax()) {
            $mainQuery = QuizCategory::whereNull('deleted_at');
            $data = $mainQuery->orderBy('id', 'desc');
            return Datatables::of($data)
                ->addColumn('index', function ($row) {
                    static $index = 0;
                    return ++$index;
                })
                //->addIndexColumn()
                ->editColumn('quiz_categories_image', function ($row) {
                    $ImagePath = $row->quiz_categories_image ? $row->quiz_categories_image : 'no_image.png';
                    return '<img src="' . url('uploads/quiz/' . $ImagePath) . '" width="32" height="32" class="bg-light my-n1"
                    alt="' . $row->title . '">';
                })
                ->editColumn('title', function ($row) {
                    return $row->title ?? '';
                })
                ->editColumn('status', function ($row) {
                    $status_btn = '<a href="javascript:void(0);"  id="status_' . $row->id . '" data-id="' . $row->id . '" data-status="' . $row->status . '" class="text-white badge bg-' . ($row->status == 1 ? 'success' : 'danger') . '">' . ($row->status == 1 ? 'Active' : 'Inactive') . '</a>';
                    return $status_btn;
                })

                ->addColumn('action', function ($row) {
                    $edit_btn = '<a href="' . route('quiz-category-edit', ['quiz_category_id' => $row->id]) . '"
                    class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Edit</a>';
                    $user = Auth::user();
                    $remove_btn = ($user->usertype == "superadmin") ? ' <a href="javascript:void(0);" data-id=' . $row->id . '
                    class="waves-effect waves-light btn btn-sm btn-outline btn-danger remove_school_data mb-5">Delete</a>' : '';
                    $action_btn = $edit_btn . $remove_btn;
                    return $action_btn;
                })

                ->rawColumns(['action', 'title', 'quiz_categories_image', 'status'])
                ->make(true);
        }
        return view('quiz.quiz-category-list');
    }


    public function addQuizCategory(Request $request)
    {
        return view('quiz.quiz-category-add');
    }

    public function storeQuizCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'image' => 'required|image|mimes:png,jpg,webp|max:2048',

        ]);
        if ($validator->passes()) {
            if ($image = $request->file('image')) {
                $destinationPath = 'uploads/quiz/';
                $originalname = $image->hashName();
                $imageName = "quiz_category_" . date('Ymd') . '_' . $originalname;
                $image->move($destinationPath, $imageName);
            }
            $quizcreate = new QuizCategory();
            $quizcreate->title = $request->title;
            $quizcreate->quiz_categories_image = $imageName;
            $quizcreate->status = 1;
            $quizcreate->save();
            return response()->json([
                'status' => true,
                'message' => "Quiz created successfully",
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }
    public function editQuizCategory(Request $request)
    {
        $quiz_id = $request->input('quiz_category_id');
        $quiz_category_data = DB::table('quiz_categories')->where('id', $quiz_id)
            ->whereNull('deleted_at')
            ->select([
                'quiz_categories.id',
                'quiz_categories.title',
                'quiz_categories.quiz_categories_image',
                'quiz_categories.status',
                'quiz_categories.created_at',
                'quiz_categories.updated_at',
            ])->orderBy('id', 'desc')->first();
        return view('quiz.quiz-category-edit', compact('quiz_category_data'));
    }

    public function updateQuizCategory(Request $request)
    {
        $updateQuiz = QuizCategory::where('id', $request->id)->first();
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
        ]);
        if ($validator->passes()) {
            if ($image = $request->file('image')) {
                $destinationPath = 'uploads/quiz/';
                $originalname = $image->hashName();
                $imageName = "quiz_category_" . date('Ymd') . '_' . $originalname;
                $image->move($destinationPath, $imageName);
                $image_path = $destinationPath . $request->update_quiz_image;
                @unlink($image_path);
            } else {
                $imageName = $request->update_quiz_image;
            }
            $updateQuiz->title = $request->title;
            $updateQuiz->quiz_categories_image = $imageName;
            $updateQuiz->save();
            return response()->json([
                'status' => true,
                'message' => "Quiz update successfully",
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function quizCategoryVerify(Request $request)
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
    public function destroyQuizCategory(Request $request)
    {

        $quiz_id = $request->input('quiz_id');
        $sr_data = QuizCategory::where('id', $quiz_id)->first();

        $check_quizzes = DB::table('quiz_titles')->where('quiz_category_id', $quiz_id)->count();
        if ($check_quizzes >= 1) {
            return response()->json(['success' => false, 'msg' => 'Quiz category  exist please remove all account.']);
        } else {
            $sr_data->delete();
            echo "removed";
        }
    }


    public function indexQuiz(Request $request)
    {

        if ($request->ajax()) {

            $dataTbl = Quiz::leftJoin('quiz_titles', 'quiz_titles.id', 'quizlist.quiz_title_id')
                ->leftJoin('quiz_categories', 'quiz_titles.quiz_category_id', 'quiz_categories.id')
                ->selectRaw('quizlist.*,quiz_categories.title as quiz_category_title,quiz_titles.quiz_title,quiz_titles.quiz_title_grade as quiz_grade');

            return Datatables::of($dataTbl)
                ->editColumn('question_image', function ($row) {
                    $ImagePath = $row->question_image ? $row->question_image : 'no_image.png';
                    return '<img src="' . url('uploads/quiz/' . $ImagePath) . '" width="32" height="32" class="bg-light my-n1"
                    alt="' . $row->title . '">';
                })
                ->editColumn('quiz_title', function ($row) {
                    return $row->quiz_title ?? '';
                })
                ->addColumn('class_name', function ($row) {
                    $class_list_name = Program::whereIn('id', explode(",", $row->quiz_grade))->get(['class_name'])->toArray();
                    return array_column($class_list_name, 'class_name');
                })
                ->editColumn('quiz_category_title', function ($row) {
                    return $row->quiz_category_title ?? '';
                })
                ->editColumn('status', function ($row) {
                    $status_btn = '<a href="javascript:void(0);"  id="status_' . $row->id . '" data-id="' . $row->id . '" data-status="' . $row->status . '" class="change_status text-white badge bg-' . ($row->status == 1 ? 'success' : 'danger') . '">' . ($row->status == 1 ? 'Demo' : 'Paid') . '</a>';
                    return $status_btn;
                })

                ->addColumn('action', function ($row) {
                    $action_btn = '<a href="' . route('quiz-edit', ['quiz_id' => $row->id]) . '"
                    class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Edit</a>';
                    $user = Auth::user();
                    /* $detail_btn = '<a href="' . route('quiz-details', ['quiz_id' => $row->id]) . '"
                    class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Details</a>'; */

                    $action_btn .= ($user->usertype == "superadmin") ? ' <a href="javascript:void(0);" data-id=' . $row->id . '
                    class="waves-effect waves-light btn btn-sm btn-outline btn-danger remove_school_data mb-5">Delete</a>' : '';


                   //$action_btn = $edit_btn  . $remove_btn;
                    return $action_btn;
                })
                ->rawColumns(['action', 'quiz_title', 'question_image', 'status'])
                ->make(true);
        }

        return view('quiz.quiz-list');
    }
    public function addQuiz(Request $request)
    {

        $quiz_title_data = DB::table('quiz_titles')->whereNull('deleted_at')->get();
        $program_list = DB::table('master_class')->where('status', 1)->get();
        return view('quiz.quiz-add', compact('quiz_title_data', 'program_list'));
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            // 'title' => 'required|string',
            // 'question_text' => 'required',
            // 'question_url' => 'required',
            'question_title' => 'required',
            //'question_image' => 'required|image|mimes:png,jpg,webp|max:2048',
            'quiztitle' => 'required',
            'mcq_input_val' => 'required',
            'crct_answer' => 'required',
        ]);
        // dd($request->quiz['answer']);
        if ($validator->passes()) {

            if ($question_image = $request->file('question_image')) {
                $destinationPath = 'uploads/quiz/';
                $originalname = $question_image->hashName();
                $question_imageName = "quiz_thumbnail_" . date('Ymd') . '_' . $originalname;
                $question_image->move($destinationPath, $question_imageName);
            } else {
                $question_imageName = '';
            }

            $quizcreate = new Quiz();
            $quizcreate->quiz_title_id = $request->quiztitle;
            $quizcreate->question_text = isset($request->question_text) ? $request->question_text : '';
            $quizcreate->question_audurl = isset($request->question_audurl) ? $request->question_audurl : '';
            $quizcreate->question_title = isset($request->question_title) ? $request->question_title : '';
            $quizcreate->question_url = isset($request->question_url) ? $request->question_url : '';
            $quizcreate->crct_feedback = isset($request->crct_feedback) ? $request->crct_feedback : '';
            $quizcreate->incrct_feedback = isset($request->incrct_feedback) ? $request->incrct_feedback : '';
            $quizcreate->crct_answer = isset($request->crct_answer) ? $request->crct_answer : '';
            $quizcreate->question_image = $question_imageName;
            $quizcreate->status = 1;
            $quizcreate->question_mcq_opt = json_encode($request->mcq_input_val);
            $quizcreate->save();
            return response()->json([
                'status' => true,
                'message' => "Quiz created successfully",
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function updateQuiz(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'question_title' => 'required',
            'quiz_title' => 'required',
            'mcq_input_val' => 'required',
        ]);
        if ($validator->passes()) {

            if ($question_image = $request->file('question_image')) {
                $destinationPath = 'uploads/quiz/';
                $originalname = $question_image->hashName();
                $question_imageName = "quiz_thumbnail_" . date('Ymd') . '_' . $originalname;
                $question_image->move($destinationPath, $question_imageName);
                $image_path = $destinationPath . $request->old_question_image;
                @unlink($image_path);
            } else {
                $question_imageName = $request->old_question_image;
            }

            $updateQuiz = Quiz::where('id', $request->quiz_id)->first();
            $updateQuiz->quiz_title_id = $request->quiz_title;
            $updateQuiz->question_text = isset($request->question_text) ? $request->question_text : '';
            $updateQuiz->question_audurl = isset($request->question_audurl) ? $request->question_audurl : '';
            $updateQuiz->question_title = isset($request->question_title) ? $request->question_title : '';
            $updateQuiz->question_url = isset($request->question_url) ? $request->question_url : '';
            $updateQuiz->crct_feedback = isset($request->crct_feedback) ? $request->crct_feedback : '';
            $updateQuiz->incrct_feedback = isset($request->incrct_feedback) ? $request->incrct_feedback : '';
            $updateQuiz->crct_answer = isset($request->crct_answer) ? $request->crct_answer : '';
            $updateQuiz->question_image = $question_imageName;
            $updateQuiz->status = 1;
            $updateQuiz->question_mcq_opt = json_encode($request->mcq_input_val);

            $updateQuiz->save();
            return response()->json([
                'status' => true,
                'message' => "Quiz update successfully",
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }


    public function editQuiz(Request $request)
    {
        $quiz_id = $request->input('quiz_id');
        $quiz_data = Quiz::where('id', $quiz_id)->first();

        $quiz_title_data = DB::table('quiz_titles')->whereNull('deleted_at')->get();
        $program_list = DB::table('master_class')->where('status', 1)->get();
        return view('quiz.quiz-edit', compact('quiz_data', 'quiz_title_data', 'program_list'));
    }


    public function changeStatus(Request $request)
    {
        $statusId = $request->sts_id;
        $status = ($request->status == 1) ? 0 : 1;
        Quiz::where('id', $statusId)->update(['status' => $status]);
        echo ($status == 1) ? 'Demo' : 'Paid';
    }
    public function quizVerify(Request $request)
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
        $quiz_id = $request->input('quiz_id');
        $sr_data = Quiz::where('id', $quiz_id)->first();
        if (!empty($sr_data)) {
            $sr_data->delete();
            echo "removed";
        }
    }

    public function detailsQuiz(Request $request)
    {
        $quiz_id = $request->input('quiz_id');
        $mainQuery = DB::table('quizzes')->where('id', $quiz_id)
            ->whereNull('deleted_at')
            ->select([
                'quizzes.id',
                'quizzes.title',
                'quizzes.quiz',
                'quizzes.quiz as quiz_json_data',
                'quizzes.quiz_image',
                'quizzes.status',
                'quizzes.created_at',
                'quizzes.updated_at',
            ])->orderBy('id', 'desc');
        $quiz_datas = $mainQuery->get()->map(function ($row) {
            $row->quiz = isset($row->quiz) ? json_decode($row->quiz) : '';
            $row->quiz_json_data = isset($row->quiz->quizes) ? $row->quiz->quizes : '';
            return $row;
        })->first();
        return view('quiz.quiz-details', compact('quiz_datas'));
    }

    public function quizTitleList(Request $request)
    {

        if ($request->ajax()) {
            $mainQuery = QuizTitle::whereNull('deleted_at');
            $data = $mainQuery->orderBy('id', 'desc');


            $mainQuery = DB::table('quiz_titles')->whereNull('quiz_titles.deleted_at')
                ->select([
                    'quiz_titles.id',
                    'quiz_titles.quiz_title',
                    'quiz_titles.quiz_title_image',
                    'quiz_titles.quiz_title_grade',
                    'quiz_titles.quiz_category_id',
                    'quiz_titles.status',
                    'quiz_titles.created_at',
                    'quiz_titles.updated_at',
                    'quiz_categories.title as quiz_category_title',
                ])->leftJoin('quiz_categories', 'quiz_categories.id', 'quiz_titles.quiz_category_id');
            $data = $mainQuery->orderBy('id', 'desc');



            return Datatables::of($data)
                ->addColumn('index', function ($row) {
                    static $index = 0;
                    return ++$index;
                })
                //->addIndexColumn()
                ->editColumn('quiz_title_image', function ($row) {
                    $ImagePath = $row->quiz_title_image ? $row->quiz_title_image : 'no_image.png';
                    return '<img src="' . url('uploads/quiz/' . $ImagePath) . '" width="32" height="32" class="bg-light my-n1"
                    alt="' . $row->quiz_title . '">';
                })
                ->editColumn('quiz_title', function ($row) {
                    return $row->quiz_title ?? '';
                })
                ->addColumn('class_name', function ($row) {
                    $class_list_name = Program::whereIn('id', explode(",", $row->quiz_title_grade))->get(['class_name'])->toArray();
                    return array_column($class_list_name, 'class_name');
                })
                ->editColumn('quiz_category_title', function ($row) {
                    return $row->quiz_category_title ?? '';
                })
                /* ->editColumn('status', function ($row) {
                    $status_btn = '<a href="javascript:void(0);"  id="status_' . $row->id . '" data-id="' . $row->id . '" data-status="' . $row->status . '" class="text-white badge bg-' . ($row->status == 1 ? 'success' : 'danger') . '">' . ($row->status == 1 ? 'Active' : 'Inactive') . '</a>';
                    return $status_btn;
                }) */
                ->editColumn('status', function ($row) {
                    $status_btn = '<a href="javascript:void(0);"  id="status_' . $row->id . '" data-id="' . $row->id . '" data-status="' . $row->status . '" class="change_status text-white badge bg-' . ($row->status == 1 ? 'success' : 'danger') . '">' . ($row->status == 1 ? 'Demo' : 'Paid') . '</a>';
                    return $status_btn;
                })

                ->addColumn('action', function ($row) {
                    $edit_btn = '<a href="' . route('quiz-title-edit', ['quiz_title_id' => $row->id]) . '"
                    class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Edit</a>';
                    $user = Auth::user();
                    $remove_btn = ($user->usertype == "superadmin") ? ' <a href="javascript:void(0);" data-id=' . $row->id . '
                    class="waves-effect waves-light btn btn-sm btn-outline btn-danger remove_school_data mb-5">Delete</a>' : '';
                    $action_btn = $edit_btn . $remove_btn;
                    return $action_btn;
                })

                ->rawColumns(['action', 'quiz_title', 'class_name', 'quiz_category_title', 'quiz_title_image', 'status'])
                ->make(true);
        }
        return view('quiz.quiz-title');
    }

    public function addQuizTitle(Request $request)
    {
        $quiz_category_datas = DB::table('quiz_categories')->whereNull('deleted_at')->get();
        $program_list = DB::table('master_class')->where('status', 1)->get();
        return view('quiz.quiz-title-add', compact('quiz_category_datas', 'program_list'));
    }

    public function storeQuizTitle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quiz_title' => 'required|string',
            'class_id' => 'required',
            'quiz_category' => 'required',
            'image' => 'required|image|mimes:png,jpg,webp|max:2048',

        ]);
        if ($validator->passes()) {
            if ($image = $request->file('image')) {
                $destinationPath = 'uploads/quiz/';
                $originalname = $image->hashName();
                $imageName = "quiz_title_" . date('Ymd') . '_' . $originalname;
                $image->move($destinationPath, $imageName);
            }
            $quizcreate = new QuizTitle();
            $quizcreate->quiz_title_grade = implode(",", $request->class_id);
            $quizcreate->quiz_title = $request->quiz_title;
            $quizcreate->quiz_category_id = $request->quiz_category;
            $quizcreate->quiz_title_image = $imageName;
            $quizcreate->status = 1;
            $quizcreate->save();
            return response()->json([
                'status' => true,
                'message' => "Quiz Title created successfully",
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function editQuizTitle(Request $request)
    {
        $quiz_title_id = $request->input('quiz_title_id');
        $quiz_title_data = DB::table('quiz_titles')->where('id', $quiz_title_id)
            ->whereNull('deleted_at')
            ->select([
                'quiz_titles.id',
                'quiz_titles.quiz_title',
                'quiz_titles.quiz_title_image',
                'quiz_titles.quiz_category_id',
                'quiz_titles.quiz_title_grade',
                'quiz_titles.status',
                'quiz_titles.created_at',
                'quiz_titles.updated_at',
            ])->orderBy('id', 'desc')->first();
        $quiz_category_datas = DB::table('quiz_categories')->whereNull('deleted_at')->get();
        $program_list = DB::table('master_class')->where('status', 1)->get();
        return view('quiz.quiz-title-edit', compact('quiz_title_data', 'quiz_category_datas', 'program_list'));
    }


    public function updateQuizTitle(Request $request)
    {
        $updateQuiztitle = QuizTitle::where('id', $request->quiz_title_id)->first();
        $validator = Validator::make($request->all(), [
            'quiz_title' => 'required|string',
            'class_id' => 'required',
        ]);
        if ($validator->passes()) {
            if ($image = $request->file('image')) {
                $destinationPath = 'uploads/quiz/';
                $originalname = $image->hashName();
                $imageName = "quiz_category_" . date('Ymd') . '_' . $originalname;
                $image->move($destinationPath, $imageName);
                $image_path = $destinationPath . $request->update_quiz_title_image;
                @unlink($image_path);
            } else {
                $imageName = $request->update_quiz_title_image;
            }
            $updateQuiztitle->quiz_title = $request->quiz_title;
            $updateQuiztitle->quiz_title_grade = implode(",", $request->class_id);
            $updateQuiztitle->quiz_category_id = $request->quiz_category;
            $updateQuiztitle->quiz_title_image = $imageName;
            $updateQuiztitle->save();

            return response()->json([
                'status' => true,
                'message' => "Quiz title update successfully",
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function changeStatusQuizTitle(Request $request)
    {
        $statusId = $request->sts_id;
        $status = ($request->status == 1) ? 0 : 1;
        QuizTitle::where('id', $statusId)->update(['status' => $status]);
        echo ($status == 1) ? 'Demo' : 'Paid';
    }

    public function quizTitleVerify(Request $request)
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
    public function destroyQuizTitle(Request $request)
    {

        $quiz_id = $request->input('quiz_id');
        $sr_data = QuizTitle::where('id', $quiz_id)->first();

        $check_quizzes = DB::table('quizzes')->where('quiz_title_id', $quiz_id)->count();
        if ($check_quizzes >= 1) {
            return response()->json(['success' => false, 'msg' => 'Quiz Title  exist please remove all account.']);
        } else {
            $sr_data->delete();
            echo "removed";
        }
    }
    public function getQuiz(Request $request)
    {
        $mainQuery = DB::table('quizzes')->where('quiz_title_id', 3)
            ->whereNull('quizzes.deleted_at')
            ->select([
                'quizzes.id',
                'quizzes.title',
                'quizzes.quiz',
                'quizzes.quiz as quiz_json_data',
                'quizzes.quiz_image',
                'quizzes.quiz_grade',
                'quizzes.question_text',
                'quizzes.quiz_title_id',
                'quizzes.question_url',
                'quizzes.question_image',
                'quizzes.feedback',
                'quizzes.quiz_category_id',
                'quizzes.status',
                'quizzes.created_at',
                'quizzes.updated_at',
                'quiz_titles.quiz_title',
            ])
            ->leftJoin('quiz_titles', 'quiz_titles.id', '=', 'quizzes.quiz_title_id')
            ->orderBy('id', 'desc');
        $quiz_data = $mainQuery->get()->map(function ($row) {
            $row->quiz = isset($row->quiz) ? json_decode($row->quiz) : '';
            $row->quiz_json_data = isset($row->quiz->answers) ? $row->quiz->answers : '';
            return $row;
        });
        return $quiz_data;
    }

    public function storeResult(Request $request)
    {
        $validator = Validator::make($request->all(), []);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()->all()]);
        }

        $quizResults = $request->all();
        foreach ($quizResults as $result) {
            $quizResult = new HistoryQuiz();
            $quizResult->question = $result['question'];
            $quizResult->selectedOption = $result['selectedOption'];
            $quizResult->answer_checkbox = $result['answer_checkbox'];
            $quizResult->question_id = $result['question_id'];
            $quizResult->quiz_title_id = $result['quiz_title_id'];
            $quizResult->user_id = 1;
            $quizResult->status = 1;
            $quizResult->answers = json_encode($result['answers']);
            $quizResult->save();
        }

        return response()->json(['status' => true, 'message' => 'Quiz results stored successfully']);
    }


    public function getAnswerList(Request $request)
    {
        $mainQuery = DB::table('history_quiz')
            ->where('user_id', 1)
            ->whereNull('history_quiz.deleted_at')
            ->select([
                'history_quiz.id',
                'history_quiz.question',
                'history_quiz.selectedOption',
                'history_quiz.answer_checkbox',
                'history_quiz.answers',
                'history_quiz.answers as answers_json_data',
                'history_quiz.user_id',
                'history_quiz.question_id',
                'history_quiz.quiz_title_id',
                'history_quiz.status',
            ])
            ->orderBy('history_quiz.id', 'desc');

        $history_quizs = $mainQuery->get()->map(function ($row) {
            $row->answers = isset($row->answers) ? json_decode($row->answers) : '';
            $row->answers_json_data = isset($row->answers) ? $row->answers : '';
            return $row;
        });
        $checkedCount = $history_quizs->where('answer_checkbox', 'checked')->count();
        $uncheckedCount = $history_quizs->where('answer_checkbox', 'unchecked')->count();
        $QuizCount = $history_quizs->count();

        return ([
            'history_quizs' => $history_quizs,
            'correct_answers' => $checkedCount,
            'incorrect_answers' => $uncheckedCount,
            'total_questions' => $QuizCount,
            'attempted_questions' => $QuizCount,
        ]);
    }


    public function studentLoginQuizTitle(Request $request)
    {
       // dd($request->categoryId);
        $user = Auth::user();
        $grades = $user->grade;
        $gradesArray = explode(',', $grades);

        $userId = $user->id;
        $schoolId = $user->school_id;
        $classId = $request->class;
        $check_premium = School::find($schoolId);
        $class_name = Program::find($classId);
        /*  $quiz_title_data = DB::table('quiz_titles')->whereNull('quiz_titles.deleted_at')
        ->select([
              'quiz_titles.id',
              'quiz_titles.quiz_title',
              'quiz_titles.quiz_title_image',
              'quiz_titles.quiz_category_id',
              'quiz_titles.status',
              'quiz_titles.created_at',
              'quiz_titles.updated_at',
        ])->orderBy('id', 'desc')->get(); */
        $quiz_title_data = DB::table('quiz_titles')->where('quiz_titles.quiz_category_id', $request->categoryId)
            ->where(function ($query) use ($gradesArray) {
                foreach ($gradesArray as $grade) {
                    $query->orWhere('quiz_title_grade', 'LIKE', "%$grade%");
                }
            })
            ->whereNull('quiz_titles.deleted_at')
            ->select([
                'quiz_titles.id',
                'quiz_titles.quiz_title',
                'quiz_titles.quiz_title_image',
                'quiz_titles.quiz_category_id',
                'quiz_titles.status',
                'quiz_titles.created_at',
                'quiz_titles.updated_at',
            ])
            ->orderBy('id', 'desc')
            ->get();
           // dd($quiz_title_data);
        return view('quiz.quiz-title-student', compact('quiz_title_data', 'check_premium', 'class_name'));
    }


    public function studentLoginQuizCategory(Request $request)
    {
        $quiz_category_data = DB::table('quiz_categories')
        ->whereNull('quiz_categories.deleted_at')
        ->select([
            'quiz_categories.id',
            'quiz_categories.title',
            'quiz_categories.quiz_categories_image',
            'quiz_categories.status',
            'quiz_categories.created_at',
            'quiz_categories.updated_at',
        ])
        ->orderBy('id', 'desc')
        ->get();
        return view('quiz.quiz-category-student', compact('quiz_category_data'));
    }

    public function classroomLoginQuizCategory(Request $request)
    {
        $quiz_category_data = DB::table('quiz_categories')
        ->whereNull('quiz_categories.deleted_at')
        ->select([
            'quiz_categories.id',
            'quiz_categories.title',
            'quiz_categories.quiz_categories_image',
            'quiz_categories.status',
            'quiz_categories.created_at',
            'quiz_categories.updated_at',
        ])
        ->orderBy('id', 'desc')
        ->get();
        return view('quiz.quiz-category-classroom', compact('quiz_category_data'));
    }



    public function classroomLoginQuizTitle(Request $request)
    {
        $user = Auth::user();
        $grades = $user->grade;
        $gradesArray = explode(',', $grades);

        $userId = $user->id;
        $schoolId = $user->school_id;
        $classId = $request->class;
        $check_premium = School::find($schoolId);
        $class_name = Program::find($classId);
        $quiz_title_data = DB::table('quiz_titles')->where('quiz_titles.quiz_category_id', $request->categoryId)
            ->where(function ($query) use ($gradesArray) {
                foreach ($gradesArray as $grade) {
                    $query->orWhere('quiz_title_grade', 'LIKE', "%$grade%");
                }
            })
            ->whereNull('quiz_titles.deleted_at')
            ->select([
                'quiz_titles.id',
                'quiz_titles.quiz_title',
                'quiz_titles.quiz_title_image',
                'quiz_titles.quiz_category_id',
                'quiz_titles.status',
                'quiz_titles.created_at',
                'quiz_titles.updated_at',
            ])
            ->orderBy('id', 'desc')
            ->get();
        return view('quiz.quiz-title-classroom', compact('quiz_title_data', 'check_premium', 'class_name'));
    }

    public function quizStudent()
    {
        $user = Auth::user();
        $grades = $user->grade; // Assuming $user->grade is "1,2,3,4"
        // Split the string into an array of individual grades
        $gradesArray = explode(',', $grades);
        // Query for quiz titles where any of the grades from $gradesArray are present
        $quizTitles = QuizTitle::where(function ($query) use ($gradesArray) {
            foreach ($gradesArray as $grade) {
                $query->orWhere('quiz_title_grade', 'LIKE', "%$grade%");
            }
        })->pluck('quiz_title_grade')->toArray();

        // dd($quizTitles);
        //   dd($grades);
        $student_class_list = [];
        foreach ($quizTitles as $grade) {
            $gradeArray = explode(',', $grade);
            $student_class_list = array_merge($student_class_list, $gradeArray);
        }
        $student_class_list = array_unique($student_class_list);
        $student_class_list = array_filter($student_class_list);
        $student_class_list = DB::table('master_class')
            ->whereIn('id', $student_class_list)
            ->where('status', 1)
            ->orderBy('id')
            ->get();
        //  dd($student_class_list);
        return view('quiz.quiz-grade-student', compact('student_class_list'));
    }

    public function studentQuizQuestion(Request $request)
    {
        $mainQuery = DB::table('quizzes')->where('quiz_title_id', $request->quiz_title_id)
            ->whereNull('quizzes.deleted_at')
            ->select([
                'quizzes.id',
                'quizzes.title',
                'quizzes.quiz',
                'quizzes.quiz as quiz_json_data',
                'quizzes.quiz_image',
                'quizzes.quiz_grade',
                'quizzes.question_text',
                'quizzes.quiz_title_id',
                'quizzes.question_url',
                'quizzes.question_image',
                'quizzes.feedback',
                'quizzes.quiz_category_id',
                'quizzes.status',
                'quizzes.created_at',
                'quizzes.updated_at',
                'quiz_titles.quiz_title',
            ])
            ->leftJoin('quiz_titles', 'quiz_titles.id', '=', 'quizzes.quiz_title_id')
            ->orderBy('id', 'desc');
        $quiz_data = $mainQuery->get()->map(function ($row) {
            $row->quiz = isset($row->quiz) ? json_decode($row->quiz) : '';
            $row->quiz_json_data = isset($row->quiz->answers) ? $row->quiz->answers : '';
            return $row;
        });
        return view('quiz', compact('quiz_data'));
    }

    public function studentQuizReport(Request $request)
    {
        $quiz_report = QuizReport::with('getquiz')->where(['user_id' => auth()->user()->id])->orderBy('id','desc')->take(3)->get();
        return view('quizreport', compact('quiz_report'));
    }
}
