<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\{User, School, LogsModel, NcfAssessment, HealtyMind, Download, NCFAssessmentSroting};
use DataTables;
use Illuminate\Support\Facades\Hash;


class NcfAssessmentController extends Controller
{
    public function indexAssessment(Request $request)
    {


        if ($request->ajax()) {
            $NcfAssessmentdata = NcfAssessment::query()->orderBy('id','desc');
            return Datatables::of($NcfAssessmentdata)
                ->addIndexColumn()
                ->editColumn('ncf_assessment_image', function ($row) {
                    $ImagePath = $row->ncf_assessment_image ? $row->ncf_assessment_image : 'no_image.png';
                    return '<img src="' . url('uploads/ncf_assessment/' . $ImagePath) . '" width="32" height="32" class="bg-light my-n1"
                    alt="' . $row->title . '">';
                })
                ->editColumn('title', function ($row) {
                    return $row->title ?? '';
                })
                ->editColumn('description', function ($row) {
                    return $row->description ?? '';
                })
                /* ->editColumn('ncf_assessment_file', function ($row) {
                    return $row->ncf_assessment_file ?? '';
                }) */
                /* ->editColumn('video_url', function ($row) {
                    return $row->video_url ?? '';
                }) */
                /* ->editColumn('healty_mind_file', function ($row) {
                   
                    return '<a href="' . url("uploads/ncf_assessment/{$row->healty_mind_file}") . '" class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5" download>Download</a>';
                
                }) */
                 ->editColumn('ncf_assessment_file', function ($row) {
                    return  '<a href="' . url("uploads/ncf_assessment/{$row->ncf_assessment_file}") . '" class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5" download>Download</a><br>';
                    
                }) 

                ->editColumn('status', function ($row) {                  
                    $status_btn = '<a href="javascript:void(0);"  id="status_' . $row->id . '" data-id="' . $row->id . '" data-status="' . $row->status . '" class="change_status text-white badge bg-' . ($row->status == 1 ? 'success' : 'danger') . '">' . ($row->status == 1 ? 'Demo' : 'Paid') . '</a>';
                    return $status_btn;
                })
                
                ->addColumn('action', function ($row) {
                    $edit_btn = '<a href="' . route('edit-ncf-assessment', ['ncf_assessment_id' => $row->id]) . '"
                    class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Edit</a>';
                    $user = Auth::user();
                    $remove_btn = ($user->usertype == "superadmin") ? ' <a href="javascript:void(0);" data-id='.$row->id.'
                    class="waves-effect waves-light btn btn-sm btn-outline btn-danger remove_school_data mb-5">Delete</a>' : '';
                    $action_btn = $edit_btn . $remove_btn;
                    return $action_btn;
                })
                ->rawColumns(['action', 'title', 'ncf_assessment_image', 'ncf_assessment_file', 'description', 'status'])
                ->make(true);
        }

        return view('assessments.ncf-assessment-list');
    }

        public function addNcfAssessment(Request $request)
        {
            return view('assessments.ncf-assessment-add');
        }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            //'video_url' => ['required','regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i'],
            'description' => 'required',
            'image' => 'required|image|mimes:png,jpg,webp|max:250',
            'upload_file' => 'required|mimes:xlsx|max:250',
           // 'upload_file' => 'required|array',
           // 'upload_file.*.file' => 'required|mimes:pdf,xlsx|max:250',
        ]);
        $aiModuleMsgRule = [];
        /* foreach ($request->upload_file as $cindex => $competence_data) {
            $aiModuleMsgRule['upload_file.'.$cindex.'.file'] = 'Row' .($cindex+1). '-'. 'File';
        } */
        $validator->setAttributeNames($aiModuleMsgRule);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                ]);
        }
        if($validator->passes()){
            if ($image = $request->file('image')) {
                $destinationPath = 'uploads/ncf_assessment/';
                $originalname = $image->hashName();
                $imageName = "ncf_" . date('Ymd') . '_' . $originalname;
                $image->move($destinationPath, $imageName);
            }
            if ($upload_file = $request->file('upload_file')) {
                $destinationPath = 'uploads/ncf_assessment/';
                $originalname = $upload_file->hashName();
                $upload_fileName = "ncf_" . date('Ymd') . '_' . $originalname;
                $upload_file->move($destinationPath, $upload_fileName);
            }
            /* $upload_file = [];
            foreach ($request->upload_file as $activityData) {
                $file = $activityData['file'] ?? null;
                if ($file) {
                      $destinationPath = 'uploads/ncf_assessment/';
                      $originalName = $file->getClientOriginalName();
                      $imageNameactivity = "healty_mind_" . date('Ymd') . '_' . $originalName;
                      $file->move($destinationPath, $imageNameactivity);
                    $activityData['file'] = $imageNameactivity;
                }
                $upload_file[] = $activityData;
            } */
            $NcfAssessmentData = [
                'title' => $request->title,
                'description' => $request->description,
                'ncf_assessment_image' => $imageName,
                'ncf_assessment_file' => $upload_fileName,
                /* 'healty_mind_upload_file' => json_encode([
                    'upload_file' => $upload_file ?? []
                ]), */
                'status' => 1, 
            ];
            NcfAssessment::create($NcfAssessmentData);
        return response()->json([
            'status' => true,
            'message' => "Ncf Assessment added successfully!",
        ], 201);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
             ]);
        
         }
    }

    public function editNcfAssessment(Request $request)
    {
        $ncf_assessment_id = $request->input('ncf_assessment_id');
        $ncf_assessment_data = NcfAssessment::where('id', $ncf_assessment_id)->first();
        return view('assessments.ncf-assessment-edit', compact('ncf_assessment_data'));
    }

    public function updateNcfAssessment(Request $request)
    {
        
        $ncf_assessment_id = $request->input('ncf_assessment_id');
            $validator = Validator::make($request->all(), [
                'title' => 'required|string',
                //'video_url' => ['required','regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i'],
                'description' => 'required',
            ]);
       // dd($ncf_assessment_id);
            if($validator->passes()){
                if ($image = $request->file('image')) {
                    $destinationPath = 'uploads/ncf_assessment/';
                    $originalname = $image->hashName();
                    $imageName = "ncf_" . date('Ymd') . '_' . $originalname;
                    $image->move($destinationPath, $imageName);
                    $image_path = $destinationPath . $request->old_image;
                    @unlink($image_path);
                }else {
                    $imageName = $request->old_image;
                }
                if ($upload_file = $request->file('upload_file')) {
                    $destinationPath = 'uploads/ncf_assessment/';
                    $originalname = $upload_file->hashName();
                    $upload_fileName = "ncf_" . date('Ymd') . '_' . $originalname;
                    $upload_file->move($destinationPath, $upload_fileName);
                    $image_path = $destinationPath . $request->old_upload_file;
                    @unlink($image_path);
                }else {
                    $upload_fileName = $request->old_upload_file;
                }


                /* $upload_file = [];
                foreach ($request->upload_file as $activityData) {
                    $file = $activityData['file'] ?? null;
                    
                    if ($file != 'undefined') {
                          $destinationPath = 'uploads/healty_mind/';
                          $originalName = $file->getClientOriginalName();
                          $imageNameactivity = "healty_mind_" . date('Ymd') . '_' . $originalName;
                          $file->move($destinationPath, $imageNameactivity);
                        $activityData['file'] = $imageNameactivity;
                    }else{
                        $activityData['file'] = $activityData['old_upload_file'];
                    }
                    $upload_file[] = $activityData;
                   
                } */
                $healtymindData = [
                    'title' => $request->title,
                    'description' => $request->description,
                    'ncf_assessment_image' => $imageName,
                    'ncf_assessment_file' => $upload_fileName,
                    /* 'healty_mind_upload_file' => json_encode([
                        'upload_file' => $upload_file ?? []
                    ]),  */
                    'status' => 1, 
                ];
                //HealtyMind::create($healtymindData);
                NcfAssessment::where('id', $ncf_assessment_id)->update($healtymindData);
            return response()->json([
                'status' => true,
                'message' => "Ncf Assessment update successfully!",
            ], 201);
    
            }else{
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors(),
                 ]);
            
             }

    }

    public function ncfAssessmentVerify(Request $request)
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
        $ncf_assessment_id = $request->input('ncf_assessment_id');
        $sr_data = NcfAssessment::where('id', $ncf_assessment_id)->first();
        if(!empty($sr_data)){
            $sr_data->delete();
            echo "removed";
        }
    }

    public function changeStatus(Request $request)
    {
        $statusId = $request->sts_id;
        $status = ($request->status == 1) ? 0 : 1;
        NcfAssessment::where('id', $statusId)->update(['status' => $status]);
        echo ($status == 1) ? 'Demo' : 'Paid';
    }
    public function teacherNcfAssessments(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $schoolId = $user->school_id;
        $check_premium = School::find($schoolId);
        $ncf_assessments = DB::table('ncf_assessments')->whereNull('deleted_at')->get();


        $lpIds = $ncf_assessments->pluck('id');
        $planSorting = NCFAssessmentSroting::whereIn('ncf_assessment_id', $lpIds)->get()->pluck('position_order', 'ncf_assessment_id');

        $ncf_assessment_data = $ncf_assessments->map(function ($item) use ($planSorting) {
            $item = (array) $item; 
            $item['position'] = $planSorting->has($item['id']) ? $planSorting[$item['id']] : 0;
            return (object) $item;
        })->sortBy('position');

        return view('assessments.ncf-assessment-teacher', compact('ncf_assessment_data','check_premium'));
    }

    public function getNcfSorting(Request $request)
    {
        $ncfAssessmentDatas = DB::table('ncf_assessments')->whereNull('deleted_at')->get();

        $ncf_assessments_sorting_list = DB::table('ncf_assessments_sorting')->get(['ncf_assessment_id', 'position_order'])->toArray();
        $postionId = 0;
        $sortedList = [];
        foreach ($ncfAssessmentDatas as $ncfAssessmentData) {
            if (!empty($ncf_assessments_sorting_list)) {
                $sortKey = array_search($ncfAssessmentData->id, array_column($ncf_assessments_sorting_list, 'ncf_assessment_id'));
                $postionId = $ncf_assessments_sorting_list[$sortKey]->position_order;
            }
            $sortedList[] = ['id' => $ncfAssessmentData->id, 'title' => $ncfAssessmentData->title, 'position' => $postionId];
        }
        $ncfAssessmentData = collect($sortedList)->sortBy('position');
        return view('assessments.assessment-list-sorting', compact('ncfAssessmentData'));
    }
    public function updateNcfSortingNumber(Request $request)
    {
        $position = $request->position;
        $i = 1;
        if (!empty($position)) {
            foreach ($position as $key => $ncf_id) {
                DB::table('ncf_assessments_sorting')->updateOrInsert(
                    [
                        'ncf_assessment_id' => (int)$ncf_id,
                    ],
                    ['position_order' => $i]
                );
                $i++;
            }
        }
    }

}
