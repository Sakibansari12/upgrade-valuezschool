<?php

namespace App\Http\Controllers;
use App\Models\{User, School, CitiesModel, StateModel, Identifier, LogsModel, Program, Course, Package, SchoolPayment, EmailTemplate,Aimodules};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Services\OpenAIService;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use DB;
use GuzzleHttp\Exception\ClientException;
use DataTables;
class ChatGptController extends Controller
{
    public function chatgptView(Request $request)
    {
       $identifier_id = $request->input('id');
       $class_id = $request->input('grade_id');
       $class_name = Program::find($class_id);
        $type = $request->input('type');
        $mainQuery = DB::table('ai_identifiers')
        ->whereNull('ai_identifiers.deleted_at')
        ->select([
            /* 'aimodules.id',
            'aimodules.user_id',
            'aimodules.display_name',
            'aimodules.content',
            'aimodules.thumbnail',
            'aimodules.generate_hybrid',
            'aimodules.slider_video_data',
            'aimodules.vision_data',
            'aimodules.course_id',
            'aimodules.type',
            'master_course.course_name as course_name_chatgpt',
 */

            'ai_identifiers.id',
            'ai_identifiers.display_name',
            'ai_identifiers.module_page_title',
            'ai_identifiers.module_page_overview',
            'ai_identifiers.video_url',
            'ai_identifiers.thumbnail',
            'ai_identifiers.type',
            'ai_identifiers.grade_id',
            'ai_identifiers.course_id',
            'ai_identifiers.status',
            'ai_identifiers.aimodule_status',
            'master_course.course_name',
            'aimodules.content',
            'aimodules.id as aimodule_id',
            'aimodules.description',
            'aimodules.own_description',
            'aimodules.own_placeholder',
            'aimodules.hello_there_description',
            'aimodules.slider_video_data',
            'aimodules.vision_data',
            'aimodules.generate_hybrid',
            'master_course.course_name as course_name_chatgpt'

        ])->where('ai_identifiers.id', $identifier_id)
        ->leftJoin('master_course', 'master_course.id', 'ai_identifiers.course_id')
        ->leftJoin('aimodules', 'aimodules.identifier_id', 'ai_identifiers.id');
        
         $aimodules = $mainQuery->get()->map(function ($row) {
            $row->content = isset($row->content) ? json_decode($row->content) : null;
            $row->promptsdata = isset($row->content->prompts) ? $row->content->prompts : [];
            $row->activitiesdata = isset($row->content->activities) ? $row->content->activities : [];
            $formattedType = str_replace('_', ' ', $row->type); 
            $formattedType = ucfirst($formattedType); 
            $row->type = $formattedType;

             $row->generate_hybrid = isset($row->generate_hybrid) ? json_decode($row->generate_hybrid) : null;
            $row->hybridsdata = isset($row->generate_hybrid->hybrid) ? $row->generate_hybrid->hybrid : [];
 
             $row->slider_video_data = isset($row->slider_video_data) ? json_decode($row->slider_video_data) : null;
          $row->sliderData = isset($row->slider_video_data->slider) ? $row->slider_video_data->slider : []; 

           $row->vision_data = isset($row->vision_data) ? json_decode($row->vision_data) : null;
          $row->visionData = isset($row->vision_data->vision) ? $row->vision_data->vision : []; 

            $row->AddOwnactivitiesdata = isset($row->content->add_own_activities_prompts) ? $row->content->add_own_activities_prompts : [];
            return $row;
        })->first();
        //dd($aimodules);
       if($type == 'chatgpt'){
        $formattedType = 'ChatGPT';
        return view('webpages.chat-gpt', compact('aimodules','class_name','class_id', 'formattedType',));
       }
       if($type == 'dalle'){
        $formattedType = 'Dall-E';
        return view('webpages.dalle', compact('aimodules','class_name','class_id', 'formattedType'));
       }
       if($type == 'text_to_speech'){
        $formattedType = 'Text to Speech';
        return view('webpages.text-to-speech', compact('aimodules','class_name','class_id', 'formattedType'));
       }
       if($type == 'speech_to_text'){
        
        $formattedType = 'Speech to Text';
        return view('webpages.speech-to-text', compact('aimodules','class_name','class_id', 'formattedType'));
       }
       if($type == 'text_to_music'){
        $formattedType = 'Text to Music';
        return view('webpages.text-to-music', compact('aimodules','class_name','class_id', 'formattedType'));
       }
       if($type == 'generate_avatar'){
        $formattedType = 'Avatar';
        return view('webpages.generate-avatar', compact('aimodules','class_name','class_id', 'formattedType'));
       }
       if($type == 'photo_to_video'){
        $formattedType = 'Photo to Video';
        return view('webpages.generate-photo-to-video', compact('aimodules','class_name','class_id', 'formattedType'));
       }
       if($type == 'fauna_and_flora'){
        $formattedType = 'Fauna & Flora';
        return view('webpages.fauna_and_flora', compact('aimodules','class_name','class_id', 'formattedType'));
       }
       if($type == 'image_to_narration'){
        $formattedType = 'Image to Narration';
        return view('webpages.image_to_narration', compact('aimodules','class_name','class_id', 'formattedType'));
       }
       /* if($type == 'bird_classifier'){
        return view('webpages.bird_classifier', compact('aimodules','class_name','class_id', 'formattedType'));
       }
       if($type == 'plant_recognizer'){
        return view('webpages.plant_recognizer', compact('aimodules','class_name','class_id', 'formattedType'));
       } */
        
    }


    public function chatgptModuleView(Request $request)
    {
        $grade_id = $request->input('grade_id');
        $courses_id = $request->input('courses_id');
        $user = Auth::user();
        $schoolId = $user->school_id;
         $gradedata = DB::table('master_class')->where('id', $grade_id)->first();
         $coursesdata = DB::table('master_course')->where('id', $courses_id)->first();
        $mainQuery = DB::table('ai_identifiers')
        ->whereNull('ai_identifiers.deleted_at')
        ->select([
            'ai_identifiers.id',
           // 'ai_identifiers.user_id',
            'ai_identifiers.display_name',
            //'ai_identifiers.content as ',
            'ai_identifiers.thumbnail',
            'ai_identifiers.type',
            'ai_identifiers.aimodule_status',
        ])
        ->join('aimodules', 'ai_identifiers.id', '=', 'aimodules.identifier_id');
       // ->leftJoin('aimodules', 'aimodules.identifier_id', 'ai_identifiers.id');
        $aimodules = $mainQuery->get()->map(function ($row) {
            $content = isset($row->content) ? $row->content : '';
           return $row;
           })->all();
        $check_premium = School::find($schoolId);
        //dd($aimodules);
        return view('webpages.ai-modules', compact('aimodules','coursesdata','gradedata','check_premium'));
    }


    public function chatgptSearch(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'ask_question_chatGpt' => 'required',
          ]);
          if($validator->passes()){
            $apiKey = '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';
        $model = 'gpt-3.5-turbo';
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $apiKey,
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => $model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a helpful assistant.',
                ],
                [
                    'role' => 'user',
                    'content' => $request->ask_question_chatGpt,
                ],
            ],
        ]);

        if ($response->successful()) {
            $result = $response->json();
            $completion = $result['choices'][0]['message']['content'];

            return response()->json([
                'status' => true,
                'message' => $completion,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => 'Error: ' . $response->status(),
            ], /* $response->status() */);
        }
    }else{
        return response()->json([
            'status' => false,
            'errors' => $validator->errors(),
            ]);
        }
    }


    public function chatgptStory(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'ask_question_chatGpt' => 'required',
          ]);
          if($validator->passes()){
        $apiKey = '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';
        $model = 'gpt-3.5-turbo';
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $apiKey,
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => $model,
            'messages' => [
                [
                    "role" => "assistant",
                    "content" => "You are an expert story generator and need to generate a short story based on a character. You will be told how character is feeling and the story should help the person through a story. Give output in json format with the keys as 'plot', 'title' and 'scenes' where scenes key is a dictionary with keys such as 'scene1', 'scene2,'scene3' whose value is the actual scene of the story.",
                ],
                [
                    'role' => 'user',
                    'content' => $request->ask_question_chatGpt,
                ],
            ],
        ]);

        if ($response->successful()) {
            $result = $response->json();
            $completion = $result['choices'][0]['message']['content'];

            return response()->json([
                'status' => true,
                'message' => $completion,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => 'Error: ' . $response->status(),
            ], /* $response->status() */);
        }
    }else{
        return response()->json([
            'status' => false,
            'errors' => $validator->errors(),
            ]);
        }
    }




    public function dalleSearch(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'ask_question_chatGpt' => 'required',
          ]);
          if($validator->passes()){
            $apiKey = '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';
            $model = 'dall-e-2';
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $apiKey,
        ])->post('https://api.openai.com/v1/images/generations', [
            "model" => $model,
            "prompt"=> $request->ask_question_chatGpt,
            "n"=> 1,
            "size"=> "512x512"
            
        ]);
        if ($response->successful()) {
            $result = $response->json();
            $completion = $result['data'][0]['url'];

            return response()->json([
                'status' => true,
                'message' => $completion,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $response->status(),
            ], /* $response->status() */);
        }
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                ]);
            }
    }

    public function staticChatgpt(Request  $request)
    {
       return view('ai-static.ai-chatgpt');
    }
    public function staticDalle(Request  $request)
    {
        return view('ai-static.ai-dalle');

    }

   

    public function getAimodules(Request $request)
    {
        if ($request->ajax()) {
            $mainQuery = DB::table('aimodules')->whereNull('deleted_at')
                ->select([
                    'aimodules.id',
                    'aimodules.user_id',
                    'aimodules.display_name',
                    'aimodules.aimodule_status',
                    'aimodules.content',
                    'aimodules.thumbnail',
                    'aimodules.type',
                    'aimodules.status',
                    'aimodules.course_id',
                    'master_course.course_name',
                ])
                ->leftJoin('master_course', 'master_course.id', 'aimodules.course_id')
                ->orderBy('aimodules.id','desc');
                $aimodules = $mainQuery->get()->map(function ($row) {
                    $row->content = isset($row->content) ? json_decode($row->content) : null;
                    if (is_array($row->content)) {
                        $row->Promptsdata = isset($row->content[0]->Prompts) ? $row->content[0]->Prompts : [];
                        $row->Activitiesdata = isset($row->content[0]->Activities) ? $row->content[0]->Activities : [];
                    } else {
                        $row->Promptsdata = [];
                        $row->Activitiesdata = [];
                    }
                    return $row;
                });
             $data = $aimodules;
            // dd($data);
             return Datatables::of($data)
             ->addColumn('index', function ($row) {
                 static $index = 0;
                 return ++$index;
             })

             ->editColumn('thumbnail', function ($row) {
                $thumbnailUrl = url('uploads/aimodule') . '/' . (!empty($row->thumbnail) ? $row->thumbnail : 'no_image.png');
                return '<img src="' . $thumbnailUrl . '" width="32" height="32" class="bg-light my-n1" alt="' . $row->display_name . '">';
            })
            ->editColumn('display_name', function ($row) {
                return $row->display_name;
            })

            ->editColumn('course_name', function ($row) {
                return $row->course_name;
            })
            /* ->editColumn('type', function ($row) {
                return $row->type;
            }) */
            ->editColumn('type', function ($row) {
                $formattedType = str_replace('_', ' ', $row->type); 
                $formattedType = ucfirst($formattedType); 
                return $formattedType;
            })
            ->editColumn('status', function ($row) {
                $statusClass = $row->status == 1 ? 'success' : 'danger';
                $statusText = $row->status == 1 ? 'Active' : 'Inactive';
                return '<a href="javascript:void(0);" class="change_status text-white badge bg-' . $statusClass . '"
                            id="status_' . $row->id . '" data-id="' . $row->id . '" data-status="' . $row->status . '">'
                            . $statusText . '</a>';
            })
            ->addColumn('aimodule_status', function ($row) {
                $statusClass = $row->aimodule_status == 1 ? 'success' : 'danger';
                $statusText = $row->aimodule_status == 1 ? 'Demo' : 'Paid';
                return '<a href="javascript:void(0);" class="change_aimodules_status text-white badge bg-' . $statusClass . '"
                            id="aimodule_status_' . $row->id . '" data-aimodule-id="' . $row->id . '"
                            data-aimodule-status="' . $row->aimodule_status . '">' . $statusText . '</a>';
            })
            ->addColumn('action', function ($row) {
                $editLink = '<a href="' . route('aimodule.edit', ['id' => $row->id]) . '"
                                class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Edit</a>';
                $deleteLink = '<a href="javascript:void(0);" data-id="' . $row->id . '"
                                class="waves-effect waves-light remove_school_data btn btn-sm btn-outline btn-danger mb-5">Delete</a>';
                return $editLink . ' ' . $deleteLink;
            })
             ->rawColumns(['index','thumbnail', 'display_name', 'course_name', 'type', 'status', 'aimodule_status', 'action'])
             ->toJson();
         }
        return view('aimodules.list');
    }



    public function createAimodules(Request $request)
    {
        $program_list = DB::table('master_class')->where('status', 1)->get();
        $course_list = DB::table('master_course')->where('status', 1)->where('ai_status', 1)->get();
       return view('aimodules.aimodule-add', compact('program_list', 'course_list'));
    }


    public function AIModulesCreate(Request $request)
    {
     //   dd($request->hybrid['generate_hybrid']);
         $validator = Validator::make($request->all(), [
            'module_name' => 'required|string',
            'module_thumbnail' => 'required|image|mimes:png,jpg,webp|max:2048',
            //'website_url' => 'required|string',
            'grades' => 'required',
            'courses' => 'required',
            'identifier' => 'required|string',
            'content' => 'required|array',
            'content.module_page_title' => 'required|string',
            'content.module_page_overview' => 'required|string',
            'content.video_url' => 'required|string',
            'content.prompts' => 'required|array',
            'content.prompts.*.prompts' => 'required|string',
            'content.prompts.*.response' => 'required|string',
            //'content.prompts.*.file' => 'required|image|mimes:png,jpg,webp|max:2048',
            'content.prompts.*.file' => 'required|mimes:png,jpg,webp,mp3|max:2048',
            'content.activities' => 'required|array',
            'content.activities.*.activities_description' => 'required|string',
            'content.activities.*.website_url' => 'required|string',
            //'content.activities.*.prompt_screenshot' => 'required|image|mimes:png,jpg,webp|max:2048',
            'content.activities.*.prompt_screenshot' => 'required|mimes:png,jpg,webp,mp3|max:2048',

            'content.add_own_activities_prompts' => 'required|array',
            'content.add_own_activities_prompts.*.add_placeholder_text' => 'required|string',
            'content.add_own_activities_prompts.*.add_ai_toll_url' => 'required|string',
            'content.add_own_activities_prompts.*.toll_name' => 'required|string',
        ]); 

        $aiModuleMsgRule = [];
        foreach ($request->content['prompts'] as $cindex => $competence_data) {
            $aiModuleMsgRule['content.prompts.'.$cindex.'.prompts'] = 'Row' .($cindex+1). '-'. 'Prompts';
            $aiModuleMsgRule['content.prompts.'.$cindex.'.response'] = 'Row' .($cindex+1). '-'. 'Response';
            $aiModuleMsgRule['content.prompts.'.$cindex.'.file'] = 'Row' .($cindex+1). '-'. 'File';
        }
        foreach ($request->content['activities'] as $cindex => $competence_data) {
            $aiModuleMsgRule['content.activities.'.$cindex.'.activities_description'] = 'Row' .($cindex+1). '-'. 'Activities Description';
            $aiModuleMsgRule['content.activities.'.$cindex.'.website_url'] = 'Row' .($cindex+1). '-'. 'Website Url ';
            $aiModuleMsgRule['content.activities.'.$cindex.'.prompt_screenshot'] = 'Row' .($cindex+1). '-'. 'Prompt Screenshots';
        }
        foreach ($request->content['add_own_activities_prompts'] as $cindex => $competence_data) {
            $aiModuleMsgRule['content.add_own_activities_prompts.'.$cindex.'.add_placeholder_text'] = 'Row' .($cindex+1). '-'. 'Add Placeholder Text';
            $aiModuleMsgRule['content.add_own_activities_prompts.'.$cindex.'.add_ai_toll_url'] = 'Row' .($cindex+1). '-'. 'Add AI Tool Url';
            $aiModuleMsgRule['content.add_own_activities_prompts.'.$cindex.'.toll_name'] = 'Row' .($cindex+1). '-'. 'Tool Name';
        }
        $validator->setAttributeNames($aiModuleMsgRule);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                ]);
        }
//dd($request->content['prompts']);
    if($validator->passes()){
       if ($image = $request->file('module_thumbnail')) {
        $destinationPath = 'uploads/aimodule/';
        $originalname = $image->hashName();
        $imageName = "aimodule_thumbnail_" . date('Ymd') . '_' . $originalname;
        $image->move($destinationPath, $imageName);
    }
        $prompts = [];
        foreach ($request->content['prompts'] as $promptData) {

            
            $file = $promptData['file'] ?? null;
            
           // dd($video);
           /*  $fileData = [
                'originalName' => "prompt_" . date('Ymd') . '_' . $file->getClientOriginalName(),
                'mimeType' => $file->getClientMimeType(),
                'size' => $file->getSize(),
                'path' => "uploads/aimodule/prompt_" . date('Ymd') . '_' . $file->getClientOriginalName(), // Remove the comma here
                'extension' => $file->getClientOriginalExtension(),
            ]; */
            if ($file) {
                  $destinationPath = 'uploads/aimodule/';
                  $originalName = $file->getClientOriginalName();
                  $imageNameprompts = "prompt_" . date('Ymd') . '_' . $originalName;
                  $file->move($destinationPath, $imageNameprompts);
                   $promptData['file'] = $imageNameprompts;
                    $fileData = [
                        'originalName' => "prompt_" . date('Ymd') . '_' . $file->getClientOriginalName(),
                        'mimeType' => $file->getClientMimeType(),
                       // 'size' => $file->getSize(),
                        'path' => "uploads/aimodule/prompt_" . date('Ymd') . '_' . $file->getClientOriginalName(), // Remove the comma here
                        'extension' => $file->getClientOriginalExtension(),
                    ];
                  $promptData['file_all_data'] = $fileData;
                //$promptData['file_all_data'] = $fileData;
            }
            $prompts[] = $promptData;
        }
        //dd($prompts);
        $activities = [];
        foreach ($request->content['activities'] as $activityData) {
            $prompt_screenshot = $activityData['prompt_screenshot'] ?? null;
            if ($prompt_screenshot) {
                  $destinationPath = 'uploads/aimodule/';
                  $originalName = $prompt_screenshot->getClientOriginalName();
                  $imageNameactivity = "activity_" . date('Ymd') . '_' . $originalName;
                  $prompt_screenshot->move($destinationPath, $imageNameactivity);
                $activityData['prompt_screenshot'] = $imageNameactivity;
            }
            $activities[] = $activityData;
        }

/* Hybrid */
$hybridData = $request->hybrid;
if (isset($hybridData['generate_hybrid'])) {
    foreach ($hybridData['generate_hybrid'] as $key => $hybridImage) {
        $hybrid_animal_one = isset($hybridImage['animal_one']) ? $hybridImage['animal_one'] : null;
        if ($hybrid_animal_one !== 'undefined') {
            $destinationPath = 'uploads/hybrid/';
            $originalName = $hybrid_animal_one->getClientOriginalName();
            $pdfName = "hybrid_" . date('Ymd') . '_' . $originalName;
            $hybrid_animal_one->move($destinationPath, $pdfName);
            $hybridData['generate_hybrid'][$key]['animal_one_new'] = $pdfName;
        } else {
            $hybridData['generate_hybrid'][$key]['animal_one_new'] = null;
        }


        $hybrid_animal_second = isset($hybridImage['animal_second']) ? $hybridImage['animal_second'] : null;
        if ($hybrid_animal_second !== 'undefined') {
            $destinationPath = 'uploads/hybrid/';
            $originalName = $hybrid_animal_second->getClientOriginalName();
            $pdfName = "hybrid_" . date('Ymd') . '_' . $originalName;
            $hybrid_animal_second->move($destinationPath, $pdfName);
            $hybridData['generate_hybrid'][$key]['animal_second_new'] = $pdfName;
        } else {
            $hybridData['generate_hybrid'][$key]['animal_second_new'] = null;
        }


        $hybrid_result = isset($hybridImage['result']) ? $hybridImage['result'] : null;
        if ($hybrid_result !== 'undefined') {
            $destinationPath = 'uploads/hybrid/';
            $originalName = $hybrid_result->getClientOriginalName();
            $pdfName = "hybrid_" . date('Ymd') . '_' . $originalName;
            $hybrid_result->move($destinationPath, $pdfName);
            $hybridData['generate_hybrid'][$key]['result_new'] = $pdfName;
        } else {
            $hybridData['generate_hybrid'][$key]['result_new'] = null;
        }
    }
}
$request->merge(['hybrid' => $hybridData]);



/* Slider */
$sliderData = $request->slider;
if (isset($sliderData['generate_slider'])) {
    foreach ($sliderData['generate_slider'] as $key => $hybridImage) {
        $hybrid_animal_one = isset($hybridImage['slider_image']) ? $hybridImage['slider_image'] : null;
        if ($hybrid_animal_one !== 'undefined') {
            $destinationPath = 'uploads/hybrid/';
            $originalName = $hybrid_animal_one->getClientOriginalName();
            $pdfName = "slider_" . date('Ymd') . '_' . $originalName;
            $hybrid_animal_one->move($destinationPath, $pdfName);
            $sliderData['generate_slider'][$key]['slider_image_new'] = $pdfName;
        } else {
            $sliderData['generate_slider'][$key]['slider_image_new'] = null;
        }
    }
}
$request->merge(['slider' => $sliderData]);


/* Vision */
$visionData = $request->vision;
if (isset($visionData['generate_vision'])) {
    foreach ($visionData['generate_vision'] as $key => $hybridImage) {
        $vision_image = isset($hybridImage['vision_image']) ? $hybridImage['vision_image'] : null;
        $vision_music = isset($hybridImage['vision_music']) ? $hybridImage['vision_music'] : null;
        if ($vision_image !== 'undefined') {
            $destinationPath = 'uploads/vision/';
            $originalName = $vision_image->getClientOriginalName();
            $pdfName = "vision_" . date('Ymd') . '_' . $originalName;
            $vision_image->move($destinationPath, $pdfName);
            $visionData['generate_vision'][$key]['vision_image_new'] = $pdfName;
        } else {
            $visionData['generate_vision'][$key]['vision_image_new'] = null;
        }
        if ($vision_music !== 'undefined') {
            $destinationPath = 'uploads/vision/';
            $originalName = $vision_music->getClientOriginalName();
            $pdfName = "vision_" . date('Ymd') . '_' . $originalName;
            $vision_music->move($destinationPath, $pdfName);
            $visionData['generate_vision'][$key]['vision_music_new'] = $pdfName;
        } else {
            $visionData['generate_vision'][$key]['vision_music_new'] = null;
        }
    }
}
$request->merge(['vision' => $visionData]);


    $data = $request->all();
    //$grade_id = implode(",", $data['grade']);
    $aimodule = new Aimodules();
    $aimodule->display_name = $data['module_name'];
    $aimodule->thumbnail = $imageName;
    $aimodule->type = $data['identifier'];
   // $aimodule->website_url = $data['website_url'];
    $aimodule->grade_id = $data['grades'];
    $aimodule->course_id = $data['courses'];
    $aimodule->status = 1;
    $aimodule->aimodule_status = 1;
   // $aimodule->content = json_encode($data['content']);
   $aimodule->generate_hybrid = json_encode([
    'hybrid' => $request->hybrid['generate_hybrid']
    ?? []]);
    $aimodule->slider_video_data = json_encode([
        'slider' => $request->slider['generate_slider']
        ?? []]);
    $aimodule->vision_data = json_encode([
        'vision' => $request->vision['generate_vision']
        ?? []]);
   $aimodule->content = json_encode([
    'module_page_title' => $request->content['module_page_title'],
    'module_page_overview' => $request->content['module_page_overview'],
    'video_url' => $request->content['video_url'],
    'prompts' => $prompts, 
    'activities' => $activities,
    'add_own_activities_prompts' => $request->content['add_own_activities_prompts']
    ?? []]);
    $aimodule->save();
    return response()->json([
        'status' => true,
        'message' => "Aimodule created successfully",
    ], 201);
    
    
}else{
    return response()->json([
        'status' => false,
        'errors' => $validator->errors(),
     ]);

 }
}

public function editaimodule(Request $request)
{

    $aimodule_id = $request->input('id');
    $program_list = DB::table('master_class')->where('status', 1)->get();
    $course_list = DB::table('master_course')->where('status', 1)->where('ai_status', 1)->get();
    $mainQuery = DB::table('aimodules')
                ->whereNull('deleted_at')
        ->select([
            'aimodules.id',
            'aimodules.user_id',
            'aimodules.display_name',
            'aimodules.content',
            'aimodules.generate_hybrid',
            'aimodules.slider_video_data',
            'aimodules.vision_data',
            'aimodules.thumbnail',
            'aimodules.type',
            'aimodules.status',
            'aimodules.website_url',
            'aimodules.grade_id',
            'aimodules.course_id',
        ])->where('id', $aimodule_id);
         $aimoduledata = $mainQuery->get()->map(function ($row) {
           
            $row->content = isset($row->content) ? json_decode($row->content) : null;
            $row->generate_hybrid = isset($row->generate_hybrid) ? json_decode($row->generate_hybrid) : null;
            $row->hybridsdata = isset($row->generate_hybrid->hybrid) ? $row->generate_hybrid->hybrid : [];

            $row->slider_video_data = isset($row->slider_video_data) ? json_decode($row->slider_video_data) : null;
            $row->sliderdata = isset($row->slider_video_data->slider) ? $row->slider_video_data->slider : [];

            $row->vision_data = isset($row->vision_data) ? json_decode($row->vision_data) : null;
            $row->visiondata = isset($row->vision_data->vision) ? $row->vision_data->vision : [];

           //  dd($row->content->prompts);
            /* if (is_array($row->content)) { */
                $row->promptsdata = isset($row->content->prompts) ? $row->content->prompts : [];
               // dd($row->promptsdata);
                $row->activitiesdata = isset($row->content->activities) ? $row->content->activities : [];
                $row->AddOwnactivitiesdata = isset($row->content->add_own_activities_prompts) ? $row->content->add_own_activities_prompts : [];
           /*  } else {
                $row->promptsdata = [];
                $row->activitiesdata = [];
            } */
            return $row;
        })->first();   
    // dd($aimoduledata);
    return view('aimodules.edit', compact('aimoduledata','program_list','course_list'));
  // dd($aimoduledata);
}
public function aiModulesUpdate(Request $request)
{ 
   // dd($request->hybrid);

        $updateaimodule = Aimodules::where('id', $request->aimodule_id)->first();
        $rules = [
            'module_name' => 'required|string',
            'grades' => 'required',
            'course' => 'required',
            'identifier' => 'required|string',
            'content' => 'required|array',
            'content.module_page_title' => 'required|string',
            'content.module_page_overview' => 'required|string',
            'content.video_url' => 'required|string',
            'content.prompts' => 'required|array',
            'content.prompts.*.prompts' => 'required|string',
            'content.prompts.*.response' => 'required|string',
           // 'content.prompts.*.file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'content.activities' => 'required|array',
            'content.activities.*.activities_description' => 'required|string',
            'content.activities.*.website_url' => 'required|string',
            //'content.activities.*.prompt_screenshot' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            'content.add_own_activities_prompts' => 'required|array',
            'content.add_own_activities_prompts.*.add_placeholder_text' => 'required|string',
            'content.add_own_activities_prompts.*.add_ai_toll_url' => 'required|string',
            'content.add_own_activities_prompts.*.toll_name' => 'required|string',
        ];
        if ($request->file('module_thumbnail')) {
            $rules['module_thumbnail'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
        }
        if ($request->has('content.prompts') && is_array($request->get('content.prompts'))) {
            foreach ($request->get('content.prompts') as $key => $value) {
                $fileKey = "content.prompts.$key.file";
                if ($request->hasFile($fileKey)) {
                    $rules[$fileKey] = 'required|image|mimes:jpeg,png,jpg,gif,mp3,mp4|max:2048';
                }
            }
        }
        if ($request->has('content.activities') && is_array($request->get('content.activities'))) {
            foreach ($request->get('content.activities') as $key => $value) {
                $fileKey = "content.activities.$key.prompt_screenshot";
                if ($request->hasFile($fileKey)) {
                    $rules[$fileKey] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
                }
            }
        }
        $validator = Validator::make($request->all(), $rules);
        if($validator->passes()){
            if($image = $request->file('module_thumbnail')) {
                $destinationPath = 'uploads/aimodule/';
                $originalname = $image->hashName();
                $imageName = "aimodule_thumbnail_" . date('Ymd') . '_' . $originalname;
                $image->move($destinationPath, $imageName);
                // REMOVE OLD IMAGE
                if ($request->old_module_thumbnail) {
                    if (file_exists($destinationPath . $request->old_module_thumbnail)) {
                        unlink($destinationPath . $request->old_module_thumbnail);
                    }
                }

             }else {
                $imageName = $request->old_module_thumbnail;
            }

            $prompts = [];
            foreach ($request->content['prompts'] as $promptData) {
                $file = $promptData['file'];

                if ($file != 'undefined') {
                    $destinationPath = 'uploads/aimodule/';
                    $originalName = $file->getClientOriginalName();
                    $imageNameprompts = "prompt_" . date('Ymd') . '_' . $originalName;
                    $file->move($destinationPath, $imageNameprompts);
                    $promptData['file'] = $imageNameprompts;
                    $fileData = [
                        'originalName' => "prompt_" . date('Ymd') . '_' . $file->getClientOriginalName(),
                        'mimeType' => $file->getClientMimeType(),
                        // 'size' => $file->getSize(),
                        'path' => "uploads/aimodule/prompt_" . date('Ymd') . '_' . $file->getClientOriginalName(), // Remove the comma here
                        'extension' => $file->getClientOriginalExtension(),
                    ];
                    $promptData['file_all_data'] = $fileData;
                     // REMOVE OLD IMAGE
                    if ($promptData['old_answers_file']) {
                        if (file_exists($destinationPath . $promptData['old_answers_file'])) {
                            unlink($destinationPath . $promptData['old_answers_file']);
                        }
                    }
                
                }else{
                    $promptData['file'] = $promptData['old_answers_file'];
                    $promptData['file_all_data'] = json_decode($promptData['old_music_data_file']);
                    
                }

               

                $prompts[] = $promptData;
            }
           // dd($prompts);
            $activities = [];
            foreach ($request->content['activities'] as $activityData) {
                $prompt_screenshot = $activityData['prompt_screenshot'];
                if ($prompt_screenshot != 'undefined') {
                    $destinationPath = 'uploads/aimodule/';
                    $originalName = $prompt_screenshot->getClientOriginalName();
                    $imageNameactivity = "activity_" . date('Ymd') . '_' . $originalName;
                    $prompt_screenshot->move($destinationPath, $imageNameactivity);
                    $activityData['prompt_screenshot'] = $imageNameactivity;

                    // REMOVE OLD IMAGE
                    if ($activityData['old_prompt_screenshot']) {
                        if (file_exists($destinationPath . $activityData['old_prompt_screenshot'])) {
                            unlink($destinationPath . $activityData['old_prompt_screenshot']);
                        }
                    }
                }else{
                    $activityData['prompt_screenshot'] = $activityData['old_prompt_screenshot'];
                }
                $activities[] = $activityData;
            }


/* Hybrid */
$hybridData = $request->hybrid;

if (isset($hybridData['generate_hybrid'])) {
    foreach ($hybridData['generate_hybrid'] as $key => $hybridImage) {
        //dd($hybridImage['update_animal_one']);
        $hybrid_animal_one = isset($hybridImage['animal_one']) ? $hybridImage['animal_one'] : null;
       // dd($hybrid_animal_one);
        if ($hybrid_animal_one !== 'undefined') {
            $destinationPath = 'uploads/hybrid/';
            $originalName = $hybrid_animal_one->getClientOriginalName();
            $pdfName = "hybrid_" . date('Ymd') . '_' . $originalName;
            $hybrid_animal_one->move($destinationPath, $pdfName);
            $hybridData['generate_hybrid'][$key]['animal_one_new'] = $pdfName;
        } else {
            $hybridData['generate_hybrid'][$key]['animal_one_new'] = $hybridImage['update_animal_one'];
        }


        $hybrid_animal_second = isset($hybridImage['animal_second']) ? $hybridImage['animal_second'] : null;
        if ($hybrid_animal_second !== 'undefined') {
            $destinationPath = 'uploads/hybrid/';
            $originalName = $hybrid_animal_second->getClientOriginalName();
            $pdfName = "hybrid_" . date('Ymd') . '_' . $originalName;
            $hybrid_animal_second->move($destinationPath, $pdfName);
            $hybridData['generate_hybrid'][$key]['animal_second_new'] = $pdfName;
        } else {
            $hybridData['generate_hybrid'][$key]['animal_second_new'] = $hybridImage['update_animal_second'];
        }


        $hybrid_result = isset($hybridImage['result']) ? $hybridImage['result'] : null;
        if ($hybrid_result !== 'undefined') {
            $destinationPath = 'uploads/hybrid/';
            $originalName = $hybrid_result->getClientOriginalName();
            $pdfName = "hybrid_" . date('Ymd') . '_' . $originalName;
            $hybrid_result->move($destinationPath, $pdfName);
            $hybridData['generate_hybrid'][$key]['result_new'] = $pdfName;
        } else {
            $hybridData['generate_hybrid'][$key]['result_new'] = $hybridImage['update_result'];
        }
    }
}
$request->merge(['hybrid' => $hybridData]);

$sliderData = $request->slider;
if (isset($sliderData['generate_slider'])) {
    foreach ($sliderData['generate_slider'] as $key => $hybridImage) {
        //dd($hybridImage);
        $hybrid_animal_one = isset($hybridImage['slider_image']) ? $hybridImage['slider_image'] : null;
        if ($hybrid_animal_one !== 'undefined') {
            $destinationPath = 'uploads/hybrid/';
            $originalName = $hybrid_animal_one->getClientOriginalName();
            $pdfName = "slider_" . date('Ymd') . '_' . $originalName;
            $hybrid_animal_one->move($destinationPath, $pdfName);
            $sliderData['generate_slider'][$key]['slider_image_new'] = $pdfName;
        } else {
            $sliderData['generate_slider'][$key]['slider_image_new'] = $hybridImage['update_slider_image'];
        }
    }
}
$request->merge(['slider' => $sliderData]);


/* Vision */
$visionData = $request->vision;
if (isset($visionData['generate_vision'])) {
    foreach ($visionData['generate_vision'] as $key => $hybridImage) {
        $vision_image = isset($hybridImage['vision_image']) ? $hybridImage['vision_image'] : null;
        $vision_music = isset($hybridImage['vision_music']) ? $hybridImage['vision_music'] : null;
        if ($vision_image !== 'undefined') {
            $destinationPath = 'uploads/vision/';
            $originalName = $vision_image->getClientOriginalName();
            $pdfName = "vision_" . date('Ymd') . '_' . $originalName;
            $vision_image->move($destinationPath, $pdfName);
            $visionData['generate_vision'][$key]['vision_image_new'] = $pdfName;
        } else {
            $visionData['generate_vision'][$key]['vision_image_new'] = $hybridImage['update_vision_image'];
        }
        if ($vision_music !== 'undefined') {
            $destinationPath = 'uploads/vision/';
            $originalName = $vision_music->getClientOriginalName();
            $pdfName = "vision_" . date('Ymd') . '_' . $originalName;
            $vision_music->move($destinationPath, $pdfName);
            $visionData['generate_vision'][$key]['vision_music_new'] = $pdfName;
        } else {
            $visionData['generate_vision'][$key]['vision_music_new'] = $hybridImage['update_vision_music'];
        }
    }
}
$request->merge(['vision' => $visionData]);





        $data = $request->all();
         $updateaimodule->display_name = $data['module_name'];
         $updateaimodule->thumbnail = $imageName;
         //$updateaimodule->slider_video_one = $data['video_one'];
        // $updateaimodule->slider_video_second = $data['video_second'];
        // $updateaimodule->slider_video_third = $data['video_third'];
         $updateaimodule->type = $data['identifier'];
         $updateaimodule->grade_id = $data['grades'];
         $updateaimodule->course_id = $data['course'];
         $updateaimodule->status = 1;
         $updateaimodule->generate_hybrid = json_encode([
            'hybrid' => $request->hybrid['generate_hybrid']
            ?? []]);
        $updateaimodule->slider_video_data = json_encode([
                'slider' => $request->slider['generate_slider']
                ?? []]);

        $updateaimodule->vision_data = json_encode([
            'vision' => $request->vision['generate_vision']
            ?? []]);
        $updateaimodule->content = json_encode([
         'module_page_title' => $request->content['module_page_title'],
         'module_page_overview' => $request->content['module_page_overview'],
         'video_url' => $request->content['video_url'],
         'prompts' => $prompts, 'activities' => $activities,
         'add_own_activities_prompts' => $request->content['add_own_activities_prompts']
         ?? []]);
         $updateaimodule->save();
        
         return response()->json([
             'status' => true,
             'message' => "Aimodule created successfully",
         ], 201);
     }else{
         return response()->json([
             'status' => false,
             'errors' => $validator->errors(),
          ]);
     
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

    public function destroy(Request $request)
    {
        $courseId = $request->input('course');
        $aimodule_id = $request->input('aimodule_id');
        if(!empty($aimodule_id)){
          // $aimodule_identy = Identifier::where('id', $aimodule_id)->first();
          // $aimodule = Aimodules::where('identifier_id', $aimodule_id)->first();
           $aimodule = Aimodules::where('id', $aimodule_id)->first();
           //$aimodule_identy->delete();
           $aimodule->delete();
            echo "removed";
        }else{
            echo "AI Module NoT Found.";
        }
    }
    public function feedbackPage()
    { 
        return view('aimodules.emotional-check');
    }

    public function change_aimodules_status(Request $request)
    {
        $aimodulesId = $request->aimodules_id;
        //dd($aimodulesId);
        $status = ($request->status == 1) ? 0 : 1;
        Identifier::where('id', $aimodulesId)->update(['aimodule_status' => $status]);
        echo ($status == 1) ? 'Demo' : 'Paid';
    }

       public function generateSpeech(Request $request)
      {
            $text = $request->input('ask_question_chatGpt');
            $apiKey = "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";
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/audio/speech', [
                'input' => $text,
                'model' => 'tts-1',
                'voice' => 'onyx', 
            ]);
            if ($response->successful()) {
                $audioData = $response->getBody()->getContents();
                $headers = [
                    'Content-Type' => 'audio/mpeg',
                    'Content-Disposition' => 'inline; filename="speech.mp3"',
                ];
                return response($audioData)->withHeaders($headers);
            } else {
                return response()->json(['error' => 'Failed to generate speech'], $response->status());
            }
          
      }  
      public function ownpromptsSpeech(Request $request)
      {
            $text = $request->input('ask_question_chatGpt');
            $apiKey = "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";
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/audio/speech', [
                'input' => $text,
                'model' => 'tts-1',
                'voice' => 'onyx', 
            ]);
            /* $audioData = $response->getBody()->getContents();

            $headers = [
                'Content-Type' => 'audio/mpeg',
                'Content-Disposition' => 'inline; filename="speech.mp3"',
            ];
            return response($audioData)->withHeaders($headers); */
            if ($response->successful()) {
                $audioData = $response->getBody()->getContents();
                $headers = [
                    'Content-Type' => 'audio/mpeg',
                    'Content-Disposition' => 'inline; filename="speech.mp3"',
                ];
                return response($audioData)->withHeaders($headers);
            } else {
                return response()->json(['error' => 'Failed to generate speech'], $response->status());
            }
           
      }  


public function generateText(Request $request)
{
    $audioFileData = json_decode($request->input('audioPlayer'));

    if (!$audioFileData || !isset($audioFileData->path)) {
        return response()->json(['error' => 'Audio file path not provided'], 400);
    }
    $audioFilePath = $audioFileData->path;
    $apiKey = "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";

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $apiKey,
    ])->attach('file', file_get_contents($audioFilePath), 'audio.mp3')
      ->post('https://api.openai.com/v1/audio/transcriptions', [
          'model' => 'whisper-1',
    ]);

    if ($response->successful()) {
        $translatedAudio = $response->getBody()->getContents();
        $data = json_decode($translatedAudio);
        $text = $data->text;
        return response()->json(['translated_audio' => $text]);
    } else {
        return response()->json(['error' => 'Failed to translate audio'], $response->status());
    }
}




 public function generateSpeechText(Request $request)
{
    //dd($request->ask_speech_to_text);
    $audioFilePath = $request->file('ask_speech_to_text');
    if (!$audioFilePath) {
        return response()->json(['error' => 'Audio file path not provided'], 400);
    }
    if (!file_exists($audioFilePath)) {
        return response()->json(['error' => 'Audio file not found'], 404);
    }
    $apiKey = "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";
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $apiKey,
    ])->attach('file', file_get_contents($audioFilePath), 'audio.mp3')
      ->post('https://api.openai.com/v1/audio/transcriptions', [
          'model' => 'whisper-1',
    ]);
    if ($response->successful()) {
        $translatedAudio = $response->getBody()->getContents();
        $data = json_decode($translatedAudio); 
        $text = $data->text; 
        return response()->json(['translated_audio' => $text]);
    } else {
        return response()->json(['error' => 'Failed to translate audio'], $response->status());
    }

}

public function secondSpeechText(Request $request)
{
   // dd($request);
    $audioFilePath = $request->file('ask_speech_to_text');
    if (!$audioFilePath) {
        return response()->json(['error' => 'Audio file path not provided'], 400);
    }
    if (!file_exists($audioFilePath)) {
        return response()->json(['error' => 'Audio file not found'], 404);
    }
    $apiKey = "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";
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $apiKey,
    ])->attach('file', file_get_contents($audioFilePath), 'audio.mp3')
      ->post('https://api.openai.com/v1/audio/transcriptions', [
          'model' => 'whisper-1',
    ]);
    if ($response->successful()) {
        $translatedAudio = $response->getBody()->getContents();
        $data = json_decode($translatedAudio); 
        $text = $data->text; 
        return response()->json(['translated_audio' => $text]);
    } else {
        return response()->json(['error' => 'Failed to translate audio'], $response->status());
    }

}



//use GuzzleHttp\Exception\ClientException;

/* public function generateMusic(Request $request)
{
    $text = $request->input('ask_question_chatGpt'); 
    
    $client = new \GuzzleHttp\Client();
    $apiKey = "le_d658a6a2_yP8Bbuc87HvZHhQ0KR0S5Z45";
    $curl = curl_init();
    curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.workflows.tryleap.ai/v1/runs",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode([
        'workflow_id' => 'wkf_6PjaoVrAJOvNmX',
        'input' => [
            'music_prompt' => $text
        ]
    ]),
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "X-Api-Key: le_d658a6a2_yP8Bbuc87HvZHhQ0KR0S5Z45"
    ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);
    if ($err) {
        return response()->json(['error' => "cURL Error #:" . $err], 500);
    } else {
        return response()->json($response);
    }
} */
/* public function generateMusic(Request $request)
{
    $text = $request->input('ask_question_chatGpt'); 
    
    $client = new \GuzzleHttp\Client();
    $apiKey = "le_34211aed_Y9JqUWuTRlW6ce0DHj0OoJbP";
    $curl = curl_init();
    curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.workflows.tryleap.ai/v1/runs",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode([
        'workflow_id' => 'wkf_CB2ncUzmrLfpEh',
        'input' => [
            'music_prompt' => $text
        ]
    ]),
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "X-Api-Key: le_34211aed_Y9JqUWuTRlW6ce0DHj0OoJbP"
    ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);
    if ($err) {
        echo "cURL Error #:" . $err;
      } else {
        echo $response;
      }
} */


public function generateMusic(Request $request)
{
    $text = $request->input('ask_question_chatGpt');
   

    $ascii_values = array_map('ord', str_split($text));
   
    $ascii_text = implode(' ', $ascii_values);

     $response = Http::withHeaders([
        'X-RapidAPI-Host' => 'text-to-music-api.p.rapidapi.com',
        'X-RapidAPI-Key' => '8b735a88a0msh542bed55e01ba47p1ff321jsn5ec3ea999b1e',
    ])->get('https://text-to-music-api.p.rapidapi.com/playpianomp3', [
        'ascii_text' => $ascii_text,
    ]);
     if ($response->failed()) {
        return response()->json(['error' => 'Failed to generate music'], $response->status());
    }
    $mp3Data = $response->body();
    return response($mp3Data)->header('Content-Type', 'audio/mpeg'); 
}








  public function generateMusicFile(Request $request)
{
    $music_id = $request->input('music_genration_id');

    // Loop until the status becomes "completed"
    do {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.workflows.tryleap.ai/v1/runs/{$music_id}/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "X-Api-Key: le_34211aed_Y9JqUWuTRlW6ce0DHj0OoJbP"
            ],
        ]);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
    
        if ($err) {
            echo "cURL Error #:" . $err;
            break; 
        }
        $response_data = json_decode($response);
        if ($response_data->status == 'completed') {
            return $response;
        } else {
            sleep(5);
        }
    } while ($response_data->status == 'running');
}  



public function generateMusxcicFile(Request $request)
{
    /* $music_id = $request->input('music_generation_id'); 
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.workflows.tryleap.ai/v1/runs/rnp_bSaclivi2z4TLemD6q/{$music_id}/",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "X-Api-Key: le_d658a6a2_yP8Bbuc87HvZHhQ0KR0S5Z45"
        ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    
    if ($err) {
        return response()->json(['error' => "cURL Error #:" . $err], 500);
    } else {
        return response()->json($response);
    } */
      /* if ($response->successful()) {
        $translatedAudio = $response->getBody()->getContents();
        $data = json_decode($translatedAudio); 
        $text = $data->text; 
        return response()->json(['translated_audio' => $text]);
    } else {
        return response()->json(['error' => 'Failed to translate audio'], $response->status());
    } */
}

/* photo to video */
      public function generatePhototVideo(Request $request)
      {
        $imageUrl = $request->input('ask_question_chatGpt');
       // $image_url =   'https://st4.depositphotos.com/1662991/31514/i/450/depositphotos_315146284-stock-photo-portrait-handsome-smiling-latin-man.jpg' ; 
        $image_url =    url('uploads/aimodule/' . $imageUrl); 
     
        $response = Http::withHeaders([
            'X-RapidAPI-Host' => 'face-animer.p.rapidapi.com',
            'X-RapidAPI-Key' => '8b735a88a0msh542bed55e01ba47p1ff321jsn5ec3e'
        ])->get('https://face-animer.p.rapidapi.com/webFaceDriven/submitTaskByUrl', [
            'imageUrl' => $image_url,
            'templateId' => "12"
        ]);
        if ($response->failed()) {
            return response()->json(['error' => 'Failed to fetch data'], $response->status());
        }
        return $response->body();
        
      }

      public function OutputPhototVideo(Request $request)
      {
          $taskId = $request->input('taskId');
          $response = Http::withHeaders([
              'X-RapidAPI-Host' => 'face-animer.p.rapidapi.com',
              'X-RapidAPI-Key' => '8b735a88a0msh542bed55e01ba47p1ff321jsn5ec3e'
          ])->get('https://face-animer.p.rapidapi.com/webFaceDriven/getTaskInfo', [
              'taskId' => $taskId
          ]);
          if ($response->failed()) {
              return response()->json(['error' => 'Failed to fetch data'], $response->status());
          }
          return $response->body();
      }

        
      /* public function generatePromptPhotoVideo(Request $request)
      {
        dd($request);
        $imageUrl = $request->input('ask_question_chatGpt');
        $image_url =   'https://st4.depositphotos.com/1662991/31514/i/450/depositphotos_315146284-stock-photo-portrait-handsome-smiling-latin-man.jpg' ; 
        $response = Http::withHeaders([
            'X-RapidAPI-Host' => 'face-animer.p.rapidapi.com',
            'X-RapidAPI-Key' => 'cce4cb558fmsh63555ea6a9ae15bp1322b8jsnad7dc80b3547'
        ])->get('https://face-animer.p.rapidapi.com/webFaceDriven/submitTaskByUrl', [
            'imageUrl' => $image_url,
            'templateId' => "12"
        ]);
        if ($response->failed()) {
            return response()->json(['error' => 'Failed to fetch data'], $response->status());
        }
        return $response->body();
        
      } */


      public function generatePromptPhotoVideo(Request $request)
        {
            
            $imageFile = $request->file('ask_photo_video');
            $imagePath = $imageFile->storeAs('temp', $imageFile->getClientOriginalName());
          //  $imageUrl =   'https://st4.depositphotos.com/1662991/31514/i/450/depositphotos_315146284-stock-photo-portrait-handsome-smiling-latin-man.jpg' ; 
            $imageUrl = url('storage/' . $imagePath);
           // dd($imageUrl);
            $response = Http::withHeaders([
                'X-RapidAPI-Host' => 'face-animer.p.rapidapi.com',
                'X-RapidAPI-Key' => '8b735a88a0msh542bed55e01ba47p1ff321jsn5ec3e'
            ])->get('https://face-animer.p.rapidapi.com/webFaceDriven/submitTaskByUrl', [
                'imageUrl' => $imageUrl,
                'templateId' => "12"
            ]);
            if ($response->failed()) {
                return response()->json(['error' => 'Failed to fetch data in api'], $response->status());
            }
            return $response->body();
        }

      public function OutputPromptPhototVideo(Request $request)
      {
          $taskId = $request->input('taskId');
          $response = Http::withHeaders([
              'X-RapidAPI-Host' => 'face-animer.p.rapidapi.com',
              'X-RapidAPI-Key' => '8b735a88a0msh542bed55e01ba47p1ff321jsn5ec3e'
          ])->get('https://face-animer.p.rapidapi.com/webFaceDriven/getTaskInfo', [
              'taskId' => $taskId
          ]);
          if ($response->failed()) {
              return response()->json(['error' => 'Failed to fetch data'], $response->status());
          }
          return $response->body();
      }

      /* Avatar  */
     /*  public function generateAvatar(Request $request)
      {
        $assetType = $request->input('ask_question_chatGpt');
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://doppelme-avatars.p.rapidapi.com/assets/1101/$assetType", // Use the variable for the asset type
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "X-RapidAPI-Host: doppelme-avatars.p.rapidapi.com",
                "X-RapidAPI-Key: e763113a27mshd2fe407fd09874cp11a08djsn6492659ebb7b"
            ],
        ]);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
      } */

      /* public function generateAvatar(Request $request)
      {
        $imageFile = $request->file('ask_photo_video');
        $assetType = $request->input('ask_question_chatGpt');
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://phototoanime1.p.rapidapi.com/photo-to-anime",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "-----011000010111000001101001\r\nContent-Disposition: form-data; name=\"image\"\r\n\r\n\r\n-----011000010111000001101001\r\nContent-Disposition: form-data; name=\"url\"\r\n\r\nhttps://openmediadata.s3.eu-west-3.amazonaws.com/face.jpg\r\n-----011000010111000001101001\r\nContent-Disposition: form-data; name=\"style\"\r\n\r\nface2paint\r\n-----011000010111000001101001--\r\n\r\n",
            CURLOPT_HTTPHEADER => [
                "X-RapidAPI-Host: phototoanime1.p.rapidapi.com",
                "X-RapidAPI-Key: 8b735a88a0msh542bed55e01ba47p1ff321jsn5ec3ea999b1e",
                "content-type: multipart/form-data; boundary=---011000010111000001101001"
            ],
        ]);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }

      } */


      public function generateAvatar(Request $request)
      {
          $imageFile = $request->file('ask_photo_video');
      
          if (!$imageFile) {
              return response()->json(['error' => 'No image file uploaded'], 400);
          }
      
          $imagePath = $imageFile->getPathname();
          $imageMimeType = $imageFile->getMimeType();
          $imageName = $imageFile->getClientOriginalName();
      
          $assetType = $request->input('ask_question_chatGpt');
      
          $curlFile = new \CURLFile($imagePath, $imageMimeType, $imageName);
      
          $postData = [
              'image' => $curlFile,
              'style' => 'face2paint'
          ];
      
          $curl = curl_init();
          curl_setopt_array($curl, [
              CURLOPT_URL => "https://phototoanime1.p.rapidapi.com/photo-to-anime",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => $postData,
              CURLOPT_HTTPHEADER => [
                  "X-RapidAPI-Host: phototoanime1.p.rapidapi.com",
                  "X-RapidAPI-Key: 8b735a88a0msh542bed55e01ba47p1ff321jsn5ec3ea999b1e",
                  "Content-Type: multipart/form-data"
              ],
          ]);
      
          $response = curl_exec($curl);
          $err = curl_error($curl);
      
          curl_close($curl);
      
          if ($err) {
              return response()->json(['error' => $err], 500);
          } else {
              return response()->json(['response' => json_decode($response, true)]);
          }
      }
      


      /* bird-classifier */
      

       public function generateBirdClassifier(Request $request)
{
    if ($request->hasFile('ask_photo_bird')) {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://bird-classifier.p.rapidapi.com/BirdClassifier/prediction?results=5",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => [
                'image' => new \CURLFile($request->file('ask_photo_bird')->getPathname(), $request->file('ask_photo_bird')->getClientMimeType(), $request->file('ask_photo_bird')->getClientOriginalName())
            ],
            CURLOPT_HTTPHEADER => [
                "X-RapidAPI-Host: bird-classifier.p.rapidapi.com",
                "X-RapidAPI-Key: 8b735a88a0msh542bed55e01ba47p1ff321jsn5ec3ea999b1e",
                "content-type: multipart/form-data"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    } else {
        echo "No file uploaded.";
    }
} 
public function generateBirdClassifierSecond(Request $request)
{
    if ($request->hasFile('answer_second_photo_bird')) {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://bird-classifier.p.rapidapi.com/BirdClassifier/prediction?results=5",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => [
                'image' => new \CURLFile($request->file('answer_second_photo_bird')->getPathname(), $request->file('answer_second_photo_bird')->getClientMimeType(), $request->file('answer_second_photo_bird')->getClientOriginalName())
            ],
            CURLOPT_HTTPHEADER => [
                "X-RapidAPI-Host: bird-classifier.p.rapidapi.com",
                "X-RapidAPI-Key: 8b735a88a0msh542bed55e01ba47p1ff321jsn5ec3ea999b1e",
                "content-type: multipart/form-data"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    } else {
        echo "No file uploaded.";
    }
} 

public function generatePlantRecognizer(Request $request)
{
    // Check if the image file is present in the request
    if ($request->hasFile('ask_photo_video')) {
        // Get the file content and encode it as base64
        $imageData = base64_encode(file_get_contents($request->file('ask_photo_video')->getPathname()));
        //dd($imageData);
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://plant-recognizer.p.rapidapi.com/identify_image",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'file' => $imageData
            ]),
            CURLOPT_HTTPHEADER => [
                "X-RapidAPI-Host: plant-recognizer.p.rapidapi.com",
                "X-RapidAPI-Key: 8b735a88a0msh542bed55e01ba47p1ff321jsn5ec3ea999b1e",
                "content-type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    } else {
        echo "No file uploaded.";
    }
}


public function generatePromptPlantRecognizer(Request $request)
{
   // dd($request->ask_question_chatGpt);
    // Check if the image file is present in the request
   /*  if ($request->hasFile('ask_question_chatGpt')) { */
        // Get the file content and encode it as base64
      //  dd($request);
        $imageData = base64_encode("https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQl6zNAiEdeV5Cv1c94M9_nB-vC53AKhl4Eo7-SqcPBvZ6u9cZv");
        //dd($imageData);
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://plant-recognizer.p.rapidapi.com/identify_image",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'file' => $imageData
            ]),
            CURLOPT_HTTPHEADER => [
                "X-RapidAPI-Host: plant-recognizer.p.rapidapi.com",
                "X-RapidAPI-Key: 8b735a88a0msh542bed55e01ba47p1ff321jsn5ec3ea999b1e",
                "content-type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    /* } else {
        echo "No file uploaded.";
    } */
}




public function analyzeImage(Request $request)
    {
        // Path to your image
        $imagePath = storage_path('app/public/abc.png.jpeg');
       // $imagePath = 'https://th.bing.com/th/id/OIP.RczLHpGhBtKxRuaNCKv_KQAAAA?rs=1&pid=ImgDetMain';
        //$imagePath = 'https://i2.wp.com/www.infinitycompliance.in/wp-content/uploads/2020/10/Advantages-of-One-Person-Company-Infinity-Compliance-scaled.jpg?resize=1436%2C2048&ssl=1';
        $imagePath = 'https://static.vecteezy.com/system/resources/previews/021/084/758/original/football-player-holds-the-ball-and-he-is-ready-to-play-with-soccer-png.png';
        
      
        $apiKey = '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';

        // Function to encode the image
       $base64Image = base64_encode(file_get_contents($imagePath));

       // $base64Image = base64_encode($imagePath);
        //dd($base64Image);
        $client = new Client();
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $apiKey,
        ];

        $payload = [
            'model' => 'gpt-4o',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => "What’s in this image?"
                        ],
                        [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => 'data:image/jpeg;base64,' . $base64Image
                            ]
                        ]
                    ]
                ]
            ],
            'max_tokens' => 300
        ];

        try {
            $response = $client->post('https://api.openai.com/v1/chat/completions', [
                'headers' => $headers,
                'json' => $payload
            ]);

            $responseBody = json_decode($response->getBody(), true);
            return response()->json($responseBody);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

/* New Start */
public function AimoduleList(Request $request)
    {
        if ($request->ajax()) {
            /* $mainQuery = DB::table('ai_identifiers')->whereNull('ai_identifiers.deleted_at')
                ->select([
                    'ai_identifiers.id',
                    'ai_identifiers.display_name',
                    'ai_identifiers.module_page_title',
                    'ai_identifiers.module_page_overview',
                    'ai_identifiers.video_url',
                    'ai_identifiers.thumbnail',
                    'ai_identifiers.type',
                    'ai_identifiers.grade_id',
                    'ai_identifiers.course_id',
                    'ai_identifiers.status',
                    'ai_identifiers.aimodule_status',
                    'master_course.course_name',
                    'aimodules.content',
                    'aimodules.id as aimodule_id',
                ])
                ->leftJoin('master_course', 'master_course.id', 'ai_identifiers.course_id')
                ->leftJoin('aimodules', 'aimodules.identifier_id', 'ai_identifiers.id')
                ->orderBy('aimodules.id','desc');
              
                $aimodules = $mainQuery->get()->map(function ($row) {
                    $row->content = isset($row->content) ? json_decode($row->content) : null;
                    if (is_array($row->content)) {
                        $row->Promptsdata = isset($row->content[0]->Prompts) ? $row->content[0]->Prompts : [];
                        $row->Activitiesdata = isset($row->content[0]->Activities) ? $row->content[0]->Activities : [];
                    } else {
                        $row->Promptsdata = [];
                        $row->Activitiesdata = [];
                    }
                    return $row;
                }); */

                $mainQuery = DB::table('aimodules as a')
    ->whereNull('a.deleted_at')
    ->select([
        'a.user_id',
        'a.display_name as aimodule_display_name',
        'a.aimodule_status as aimodule_status',
        'a.content',
        'a.thumbnail as aimodule_thumbnail',
        'a.id as aimodule_id',
        'ai.display_name as identifier_display_name',
        'ai.module_page_title',
        'ai.module_page_overview',
        'ai.video_url',
        'ai.thumbnail as identifier_thumbnail',
        'ai.type',
        'ai.grade_id',
        'ai.course_id',
        'ai.status as identifier_status',
        'ai.aimodule_status as identifier_aimodule_status',
        'mc.course_name',
    ])
    ->leftJoin('ai_identifiers as ai', 'a.identifier_id', '=', 'ai.id')
    ->leftJoin('master_course as mc', 'mc.id', '=', 'ai.course_id')
    ->orderBy('a.id', 'desc')
    ->get();

$data = $mainQuery;

            // dd($data);
             return Datatables::of($data)
             ->addColumn('index', function ($row) {
                 static $index = 0;
                 return ++$index;
             })

             ->editColumn('thumbnail', function ($row) {
                $thumbnailUrl = url('uploads/Identifier') . '/' . (!empty($row->identifier_thumbnail) ? $row->identifier_thumbnail : 'no_image.png');
                return '<img src="' . $thumbnailUrl . '" width="32" height="32" class="bg-light my-n1" >';
            })
            ->editColumn('display_name', function ($row) {
                return $row->identifier_display_name;
            })

            ->editColumn('course_name', function ($row) {
                return $row->course_name;
            })
            /* ->editColumn('type', function ($row) {
                return $row->type;
            }) */
            ->editColumn('type', function ($row) {
                $formattedType = str_replace('_', ' ', $row->type); 
                $formattedType = ucfirst($formattedType); 
                return $formattedType;
            })
            /* ->editColumn('status', function ($row) {
                $statusClass = $row->status == 1 ? 'success' : 'danger';
                $statusText = $row->status == 1 ? 'Active' : 'Inactive';
                return '<a href="javascript:void(0);" class="change_status text-white badge bg-' . $statusClass . '"
                            id="status_' . $row->id . '" data-id="' . $row->id . '" data-status="' . $row->status . '">'
                            . $statusText . '</a>';
            }) */
            /* ->addColumn('aimodule_status', function ($row) {
                $statusClass = $row->aimodule_status == 1 ? 'success' : 'danger';
                $statusText = $row->aimodule_status == 1 ? 'Demo' : 'Paid';
                return '<a href="javascript:void(0);" class="change_aimodules_status text-white badge bg-' . $statusClass . '"
                            id="aimodule_status_' . $row->id . '" data-aimodule-id="' . $row->id . '"
                            data-aimodule-status="' . $row->aimodule_status . '">' . $statusText . '</a>';
            }) */    
            ->addColumn('action', function ($row) {
                $editLink = '<a href="' . route('aimodule-update', ['aimodule_id' => $row->aimodule_id , 'type' => $row->type]) . '"
                                class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Edit</a>';
                 $deleteLink = '<a href="javascript:void(0);" data-id="' . $row->aimodule_id . '"
                                class="waves-effect waves-light remove_school_data btn btn-sm btn-outline btn-danger mb-5">Delete</a>'; 
                return $editLink  . ' ' . $deleteLink;
            })
             ->rawColumns(['index','thumbnail', 'display_name', 'course_name', 'type', 'status', 'aimodule_status', 'action'])
             ->toJson();
         }
        return view('aimodules.list');
    }


    public function createModule(Request $request)
    {
        $identifier_id   = $request->id;
        if($request->type == 'chatgpt' || $request->type == 'dalle'){
            if($request->type == 'chatgpt'){
                $formattedType = 'ChatGPT';
            }else{
                $formattedType = 'Dall-E';
            }
            return view('aimodules.chatgpt-add', compact('identifier_id', 'formattedType'));
        }
        if($request->type == 'fauna_and_flora'){
            $formattedType = 'Fauna & Flora';
            return view('aimodules.fauna-flora-add', compact('identifier_id' ,'formattedType'));
        }
        if($request->type == 'text_to_music'){
            $formattedType = 'Text to Music';
            return view('aimodules.text-to-music-add', compact('identifier_id' ,'formattedType'));
        }
        if($request->type == 'text_to_speech'){
            $formattedType = 'Text to Speech';
            return view('aimodules.text-to-speech-add', compact('identifier_id' ,'formattedType'));
        }
        if($request->type == 'speech_to_text'){
            $formattedType = 'Speech to Text';
            return view('aimodules.speech_to_text-add', compact('identifier_id' ,'formattedType'));
        }
        if($request->type == 'image_to_narration'){
            $formattedType = 'Image to Narration';
            return view('aimodules.image-to-narration-add', compact('identifier_id' ,'formattedType'));
        }
        if($request->type == 'photo_to_video'){
            $formattedType = 'Photo to Video';
            return view('aimodules.photo-to-video-add', compact('identifier_id' ,'formattedType'));
        }
        if($request->type == 'generate_avatar'){
            $formattedType = 'Avatar';
            return view('aimodules.avatar-add', compact('identifier_id' ,'formattedType'));
        }
    }
    
    public function editModule(Request $request)
    {
    
        $aimodule_id = $request->input('aimodule_id');
        $type = $request->input('type');
        $mainQuery = DB::table('aimodules')
                    ->whereNull('aimodules.deleted_at')
            ->select([
                'aimodules.id',
                'aimodules.content',
                'aimodules.generate_hybrid',
                'aimodules.slider_video_data',
                'aimodules.vision_data',
                'aimodules.description',
                'aimodules.own_placeholder',
                'aimodules.own_description',
                'aimodules.hello_there_description',
                'aimodules.vision_data',
                'aimodules.thumbnail',
            ])->where('aimodules.id', $aimodule_id);
                    $aimoduledata = $mainQuery->get()->map(function ($row) {
                    $row->content = isset($row->content) ? json_decode($row->content) : null;
                    $row->promptsdata = isset($row->content->prompts) ? $row->content->prompts : [];
                    $row->activitiesdata = isset($row->content->activities) ? $row->content->activities : [];
                    $row->AddOwnactivitiesdata = isset($row->content->add_own_activities_prompts) ? $row->content->add_own_activities_prompts : [];

                    $row->generate_hybrid = isset($row->generate_hybrid) ? json_decode($row->generate_hybrid) : null;
                    $row->hybridsdata = isset($row->generate_hybrid->hybrid) ? $row->generate_hybrid->hybrid : [];

                    $row->slider_video_data = isset($row->slider_video_data) ? json_decode($row->slider_video_data) : null;
                    $row->sliderdata = isset($row->slider_video_data->slider) ? $row->slider_video_data->slider : [];

                    $row->vision_data = isset($row->vision_data) ? json_decode($row->vision_data) : null;
                    $row->visiondata = isset($row->vision_data->vision) ? $row->vision_data->vision : [];

                return $row;
            })->first();
            if($request->type == 'chatgpt' || $request->type == 'dalle'){
                if($request->type == 'chatgpt'){
                    $formattedType = 'ChatGPT';
                }else{
                    $formattedType = 'Dall-E';
                }
                return view('aimodules.chatgpt-edit', compact('aimoduledata', 'formattedType'));
            }
            if($request->type == 'fauna_and_flora'){
                $formattedType = 'Fauna & Flora';
                return view('aimodules.fauna-flora-edit', compact('aimoduledata', 'formattedType'));
            }
            if($request->type == 'text_to_music'){
                $formattedType = 'Text to Music';
                return view('aimodules.text-to-music-edit', compact('aimoduledata', 'formattedType'));
            }
            if($request->type == 'text_to_speech'){
                $formattedType = 'Text to Speech';
                return view('aimodules.text-to-speech-edit', compact('aimoduledata', 'formattedType'));
            }
            if($request->type == 'speech_to_text'){
                $formattedType = 'Speech to Text';
                return view('aimodules.speech_to_text-edit', compact('aimoduledata', 'formattedType'));
            }
            if($request->type == 'image_to_narration'){
                $formattedType = 'Image to Narration';
                return view('aimodules.image-to-narration-edit', compact('aimoduledata', 'formattedType'));
            }
            if($request->type == 'photo_to_video'){
                $formattedType = 'Photo to Video';
                return view('aimodules.photo-to-video-edit', compact('aimoduledata', 'formattedType'));
            }
            if($request->type == 'generate_avatar'){
                $formattedType = 'Avatar';
                return view('aimodules.avatar-edit', compact('aimoduledata' ,'formattedType'));
            }
        
    }


    public function AIModulesSave(Request $request)
    {

        
        $identifier_data = Identifier::where('id', $request->identifier_id)->first();
        
// Define validation rules for different types
$validationRules = [
    'chatgpt' => [
        'content.prompts' => 'required|array',
        'content.prompts.*.prompts' => 'required|string',
        'content.prompts.*.response' => 'required|string',
        //'content.prompts.*.file' => 'required|mimes:png,jpg,webp,mp3|max:2048',
        
        'content.activities' => 'required|array',
        'content.activities.*.activities_description' => 'required|string',
        'content.activities.*.website_url' => 'required|string',
        'content.activities.*.prompt_screenshot' => 'required|mimes:png,jpg,webp,mp3|max:2048',

        /* 'content.add_own_activities_prompts' => 'required|array',
        'content.add_own_activities_prompts.*.add_placeholder_text' => 'required|string',
        'content.add_own_activities_prompts.*.add_ai_toll_url' => 'required|string',
        'content.add_own_activities_prompts.*.toll_name' => 'required|string', */
    ],
   'dalle' => [
        'content.prompts' => 'required|array',
        'content.prompts.*.prompts' => 'required|string',
        //'content.prompts.*.response' => 'required|string',
        'content.prompts.*.file' => 'required|mimes:png,jpg,webp,mp3|max:2048',
        
        'content.activities' => 'required|array',
        'content.activities.*.activities_description' => 'required|string',
        'content.activities.*.website_url' => 'required|string',
        'content.activities.*.prompt_screenshot' => 'required|mimes:png,jpg,webp,mp3|max:2048',

       /*  'content.add_own_activities_prompts' => 'required|array',
        'content.add_own_activities_prompts.*.add_placeholder_text' => 'required|string',
        'content.add_own_activities_prompts.*.add_ai_toll_url' => 'required|string',
        'content.add_own_activities_prompts.*.toll_name' => 'required|string', */
    ],
    'text_to_speech' => [
        'content.prompts' => 'required|array',
        'content.prompts.*.prompts' => 'required|string',
        'content.prompts.*.response' => 'required|string',
        'content.prompts.*.file' => 'required|mimes:png,jpg,webp,mp3|max:2048',
        
        'content.activities' => 'required|array',
        'content.activities.*.activities_description' => 'required|string',
        'content.activities.*.website_url' => 'required|string',
        'content.activities.*.prompt_screenshot' => 'required|mimes:png,jpg,webp,mp3|max:2048',

       /*  'content.add_own_activities_prompts' => 'required|array',
        'content.add_own_activities_prompts.*.add_placeholder_text' => 'required|string',
        'content.add_own_activities_prompts.*.add_ai_toll_url' => 'required|string',
        'content.add_own_activities_prompts.*.toll_name' => 'required|string', */
    ],
    'speech_to_text' => [
        'content.prompts' => 'required|array',
        'content.prompts.*.prompts' => 'required|string',
        'content.prompts.*.response' => 'required|string',
        'content.prompts.*.file' => 'required|mimes:png,jpg,webp,mp3|max:2048',
        
        'content.activities' => 'required|array',
        'content.activities.*.activities_description' => 'required|string',
        'content.activities.*.website_url' => 'required|string',
        'content.activities.*.prompt_screenshot' => 'required|mimes:png,jpg,webp,mp3|max:2048',

        /* 'content.add_own_activities_prompts' => 'required|array',
        'content.add_own_activities_prompts.*.add_placeholder_text' => 'required|string',
        'content.add_own_activities_prompts.*.add_ai_toll_url' => 'required|string',
        'content.add_own_activities_prompts.*.toll_name' => 'required|string', */
    ],
    'text_to_music' => [
        'content.prompts' => 'required|array',
        'content.prompts.*.prompts' => 'required|string',
        'content.prompts.*.response' => 'required|string',
        'content.prompts.*.file' => 'required|mimes:png,jpg,webp,mp3|max:2048',
        
        'content.activities' => 'required|array',
        'content.activities.*.activities_description' => 'required|string',
        'content.activities.*.website_url' => 'required|string',
        'content.activities.*.prompt_screenshot' => 'required|mimes:png,jpg,webp,mp3|max:2048',

        /* 'content.add_own_activities_prompts' => 'required|array',
        'content.add_own_activities_prompts.*.add_placeholder_text' => 'required|string',
        'content.add_own_activities_prompts.*.add_ai_toll_url' => 'required|string',
        'content.add_own_activities_prompts.*.toll_name' => 'required|string', */

        'slider.generate_slider' => 'required|array',
        'slider.generate_slider.*.slider_video' => 'required',
        'slider.generate_slider.*.slider_text' => 'required',
        'slider.generate_slider.*.slider_image' => 'required|mimes:png,jpg,webp|max:2048',
    ],
    'image_to_narration' => [
        'vision.generate_vision' => 'required|array',
        'vision.generate_vision.*.vision_music' => 'required|mimes:mp3',
        'vision.generate_vision.*.vision_text' => 'required',
        'vision.generate_vision.*.vision_image' => 'required|mimes:png,jpg,webp|max:2048',

        'content.activities' => 'required|array',
        'content.activities.*.activities_description' => 'required|string',
        'content.activities.*.website_url' => 'required|string',
        'content.activities.*.prompt_screenshot' => 'required|mimes:png,jpg,webp,mp3|max:2048',

        /* 'content.add_own_activities_prompts' => 'required|array',
        'content.add_own_activities_prompts.*.add_placeholder_text' => 'required|string',
        'content.add_own_activities_prompts.*.add_ai_toll_url' => 'required|string',
        'content.add_own_activities_prompts.*.toll_name' => 'required|string', */

        'slider.generate_slider' => 'required|array',
        'slider.generate_slider.*.slider_video' => 'required',
        'slider.generate_slider.*.slider_text' => 'required',
        'slider.generate_slider.*.slider_image' => 'required|mimes:png,jpg,webp|max:2048',
    ],
    'fauna_and_flora' => [
        'content.prompts' => 'required|array',
        'content.prompts.*.prompts' => 'required|string',
        'content.prompts.*.response' => 'required|string',
        'content.prompts.*.file' => 'required|mimes:png,jpg,webp,mp3|max:2048',
        
        'content.activities' => 'required|array',
        'content.activities.*.activities_description' => 'required|string',
        'content.activities.*.website_url' => 'required|string',
        'content.activities.*.prompt_screenshot' => 'required|mimes:png,jpg,webp,mp3|max:2048',

        /* 'content.add_own_activities_prompts' => 'required|array',
        'content.add_own_activities_prompts.*.add_placeholder_text' => 'required|string',
        'content.add_own_activities_prompts.*.add_ai_toll_url' => 'required|string',
        'content.add_own_activities_prompts.*.toll_name' => 'required|string', */

        'hybrid.generate_hybrid' => 'required|array',
        'hybrid.generate_hybrid.*.animal_one' => 'required|mimes:png,jpg,webp',
        'hybrid.generate_hybrid.*.animal_one_name' => 'required',
        'hybrid.generate_hybrid.*.animal_second' => 'required|mimes:png,jpg,webp',
        'hybrid.generate_hybrid.*.animal_second_name' => 'required',
        'hybrid.generate_hybrid.*.result' => 'required|mimes:png,jpg,webp',
        'hybrid.generate_hybrid.*.result_name' => 'required',
    ],
    'photo_to_video' => [
        'content.prompts' => 'required|array',
        'content.prompts.*.prompts' => 'required|string',
        'content.prompts.*.response' => 'required|string',
        'content.prompts.*.file' => 'required|mimes:png,jpg,webp,mp3|max:2048',
        
        'content.activities' => 'required|array',
        'content.activities.*.activities_description' => 'required|string',
        'content.activities.*.website_url' => 'required|string',
        'content.activities.*.prompt_screenshot' => 'required|mimes:png,jpg,webp,mp3|max:2048',

        /* 'content.add_own_activities_prompts' => 'required|array',
        'content.add_own_activities_prompts.*.add_placeholder_text' => 'required|string',
        'content.add_own_activities_prompts.*.add_ai_toll_url' => 'required|string',
        'content.add_own_activities_prompts.*.toll_name' => 'required|string', */

        'slider.generate_slider' => 'required|array',
        'slider.generate_slider.*.slider_video' => 'required',
        'slider.generate_slider.*.slider_text' => 'required',
        'slider.generate_slider.*.slider_image' => 'required|mimes:png,jpg,webp|max:2048',
    ],
    'generate_avatar' => [
        'content.prompts' => 'required|array',
        'content.prompts.*.prompts' => 'required|string',
        'content.prompts.*.response' => 'required|string',
        'content.prompts.*.file' => 'required|mimes:png,jpg,webp,mp3|max:2048',
        
        'content.activities' => 'required|array',
        'content.activities.*.activities_description' => 'required|string',
        'content.activities.*.website_url' => 'required|string',
        'content.activities.*.prompt_screenshot' => 'required|mimes:png,jpg,webp,mp3|max:2048',

        /* 'content.add_own_activities_prompts' => 'required|array',
        'content.add_own_activities_prompts.*.add_placeholder_text' => 'required|string',
        'content.add_own_activities_prompts.*.add_ai_toll_url' => 'required|string',
        'content.add_own_activities_prompts.*.toll_name' => 'required|string', */
    ],
];

// Define custom attribute names for different types
$attributeNames = [
    'chatgpt' => [
        'prompts' => 'Prompts',
        'response' => 'Response',
        'file' => 'File',
        'activities_description' => 'Activities Description',
        'website_url' => 'Website URL',
        'prompt_screenshot' => 'Prompt Screenshots',
        'add_placeholder_text' => 'Add Placeholder Text',
        'add_ai_toll_url' => 'Add AI Tool URL',
        'toll_name' => 'Tool Name',
    ],
    'dalle' => [
        'prompts' => 'Prompts',
        'response' => 'Response',
        'file' => 'File',
        'activities_description' => 'Activities Description',
        'website_url' => 'Website URL',
        'prompt_screenshot' => 'Prompt Screenshots',
        'add_placeholder_text' => 'Add Placeholder Text',
        'add_ai_toll_url' => 'Add AI Tool URL',
        'toll_name' => 'Tool Name',
    ],
    'text_to_speech' => [
        'prompts' => 'Prompts',
        'response' => 'Response',
        'file' => 'File',
        'activities_description' => 'Activities Description',
        'website_url' => 'Website URL',
        'prompt_screenshot' => 'Prompt Screenshots',
        'add_placeholder_text' => 'Add Placeholder Text',
        'add_ai_toll_url' => 'Add AI Tool URL',
        'toll_name' => 'Tool Name',
    ],
    'speech_to_text' => [
        'prompts' => 'Prompts',
        'response' => 'Response',
        'file' => 'File',
        'activities_description' => 'Activities Description',
        'website_url' => 'Website URL',
        'prompt_screenshot' => 'Prompt Screenshots',
        'add_placeholder_text' => 'Add Placeholder Text',
        'add_ai_toll_url' => 'Add AI Tool URL',
        'toll_name' => 'Tool Name',
    ],
    'text_to_music' => [
        'prompts' => 'Prompts',
        'response' => 'Response',
        'file' => 'File',
        'activities_description' => 'Activities Description',
        'website_url' => 'Website URL',
        'prompt_screenshot' => 'Prompt Screenshots',
        'add_placeholder_text' => 'Add Placeholder Text',
        'add_ai_toll_url' => 'Add AI Tool URL',
        'toll_name' => 'Tool Name',
        'slider_text' => 'Slider Text',
        'slider_video' => 'Slider Video Url',
        'slider_image' => 'Slider Image',

    ],
    'image_to_narration' => [
        'vision_text' => 'Vision Text',
        'vision_image' => 'Vision Image',
        'vision_music' => 'Vision Music',

        'activities_description' => 'Activities Description',
        'website_url' => 'Website URL',
        'prompt_screenshot' => 'Prompt Screenshots',
        'add_placeholder_text' => 'Add Placeholder Text',
        'add_ai_toll_url' => 'Add AI Tool URL',
        'toll_name' => 'Tool Name',
        'slider_text' => 'Slider Text',
        'slider_video' => 'Slider Video Url',
        'slider_image' => 'Slider Image',
    ],
    'fauna_and_flora' => [
        'prompts' => 'Prompts',
        'response' => 'Response',
        'file' => 'File',
        'activities_description' => 'Activities Description',
        'website_url' => 'Website URL',
        'prompt_screenshot' => 'Prompt Screenshots',
        'add_placeholder_text' => 'Add Placeholder Text',
        'add_ai_toll_url' => 'Add AI Tool URL',
        'toll_name' => 'Tool Name',

        'animal_one' => 'Animal Imgae',
        'animal_one_name' => 'Animal One Name',
        'animal_second' => 'Animal Second Imgae',
        'animal_second_name' => 'Animal Second Name',
        'result' => 'Result Image',
        'result_name' => 'Result Name',

    ],
    'photo_to_video' => [
        'prompts' => 'Prompts',
        'response' => 'Response',
        'file' => 'File',
        'activities_description' => 'Activities Description',
        'website_url' => 'Website URL',
        'prompt_screenshot' => 'Prompt Screenshots',
        'add_placeholder_text' => 'Add Placeholder Text',
        'add_ai_toll_url' => 'Add AI Tool URL',
        'toll_name' => 'Tool Name',
        'slider_text' => 'Slider Text',
        'slider_video' => 'Slider Video Url',
        'slider_image' => 'Slider Image',

    ],
    'generate_avatar' => [
        'prompts' => 'Prompts',
        'response' => 'Response',
        'file' => 'File',
        'activities_description' => 'Activities Description',
        'website_url' => 'Website URL',
        'prompt_screenshot' => 'Prompt Screenshots',
        'add_placeholder_text' => 'Add Placeholder Text',
        'add_ai_toll_url' => 'Add AI Tool URL',
        'toll_name' => 'Tool Name',
    ],
    
];
//dd($request->content);
$type = $identifier_data->type;
if (array_key_exists($type, $validationRules)) {
    $validator = Validator::make($request->all(), $validationRules[$type]);

    if (array_key_exists($type, $attributeNames)) {
        $customAttributes = [];
        
        if ($type == 'chatgpt' || $type == 'dalle' || $type == 'text_to_speech' || $type == 'speech_to_text' || $type == 'text_to_music' || $type == 'fauna_and_flora' || $type == 'photo_to_video' || $type == 'generate_avatar') {

            foreach ($request->content['prompts'] as $cindex => $competence_data) {
                $customAttributes['content.prompts.' . $cindex . '.prompts'] = 'Row ' . ($cindex + 1) . ' - ' . $attributeNames[$type]['prompts'];
                $customAttributes['content.prompts.' . $cindex . '.response'] = 'Row ' . ($cindex + 1) . ' - ' . $attributeNames[$type]['response'];
            }

            foreach ($request->content['prompts'] as $cindex => $competence_data) {
                $customAttributes['content.prompts.' . $cindex . '.file'] = 'Row ' . ($cindex + 1) . ' - ' . $attributeNames[$type]['file'];
            }
            foreach ($request->content['activities'] as $cindex => $competence_data) {
                $customAttributes['content.activities.' . $cindex . '.activities_description'] = 'Row ' . ($cindex + 1) . ' - ' . $attributeNames[$type]['activities_description'];
                $customAttributes['content.activities.' . $cindex . '.website_url'] = 'Row ' . ($cindex + 1) . ' - ' . $attributeNames[$type]['website_url'];
                $customAttributes['content.activities.' . $cindex . '.prompt_screenshot'] = 'Row ' . ($cindex + 1) . ' - ' . $attributeNames[$type]['prompt_screenshot'];
            }
            foreach ($request->content['add_own_activities_prompts'] as $cindex => $competence_data) {
                $customAttributes['content.add_own_activities_prompts.' . $cindex . '.add_placeholder_text'] = 'Row ' . ($cindex + 1) . ' - ' . $attributeNames[$type]['add_placeholder_text'];
                $customAttributes['content.add_own_activities_prompts.' . $cindex . '.add_ai_toll_url'] = 'Row ' . ($cindex + 1) . ' - ' . $attributeNames[$type]['add_ai_toll_url'];
                $customAttributes['content.add_own_activities_prompts.' . $cindex . '.toll_name'] = 'Row ' . ($cindex + 1) . ' - ' . $attributeNames[$type]['toll_name'];
            }

            if($type == 'text_to_music'){
                foreach ($request->slider['generate_slider'] as $cindex => $competence_data) {
                    $customAttributes['slider.generate_slider.' . $cindex . '.slider_image'] = 'Row ' . ($cindex + 1) . ' - ' . $attributeNames[$type]['slider_image'];
                    $customAttributes['slider.generate_slider.' . $cindex . '.slider_video'] = 'Row ' . ($cindex + 1) . ' - ' . $attributeNames[$type]['slider_video'];
                    $customAttributes['slider.generate_slider.' . $cindex . '.slider_text'] = 'Row ' . ($cindex + 1) . ' - ' . $attributeNames[$type]['slider_text'];
                }
            }
            if($type == 'photo_to_video'){
                foreach ($request->slider['generate_slider'] as $cindex => $competence_data) {
                    $customAttributes['slider.generate_slider.' . $cindex . '.slider_image'] = 'Row ' . ($cindex + 1) . ' - ' . $attributeNames[$type]['slider_image'];
                    $customAttributes['slider.generate_slider.' . $cindex . '.slider_video'] = 'Row ' . ($cindex + 1) . ' - ' . $attributeNames[$type]['slider_video'];
                    $customAttributes['slider.generate_slider.' . $cindex . '.slider_text'] = 'Row ' . ($cindex + 1) . ' - ' . $attributeNames[$type]['slider_text'];
                }
            }
            if($type == 'fauna_and_flora'){
                foreach ($request->hybrid['generate_hybrid'] as $cindex => $competence_data) {
                    $customAttributes['hybrid.generate_hybrid.' . $cindex . '.animal_one'] = 'Row ' . ($cindex + 1) . ' - ' . $attributeNames[$type]['animal_one'];
                    $customAttributes['hybrid.generate_hybrid.' . $cindex . '.animal_one_name'] = 'Row ' . ($cindex + 1) . ' - ' . $attributeNames[$type]['animal_one_name'];
                    $customAttributes['hybrid.generate_hybrid.' . $cindex . '.animal_second'] = 'Row ' . ($cindex + 1) . ' - ' . $attributeNames[$type]['animal_second'];
                    $customAttributes['hybrid.generate_hybrid.' . $cindex . '.animal_second_name'] = 'Row ' . ($cindex + 1) . ' - ' . $attributeNames[$type]['animal_second_name'];
                    $customAttributes['hybrid.generate_hybrid.' . $cindex . '.result'] = 'Row ' . ($cindex + 1) . ' - ' . $attributeNames[$type]['result'];
                    $customAttributes['hybrid.generate_hybrid.' . $cindex . '.result_name'] = 'Row ' . ($cindex + 1) . ' - ' . $attributeNames[$type]['result_name'];
                }
            }
        }
        if($type == 'image_to_narration'){

            foreach ($request->vision['generate_vision'] as $cindex => $competence_data) {
                $customAttributes['vision.generate_vision.' . $cindex . '.vision_image'] = 'Row ' . ($cindex + 1) . ' - ' . $attributeNames[$type]['vision_image'];
                $customAttributes['vision.generate_vision.' . $cindex . '.vision_text'] = 'Row ' . ($cindex + 1) . ' - ' . $attributeNames[$type]['vision_text'];
                $customAttributes['vision.generate_vision.' . $cindex . '.vision_music'] = 'Row ' . ($cindex + 1) . ' - ' . $attributeNames[$type]['vision_music'];
            }


            foreach ($request->slider['generate_slider'] as $cindex => $competence_data) {
                $customAttributes['slider.generate_slider.' . $cindex . '.slider_image'] = 'Row ' . ($cindex + 1) . ' - ' . $attributeNames[$type]['slider_image'];
                $customAttributes['slider.generate_slider.' . $cindex . '.slider_video'] = 'Row ' . ($cindex + 1) . ' - ' . $attributeNames[$type]['slider_video'];
                $customAttributes['slider.generate_slider.' . $cindex . '.slider_text'] = 'Row ' . ($cindex + 1) . ' - ' . $attributeNames[$type]['slider_text'];
            }
            foreach ($request->content['activities'] as $cindex => $competence_data) {
                $customAttributes['content.activities.' . $cindex . '.activities_description'] = 'Row ' . ($cindex + 1) . ' - ' . $attributeNames[$type]['activities_description'];
                $customAttributes['content.activities.' . $cindex . '.website_url'] = 'Row ' . ($cindex + 1) . ' - ' . $attributeNames[$type]['website_url'];
                $customAttributes['content.activities.' . $cindex . '.prompt_screenshot'] = 'Row ' . ($cindex + 1) . ' - ' . $attributeNames[$type]['prompt_screenshot'];
            }
            foreach ($request->content['add_own_activities_prompts'] as $cindex => $competence_data) {
                $customAttributes['content.add_own_activities_prompts.' . $cindex . '.add_placeholder_text'] = 'Row ' . ($cindex + 1) . ' - ' . $attributeNames[$type]['add_placeholder_text'];
                $customAttributes['content.add_own_activities_prompts.' . $cindex . '.add_ai_toll_url'] = 'Row ' . ($cindex + 1) . ' - ' . $attributeNames[$type]['add_ai_toll_url'];
                $customAttributes['content.add_own_activities_prompts.' . $cindex . '.toll_name'] = 'Row ' . ($cindex + 1) . ' - ' . $attributeNames[$type]['toll_name'];
            }
        }
        $validator->setAttributeNames($customAttributes);
    }

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors(),
        ]);
    }
}





//dd($request->content);
       
    if($validator->passes()){
        $prompts = [];
        if(isset($request->content['prompts'])){
            
        foreach ($request->content['prompts'] as $promptData) {
            $file = $promptData['file'] ?? null;
           // dd($file);
            if ($file != 'undefined') {
                  $destinationPath = 'uploads/aimodule/';
                  $originalName = $file->getClientOriginalName();
                  $imageNameprompts = "prompt_" . date('Ymd') . '_' . $originalName;
                  $file->move($destinationPath, $imageNameprompts);
                   $promptData['file'] = $imageNameprompts;
                    $fileData = [
                        'originalName' => "prompt_" . date('Ymd') . '_' . $file->getClientOriginalName(),
                        'mimeType' => $file->getClientMimeType(),
                        'path' => "uploads/aimodule/prompt_" . date('Ymd') . '_' . $file->getClientOriginalName(), // Remove the comma here
                        'extension' => $file->getClientOriginalExtension(),
                    ];
                  $promptData['file_all_data'] = $fileData;
            }else{
                $promptData['file_all_data'] = null;
            }
            $prompts[] = $promptData;
        }
    }
  //  dd($prompts);
        $activities = [];
       
        foreach ($request->content['activities'] as $activityData) {
            $prompt_screenshot = $activityData['prompt_screenshot'] ?? null;
            if ($prompt_screenshot != 'undefined') {
                  $destinationPath = 'uploads/aimodule/';
                  $originalName = $prompt_screenshot->getClientOriginalName();
                  $imageNameactivity = "activity_" . date('Ymd') . '_' . $originalName;
                  $prompt_screenshot->move($destinationPath, $imageNameactivity);
                $activityData['prompt_screenshot'] = $imageNameactivity;
            }
            $activities[] = $activityData;
        }


/* Hybrid */
$hybridData = $request->hybrid;
if (isset($hybridData['generate_hybrid'])) {
    foreach ($hybridData['generate_hybrid'] as $key => $hybridImage) {
        $hybrid_animal_one = isset($hybridImage['animal_one']) ? $hybridImage['animal_one'] : null;
        if ($hybrid_animal_one !== 'undefined') {
            $destinationPath = 'uploads/hybrid/';
            $originalName = $hybrid_animal_one->getClientOriginalName();
            $pdfName = "hybrid_" . date('Ymd') . '_' . $originalName;
            $hybrid_animal_one->move($destinationPath, $pdfName);
            $hybridData['generate_hybrid'][$key]['animal_one_new'] = $pdfName;
        } else {
            $hybridData['generate_hybrid'][$key]['animal_one_new'] = null;
        }


        $hybrid_animal_second = isset($hybridImage['animal_second']) ? $hybridImage['animal_second'] : null;
        if ($hybrid_animal_second !== 'undefined') {
            $destinationPath = 'uploads/hybrid/';
            $originalName = $hybrid_animal_second->getClientOriginalName();
            $pdfName = "hybrid_" . date('Ymd') . '_' . $originalName;
            $hybrid_animal_second->move($destinationPath, $pdfName);
            $hybridData['generate_hybrid'][$key]['animal_second_new'] = $pdfName;
        } else {
            $hybridData['generate_hybrid'][$key]['animal_second_new'] = null;
        }


        $hybrid_result = isset($hybridImage['result']) ? $hybridImage['result'] : null;
        if ($hybrid_result !== 'undefined') {
            $destinationPath = 'uploads/hybrid/';
            $originalName = $hybrid_result->getClientOriginalName();
            $pdfName = "hybrid_" . date('Ymd') . '_' . $originalName;
            $hybrid_result->move($destinationPath, $pdfName);
            $hybridData['generate_hybrid'][$key]['result_new'] = $pdfName;
        } else {
            $hybridData['generate_hybrid'][$key]['result_new'] = null;
        }
    }
}
$request->merge(['hybrid' => $hybridData]);

/* Vision */
$visionData = $request->vision;
if (isset($visionData['generate_vision'])) {
    foreach ($visionData['generate_vision'] as $key => $hybridImage) {
        $vision_image = isset($hybridImage['vision_image']) ? $hybridImage['vision_image'] : null;
        $vision_music = isset($hybridImage['vision_music']) ? $hybridImage['vision_music'] : null;
        if ($vision_image !== 'undefined') {
            $destinationPath = 'uploads/vision/';
            $originalName = $vision_image->getClientOriginalName();
            $pdfName = "vision_" . date('Ymd') . '_' . $originalName;
            $vision_image->move($destinationPath, $pdfName);
            $visionData['generate_vision'][$key]['vision_image_new'] = $pdfName;
        } else {
            $visionData['generate_vision'][$key]['vision_image_new'] = null;
        }
        if ($vision_music !== 'undefined') {
            $destinationPath = 'uploads/vision/';
            $originalName = $vision_music->getClientOriginalName();
            $pdfName = "vision_" . date('Ymd') . '_' . $originalName;
            $vision_music->move($destinationPath, $pdfName);
            $visionData['generate_vision'][$key]['vision_music_new'] = $pdfName;
        } else {
            $visionData['generate_vision'][$key]['vision_music_new'] = null;
        }
    }
}
$request->merge(['vision' => $visionData]);


/* Slider */
$sliderData = $request->slider;
if (isset($sliderData['generate_slider'])) {
    foreach ($sliderData['generate_slider'] as $key => $hybridImage) {
        $hybrid_animal_one = isset($hybridImage['slider_image']) ? $hybridImage['slider_image'] : null;
        if ($hybrid_animal_one !== 'undefined') {
            $destinationPath = 'uploads/hybrid/';
            $originalName = $hybrid_animal_one->getClientOriginalName();
            $pdfName = "slider_" . date('Ymd') . '_' . $originalName;
            $hybrid_animal_one->move($destinationPath, $pdfName);
            $sliderData['generate_slider'][$key]['slider_image_new'] = $pdfName;
        } else {
            $sliderData['generate_slider'][$key]['slider_image_new'] = null;
        }
    }
}
$request->merge(['slider' => $sliderData]);

        
   // $data = $request->all();
    $aimodule = new Aimodules();
    $aimodule->identifier_id = $request->identifier_id;
    $aimodule->description = isset($request->description) ? $request->description : '';
    $aimodule->own_description = isset($request->own_description) ? $request->own_description : '';
    $aimodule->own_placeholder = isset($request->own_placeholder) ? $request->own_placeholder : '';
    $aimodule->hello_there_description = isset($request->hello_there_description) ? $request->hello_there_description : '';

   $aimodule->content = json_encode([
    'prompts' => $prompts, 
    'activities' => $activities,
    'add_own_activities_prompts' => $request->content['add_own_activities_prompts']
    ?? []]);

    $aimodule->generate_hybrid = json_encode([
        'hybrid' => $request->hybrid['generate_hybrid']
        ?? []]);
    $aimodule->vision_data = json_encode([
            'vision' => $request->vision['generate_vision']
            ?? []]);
    $aimodule->slider_video_data = json_encode([
                'slider' => $request->slider['generate_slider']
                ?? []]);        
    $aimodule->save();
    return response()->json([
        'status' => true,
        'message' => "Aimodule created successfully",
    ], 201);
}else{
    return response()->json([
        'status' => false,
        'errors' => $validator->errors(),
     ]);
 }
}


public function UpdateModule(Request $request)
{ 
       // dd($request->aimodule_id);
        $updateaimodule = Aimodules::where('id', $request->aimodule_id)->first();
      //  dd($updateaimodule);
         $rules = [
            'content.prompts' => 'nullable|array',
            'content.prompts.*.prompts' => 'nullable|string',
            'content.prompts.*.response' => 'nullable|string',
            'content.activities' => 'nullable|array',
            'content.activities.*.activities_description' => 'nullable|string',
            'content.activities.*.website_url' => 'nullable|string',
            /* 'content.add_own_activities_prompts' => 'nullable|array',
            'content.add_own_activities_prompts.*.add_placeholder_text' => 'nullable|string',
            'content.add_own_activities_prompts.*.add_ai_toll_url' => 'nullable|string',
            'content.add_own_activities_prompts.*.toll_name' => 'nullable|string', */
        ]; 
        /* if ($request->file('module_thumbnail')) {
            $rules['module_thumbnail'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
        }
        if ($request->has('content.prompts') && is_array($request->get('content.prompts'))) {
            foreach ($request->get('content.prompts') as $key => $value) {
                $fileKey = "content.prompts.$key.file";
                if ($request->hasFile($fileKey)) {
                    $rules[$fileKey] = 'required|image|mimes:jpeg,png,jpg,gif,mp3,mp4|max:2048';
                }
            }
        }
        if ($request->has('content.activities') && is_array($request->get('content.activities'))) {
            foreach ($request->get('content.activities') as $key => $value) {
                $fileKey = "content.activities.$key.prompt_screenshot";
                if ($request->hasFile($fileKey)) {
                    $rules[$fileKey] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
                }
            }
        }*/
        $validator = Validator::make($request->all(), $rules); 
        if($validator->passes()){
            $prompts = [];
            if(isset($request->content['prompts'])){
            foreach ($request->content['prompts'] as $promptData) {
                $file = $promptData['file'];

                if ($file != 'undefined') {
                    $destinationPath = 'uploads/aimodule/';
                    $originalName = $file->getClientOriginalName();
                    $imageNameprompts = "prompt_" . date('Ymd') . '_' . $originalName;
                    $file->move($destinationPath, $imageNameprompts);
                    $promptData['file'] = $imageNameprompts;
                    $fileData = [
                        'originalName' => "prompt_" . date('Ymd') . '_' . $file->getClientOriginalName(),
                        'mimeType' => $file->getClientMimeType(),
                        'path' => "uploads/aimodule/prompt_" . date('Ymd') . '_' . $file->getClientOriginalName(), // Remove the comma here
                        'extension' => $file->getClientOriginalExtension(),
                    ];
                    $promptData['file_all_data'] = $fileData;
                    if ($promptData['old_answers_file']) {
                        if (file_exists($destinationPath . $promptData['old_answers_file'])) {
                            unlink($destinationPath . $promptData['old_answers_file']);
                        }
                    }
                }else{
                    $promptData['file'] = $promptData['old_answers_file'];
                    $promptData['file_all_data'] = json_decode($promptData['old_music_data_file']);
                    
                }
                $prompts[] = $promptData;
            }
        }
            $activities = [];
            foreach ($request->content['activities'] as $activityData) {
                $prompt_screenshot = $activityData['prompt_screenshot'];
                if ($prompt_screenshot != 'undefined') {
                    $destinationPath = 'uploads/aimodule/';
                    $originalName = $prompt_screenshot->getClientOriginalName();
                    $imageNameactivity = "activity_" . date('Ymd') . '_' . $originalName;
                    $prompt_screenshot->move($destinationPath, $imageNameactivity);
                    $activityData['prompt_screenshot'] = $imageNameactivity;
                    if ($activityData['old_prompt_screenshot']) {
                        if (file_exists($destinationPath . $activityData['old_prompt_screenshot'])) {
                            unlink($destinationPath . $activityData['old_prompt_screenshot']);
                        }
                    }
                }else{
                    $activityData['prompt_screenshot'] = $activityData['old_prompt_screenshot'];
                }
                $activities[] = $activityData;
            }



            /* Hybrid */
$hybridData = $request->hybrid;

if (isset($hybridData['generate_hybrid'])) {
    foreach ($hybridData['generate_hybrid'] as $key => $hybridImage) {
        //dd($hybridImage['update_animal_one']);
        $hybrid_animal_one = isset($hybridImage['animal_one']) ? $hybridImage['animal_one'] : null;
       // dd($hybrid_animal_one);
        if ($hybrid_animal_one !== 'undefined') {
            $destinationPath = 'uploads/hybrid/';
            $originalName = $hybrid_animal_one->getClientOriginalName();
            $pdfName = "hybrid_" . date('Ymd') . '_' . $originalName;
            $hybrid_animal_one->move($destinationPath, $pdfName);
            $hybridData['generate_hybrid'][$key]['animal_one_new'] = $pdfName;
        } else {
            $hybridData['generate_hybrid'][$key]['animal_one_new'] = $hybridImage['update_animal_one'];
        }


        $hybrid_animal_second = isset($hybridImage['animal_second']) ? $hybridImage['animal_second'] : null;
        if ($hybrid_animal_second !== 'undefined') {
            $destinationPath = 'uploads/hybrid/';
            $originalName = $hybrid_animal_second->getClientOriginalName();
            $pdfName = "hybrid_" . date('Ymd') . '_' . $originalName;
            $hybrid_animal_second->move($destinationPath, $pdfName);
            $hybridData['generate_hybrid'][$key]['animal_second_new'] = $pdfName;
        } else {
            $hybridData['generate_hybrid'][$key]['animal_second_new'] = $hybridImage['update_animal_second'];
        }


        $hybrid_result = isset($hybridImage['result']) ? $hybridImage['result'] : null;
        if ($hybrid_result !== 'undefined') {
            $destinationPath = 'uploads/hybrid/';
            $originalName = $hybrid_result->getClientOriginalName();
            $pdfName = "hybrid_" . date('Ymd') . '_' . $originalName;
            $hybrid_result->move($destinationPath, $pdfName);
            $hybridData['generate_hybrid'][$key]['result_new'] = $pdfName;
        } else {
            $hybridData['generate_hybrid'][$key]['result_new'] = $hybridImage['update_result'];
        }
    }
}
$request->merge(['hybrid' => $hybridData]);


/* Vision */
$visionData = $request->vision;
if (isset($visionData['generate_vision'])) {
    foreach ($visionData['generate_vision'] as $key => $hybridImage) {
        $vision_image = isset($hybridImage['vision_image']) ? $hybridImage['vision_image'] : null;
        $vision_music = isset($hybridImage['vision_music']) ? $hybridImage['vision_music'] : null;
        if ($vision_image !== 'undefined') {
            $destinationPath = 'uploads/vision/';
            $originalName = $vision_image->getClientOriginalName();
            $pdfName = "vision_" . date('Ymd') . '_' . $originalName;
            $vision_image->move($destinationPath, $pdfName);
            $visionData['generate_vision'][$key]['vision_image_new'] = $pdfName;
        } else {
            $visionData['generate_vision'][$key]['vision_image_new'] = $hybridImage['update_vision_image'];
        }
        if ($vision_music !== 'undefined') {
            $destinationPath = 'uploads/vision/';
            $originalName = $vision_music->getClientOriginalName();
            $pdfName = "vision_" . date('Ymd') . '_' . $originalName;
            $vision_music->move($destinationPath, $pdfName);
            $visionData['generate_vision'][$key]['vision_music_new'] = $pdfName;
        } else {
            $visionData['generate_vision'][$key]['vision_music_new'] = $hybridImage['update_vision_music'];
        }
    }
}
$request->merge(['vision' => $visionData]);


$sliderData = $request->slider;
if (isset($sliderData['generate_slider'])) {
    foreach ($sliderData['generate_slider'] as $key => $hybridImage) {
        //dd($hybridImage);
        $hybrid_animal_one = isset($hybridImage['slider_image']) ? $hybridImage['slider_image'] : null;
        if ($hybrid_animal_one !== 'undefined') {
            $destinationPath = 'uploads/hybrid/';
            $originalName = $hybrid_animal_one->getClientOriginalName();
            $pdfName = "slider_" . date('Ymd') . '_' . $originalName;
            $hybrid_animal_one->move($destinationPath, $pdfName);
            $sliderData['generate_slider'][$key]['slider_image_new'] = $pdfName;
        } else {
            $sliderData['generate_slider'][$key]['slider_image_new'] = $hybridImage['update_slider_image'];
        }
    }
}
$request->merge(['slider' => $sliderData]);


        $data = $request->all();
        $updateaimodule->description = isset($request->description) ? $request->description : '';
        $updateaimodule->own_description = isset($request->own_description) ? $request->own_description : '';
        $updateaimodule->own_placeholder = isset($request->own_placeholder) ? $request->own_placeholder : '';
        $updateaimodule->hello_there_description = isset($request->hello_there_description) ? $request->hello_there_description : '';
        $updateaimodule->generate_hybrid = json_encode([
            'hybrid' => $request->hybrid['generate_hybrid']
            ?? []]);
        $updateaimodule->vision_data = json_encode([
                'vision' => $request->vision['generate_vision']
                ?? []]);
        $updateaimodule->slider_video_data = json_encode([
                'slider' => $request->slider['generate_slider']
                ?? []]);

        $updateaimodule->content = json_encode([
         'prompts' => $prompts, 
         'activities' => $activities,
         'add_own_activities_prompts' => $request->content['add_own_activities_prompts']
         ?? []]);
         $updateaimodule->save();
         return response()->json([
             'status' => true,
             'message' => "Aimodule update successfully",
         ], 201);
     }else{
         return response()->json([
             'status' => false,
             'errors' => $validator->errors(),
          ]);
     
      }
}

}
