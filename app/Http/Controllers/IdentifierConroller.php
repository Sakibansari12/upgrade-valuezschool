<?php

namespace App\Http\Controllers;
use App\Models\{User, School, CitiesModel, StateModel, LogsModel, Program, Course, Package, Identifier, SchoolPayment, EmailTemplate,Aimodules};
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

class IdentifierConroller extends Controller
{
    public function getIdentifier(Request $request)
    {
        if ($request->ajax()) {
            $mainQuery = DB::table('ai_identifiers')->whereNull('ai_identifiers.deleted_at')
                ->select([
                    'ai_identifiers.id',
                    'ai_identifiers.display_name',
                    'ai_identifiers.module_page_title',
                    'ai_identifiers.module_page_overview',
                    'ai_identifiers.type',
                    'ai_identifiers.thumbnail',
                    'ai_identifiers.video_url',
                    'ai_identifiers.status',
                    'ai_identifiers.aimodule_status',
                    'ai_identifiers.course_id',
                    'master_course.course_name',
                    DB::raw('(SELECT COUNT(*) FROM aimodules WHERE aimodules.identifier_id = ai_identifiers.id) as aimodules_count')
                ])
                ->leftJoin('master_course', 'master_course.id', 'ai_identifiers.course_id')
                ->orderBy('ai_identifiers.id','desc');
                //dd($mainQuery);
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
             return Datatables::of($data)
             ->addColumn('index', function ($row) {
                 static $index = 0;
                 return ++$index;
             })

             ->editColumn('thumbnail', function ($row) {
                $thumbnailUrl = url('uploads/Identifier') . '/' . (!empty($row->thumbnail) ? $row->thumbnail : 'no_image.png');
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
                $editLink = '<a href="' . route('identifier.edit', ['id' => $row->id]) . '"
                                class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Edit</a>';
                $AiModule = '';                
                  if ($row->aimodules_count == 0) {  
                    $AiModule = '<a href="' . route('identifier.aicreate', ['id' => $row->id, 'type' => $row->type]) . '" 
                                    class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Module Prompts</a>';
                  }  
              
                $deleteLink = '<a href="javascript:void(0);" data-id="' . $row->id . '"
                                class="waves-effect waves-light remove_school_data btn btn-sm btn-outline btn-danger mb-5">Delete</a>';
                return $editLink . ' ' . $AiModule . ' ' . $deleteLink;
            })
             ->rawColumns(['index','thumbnail', 'display_name', 'course_name', 'type', 'status', 'aimodule_status', 'action'])
             ->toJson();
         }
        return view('identifier.index');
    }

    public function CreateIdentifier(Request $request)
    {
        $program_list = DB::table('master_class')->where('status', 1)->get();
        $course_list = DB::table('master_course')->where('status', 1)->where('ai_status', 1)->get();
       return view('identifier.create', compact('program_list', 'course_list'));
    }




    public function IdentifierCreate(Request $request)
    {
    $validator = Validator::make($request->all(), [
        'identifier' => 'required',
        'module_name' => 'required',
        'module_thumbnail' => 'required|image|mimes:png,jpg,webp|max:2048',
        'class_id' => 'required',
        'course_id' => 'required',
        'module_page_title' => 'required',
        'module_page_overview' => 'required',
        //'video_url' => 'required',
        'video_url' => 'required|url',
    ]); 
   // dd($request->class_id);
    if($validator->passes()){
       if ($image = $request->file('module_thumbnail')) {
        $destinationPath = 'uploads/Identifier/';
        $originalname = $image->hashName();
        $imageName = "aimodule_thumbnail_" . date('Ymd') . '_' . $originalname;
        $image->move($destinationPath, $imageName);
    }
   // $data = $request->all();
    $aimodule = new Identifier();
    $aimodule->display_name = $request->module_name;
    $aimodule->thumbnail = $imageName;
    $aimodule->type = $request->identifier;
    $aimodule->grade_id = implode(",", $request->class_id);
    $aimodule->course_id = $request->course_id;
    $aimodule->module_page_title = $request->module_page_title;
    $aimodule->module_page_overview = $request->module_page_overview;
    $aimodule->video_url = $request->video_url;
    $aimodule->status = 1;
    $aimodule->aimodule_status = 1;
    $aimodule->save();
    return response()->json([
        'status' => true,
        'message' => "Identifier created successfully",
    ], 201);
    
    
}else{
    return response()->json([
        'status' => false,
        'errors' => $validator->errors(),
     ]);

 }
}


public function IdentifierUpdate(Request $request)
{ 
        
    $validator = Validator::make($request->all(), [
        'identifier' => 'required',
        'module_name' => 'required',
        //'module_thumbnail' => 'required|image|mimes:png,jpg,webp|max:2048',
        'class_id' => 'required',
        'course_id' => 'required',
        'module_page_title' => 'required',
        'module_page_overview' => 'required',
        //'video_url' => 'required',
        'video_url' => 'required|url',
    ]); 
        

      //  dd($request->module_thumbnail);
        if($validator->passes()){

            if ($request->hasFile('module_thumbnail')) {
                $file = $request->file('module_thumbnail');
                $destinationPath = 'uploads/Identifier/';
                $imageName =  date('Ymd') . '_module_thumbnail.' . $file->getClientOriginalExtension();
                $file->move($destinationPath, $imageName);
            } else {
                $file = Identifier::select('thumbnail')->where('id',  $request->aimodule_id)->first();
                $imageName = $file->thumbnail;
            }

        $updateaimodule = Identifier::where('id', $request->aimodule_id)->first();
      //  $validator = Validator::make($request->all(), $rules);
        $updateaimodule->display_name = $request->module_name;
        $updateaimodule->thumbnail = $imageName;
        $updateaimodule->type = $request->identifier;
        $updateaimodule->grade_id = implode(",", $request->class_id);
        $updateaimodule->course_id = $request->course_id;
        $updateaimodule->module_page_title = $request->module_page_title;
        $updateaimodule->module_page_overview = $request->module_page_overview;
        $updateaimodule->video_url = $request->video_url;
        $updateaimodule->status = 1;
        $updateaimodule->aimodule_status = 1;
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





public function editIdentifier(Request $request)
{

    $aimodule_id = $request->input('id');
    $program_list = DB::table('master_class')->where('status', 1)->get();
    $course_list = DB::table('master_course')->where('status', 1)->where('ai_status', 1)->get();
    $aimoduledata = DB::table('ai_identifiers')->where('id', $aimodule_id)
                ->whereNull('deleted_at')->first();  
    return view('identifier.update', compact('aimoduledata','program_list','course_list'));
  // dd($aimoduledata);
}

public function destroy(Request $request)
{
    $identifier_id = $request->input('identifier_id');
    if(!empty($identifier_id)){
       $identifier = Identifier::where('id', $identifier_id)->first();
       $check_distric = DB::table('aimodules')->where('identifier_id', $identifier->id)->count();
     if ($check_distric >= 1) {
        echo "AI Module exists, please remove all accounts first.";
    }else{
        $identifier->delete();
        echo "removed";
        
    }
}

}
}
