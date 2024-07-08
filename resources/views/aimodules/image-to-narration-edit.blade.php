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
                        <h4 class="box-title">Update {{ isset($formattedType) ? $formattedType : '' }}</h4>
                    </div>
                    <!-- /.box-header -->
                    <form action="" id="aimodulesupdate" method="POST" enctype="multipart/form-data">
                        @csrf
                        <!-- Vision Start -->       
<div class="box-body">

                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label class="form-label">Description for "Try the prompts below" </label>
                                                <textarea  rows="5" name="description" id="description" class="form-control"
                                                    placeholder="Enter Description for Try the prompts below">{{ isset($aimoduledata->description) ? $aimoduledata->description : '' }}</textarea>
                                                    <p></p>
                                            </div>
                                        </div>
                                    </div>

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
                                <label class="form-label">Let's get hands on with AI tools  <span class="text-danger">*</span></label>
                                <div class="row" id="add-own-activity-fields-dynamic">
                                @if (!empty($aimoduledata->AddOwnactivitiesdata))
                                    @foreach ($aimoduledata->AddOwnactivitiesdata as $key => $cdata)
                                <div id="prompts-removie-data" class="row">
                                    <div class="col-md-9">
                                        <div class="form-inline-own-activities">
                                            <div class="mb-3">
                                            <!-- <label class="form-label">Activities Description <span class="text-danger">*</span></label> -->
                                                <textarea name="add_placeholder_text[]" class="form-control" rows="4" placeholder="Lets get hands on with AI tools">{{ isset($cdata->add_placeholder_text) ? $cdata->add_placeholder_text : '' }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                            <label class="form-label">Add website url <span class="text-danger">*</span></label>
                                                <input type="text" name="add_ai_toll_url[]" value="{{ isset($cdata->add_ai_toll_url) ? $cdata->add_ai_toll_url : '' }}" class="form-control" placeholder="Add website url">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Add tool name <span class="text-danger">*</span></label>
                                                <input type="text" name="toll_name[]" value="{{ isset($cdata->toll_name) ? $cdata->toll_name : '' }}" class="form-control" placeholder="Add tool name">
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
                                                <textarea name="add_placeholder_text[]" class="form-control" rows="4" placeholder="Let's get hands on with AI tools"></textarea>
                                            </div>
                                            <div class="mb-3">
                                            <label class="form-label"> <span class="text-danger">*</span></label>
                                                <input type="text" name="add_ai_toll_url[]" class="form-control" placeholder="Add website url">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Add tool name<span class="text-danger">*</span></label>
                                                <input type="text" name="toll_name[]" class="form-control" placeholder="Add tool name">
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
                                    <label class="form-label">Slider Part </label>
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
                            

                            <hr>
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label class="form-label">Image Narration Description </label>
                                        <textarea  rows="5" name="own_description" id="own_description" class="form-control"
                                            placeholder="Enter Image Narration Description">{{ isset($aimoduledata->own_description) ? $aimoduledata->own_description : '' }}</textarea>
                                            <p></p>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label class="form-label">Hello There Description </label>
                                        <textarea  rows="5" name="hello_there_description" id="hello_there_description" class="form-control"
                                            placeholder="Enter Description">{{ isset($aimoduledata->hello_there_description) ? $aimoduledata->hello_there_description : '' }}</textarea>
                                            <p></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="aimodule_id" id="aimodule_id" value="{{ $aimoduledata->id}}">
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
        $('#description').wysihtml5();
        $('#own_description').wysihtml5();
        $('#hello_there_description').wysihtml5();
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
                      '<textarea name="add_placeholder_text[]" class="form-control" rows="4" placeholder="Lets get hands on with AI tools"></textarea>' +
                    '</div>' +
                    '<div class="mb-3">' +
                    '<label class="form-label"></label>' +
                      '<input type="text" name="add_ai_toll_url[]" class="form-control" placeholder="Add website url">' +
                    '</div>' +
                    '<div class="mb-3">' +
                    '<label class="form-label"></label>' +
                    '<input type="text" name="toll_name[]" class="form-control" placeholder="Add tool name">' +
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



$('#aimodulesupdate').submit(function (event) {
event.preventDefault();

    var formData = new FormData();
    formData.append('aimodule_id', $('#aimodule_id').val());
    formData.append('description', $('#description').val());
    formData.append('own_description', $('#own_description').val());
    formData.append('hello_there_description', $('#hello_there_description').val());
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
    $('.form-inline-slider').each(function () {
        formData.append('slider[generate_slider][' + (id - 1) + '][id]', id);
        formData.append('slider[generate_slider][' + (id - 1) + '][slider_image]', $(this).find('input[name="slider_image[]"]')[0].files[0]);

        formData.append('slider[generate_slider][' + (id - 1) + '][update_slider_image]', $(this).find('input[name="update_slider_image[]"]').val());

        formData.append('slider[generate_slider][' + (id - 1) + '][slider_video]', $(this).find('input[name="slider_video[]"]').val());
        formData.append('slider[generate_slider][' + (id - 1) + '][slider_text]', $(this).find('input[name="slider_text[]"]').val());
        id++;
    });


    $.ajax({
        url: '{{ route("module-update") }}',
        type: 'POST',
        processData: false,
        contentType: false,
        data: formData,
        success: function (response) {
            if (response['status'] == true) {
                window.location.href="{{  route('list-aimodule') }}";
            } else {
                var errors = response['errors'];
                $.each(errors, function (key, value) {
                    console.log(value[0],"value[0]");
                    var elementId = key.replace(/\./g, '_');
                    console.log(elementId,"key");
                    $('#' + elementId).siblings('p').addClass('text-danger').html(value[0]);
                    
                });
            }
        },
        error: function () {
            console.log('Something went wrong');
        }
    });
});
</script>
@endsection