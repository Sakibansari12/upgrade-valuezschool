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
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page">Add AI Module</li>
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
                        <h4 class="box-title">Add New AI Module</h4>
                    </div>
                    <!-- /.box-header -->
                    <form action="" id="aimodulescreate" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                        <div class="form-group">
                            <label for="Identifier">Identifier  <span class="text-danger">*</span></label>
                            <select name="identifier" id="identifier" class="form-control">
                                <option value="">Select Identifier</option>
                                <option value="chatgpt">ChatGPT</option>
                                <option value="dalle">Dalle</option>
                                <option value="text_to_speech">Text to speech</option>
                                <option value="speech_to_text">Speech to text</option>
                                <option value="text_to_music">Text to music</option>
                                <option value="generate_avatar">Generate Avatar</option>
                                <option value="photo_to_video">Photo to Video</option>
                                <option value="fauna_and_flora">Fauna and Flora</option>
                                <option value="image_to_narration">Image to Narration</option>
                                <!-- <option value="bird_classifier">Bird Classifier</option>
                                <option value="plant_recognizer">Plant Recognizer</option> -->
                                
                            </select>
                            <p></p>
                            @error('identifier')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        </div>


                            <div class="form-group">
                                <label class="form-label">Module Name <span class="text-danger">*</span></label>
                                <input type="text" name="module_name" id="module_name" class="form-control" placeholder="Module Name">
                                <p></p>
                                @error('module_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group" id="content_module_page_title">
                                <label class="form-label">Module page title <span class="text-danger">*</span></label>
                                <input type="text" name="module_page_title" id="module_page_title" class="form-control" placeholder="Module page title">
                                
                                @error('module_page_title')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <p></p>
                            <div class="form-group" id="grades">
                                <label class="form-label">Grade <span class="text-danger">*</span></label>
                                <select class="form-control select2" id="grade" name="class_id[]" style="width: 100%;"
                                    multiple="multiple" >
                                    @foreach ($program_list as $prog)
                                        @php $classIds = old('class_id'); @endphp
                                        <option value="{{ $prog->id }}"
                                            {{ (!empty($classIds) && in_array($prog->id, $classIds)) ? 'selected' : '' }}>
                                            {{ $prog->class_name }}</option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <p></p>

                            <div class="form-group" id="courses">
                                <label class="form-label">Course <span class="text-danger">*</span></label>
                                <select class="form-control select2" id="course" name="course_id" style="width: 100%;">
                                    @foreach ($course_list as $course)
                                        <option value="{{ $course->id }}"
                                            {{ $course->id == old('course_id') ? 'selected' : '' }}>
                                            {{ $course->course_name }}</option>
                                    @endforeach
                                </select>
                                @error('course_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <p></p>
                           
                            <div class="form-group">
                                <label for="formFile" class="form-label">Module Thumbnail<span
                                        class="text-danger">*</span></label>
                                <input type="file" class="form-control"  name="module_thumbnail" id="module_thumbnail">
                                <p></p>
                                <span  id="file-size-limit">(Max file size: 250 KB)</span>
                                
                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group" id="content_module_page_overview">
                                <label class="form-label">Module Page Overview<span class="text-danger">*</span></label>
                                <textarea  name="module_page_overview" id="module_page_overview" rows="4" class="form-control" placeholder="Module Page Overview"></textarea>
                                @error('module_page_overview')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <p></p>
                            <div class="form-group" id="content_video_url">
                                <label class="form-label">Video url <span class="text-danger">*</span></label>
                                <input type="text" name="video_url" id="video_url" class="form-control" placeholder="Video url">
                                
                                @error('video_url')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <p></p>
                            <hr>
                        </div>
                        <div class="box-body">
                            <div class="form-group" id="activitie-fields-add">
                                <label class="form-label">Prompts <span class="text-danger">*</span></label>
                                <div class="row" id="dynamic-fields">
                                    <div class="col-md-9">
                                        <div class="form-inline-Prompts">
                                            <div class="mb-3">
                                                <textarea name="prompts[]" class="form-control " id="content_prompts_0_prompts" rows="4" placeholder="Prompts"></textarea>
                                              <p></p>
                                            </div>
                                            <div class="mb-3">
                                                <textarea name="response[]" class="form-control" id="content_prompts_0_response" rows="4" placeholder="Response"></textarea>
                                            <p></p>
                                            </div>
                                            <div class="mb-3">
                                                <input type="file" class="form-control form-control" id="content_prompts_0_file" name="answers_file[]">
                                            <p></p>
                                            </div>
                                            <!-- <div class="mb-3">
                                                <input type="file" class="form-control form-control" id="content_prompts_0_video" name="answers_video[]">
                                            <p></p>
                                            </div> -->
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-md-end">
                                            <button type="button" class="btn small-button-prompts" style="background-color: #00205c; color: #fff;" id="add-fields">Add Prompts</button>
                                        </div>
                                    </div>
                                </div>

        @error('questions')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>
 <hr>                       
<div class="box-body">
    <div class="form-group" id="activitie-fields-add">
        <label class="form-label">Activities <span class="text-danger">*</span></label>
        <div class="row" id="dynamic-fields-activitie">
            <div class="col-md-9">
                <div class="form-inline-activities">
                    <div class="mb-3">
                    <!-- <label class="form-label">Activities Description <span class="text-danger">*</span></label> -->
                        <textarea name="activities_description[]" id="content_activities_0_activities_description" class="form-control" rows="4" placeholder="Activities Description"></textarea>
                        <p></p>
                    </div>
                    <div class="mb-3">
                    <label class="form-label">Website Url <span class="text-danger">*</span></label>
                        <input type="text" name="website_url[]" id="content_activities_0_website_url" class="form-control" placeholder="Website Url">
                    <p></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Prompt Screenshots <span class="text-danger">*</span></label>
                        <input type="file" class="form-control form-control prompt-screenshot" id="content_activities_0_prompt_screenshot"  name="prompt_screenshot[]">
                        <p></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-md-end">
                    <button type="button" class="btn small-button-activities" style="background-color: #00205c; color: #fff;" id="add-fields-activitie">Add Activities</button>
                </div>
            </div>
            <hr>
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
            <div class="col-md-9">
                <div class="form-inline-own-activities">
                    <div class="mb-3">
                    <!-- <label class="form-label">Activities Description <span class="text-danger">*</span></label> -->
                        <textarea name="add_placeholder_text[]" id="content_add_own_activities_prompts_0_add_placeholder_text" class="form-control" rows="4" placeholder="Add Placeholder Text"></textarea>
                     <P></P>
                    </div>
                    <div class="mb-3">
                    <label class="form-label">Add AI Tool Url <span class="text-danger">*</span></label>
                        <input type="text" name="add_ai_toll_url[]" id="content_add_own_activities_prompts_0_add_ai_toll_url" class="form-control" placeholder="Add AI Tool Url">
                      <p></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tool Name <span class="text-danger">*</span></label>
                        <input type="text" name="toll_name[]" id="content_add_own_activities_prompts_0_toll_name" class="form-control" placeholder="Tool Name">
                        <p></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-md-end">
                    <button type="button" class="btn small-button-activities" style="background-color: #00205c; color: #fff;" id="add-own-activities-fields">Add Own Activities</button>
                </div>
            </div>
            <hr>
        </div>

        @error('questions')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>




<!-- Add Generate Hybrid -->              
<!-- <div class="box-body">
    <div class="form-group" id="activitie-fields-add">
        <div class="row" id="add-Hybrid-dynamic">
            <div class="col-md-9">
                <div class="form-inline-hybrid">
                    <div class="mb-3">
                     <label class="form-label">Animal 1 </label>
                        <input type="file" name="animal_one[]" id="animal_one" class="form-control">
                      <p></p>
                    </div>
                    <div class="mb-3">
                     <label class="form-label">Animal 2 </label>
                        <input type="file" name="animal_second[]" id="animal_second" class="form-control">
                      <p></p>
                    </div>
                    <div class="mb-3">
                     <label class="form-label">Result </label>
                        <input type="file" name="result[]" id="result" class="form-control">
                      <p></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-md-end mt-4">
                    <button type="button" class="btn small-button-activities" style="background-color: #00205c; color: #fff;" id="add-hybrid">Add Hybrid</button>
                </div>
            </div>
            <hr>
        </div>
    </div>
</div> -->
<div class="box-body">
    <div class="form-group" id="activitie-fields-add">
        <div class="row">
            <div id="package-report-video">
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
                            <div class="text-md-end mt-4">
                                <button type="button" class="btn small-button-activities add-report-video" style="background-color: #00205c; color: #fff;" >Add Hybrid</button>
                            </div>
                        </div>
                    </div>   
                </div>


            </div>
        </div>
    </div>
</div>


<!-- Slider Start -->       
<div class="box-body">
    <div class="form-group" id="activitie-fields-add">
        <div class="row">
            <div id="slider-video-data">
                <div class="add_slider_video">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-inline-slider">
                                <div class="mb-3">
                                <label class="form-label">Slider Part <span class="text-danger">*</span></label>
                                      <div>
                                        <input type="file" name="slider_image[]" id="slider_generate_slider_0_slider_image"  class="form-control">
                                        <p></p>
                                      </div>
                                    <div>
                                    <input type="text" name="slider_video[]" id="slider_generate_slider_0_slider_video" placeholder="Enter Video Url" class="form-control mt-1">
                                    <p></p>
                                    </div>
                                    <div>
                                     <input type="text" name="slider_text[]" id="slider_generate_slider_0_slider_text"    placeholder="Enter Video Text" class="form-control mt-1">
                                    <p></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-md-end mt-4">
                                <button type="button" class="btn small-button-activities add-slider-video" style="background-color: #00205c; color: #fff;" >Add Slider</button>
                            </div>
                        </div>
                    </div>   
                </div>
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
                <div class="add_vision_data">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-inline-vision">
                                <div class="mb-3">
                                    <label class="form-label">Vision Part</label>
                                    <input type="file" name="vision_image[]" id="vision_image"  class="form-control">
                                    <input type="text" name="vision_text[]" id="vision_text" placeholder="Enter Text" class="form-control mt-1">
                                    <input type="file" name="vision_music[]" id="vision_music"  class="form-control mt-1">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-md-end mt-4">
                                <button type="button" class="btn small-button-activities add-vision-field" style="background-color: #00205c; color: #fff;" >Add Vision</button>
                            </div>
                        </div>
                    </div>   
                </div>
            </div>
        </div>
    </div>
</div>  
<!-- Vision End -->



                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                    
                </div>
                <!-- /.box -->
                
            </div>

        </div>
        
    </section>


<!-- popup deactivate -->
<div class="modal fade" id="aimodule-error-model" tabindex="-1" role="dialog" aria-labelledby="modal-label"
        aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" style="max-width: 800px;">
            <div class="modal-content">
            <div class="modal-body">
                <div id="aimodule_custom_msg" class="custom-alert-snackbar">
   
               </div>
               </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- /.content -->
@endsection
@section('script-section')
<script>
    /* prompts */
   $(document).ready(function() {
    var fieldCounterpromts = 1;
    errorprompts = 1;
    $("#add-fields").click(function() {
        fieldCounterpromts++;
        var newRow = 
        $(
            '<div class="col-md-9">' +
                '<div class="form-inline-Prompts">' +
                    '<div class="mb-3">' +
                      '<textarea name="prompts[]" id="content_prompts_'+ errorprompts +'_prompts" class="form-control" rows="4" placeholder="Prompts"></textarea>' +
                    '<p></p>' +
                      '</div>' +
                    '<div class="mb-3">' +
                      '<textarea name="response[]" id="content_prompts_'+ errorprompts +'_response" class="form-control" rows="4" placeholder="Response"></textarea>' +
                      '<p></p>' +
                      '</div>' +
                    '<div class="mb-3">' +
                    '<input type="file" class="form-control form-control" name="answers_file[]" id="content_prompts_' + errorprompts + '_file">' +
                    '<p></p>' +
                    '</div>' +

                '</div>' +
            '</div>' +
            '<div class="col-md-3">' +
                '<div class="text-md-end">' +
                '<button type="button" class="btn btn-danger small-button-remove remove-field">Remove</button>' +
                '</div>' +
            '</div>'
        );
        errorprompts++
        $("#dynamic-fields").append(newRow);
        newRow.find(".remove-field").click(function() {
            newRow.remove();
        });
    });
});

/* activities */
$(document).ready(function() {
    var fieldCounter = 1;
    var erroractivities = 1;
    $("#add-fields-activitie").click(function() {
        fieldCounter++;
        var newRow = $(
            '<div class="col-md-9">' +
                '<div class="form-inline-activities">' +
                    '<div class="mb-3">' +
                      '<textarea name="activities_description[]" id="content_activities_'+ erroractivities +'_activities_description" class="form-control" rows="4" placeholder="Activities Description"></textarea>' +
                      '<p></p>' +
                      '</div>' +
                    '<div class="mb-3">' +
                    '<label class="form-label">Website Url <span class="text-danger">*</span></label>' +
                      '<input type="text" name="website_url[]" id="content_activities_'+ erroractivities +'_website_url" class="form-control" placeholder="Website Url">' +
                      '<p></p>' +
                      '</div>' +
                    '<div class="mb-3">' +
                    '<label class="form-label">Prompt Screenshots <span class="text-danger">*</span></label>' +
                    '<input type="file" class="form-control form-control" name="prompt_screenshot[]" id="content_activities_'+ erroractivities +'_prompt_screenshot">' +
                    '<p></p>' +
                    '</div>' +
                '</div>' +
            '</div>' +
            '<div class="col-md-3">' +
                '<div class="text-md-end">' +
                '<button type="button" class="btn btn-danger small-button-remove remove-field">Remove</button>' +
                '</div>' +
            '</div>'
        );
        erroractivities++;
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
    var errorownactivities = 1; 
    $("#add-own-activities-fields").click(function() {
        fieldCounterpromts++;
        var newRow = 
        $(
            '<div class="col-md-9">' +
                '<div class="form-inline-own-activities">' +
                    '<div class="mb-3">' +
                      '<textarea name="add_placeholder_text[]" id="content_add_own_activities_prompts_'+ errorownactivities +'_add_placeholder_text" class="form-control" rows="4" placeholder="Add Placeholder Text"></textarea>' +
                      '<p></p>' +
                      '</div>' +
                    '<div class="mb-3">' + 
                    '<label class="form-label">Add AI Tool Url <span class="text-danger">*</span></label>' +
                      '<input type="text" name="add_ai_toll_url[]" id="content_add_own_activities_prompts_'+ errorownactivities +'_add_ai_toll_url" class="form-control" placeholder="Add AI Tool Url">' +
                      '<p></p>' +
                      '</div>' +
                    '<div class="mb-3">' +
                    '<label class="form-label">Tool Name <span class="text-danger">*</span></label>' +
                    '<input type="text" name="toll_name[]" id="content_add_own_activities_prompts_'+ errorownactivities +'_toll_name" class="form-control" placeholder="Tool Name">' +
                    '<p></p>' +
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
        errorownactivities++
        $("#add-own-activity-fields-dynamic").append(newRow);
        newRow.find(".remove-field").click(function() {
            newRow.remove();
        });
    });
});





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
                                        <div>
                                        <input type="file" name="slider_image[]" id="slider_generate_slider_${errorslider}_slider_image"   class="form-control">
                                        <p></p>
                                         </div>

                                         <div>
                                        <input type="text" name="slider_video[]" id="slider_generate_slider_${errorslider}_slider_video"  placeholder="Enter Video Url" class="form-control mt-1">
                                        <p></p>
                                        </div>
                                        <div>
                                            <input type="text" name="slider_text[]" id="slider_generate_slider_${errorslider}_slider_text"     placeholder="Enter Video Text" class="form-control mt-1">
                                            <p></p>
                                        </div>
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



$('#aimodulescreate').submit(function (event) {
    event.preventDefault();

    var formData = new FormData();
    formData.append('module_name', $('#module_name').val());
    formData.append('identifier', $('#identifier').val());
    formData.append('module_thumbnail', $('#module_thumbnail')[0].files[0]);
    formData.append('grades', $('#grade').val());
    formData.append('courses', $('#course').val());
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
        //formData.append('content[prompts][' + (id - 1) + '][video]', $(this).find('input[name="answers_video[]"]')[0].files[0]);
        id++;
    });

    var id = 1;
    $('.form-inline-activities').each(function () {
        formData.append('content[activities][' + (id - 1) + '][id]', id);
        formData.append('content[activities][' + (id - 1) + '][activities_description]', $(this).find('textarea[name="activities_description[]"]').val());
        formData.append('content[activities][' + (id - 1) + '][website_url]', $(this).find('input[name="website_url[]"]').val());
        formData.append('content[activities][' + (id - 1) + '][prompt_screenshot]', $(this).find('input[name="prompt_screenshot[]"]')[0].files[0]);
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
        formData.append('hybrid[generate_hybrid][' + (id - 1) + '][animal_one_name]', $(this).find('input[name="animal_one_name[]"]').val());
        formData.append('hybrid[generate_hybrid][' + (id - 1) + '][animal_second]', $(this).find('input[name="animal_second[]"]')[0].files[0]);
        formData.append('hybrid[generate_hybrid][' + (id - 1) + '][animal_second_name]', $(this).find('input[name="animal_second_name[]"]').val());
        formData.append('hybrid[generate_hybrid][' + (id - 1) + '][result]', $(this).find('input[name="result[]"]')[0].files[0]);
        formData.append('hybrid[generate_hybrid][' + (id - 1) + '][result_name]', $(this).find('input[name="result_name[]"]').val());
        id++;
    });

    var id = 1;
    $('.form-inline-slider').each(function () {
        formData.append('slider[generate_slider][' + (id - 1) + '][id]', id);
        formData.append('slider[generate_slider][' + (id - 1) + '][slider_image]', $(this).find('input[name="slider_image[]"]')[0].files[0]);
        formData.append('slider[generate_slider][' + (id - 1) + '][slider_video]', $(this).find('input[name="slider_video[]"]').val());
        formData.append('slider[generate_slider][' + (id - 1) + '][slider_text]', $(this).find('input[name="slider_text[]"]').val());
        id++;
    });

    var id = 1;
    $('.form-inline-vision').each(function () {
        formData.append('vision[generate_vision][' + (id - 1) + '][id]', id);
        formData.append('vision[generate_vision][' + (id - 1) + '][vision_image]', $(this).find('input[name="vision_image[]"]')[0].files[0]);
        formData.append('vision[generate_vision][' + (id - 1) + '][vision_text]', $(this).find('input[name="vision_text[]"]').val());
        formData.append('vision[generate_vision][' + (id - 1) + '][vision_music]', $(this).find('input[name="vision_music[]"]')[0].files[0]);
        id++;
    });

    $.ajax({
        url: '{{ route("add-module") }}',
        type: 'POST',
        processData: false,
        contentType: false,
        data: formData,
        success: function (response) {
            if (response['status'] == true) {
                window.location.href="{{  route('aimodules.list') }}";
            } else {
                var errors = response['errors'];
                console.log(errors,"errors");
                $.each(errors, function (key, value) {
                    console.log(value[0],"value[0]");
                    var elementId = key.replace(/\./g, '_');
                    console.log(elementId,"key");
                    //$('#' + key).siblings('p').addClass('text-danger').html(value[0]);
                    $('#' + elementId).next('p').addClass('text-danger').html(value[0]);
                });
               // $('#aimodule-error-model').modal('show');
        /* $.each(errors, function (key, value) {
            var errorRow = $(`
                <div class="custom-alert-snackbar">
                    <div role="alert" class="v-alert v-sheet theme--dark elevation-2 v-alert--border v-alert--border-left error">
                        <div class="v-alert__wrapper">
                            <div class="v-alert__content text-danger"><span>${value}</span></div>
                        </div>
                    </div>
                </div>
            `);

            $('#aimodule_custom_msg').append(errorRow);
        });
 */




            }
        },
        error: function () {
            console.log('Something went wrong');
        }
    });
});



</script>
<script src="{{ asset('assets/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.js') }}"></script>
    <script>
        $('.select2').select2();
        $('#grade').select2({
            tags: true,
            placeholder: "Select a Grade",
        });
        //Add text editor
        //bootstrap WYSIHTML5 - text editor
       // $('#lesson_inst').wysihtml5();
    </script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const maxSizeInBytes = 251 * 1024; // 250 KB
    const fileInput = document.getElementById('module_thumbnail');
    const fileSizeLimitText = document.getElementById('file-size-limit');

    fileInput.addEventListener('change', function() {
        const files = fileInput.files;
        if (files.length > 0) {
            const fileSize = files[0].size; // Size in bytes
            const fileSizeKB = fileSize / 1024; // Size in KB
            if (fileSize > maxSizeInBytes) {
                fileSizeLimitText.textContent = '(File size exceeds 250 KB)';
                fileSizeLimitText.style.color = 'red';
                fileInput.value = ''; // Clear the file input
            } else {
                fileSizeLimitText.textContent = '(Max file size: 250 KB)';
                fileSizeLimitText.style.color = 'initial';
            }
        } else {
            fileSizeLimitText.textContent = '(Max file size: 250 KB)';
            fileSizeLimitText.style.color = 'initial';
        }
    });
});
</script>
@endsection