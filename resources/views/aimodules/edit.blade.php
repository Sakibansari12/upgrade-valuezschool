@extends('layout.main')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title"><!-- AI Module --></h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item alignment-text-new" aria-current="page"><a href="{{ route('aimodules.list') }}"><i class="mdi mdi-home-outline"></i> - AI Module</a></li>
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page">Edit AI Module</li>
                        </ol>
                    </nav>
                </div>
            </div>

        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-lg-8 col-12">
                <!-- Basic Forms -->
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Update AI Module</h4>
                    </div>
                    <!-- /.box-header -->
                    <form action="" id="aimodulesupdate" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                        <div class="form-group">
                            <label for="identifier ">Identifier  <span class="text-danger">*</span></label>
                            <select name="identifier" id="identifier" class="form-control">
                                <option value="">Select Identifier</option>
                                <!-- <option value="chatgpt">ChatGPT</option>
                                <option value="dalle">Dalle</option> -->

                                <option {{ ($aimoduledata->type == 'chatgpt') ? 'selected' : '' }} value="chatgpt">ChatGPT</option>
                                <option {{ ($aimoduledata->type == 'dalle') ? 'selected' : '' }} value="dalle">Dalle</option>
                                <option {{ ($aimoduledata->type == 'text_to_speech') ? 'selected' : '' }} value="text_to_speech">Text To Speech</option>
                                <option {{ ($aimoduledata->type == 'speech_to_text') ? 'selected' : '' }} value="speech_to_text">Speech To Text</option>
                                <option {{ ($aimoduledata->type == 'text_to_music') ? 'selected' : '' }} value="text_to_music">Text To Music</option>
                                <option {{ ($aimoduledata->type == 'generate_avatar') ? 'selected' : '' }} value="generate_avatar">Generate Avatar</option>
                                <option {{ ($aimoduledata->type == 'photo_to_video') ? 'selected' : '' }} value="photo_to_video">Photo to Video</option>
                                <option {{ ($aimoduledata->type == 'bird_classifier') ? 'selected' : '' }} value="bird_classifier">Bird Classifier</option>
                                <option {{ ($aimoduledata->type == 'fauna_and_flora') ? 'selected' : '' }} value="fauna_and_flora">Fauna and Flora</option>
                                <option {{ ($aimoduledata->type == 'image_to_narration') ? 'selected' : '' }} value="image_to_narration">Image to Narration</option>
                                <!-- <option {{ ($aimoduledata->type == 'bird_classifier') ? 'selected' : '' }} value="bird_classifier">Bird Classifier</option>
                                <option {{ ($aimoduledata->type == 'plant_recognizer') ? 'selected' : '' }} value="plant_recognizer">Plant Recognizer</option> -->
                                
                            </select>
                            <p></p>
                            @error('grade')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        </div>


                            <div class="form-group">
                                 <input type="hidden" name="aimodule_id" id="aimodule_id" value="{{ $aimoduledata->id}}">
                                <label class="form-label">Module Name <span class="text-danger">*</span></label>
                                <input type="text" name="module_name" value="{{ $aimoduledata->display_name}}" id="module_name" class="form-control" placeholder="Module Name">
                                <p></p>
                                @error('module_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group" id="content_module_page_title">
                                <label class="form-label">Module page title <span class="text-danger">*</span></label>
                                <input type="text" name="module_page_title" id="module_page_title" value="{{ $aimoduledata->content->module_page_title}}" class="form-control" placeholder="Module page title">
                                <p></p>
                                @error('module_page_title')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <p></p>
                            <div class="form-group" id="grades">
                                <label class="form-label">Grade <span class="text-danger">*</span></label>
                                <select class="form-control select21" id="grade" name="class_id[]" style="width: 100%;"
                                    multiple="multiple" >
                                    @foreach ($program_list as $prog)
                                        @php $classIds = explode(",",$aimoduledata->grade_id); @endphp
                                        <option value="{{ $prog->id }}"
                                            {{ in_array($prog->id, $classIds) ? 'selected' : '' }}>
                                            {{ $prog->class_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <p></p>
                            <div class="form-group">
                                <label class="form-label">Course <span class="text-danger">*</span></label>
                                <select class="form-control select2" id="course" name="course_id" style="width: 100%;">
                                    @foreach ($course_list as $course)
                                        <option value="{{ $course->id }}"
                                            {{ $course->id == $aimoduledata->course_id ? 'selected' : '' }}>
                                            {{ $course->course_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Module Thumbnail <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" name="module_thumbnail" id="module_thumbnail">
                                <p></p>
                                <input type="hidden" id="update_module_thumbnail" value="{{ $aimoduledata->thumbnail ? $aimoduledata->thumbnail : 'no_image.png' }}">
                                <img id="imagePreview" src="{{ url('uploads/aimodule') }}/{{ $aimoduledata->thumbnail ? $aimoduledata->thumbnail : 'no_image.png' }}"
                                    width="100px">
                                
                                @error('thumbnail')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                             <div class="form-group" id="content_module_page_overview">
                                <label class="form-label">Module Page Overview <span class="text-danger">*</span></label>
                                <textarea  name="module_page_overview" id="module_page_overview" class="form-control" rows="4" placeholder="Module Page Overview">{{ isset($aimoduledata->content->module_page_overview) ? $aimoduledata->content->module_page_overview : '' }}</textarea>
                                @error('module_page_overview')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div> 
                             <p></p>
                            <div class="form-group" id="content_video_url">
                                <label class="form-label">Video url <span class="text-danger">*</span></label>
                                <input type="text" name="video_url" id="video_url" value="{{ isset($aimoduledata->content->video_url) ? $aimoduledata->content->video_url : ''}}" class="form-control" placeholder="Video url">
                                <p></p>
                                @error('video_url')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <p></p>
                            <hr>
                        </div>

                        <!-- <div class="box-body">
                                <div class="form-group" id="prompts-fields-add">
                                    <label class="form-label">Prompts <span class="text-danger">*</span></label>
                                    <div id="dynamic-fields" class="form-inline">
                                    @if (!empty($aimoduledata->promptsdata))
                                        @foreach ($aimoduledata->promptsdata as $key => $cdata)
                                        <div class="input-group">
                                            <textarea name="questions[]" class="form-control m-2" rows="4" placeholder="Questions">{{ isset($cdata->question) ? $cdata->question : '' }}</textarea>
                                            <textarea name="answers[]" class="form-control m-2" rows="4" placeholder="Answers">{{ isset($cdata->answer) ? $cdata->answer : '' }}</textarea>
                                            @if ($key + 1 == 1)
                                            <div class="input-group-append mt-2">
                                                <button type="button" class="btn btn-primary small-button-prompts" id="add-fields">Add Prompts</button>
                                            </div>
                                            @else
                                            <div class="input-group-append mt-2">
                                                <button type="button" class="btn btn-danger small-button-remove remove-field">Remove</button>
                                            </div>
                                            @endif
                                        </div>
                                        @endforeach
                                      @endif
                                    </div>
                                    @error('questions')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            <hr>
                        </div> -->
<div class="box-body">
    <div class="form-group" id="activitie-fields-add">
        <label class="form-label">Prompts <span class="text-danger">*</span></label>
        <div class="row" id="dynamic-fields">
        @if (!empty($aimoduledata->promptsdata))
          @foreach ($aimoduledata->promptsdata as $key => $cdata)
          <div id="prompts-removie-data" class="row">
            <div class="col-md-9">
                <div class="form-inline-Prompts">
                    <div class="mb-3">
                        <textarea name="prompts[]" class="form-control" rows="4" placeholder="Prompts">{{ isset($cdata->prompts) ? $cdata->prompts : '' }}</textarea>
                    </div>
                    <div class="mb-3">
                        <textarea name="response[]" class="form-control" rows="4" placeholder="Response">{{ isset($cdata->response) ? $cdata->response : '' }}</textarea>
                    </div>
                    <div class="mb-3">
                        <input type="file" class="form-control form-control" name="answers_file[]" id="answers_file_1">
                        <input type="hidden" id="old_answers_file_{{$key + 1}}" name="old_answer_image[]" value="{{ $cdata->file ? $cdata->file : 'no_image.png' }}">
                         <input type="hidden" id="old_music_file_{{$key + 1}}" name="old_music_file[]" value="{{ $cdata->file_all_data ? json_encode($cdata->file_all_data) : '' }}">
                         
                        
                       <!--  <img src="{{ url('uploads/aimodule') }}/{{ $cdata->file ? $cdata->file : 'no_image.png' }}"
                                    width="100px"> -->
                                    @if (pathinfo($cdata->file, PATHINFO_EXTENSION) === 'mp3')
                            <audio controls>
                                <source src="{{ url('uploads/aimodule', $cdata->file) }}" type="audio/mp3">
                                Your browser does not support the audio element.
                            </audio>
                        @else
                            <img src="{{ url('uploads/aimodule', $cdata->file ? $cdata->file : 'no_image.png') }}" width="100px">
                        @endif

                    </div>
                    
                </div>
            </div>
            
            @if ($key + 1 == 1)
            <div class="col-md-3">
                <div class="text-md-end">
                    <button type="button" class="btn small-button-prompts" style="background-color: #00205c; color: #fff;" id="add-fields">Add Prompts</button>
                </div>
            </div>
            <hr>
            @else
            <div class="col-md-3">
                <div class="text-md-end">
                   <button type="button" class="btn btn-danger small-button-remove remove-field">Remove</button>
                </div>
            </div>
            <hr>
            @endif
            </div>
            @endforeach
        @endif
        </div>
        @error('questions')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>
                     
<div class="box-body">
    <div class="form-group" id="activitie-fields-add">
        <label class="form-label">Activities <span class="text-danger">*</span></label>
        <div class="row" id="dynamic-fields-activitie">
        @if (!empty($aimoduledata->activitiesdata))
             @foreach ($aimoduledata->activitiesdata as $key => $cdata)
        <div id="prompts-removie-data" class="row">
            <div class="col-md-9">
                <div class="form-inline-activities">
                    <div class="mb-3">
                    <!-- <label class="form-label">Activities Description <span class="text-danger">*</span></label> -->
                        <textarea name="activities_description[]" class="form-control" rows="4" placeholder="Activities Description">{{ isset($cdata->activities_description) ? $cdata->activities_description : '' }}</textarea>
                    </div>
                    <div class="mb-3">
                    <label class="form-label">Website Url <span class="text-danger">*</span></label>
                        <input type="text" name="website_url[]" value="{{ isset($cdata->website_url) ? $cdata->website_url : ''}}" class="form-control" placeholder="Website Url">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Prompt Screenshots <span class="text-danger">*</span></label>
                        <input type="file" class="form-control form-control prompt-screenshot" name="prompt_screenshot[]">
                        <input type="hidden" id="old_prompt_screenshot_{{$key + 1}}" name="old_prompt_screenshot_image[]" value="{{ isset($cdata->prompt_screenshot) ? $cdata->prompt_screenshot : 'no_image.png' }}">
                        
                        <img src="{{ url('uploads/aimodule') }}/{{ isset($cdata->prompt_screenshot) ? $cdata->prompt_screenshot : 'no_image.png' }}"
                                    width="100px">
                    </div>
                </div>
            </div>
            @if ($key + 1 == 1)
            <div class="col-md-3">
                <div class="text-md-end">
                    <button type="button" class="btn small-button-activities" style="background-color: #00205c; color: #fff;" id="add-fields-activitie">Add Activities</button>
                </div>
            </div>
            <hr>
            @else
            <div class="col-md-3">
                <div class="text-md-end">
                  <button type="button" class="btn btn-danger small-button-remove remove-field">Remove</button>
                </div>
            </div>
            <hr>
            @endif
            </div>
            @endforeach
            
        @else
        <div class="col-md-9">
                <div class="form-inline-activities">
                    <div class="mb-3">
                    <!-- <label class="form-label">Activities Description <span class="text-danger">*</span></label> -->
                        <textarea name="activities_description[]" class="form-control" rows="4" placeholder="Activities Description"></textarea>
                    </div>
                    <div class="mb-3">
                    <label class="form-label">Website Url <span class="text-danger">*</span></label>
                        <input type="text" name="website_url[]" class="form-control" placeholder="Website Url">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Prompt Screenshots <span class="text-danger">*</span></label>
                        <input type="file" class="form-control form-control prompt-screenshot" name="prompt_screenshot[]">
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-md-end">
                    <button type="button" class="btn small-button-activities" style="background-color: #00205c; color: #fff;" id="add-fields-activitie">Add Activities</button>
                </div>
            </div>
            <hr>    
        @endif
        </div>

        @error('questions')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>

<!-- Add Own Activity Promts -->
                      
<div class="box-body">
    <div class="form-group" id="activitie-fields-add">
        <label class="form-label">Add Own Activities Prompts <span class="text-danger">*</span></label>
        <div class="row" id="add-own-activity-fields-dynamic">
        @if (!empty($aimoduledata->AddOwnactivitiesdata))
             @foreach ($aimoduledata->AddOwnactivitiesdata as $key => $cdata)
        <div id="prompts-removie-data" class="row">
            <div class="col-md-9">
                <div class="form-inline-own-activities">
                    <div class="mb-3">
                    <!-- <label class="form-label">Activities Description <span class="text-danger">*</span></label> -->
                        <textarea name="add_placeholder_text[]" class="form-control" rows="4" placeholder="Add Placeholder Text">{{ isset($cdata->add_placeholder_text) ? $cdata->add_placeholder_text : '' }}</textarea>
                    </div>
                    <div class="mb-3">
                    <label class="form-label">Add AI Tool Url <span class="text-danger">*</span></label>
                        <input type="text" name="add_ai_toll_url[]" value="{{ isset($cdata->add_ai_toll_url) ? $cdata->add_ai_toll_url : '' }}" class="form-control" placeholder="Add AI Tool Url">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tool Name <span class="text-danger">*</span></label>
                        <input type="text" name="toll_name[]" value="{{ isset($cdata->toll_name) ? $cdata->toll_name : '' }}" class="form-control" placeholder="Tool Name">
                    </div>
                </div>
            </div>
            @if ($key + 1 == 1)
            <div class="col-md-3">
                <div class="text-md-end">
                    <button type="button" class="btn small-button-activities"  style="font-weight: 350; background-color: #00205c; color: #fff;" id="add-own-activities-fields">Add Own Activities</button>
                </div>
            </div>
            <hr>
            @else
            <div class="col-md-3">
                <div class="text-md-end">
                  <button type="button" class="btn btn-danger small-button-remove remove-field">Remove</button>
                </div>
            </div>
            <hr>
            @endif
            </div>
            @endforeach
        @else   
        <div class="col-md-9">
                <div class="form-inline-own-activities">
                    <div class="mb-3">
                    <!-- <label class="form-label">Activities Description <span class="text-danger">*</span></label> -->
                        <textarea name="add_placeholder_text[]" class="form-control" rows="4" placeholder="Add Placeholder Text"></textarea>
                    </div>
                    <div class="mb-3">
                    <label class="form-label">Add AI Tool Url <span class="text-danger">*</span></label>
                        <input type="text" name="add_ai_toll_url[]" class="form-control" placeholder="Add AI Tool Url">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tool Name <span class="text-danger">*</span></label>
                        <input type="text" name="toll_name[]" class="form-control" placeholder="Tool Name">
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-md-end">
                    <button type="button" class="btn small-button-activities" style="background-color: #00205c; color: #fff;" id="add-own-activities-fields">Add Own Activities</button>
                </div>
            </div>
            <hr>
        @endif
            
        </div>

        @error('questions')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="box-body">
    <div class="form-group" id="activitie-fields-add">
        <div class="row">
            <div id="package-report-video">
            @if (!empty($aimoduledata->hybridsdata))
          @foreach ($aimoduledata->hybridsdata as $key => $cdata)
                <div class="add_report_video">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-inline-hybrid">
                                <div class="mb-3">
                                <label class="form-label">Animal 1 </label>
                                    <input type="file" name="animal_one[]" id="animal_one" class="form-control">
                                    <input type="text" name="animal_one_name[]" id="animal_one_name" value="{{ $cdata->animal_one_name ? $cdata->animal_one_name : '' }}" class="form-control mt-1">

                                    <input type="hidden" name="update_animal_one[]" id="update_animal_one" value="{{ $cdata->animal_one_new ? $cdata->animal_one_new : 'no_image.png' }}">
                                   <img id="imagePreview" src="{{ url('uploads/hybrid') }}/{{ $cdata->animal_one_new ? $cdata->animal_one_new : 'no_image.png' }}"
                                    width="100px">


                                </div>
                                <div class="mb-3">
                                <label class="form-label">Animal 2 </label>
                                    <input type="file" name="animal_second[]" id="animal_second" class="form-control">
                                    <input type="text" name="animal_second_name[]" id="animal_second_name" value="{{ $cdata->animal_second_name ? $cdata->animal_second_name : '' }}" class="form-control mt-1">
                                    <input type="hidden" id="update_animal_second" name="update_animal_second[]" value="{{ $cdata->animal_second_new ? $cdata->animal_second_new : 'no_image.png' }}">
                                    <img id="imagePreview" src="{{ url('uploads/hybrid') }}/{{ $cdata->animal_second_new ? $cdata->animal_second_new : 'no_image.png' }}"
                                    width="100px">
                                </div>
                                <div class="mb-3">
                                <label class="form-label">Result </label>
                                    <input type="file" name="result[]" id="result" class="form-control">
                                    <input type="text" name="result_name[]" id="result_name" value="{{ $cdata->result_name ? $cdata->result_name : '' }}" class="form-control mt-1">
                                    <input type="hidden" name="update_result[]" id="update_result" value="{{ $cdata->result_new ? $cdata->result_new : 'no_image.png' }}">
                                    <img id="imagePreview" src="{{ url('uploads/hybrid') }}/{{ $cdata->result_new ? $cdata->result_new : 'no_image.png' }}"
                                    width="100px">
                                </div>
                            </div>
                        </div>
                        @if ($key + 1 == 1)
                        <div class="col-md-3">
                            <div class="text-md-end mt-4">
                               <button type="button" class="btn small-button-activities add-report-video" style="background-color: #00205c; color: #fff;" >Add Hybrid</button>
                            </div>
                        </div>
                        <hr>
                        @else
                        <div class="col-md-3">
                            <div class="text-md-end">
                            <button type="button" class="btn btn-danger small-button-remove remove-field">Remove</button>
                            </div>
                        </div>
                        <hr>
                        @endif
                    </div>   
                </div>
                @endforeach
        @endif
            </div>
        </div>
    </div>
</div>

<!-- Slider Start -->       
<div class="box-body">
    <div class="form-group" id="activitie-fields-add">
        <div class="row">
            <div id="slider-video-data">
            @if (!empty($aimoduledata->sliderdata))
          @foreach ($aimoduledata->sliderdata as $key => $cdata)
                <div class="add_slider_video">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-inline-slider">
                                <div class="mb-3">
                                    <label class="form-label">Slider Part</label>
                                    <input type="file" name="slider_image[]" id="slider_image"  class="form-control">

                                    <input type="hidden" id="update_slider_image" name="update_slider_image[]" value="{{ $cdata->slider_image_new ? $cdata->slider_image_new : '' }}">

                                    <img id="imagePreview" src="{{ url('uploads/hybrid') }}/{{ $cdata->slider_image_new ? $cdata->slider_image_new : 'no_image.png' }}"
                                    width="100px">

                                    <input type="text" name="slider_video[]" id="slider_video" value="{{ $cdata->slider_video ? $cdata->slider_video : '' }}" placeholder="Enter Video Url" class="form-control mt-1">
                                    <input type="text" name="slider_text[]" id="slider_text" value="{{ $cdata->slider_text ? $cdata->slider_text : '' }}"   placeholder="Enter Video Text" class="form-control mt-1">
                                </div>
                            </div>
                        </div>
                        @if ($key + 1 == 1)
                        <div class="col-md-3">
                            <div class="text-md-end mt-4">
                                <button type="button" class="btn small-button-activities add-slider-video" style="background-color: #00205c; color: #fff;" >Add Slider</button>
                            </div>
                        </div>
                        <hr>
                        @else
                        <div class="col-md-3">
                            <div class="text-md-end">
                            <button type="button" class="btn btn-danger small-button-remove remove-field">Remove</button>
                            </div>
                        </div>
                        <hr>
                        @endif
                    </div>   
                </div>
                @endforeach
              @endif
            </div>
        </div>
    </div>
</div>  
<!-- Slider End -->         
                        

<!-- Vision Start -->       
<div class="box-body">
    <div class="form-group" id="activitie-fields-add">
        <div class="row">
            <div id="vision-data">
            @if (!empty($aimoduledata->visiondata))
              @foreach ($aimoduledata->visiondata as $key => $cdata)
                <div class="add_vision_data">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-inline-vision">
                                <div class="mb-3">
                                    <label class="form-label">Vision Part</label>
                                    <input type="file" name="vision_image[]" id="vision_image"  class="form-control">


                                    <input type="hidden" id="update_vision_image" name="update_vision_image[]" value="{{ $cdata->vision_image_new ? $cdata->vision_image_new : '' }}">
                                    <img id="imagePreview" src="{{ url('uploads/vision') }}/{{ $cdata->vision_image_new ? $cdata->vision_image_new : 'no_image.png' }}"
                                    width="100px">



                                    <input type="text" name="vision_text[]" id="vision_text" value="{{ $cdata->vision_text ? $cdata->vision_text : '' }}" placeholder="Enter Text" class="form-control mt-1">
                                    <input type="file" name="vision_music[]" id="vision_music"  class="form-control mt-1">

                                    <input type="hidden" id="update_vision_music" name="update_vision_music[]" value="{{ $cdata->vision_music_new ? $cdata->vision_music_new : '' }}">
                                    <audio class="mt-2" src="{{ url('uploads/vision') }}/{{ $cdata->vision_music_new ? $cdata->vision_music_new : '' }}" controls></audio>
                                </div>
                            </div>
                        </div>

                        @if ($key + 1 == 1)
                        <div class="col-md-3">
                            <div class="text-md-end mt-4">
                                <button type="button" class="btn small-button-activities add-vision-field" style="background-color: #00205c; color: #fff;" >Add Vision</button>
                            </div>
                        </div>
                        <hr>
                        @else
                        <div class="col-md-3">
                            <div class="text-md-end">
                            <button type="button" class="btn btn-danger small-button-remove remove-field">Remove</button>
                            </div>
                        </div>
                        <hr>
                        @endif



                    </div>   
                </div>
                @endforeach
              @endif
            </div>
        </div>
    </div>
</div>  
<!-- Vision End -->

                       
                        <div class="box-footer">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>

        </div>
    </section>
    <!-- /.content -->
@endsection
@section('script-section')
<script src="{{ asset('assets/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.js') }}"></script>

    <script>
        $('.select2').select2();
        $('#grade').select2({
            tags: true
        });
        //Add text editor

        //bootstrap WYSIHTML5 - text editor
        $('#lesson_inst').wysihtml5();
    </script>
<script>
$(document).ready(function() {
    
        $(".remove-field").click(function() {
           // alert(435);
            $(this).closest("#prompts-removie-data").remove();
        });
    });

    /* prompts */
   /* $(document).ready(function() {
    $("#add-fields").click(function() {
        var newRow = $('<div class="input-group mt-2"><textarea name="questions[]" class="form-control m-2" rows="2" placeholder="Questions"></textarea><textarea name="answers[]" class="form-control m-2" rows="2" placeholder="Answers"></textarea><div class="input-group-append mt-2"><button type="button" class="btn btn-danger small-button-remove remove-field">Remove</button></div></div>');
        $("#dynamic-fields").append(newRow);
        newRow.find(".remove-field").click(function() {
            newRow.remove();
        });
    });
}); */

 /* prompts */
 $(document).ready(function() {
    var fieldCounterpromts = 1;
    $("#add-fields").click(function() {
        fieldCounterpromts++;
        var newRow = 
        $(
            '<div class="col-md-9">' +
                '<div class="form-inline-Prompts">' +
                    '<div class="mb-3">' +
                      '<textarea name="prompts[]" class="form-control" rows="4" placeholder="Prompts"></textarea>' +
                    '</div>' +
                    '<div class="mb-3">' +
                      '<textarea name="response[]" class="form-control" rows="4" placeholder="Response"></textarea>' +
                    '</div>' +
                    '<div class="mb-3">' +
                    '<input type="file" class="form-control form-control" name="answers_file[]" id="answers_file_' + fieldCounterpromts + '">' +
                    '</div>' +
                    
                '</div>' +
            '</div>' +
            '<div class="col-md-3">' +
                '<div class="text-md-end">' +
                '<button type="button" class="btn btn-danger small-button-remove remove-field">Remove</button>' +
                '</div>' +
            '</div>'
        );
        $("#dynamic-fields").append(newRow);
        newRow.find(".remove-field").click(function() {
            newRow.remove();
        });
    });
});






/* activities */
/* $(document).ready(function() {
    $("#add-fields-activitie").click(function() {
        var newRow = $('<div class="input-group"><textarea name="questions[]" class="form-control m-2" rows="2" placeholder="Questions"></textarea><textarea name="answers[]" class="form-control m-2" rows="2" placeholder="Answers"></textarea><div class="input-group-append mt-2"><button type="button" class="btn btn-danger small-button-remove remove-field">Remove</button></div></div>');
    
        $("#dynamic-fields-activitie").append(newRow);
        newRow.find(".remove-field").click(function() {
            newRow.remove();
        });
    });
}); */

$(document).ready(function() {
    var fieldCounter = 1;
    $("#add-fields-activitie").click(function() {
        fieldCounter++;
        var newRow = $(
            '<div class="col-md-9">' +
                '<div class="form-inline-activities">' +
                    '<div class="mb-3">' +
                      '<textarea name="activities_description[]" class="form-control" rows="4" placeholder="Activities Description"></textarea>' +
                    '</div>' +
                    '<div class="mb-3">' +
                    '<label class="form-label">Website Url <span class="text-danger">*</span></label>' +
                      '<input type="text" name="website_url[]" class="form-control" placeholder="Website Url">' +
                    '</div>' +
                    '<div class="mb-3">' +
                    '<label class="form-label">Prompt Screenshots <span class="text-danger">*</span></label>' +
                    '<input type="file" class="form-control form-control" name="prompt_screenshot[]" id="prompt_screenshot_' + fieldCounter + '">' +
                    '</div>' +
                '</div>' +
            '</div>' +
            '<div class="col-md-3">' +
                '<div class="text-md-end">' +
                '<button type="button" class="btn btn-danger small-button-remove remove-field">Remove</button>' +
                '</div>' +
            '</div>'
        );
        $("#dynamic-fields-activitie").append(newRow);
        newRow.find(".remove-field").click(function() {
            newRow.remove();
        });
    });

    // For dynamically added remove buttons
    $(document).on("click", ".remove-field", function() {
        $(this).closest('.activity-fields').remove();
    });
});

/* Add Own Activity */
$(document).ready(function() {
    var fieldCounterpromts = 1;
    $("#add-own-activities-fields").click(function() {
        fieldCounterpromts++;
        var newRow = 
        $(
            '<div class="col-md-9">' +
                '<div class="form-inline-own-activities">' +
                    '<div class="mb-3">' +
                      '<textarea name="add_placeholder_text[]" class="form-control" rows="4" placeholder="Add Placeholder Text"></textarea>' +
                    '</div>' +
                    '<div class="mb-3">' +
                    '<label class="form-label">Add AI Tool Url <span class="text-danger">*</span></label>' +
                      '<input type="text" name="add_ai_toll_url[]" class="form-control" placeholder="Add AI Tool Url">' +
                    '</div>' +
                    '<div class="mb-3">' +
                    '<label class="form-label">Tool Name <span class="text-danger">*</span></label>' +
                    '<input type="text" name="toll_name[]" class="form-control" placeholder="Tool Name">' +
                    '</div>' +
                '</div>' +
            '</div>' +
            '<div class="col-md-3">' +
                '<div class="text-md-end">' +
                '<button type="button" class="btn btn-danger small-button-remove remove-field">Remove</button>' +
                '</div>' +
            '</div>' +
            '<hr>'
        );
        $("#add-own-activity-fields-dynamic").append(newRow);
        newRow.find(".remove-field").click(function() {
            newRow.remove();
        });
    });
});


/* $('#aimodulesupdate').submit(function (event) {
    event.preventDefault();
    console.log($('#identifier').val());
    var formData = {
    display_name: $('#display_name').val(),
    aimodule_id: $('#aimodule_id').val(),
    thumbnail: $('#thumbnail').val(),
    identifier: $('#identifier').val(),
    
    grade: $('#grade').val(),
    course: $('#course').val(),
    website_url: $('#website_url').val(),
    content: {
        title: $('#title').val(),
        overview: $('#overview').val(),
        video_url: $('#video_url').val(),
        prompts: [], 
        activities: [] 
    }
};
var id = 1;
$('.form-inline textarea[name="questions[]"]').each(function () {
    var question = $(this).val();
    var answer = $(this).closest('.form-inline').find('textarea[name="answers[]"]').val(); // Find corresponding answer
    var prompt = {
        id: id,
        question: question,
        answer: answer
    };
    id++;
    formData.content.prompts.push(prompt);
});
$('.form-inline-activities textarea[name="questions[]"]').each(function () {
    var question = $(this).val();
    var answer = $(this).closest('.form-inline-activities').find('textarea[name="answers[]"]').val(); // Find corresponding answer

    var activitie = {
        id: id,
        question: question,
        answer: answer
    };
    id++;
    formData.content.activities.push(activitie);
});
    $.ajax({
        url: '{{ route("update-aimodule") }}',
        type: 'POST',
        contentType: 'application/json', 
        data: JSON.stringify(formData), 
        dataType: 'json',
        success: function (response) {
            if (response['status'] == true) {
                window.location.href="{{  route('aimodules.list') }}";
            } else {
                var errors = response['errors'];
                $.each(errors, function (key, value) {
                    $('#' + key).siblings('p').addClass('text-danger').html(value);
                });
            }
        },
        error: function () {
            console.log('Something went wrong');
        }
    });
}); */


/* Add Hybrid */

$(document).ready(function () {
         var wrapper = $('#package-report-video');
        var errorpackage = 0;
        function createFieldHTMLReportVideo() {
            errorpackage++;
            return `
               <div class="add_report_video">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-inline-hybrid">
                                <div class="mb-3">
                                <label class="form-label">Animal 1 </label>
                                    <input type="file" name="animal_one[]" id="animal_one" class="form-control">
                                    <input type="text" name="animal_one_name[]" id="animal_one_name" class="form-control mt-1">
                                </div>
                                <div class="mb-3">
                                <label class="form-label">Animal 2 </label>
                                    <input type="file" name="animal_second[]" id="animal_second" class="form-control">
                                    <input type="text" name="animal_second_name[]" id="animal_second_name" class="form-control mt-1">
                                </div>
                                <div class="mb-3">
                                <label class="form-label">Result </label>
                                    <input type="file" name="result[]" id="result" class="form-control">
                                    <input type="text" name="result_name[]" id="result_name" class="form-control mt-1">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-md-end ">
                                
                                <button type="button" class="btn btn-danger  remove-report-video" style="margin-top: 24px;">Remove</button>
                            </div>
                        </div>
                    </div>   
                </div>

            
            `;
        }
        $('.add-report-video').click(function () {
            wrapper.append(createFieldHTMLReportVideo());
        }); 
        wrapper.on('click', '.remove-report-video', function () {
            $(this).closest('.add_report_video').remove();
        });

    }); 


/* Slider */
$(document).ready(function () {
         var wrapper = $('#slider-video-data');
        var errorpackage = 0;
        function createFieldHTMLReportVideo() {
            errorpackage++;
            return `
               <div class="add_slider_video">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-inline-slider">
                                <div class="mb-3">
                                    <label class="form-label"></label>
                                        <input type="file" name="slider_image[]" id="slider_image"  class="form-control">
                                        <input type="text" name="slider_video[]" id="slider_video" placeholder="Enter Video Url" class="form-control mt-1">
                                        <input type="text" name="slider_text[]" id="slider_text"    placeholder="Enter Video Text" class="form-control mt-1">
                                    </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-md-end ">
                                <button type="button" class="btn btn-danger  remove-report-video" style="margin-top: 24px;">Remove</button>
                            </div>
                        </div>
                    </div>   
                </div>

            
            `;
        }
        $('.add-slider-video').click(function () {
            wrapper.append(createFieldHTMLReportVideo());
        }); 
        wrapper.on('click', '.remove-report-video', function () {
            $(this).closest('.add_slider_video').remove();
        });

    }); 

/* Vision */
$(document).ready(function () {
         var wrapper = $('#vision-data');
        var errorpackage = 0;
        function createFieldHTMLReportVideo() {
            errorpackage++;
            return `
               <div class="add_vision_data">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-inline-vision">
                                <div class="mb-3">
                                    <label class="form-label"></label>
                                    <input type="file" name="vision_image[]" id="vision_image"  class="form-control">
                                    <input type="text" name="vision_text[]" id="vision_text" placeholder="Enter Text" class="form-control mt-1">
                                    <input type="file" name="vision_music[]" id="vision_music"  class="form-control mt-1">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-md-end ">
                                <button type="button" class="btn btn-danger  remove-report-video" style="margin-top: 24px;">Remove</button>
                            </div>
                        </div>
                    </div>   
                </div>

            
            `;
        }
        $('.add-vision-field').click(function () {
            wrapper.append(createFieldHTMLReportVideo());
        }); 
        wrapper.on('click', '.remove-report-video', function () {
            $(this).closest('.add_vision_data').remove();
        });

    }); 

$('#aimodulesupdate').submit(function (event) {
    event.preventDefault();

    
    var dataimage = $('#update_module_thumbnail').val();
console.log(dataimage,"dataimage");
    

   // reader.readAsDataURL(file);


    var formData = new FormData();
    formData.append('module_name', $('#module_name').val());
    formData.append('aimodule_id', $('#aimodule_id').val());
    formData.append('identifier', $('#identifier').val());
    formData.append('old_module_thumbnail', $('#update_module_thumbnail').val());
    formData.append('module_thumbnail', $('#module_thumbnail')[0].files[0]);
    formData.append('grades', $('#grade').val());
    formData.append('course', $('#course').val());
    formData.append('website_url', $('#website_url').val());
    formData.append('content[module_page_title]', $('#module_page_title').val());
    formData.append('content[module_page_overview]', $('#module_page_overview').val());
    formData.append('content[video_url]', $('#video_url').val());

    var id = 1;
    $('.form-inline-Prompts').each(function () {
        formData.append('content[prompts][' + (id - 1) + '][id]', id);
        formData.append('content[prompts][' + (id - 1) + '][prompts]', $(this).find('textarea[name="prompts[]"]').val());
        formData.append('content[prompts][' + (id - 1) + '][response]', $(this).find('textarea[name="response[]"]').val());
        formData.append('content[prompts][' + (id - 1) + '][file]', $(this).find('input[name="answers_file[]"]')[0].files[0]);
        formData.append('content[prompts][' + (id - 1) + '][old_answers_file]', $(this).find('input[name="old_answer_image[]"]').val());
        formData.append('content[prompts][' + (id - 1) + '][old_music_data_file]', $(this).find('input[name="old_music_file[]"]').val());
       // formData.append('content[prompts][' + (id - 1) + '][old_answer_video]', $(this).find('input[name="old_answer_video[]"]').val());
       // formData.append('content[prompts][' + (id - 1) + '][video]', $(this).find('input[name="answers_video[]"]')[0].files[0]);
        id++;
    });

    var id = 1;
    $('.form-inline-activities').each(function () {
        formData.append('content[activities][' + (id - 1) + '][id]', id);
        formData.append('content[activities][' + (id - 1) + '][activities_description]', $(this).find('textarea[name="activities_description[]"]').val());
        formData.append('content[activities][' + (id - 1) + '][website_url]', $(this).find('input[name="website_url[]"]').val());
        formData.append('content[activities][' + (id - 1) + '][prompt_screenshot]', $(this).find('input[name="prompt_screenshot[]"]')[0].files[0]);
        formData.append('content[activities][' + (id - 1) + '][old_prompt_screenshot]', $(this).find('input[name="old_prompt_screenshot_image[]"]').val());
        id++;
    });

    var id = 1;
    $('.form-inline-own-activities').each(function () {
        formData.append('content[add_own_activities_prompts][' + (id - 1) + '][id]', id);
        formData.append('content[add_own_activities_prompts][' + (id - 1) + '][add_placeholder_text]', $(this).find('textarea[name="add_placeholder_text[]"]').val());
        formData.append('content[add_own_activities_prompts][' + (id - 1) + '][add_ai_toll_url]', $(this).find('input[name="add_ai_toll_url[]"]').val());
        formData.append('content[add_own_activities_prompts][' + (id - 1) + '][toll_name]', $(this).find('input[name="toll_name[]"]').val());
        id++;
    });

    var id = 1;
    $('.form-inline-hybrid').each(function () {
        formData.append('hybrid[generate_hybrid][' + (id - 1) + '][id]', id);
        
        formData.append('hybrid[generate_hybrid][' + (id - 1) + '][animal_one]', $(this).find('input[name="animal_one[]"]')[0].files[0]);
        formData.append('hybrid[generate_hybrid][' + (id - 1) + '][update_animal_one]', $(this).find('input[name="update_animal_one[]"]').val());
        formData.append('hybrid[generate_hybrid][' + (id - 1) + '][animal_one_name]', $(this).find('input[name="animal_one_name[]"]').val());

        formData.append('hybrid[generate_hybrid][' + (id - 1) + '][animal_second]', $(this).find('input[name="animal_second[]"]')[0].files[0]);
        formData.append('hybrid[generate_hybrid][' + (id - 1) + '][update_animal_second]', $(this).find('input[name="update_animal_second[]"]').val());
        formData.append('hybrid[generate_hybrid][' + (id - 1) + '][animal_second_name]', $(this).find('input[name="animal_second_name[]"]').val());

        formData.append('hybrid[generate_hybrid][' + (id - 1) + '][result]', $(this).find('input[name="result[]"]')[0].files[0]);
        formData.append('hybrid[generate_hybrid][' + (id - 1) + '][update_result]', $(this).find('input[name="update_result[]"]').val());
        formData.append('hybrid[generate_hybrid][' + (id - 1) + '][result_name]', $(this).find('input[name="result_name[]"]').val());
        id++;
    });

    var id = 1;
    $('.form-inline-slider').each(function () {
        formData.append('slider[generate_slider][' + (id - 1) + '][id]', id);
        formData.append('slider[generate_slider][' + (id - 1) + '][slider_image]', $(this).find('input[name="slider_image[]"]')[0].files[0]);

        formData.append('slider[generate_slider][' + (id - 1) + '][update_slider_image]', $(this).find('input[name="update_slider_image[]"]').val());

        formData.append('slider[generate_slider][' + (id - 1) + '][slider_video]', $(this).find('input[name="slider_video[]"]').val());
        formData.append('slider[generate_slider][' + (id - 1) + '][slider_text]', $(this).find('input[name="slider_text[]"]').val());
        id++;
    });

    var id = 1;
    $('.form-inline-vision').each(function () {
        formData.append('vision[generate_vision][' + (id - 1) + '][id]', id);
        formData.append('vision[generate_vision][' + (id - 1) + '][vision_image]', $(this).find('input[name="vision_image[]"]')[0].files[0]);
        formData.append('vision[generate_vision][' + (id - 1) + '][vision_text]', $(this).find('input[name="vision_text[]"]').val());
        formData.append('vision[generate_vision][' + (id - 1) + '][update_vision_image]', $(this).find('input[name="update_vision_image[]"]').val());
        formData.append('vision[generate_vision][' + (id - 1) + '][update_vision_music]', $(this).find('input[name="update_vision_music[]"]').val());
        formData.append('vision[generate_vision][' + (id - 1) + '][vision_music]', $(this).find('input[name="vision_music[]"]')[0].files[0]);
        id++;
    });
    $.ajax({
        url: '{{ route("update-aimodule") }}',
        type: 'POST',
        processData: false,
        contentType: false,
        data: formData,
        success: function (response) {
            if (response['status'] == true) {
                window.location.href="{{  route('aimodules.list') }}";
            } else {
                var errors = response['errors'];
                $.each(errors, function (key, value) {
                    console.log(value[0],"value[0]");
                    var elementId = key.replace(/\./g, '_');
                    console.log(elementId,"key");
                    //$('#' + key).siblings('p').addClass('text-danger').html(value[0]);
                    $('#' + elementId).siblings('p').addClass('text-danger').html(value[0]);
                });
                /* $.each(errors, function (key, value) {
                    $('#' + key).siblings('p').addClass('text-danger').html(value);
                }); */
            }
        },
        error: function () {
            console.log('Something went wrong');
        }
    });
});
</script>
@endsection