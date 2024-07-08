<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\{User, School, LogsModel, HealtyMind, Download};
use DataTables;
use Illuminate\Support\Facades\Hash;

class HealtyMindController extends Controller
{
    public function indexHealtyMind(Request $request)
    {

        if ($request->ajax()) {
            $healtyMinddata = HealtyMind::query()->orderBy('id','desc');
            return Datatables::of($healtyMinddata)
                ->addIndexColumn()
                ->editColumn('healty_mind_image', function ($row) {
                    $ImagePath = $row->healty_mind_image ? $row->healty_mind_image : 'no_image.png';
                    return '<img src="' . url('uploads/healty_mind/' . $ImagePath) . '" width="32" height="32" class="bg-light my-n1"
                    alt="' . $row->title . '">';
                })
                ->editColumn('title', function ($row) {
                    return $row->title ?? '';
                })
                ->editColumn('red_guidance_desc', function ($row) {
                    return $row->red_guidance_desc ?? '';
                })
                ->editColumn('video_url', function ($row) {
                    return $row->video_url ?? '';
                })
                /* ->editColumn('healty_mind_file', function ($row) {
                   
                    return '<a href="' . url("uploads/healty_mind/{$row->healty_mind_file}") . '" class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5" download>Download</a>';
                
                }) */
                ->editColumn('healty_mind_file', function ($row) {
                    $healty_mind_upload_files = isset($row->healty_mind_upload_file) ? json_decode($row->healty_mind_upload_file) : [];
                    $downloadLinks = '';
                
                    // Check if $healty_mind_upload_files is an object and if it has the property 'upload_file'
                    if (is_object($healty_mind_upload_files) && property_exists($healty_mind_upload_files, 'upload_file')) {
                        foreach ($healty_mind_upload_files->upload_file as $fileData) {
                            $file = $fileData->file;
                            $downloadLinks .= '<a href="' . url("uploads/healty_mind/{$file}") . '" class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5" download>Download</a><br>';
                        }
                    }
                
                    return $downloadLinks;
                })
                


                ->editColumn('status', function ($row) {                  
                    $status_btn = '<a href="javascript:void(0);"  id="status_' . $row->id . '" data-id="' . $row->id . '" data-status="' . $row->status . '" class="change_status text-white badge bg-' . ($row->status == 1 ? 'success' : 'danger') . '">' . ($row->status == 1 ? 'Demo' : 'Paid') . '</a>';
                    return $status_btn;
                })
                
                ->addColumn('action', function ($row) {
                    $edit_btn = '<a href="' . route('edit-healty-mind', ['healtymind_id' => $row->id]) . '"
                    class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Edit</a>';
                    $user = Auth::user();
                    $remove_btn = ($user->usertype == "superadmin") ? ' <a href="javascript:void(0);" data-id='.$row->id.'
                    class="waves-effect waves-light btn btn-sm btn-outline btn-danger remove_school_data mb-5">Delete</a>' : '';
                    $action_btn = $edit_btn . $remove_btn;
                    return $action_btn;
                })
                ->rawColumns(['action', 'title', 'healty_mind_image', 'video_url', 'healty_mind_file', 'red_guidance_desc', 'status'])
                ->make(true);
        }
        return view('Healtymind.healty-mind');
    }

    public function addHealtyMind(Request $request)
    {
       return view('Healtymind.healty-mind-add');
    }

   

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'video_url' => ['required','regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i'],
            'red_guidance' => 'required',
           // 'image' => 'required',
            'image' => 'required|image|mimes:png,jpg,webp|max:250',
            'upload_file' => 'required|array',
            'upload_file.*.file' => 'required|mimes:pdf,xlsx|max:250',
        ]);
        $aiModuleMsgRule = [];
        foreach ($request->upload_file as $cindex => $competence_data) {
            $aiModuleMsgRule['upload_file.'.$cindex.'.file'] = 'Row' .($cindex+1). '-'. 'File';
        }
        $validator->setAttributeNames($aiModuleMsgRule);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                ]);
        }
        if($validator->passes()){
            if ($image = $request->file('image')) {
                $destinationPath = 'uploads/healty_mind/';
                $originalname = $image->hashName();
                $imageName = "plan_" . date('Ymd') . '_' . $originalname;
                $image->move($destinationPath, $imageName);
            }
            $upload_file = [];
            foreach ($request->upload_file as $activityData) {
                $file = $activityData['file'] ?? null;
                if ($file) {
                      $destinationPath = 'uploads/healty_mind/';
                      $originalName = $file->getClientOriginalName();
                      $imageNameactivity = "healty_mind_" . date('Ymd') . '_' . $originalName;
                      $file->move($destinationPath, $imageNameactivity);
                    $activityData['file'] = $imageNameactivity;
                }
                $upload_file[] = $activityData;
            }
            $healtymindData = [
                'title' => $request->title,
                'video_url' => $request->video_url,
                'red_guidance_desc' => $request->red_guidance,
                'healty_mind_image' => $imageName,
                // 'healty_mind_file' => $upload_file_name,
                'healty_mind_upload_file' => json_encode([
                    'upload_file' => $upload_file ?? []
                ]), // <--- Missing semicolon here
                'status' => 1, 
            ];
            HealtyMind::create($healtymindData);
        return response()->json([
            'status' => true,
            'message' => "Healty mind added successfully!",
        ], 201);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
             ]);
        
         }

      //  return redirect(route('healty-mind-list'))->with(['message' => 'Healty mind added successfully!', 'status' => 'success']);
    }

    public function editHealtyMind(Request $request)
    {
        $healtymind_id = $request->input('healtymind_id');
        $healtymind_data = HealtyMind::where('id', $healtymind_id)->first();
        $mainQuery = DB::table('healty_minds')
        ->select([
            'healty_minds.id',
            'healty_minds.title',
            'healty_minds.video_url',
            'healty_minds.red_guidance_desc',
            'healty_minds.healty_mind_image',
            'healty_minds.healty_mind_file',
            'healty_minds.healty_mind_upload_file',
            'healty_minds.status',
            'healty_minds.deleted_at',
        ])->where('id', $healtymind_id);
         $healtymind_data = $mainQuery->get()->map(function ($row) {
                $row->healty_mind_upload_file = isset($row->healty_mind_upload_file) ? json_decode($row->healty_mind_upload_file) : null;
                return $row;
        })->first();
        return view('Healtymind.healty-mind-edit', compact('healtymind_data'));
    }

    public function updateHealtyMind(Request $request)
    {
        
        $healtymind_id = $request->input('healtymind_id');
            $validator = Validator::make($request->all(), [
                'title' => 'required|string',
                'video_url' => ['required','regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i'],
                'red_guidance' => 'required',
               // 'image' => 'required',
               // 'image' => 'required|image|mimes:png,jpg,webp|max:2048',
              //  'upload_file' => 'required|array',
              //  'upload_file.*.file' => 'required|mimes:pdf,xlsx|max:2048',
            ]);
            if($validator->passes()){
                if ($image = $request->file('image')) {
                    $destinationPath = 'uploads/healty_mind/';
                    $originalname = $image->hashName();
                    $imageName = "plan_" . date('Ymd') . '_' . $originalname;
                    $image->move($destinationPath, $imageName);
                    $image_path = $destinationPath . $request->old_image;
                    @unlink($image_path);
                }else {
                    $imageName = $request->old_image;
                }


                $upload_file = [];
                foreach ($request->upload_file as $activityData) {
                  //  dd($activityData['old_upload_file']);
                    $file = $activityData['file'] ?? null;
                    
                    if ($file != 'undefined') {
                          $destinationPath = 'uploads/healty_mind/';
                          $originalName = $file->getClientOriginalName();
                          $imageNameactivity = "healty_mind_" . date('Ymd') . '_' . $originalName;
                          $file->move($destinationPath, $imageNameactivity);
                        $activityData['file'] = $imageNameactivity;

                        // REMOVE OLD IMAGE
                        if ($activityData['old_upload_file']) {
                            if (file_exists($destinationPath . $activityData['old_upload_file'])) {
                                unlink($destinationPath . $activityData['old_upload_file']);
                            }
                        }

                    }else{
                        $activityData['file'] = $activityData['old_upload_file'];
                    }
                    $upload_file[] = $activityData;
                   
                }
              //  dd($upload_file);
                $healtymindData = [
                    'title' => $request->title,
                    'video_url' => $request->video_url,
                    'red_guidance_desc' => $request->red_guidance,
                    'healty_mind_image' => $imageName,
                    
                    'healty_mind_upload_file' => json_encode([
                        'upload_file' => $upload_file ?? []
                    ]), 
                    'status' => 1, 
                ];
                //HealtyMind::create($healtymindData);
                HealtyMind::where('id', $healtymind_id)->update($healtymindData);
            return response()->json([
                'status' => true,
                'message' => "Healty mind added successfully!",
            ], 201);
    
            }else{
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors(),
                 ]);
            
             }

    }

    public function HMVerifyadminPassword(Request $request)
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
        $healtymind_id = $request->input('healtymind_id');
        $sr_data = HealtyMind::where('id', $healtymind_id)->first();
        if(!empty($sr_data)){
            $sr_data->delete();
            echo "removed";
        }
    }

    public function teacherHealtyMind(Request $request)
    {
         $user = Auth::user();
        $userId = $user->id;
        $schoolId = $user->school_id;

      /*  $healtymind_data = DB::table('healty_minds')->whereNull('deleted_at')->get();
        $check_premium = School::find($schoolId);
        return view('Healtymind.healty-mind-teacher', compact('healtymind_data','check_premium')); */
        //dd($healtymind_data);

        $check_premium = School::find($schoolId);
        $mainQuery = DB::table('healty_minds')->whereNull('deleted_at')
        ->select([
            'healty_minds.id',
            'healty_minds.title',
            'healty_minds.video_url',
            'healty_minds.red_guidance_desc',
            'healty_minds.healty_mind_image',
            'healty_minds.healty_mind_file',
            'healty_minds.healty_mind_upload_file',
            'healty_minds.status',
            'healty_minds.deleted_at',
        ]);
         $healtymind_data = $mainQuery->get()->map(function ($row) {
                $row->healty_mind_upload_file = isset($row->healty_mind_upload_file) ? json_decode($row->healty_mind_upload_file) : null;
                return $row;
        })->all();
//dd($healtymind_data);

        return view('Healtymind.healty-mind-teacher', compact('healtymind_data','check_premium'));

    }

    public function change_status(Request $request)
    {
        $statusId = $request->sts_id;
        $status = ($request->status == 1) ? 0 : 1;
        HealtyMind::where('id', $statusId)->update(['status' => $status]);
        echo ($status == 1) ? 'Demo' : 'Paid';
    }

    public function indexDownload(Request $request)
    {

        if ($request->ajax()) {
            $Downloaddata = Download::query()->orderBy('id','desc');
            //$Downloaddata = Download::query()->orderBy('id','desc')->join('school', 'school.id', '=', 'downloads.school_id')
           // ->select('downloads.*', 'school.school_name');
            return Datatables::of($Downloaddata)
                ->addIndexColumn()
                ->editColumn('image', function ($row) {
                    $ImagePath = $row->image ? $row->image : 'no_image.png';
                    return '<img src="' . url('uploads/download/' . $ImagePath) . '" width="32" height="32" class="bg-light my-n1"
                    >';
                })
                ->editColumn('description', function ($row) {
                    return $row->description ?? '';
                })
                ->editColumn('status', function ($row) {                  
                    $status_btn = '<a href="javascript:void(0);"  id="status_' . $row->id . '" data-id="' . $row->id . '" data-status="' . $row->status . '" class="change_status text-white badge bg-' . ($row->status == 1 ? 'success' : 'danger') . '">' . ($row->status == 1 ? 'Demo' : 'Paid') . '</a>';
                    return $status_btn;
                })
                ->addColumn('action', function ($row) {
                    $edit_btn = '<a href="' . route('edit-download', ['download_id' => $row->id]) . '"
                    class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Edit</a>';
                    $user = Auth::user();
                    $remove_btn = ($user->usertype == "superadmin") ? ' <a href="javascript:void(0);" data-id='.$row->id.'
                    class="waves-effect waves-light btn btn-sm btn-outline btn-danger remove_school_data mb-5">Delete</a>' : '';
                    $action_btn = $edit_btn . $remove_btn;
                    return $action_btn;
                })
                ->rawColumns(['action', 'description', 'image', 'status'])
                ->make(true);
        }
       return view('download.download-list');
    }
    public function addDownload(Request $request)
    { 
       $school_all_data = School::get();
       return view('download.download-add', compact('school_all_data'));
    }

    public function addstoreDownload(Request $request)
    {
        $valid_rule = [
            'description' => 'required',
            'image' => 'required',
            'school_ids' => 'required',
        ];
        $request->validate($valid_rule);
        if ($image = $request->file('image')) {
            $destinationPath = 'uploads/download/';
            $originalname = $image->hashName();
            $imageName = "plan_" . date('Ymd') . '_' . $originalname;
            $image->move($destinationPath, $imageName);
        }
        $school_ids = !empty($request->school_ids) ? implode(",", $request->school_ids) : 0;
        $downloadData = [
            'description' => $request->description,
            'image' => $imageName,
            'school_id' => $school_ids,
            'status' => 1, 
        ];
        Download::create($downloadData);
        return redirect(route('download-list'))->with(['message' => 'Download added successfully!', 'status' => 'success']);
   
    }

    public function editdownload(Request $request)
    {
        $school_all_data = School::get();
        $download_id = $request->input('download_id');
        $download_data = Download::where('id', $download_id)->first();
        $school_ids = isset($download_data->school_id) ? explode(",", $download_data->school_id) : [];
        return view('download.download-edit', compact('download_data','school_all_data','school_ids'));
    }

    public function updateDownload(Request $request)
    {
        $download_id = $request->input('download_id');
        $valid_rule = [
            'description' => 'required',
            'school_ids' => 'required',
        ];
        $request->validate($valid_rule);
        if ($image = $request->file('image')) {
            $destinationPath = 'uploads/download/';
            $originalname = $image->hashName();
            $imageName = "plan_" . date('Ymd') . '_' . $originalname;
            $image->move($destinationPath, $imageName);
            $image_path = $destinationPath . $request->old_image;
            @unlink($image_path);
        }else {
            $imageName = $request->old_image;
        }
        $school_ids = !empty($request->school_ids) ? implode(",", $request->school_ids) : 0;
        $downloadData = [
            'description' => $request->description,
            'image' => $imageName,
            'school_id' => $school_ids,
            'status' => 1, 
        ];
        Download::where('id', $download_id)->update($downloadData);
        return redirect(route('download-list'))->with(['message' => 'Download update successfully!', 'status' => 'success']);
   
    }


    public function downloadadminPassword(Request $request)
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

    public function downloadDestroy(Request $request)
    {
        $download_id = $request->input('download_id');
        $sr_data = Download::where('id', $download_id)->first();
        if(!empty($sr_data)){
            $sr_data->delete();
            echo "removed";
        }
    }

    public function changeDownloadstatus(Request $request)
    {
        $download_id = $request->download_id;
        $status = ($request->status == 1) ? 0 : 1;
        Download::where('id', $download_id)->update(['status' => $status]);
        echo ($status == 1) ? 'Demo' : 'Paid';
    }

    public function schoolAdminDownload(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $schoolId = $user->school_id;
        $download_data = DB::table('downloads')
        ->whereNull('deleted_at')
        ->whereRaw("FIND_IN_SET($schoolId, school_id)") // Using FIND_IN_SET function
        ->get();
        $check_premium = School::find($schoolId);
        return view('download.download-admin-list', compact('download_data','check_premium'));
        //dd($healtymind_data);
    }
}
