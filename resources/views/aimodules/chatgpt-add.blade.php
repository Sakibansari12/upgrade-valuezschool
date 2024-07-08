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
                        <h4 class="box-title">Add New {{ isset($formattedType) ? $formattedType : '' }}</h4>
                    </div>
                    <!-- /.box-header -->
                    <form action="" id="aimodulescreate" method="POST" enctype="multipart/form-data">
                        @csrf
                       
                        <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label class="form-label">Description for "Try the prompts below" </label>
                                                <textarea  rows="5" name="description" id="description" class="form-control"
                                                    placeholder="Enter Description for Try the prompts below">{{ old('description') }}</textarea>
                                                    <p></p>
                                            </div>
                                        </div>
                                    </div>
                               <hr>
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
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-md-end">
                                            <button type="button" class="btn small-button-prompts" style="background-color: #00205c; color: #fff;" id="add-fields">Add Prompts</button>
                                        </div>
                                    </div>
                                </div>
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
                                    
                                </div>
                            </div>
                        </div>
                        <hr>
                        <!-- Add Own Activity Promts -->
                        <div class="box-body">
                            <div class="form-group" id="activitie-fields-add">
                                <label class="form-label">Let's get hands on with AI tools <span class="text-danger">*</span></label>
                                <div class="row" id="add-own-activity-fields-dynamic">
                                    <div class="col-md-9">
                                        <div class="form-inline-own-activities">
                                            <div class="mb-3">
                                                <textarea name="add_placeholder_text[]" id="content_add_own_activities_prompts_0_add_placeholder_text" class="form-control" rows="4" placeholder="Let's get hands on with AI tools"></textarea>
                                            <P></P>
                                            </div>
                                            <div class="mb-3">
                                            <label class="form-label">Add website url <span class="text-danger">*</span></label>
                                                <input type="text" name="add_ai_toll_url[]" id="content_add_own_activities_prompts_0_add_ai_toll_url" class="form-control" placeholder="Add website url">
                                            <p></p>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Add tool name <span class="text-danger">*</span></label>
                                                <input type="text" name="toll_name[]" id="content_add_own_activities_prompts_0_toll_name" class="form-control" placeholder="Add tool name">
                                                <p></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-md-end">
                                            <button type="button" class="btn small-button-activities" style="background-color: #00205c; color: #fff;" id="add-own-activities-fields">Add Own Activities</button>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label class="form-label">Description for "Try your own prompts" </label>
                                        <textarea  rows="5" name="own_description" id="own_description" class="form-control"
                                            placeholder="Description for Try your own prompts">{{ old('own_description') }}</textarea>
                                            <p></p>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label class="form-label">Enter Own Placeholder</label>
                                        <input type="text" name="own_placeholder" id="own_placeholder" class="form-control" placeholder="Enter Own Placeholder">
                                        <p></p>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label class="form-label">Hello There Description</label>
                                        <textarea  rows="5" name="hello_there_description" id="hello_there_description" class="form-control"
                                            placeholder="Enter  Description">{{ old('own_description') }}</textarea>
                                            <p></p>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <input type="hidden" id="identifier_id" name="identifier_id" value=" {{$identifier_id}} ">
                        <div class="box-footer">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
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
<script src="{{ asset('assets/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.js') }}"></script>
<script>
        $('#description').wysihtml5();
        $('#own_description').wysihtml5();
        $('#hello_there_description').wysihtml5();
        $('#content_prompts_0_prompts').wysihtml5();
        $('#content_prompts_0_response').wysihtml5();
    </script>
<script src="{{ asset('assets/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.js') }}"></script>
<script>
$(document).ready(function() {
    var fieldCounterpromts = 1;
    var errorprompts = 1;
    
    $("#add-fields").click(function() {
        fieldCounterpromts++;
        var newRow = $(
            '<div class="row">' + 
                '<div class="col-md-9">' +
                    '<div class="form-inline-Prompts">' +
                        '<div class="mb-3">' +
                            '<textarea name="prompts[]" id="content_prompts_' + errorprompts + '_prompts" class="form-control" rows="4" placeholder="Prompts"></textarea>' +
                            '<p></p>' +
                        '</div>' +
                        '<div class="mb-3">' +
                            '<textarea name="response[]" id="content_prompts_' + errorprompts + '_response" class="form-control" rows="4" placeholder="Response"></textarea>' +
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
                '</div>' +
            '</div>'
        );

        $("#dynamic-fields").append(newRow);

        $('#content_prompts_' + errorprompts + '_prompts').wysihtml5();
        $('#content_prompts_' + errorprompts + '_response').wysihtml5();
        
        errorprompts++;

        newRow.find(".remove-field").click(function() {
            newRow.remove();
        });
    });
});
</script>
<script>
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
                    '<label class="form-label"></label>' +
                      '<input type="text" name="website_url[]" id="content_activities_'+ erroractivities +'_website_url" class="form-control" placeholder="Website Url">' +
                      '<p></p>' +
                      '</div>' +
                    '<div class="mb-3">' +
                    '<label class="form-label"></label>' +
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
       
        $("#dynamic-fields-activitie").append(newRow);
        $('#content_prompts_' + errorprompts + '_prompts').wysihtml5();
        $('#content_prompts_' + errorprompts + '_response').wysihtml5();
        erroractivities++;
        newRow.find(".remove-field").click(function() {
            newRow.remove();
        });
    });
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
                      '<textarea name="add_placeholder_text[]" id="content_add_own_activities_prompts_'+ errorownactivities +'_add_placeholder_text" class="form-control" rows="4" placeholder="Lets get hands on with AI tools"></textarea>' +
                      '<p></p>' +
                      '</div>' +
                    '<div class="mb-3">' + 
                    '<label class="form-label"> </label>' +
                      '<input type="text" name="add_ai_toll_url[]" id="content_add_own_activities_prompts_'+ errorownactivities +'_add_ai_toll_url" class="form-control" placeholder="Add website url">' +
                      '<p></p>' +
                      '</div>' +
                    '<div class="mb-3">' +
                    '<label class="form-label"> </label>' +
                    '<input type="text" name="toll_name[]" id="content_add_own_activities_prompts_'+ errorownactivities +'_toll_name" class="form-control" placeholder="Add tool name">' +
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
        errorownactivities++
        $("#add-own-activity-fields-dynamic").append(newRow);
        newRow.find(".remove-field").click(function() {
            newRow.remove();
        });
    });
});


$('#aimodulescreate').submit(function (event) {
    event.preventDefault();
    var formData = new FormData(this);
    formData.append('description', $('#description').val());
    formData.append('own_description', $('#own_description').val());
    formData.append('own_placeholder', $('#own_placeholder').val());
    formData.append('hello_there_description', $('#hello_there_description').val());
    var id = 1;
    $('.form-inline-Prompts').each(function () {
        formData.append('content[prompts][' + (id - 1) + '][id]', id);
        formData.append('content[prompts][' + (id - 1) + '][prompts]', $(this).find('textarea[name="prompts[]"]').val());
        formData.append('content[prompts][' + (id - 1) + '][response]', $(this).find('textarea[name="response[]"]').val());
        formData.append('content[prompts][' + (id - 1) + '][file]', $(this).find('input[name="answers_file[]"]')[0].files[0]);
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
    $.ajax({
        url: '{{ route("create-aimodule") }}',
        type: 'POST',
        processData: false,
        contentType: false,
        data: formData,
        success: function (response) {
            if (response['status'] == true) {
                window.location.href="{{  route('list-aimodule') }}";
            } else {
                var errors = response['errors'];
                console.log(errors,"errors");
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
<script src="{{ asset('assets/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.js') }}"></script>
    <script>
        $('.select2').select2();
        $('#grade').select2({
            tags: true,
            placeholder: "Select a Grade",
        });
    </script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const maxSizeInBytes = 251 * 1024; // 250 KB
    const fileInput = document.getElementById('module_thumbnail');
    const fileSizeLimitText = document.getElementById('file-size-limit');

    fileInput.addEventListener('change', function() {
        const files = fileInput.files;
        if (files.length > 0) {
            const fileSize = files[0].size; 
            const fileSizeKB = fileSize / 1024; 
            if (fileSize > maxSizeInBytes) {
                fileSizeLimitText.textContent = '(File size exceeds 250 KB)';
                fileSizeLimitText.style.color = 'red';
                fileInput.value = ''; 
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