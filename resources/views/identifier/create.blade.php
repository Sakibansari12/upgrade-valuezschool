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
                            <li class="breadcrumb-item alignment-text-new" aria-current="page"><a href="{{ route('identifier.list') }}"><i class="mdi mdi-home-outline"></i> - AI Module</a></li>
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
                    <form name="aimodulescreate" id="aimodulescreate" method="POST" enctype="multipart/form-data">
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
                            </select>
                            <p></p>
                        </div>


                            <div class="form-group">
                                <label class="form-label">Module Name <span class="text-danger">*</span></label>
                                <input type="text" name="module_name" id="module_name" class="form-control" placeholder="Module Name">
                                <p></p>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Module page title <span class="text-danger">*</span></label>
                                <input type="text" name="module_page_title" id="module_page_title" class="form-control" placeholder="Module page title">
                           <p></p>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Grade <span class="text-danger">*</span></label>
                                <select class="form-control select2" id="class_id" name="class_id[]" style="width: 100%;"
                                    multiple="multiple" >
                                    @foreach ($program_list as $prog)
                                        @php $classIds = old('class_id'); @endphp
                                        <option value="{{ $prog->id }}"
                                            {{ (!empty($classIds) && in_array($prog->id, $classIds)) ? 'selected' : '' }}>
                                            {{ $prog->class_name }}</option>
                                    @endforeach
                                </select>
                                <p></p>
                            </div>
                            

                            <div class="form-group">
                                <label class="form-label">Course <span class="text-danger">*</span></label>
                                <select class="form-control select2" id="course_id" name="course_id" style="width: 100%;">
                                    @foreach ($course_list as $course)
                                        <option value="{{ $course->id }}"
                                            {{ $course->id == old('course_id') ? 'selected' : '' }}>
                                            {{ $course->course_name }}</option>
                                    @endforeach
                                </select>
                                <p></p>
                            </div>
                            
                           
                            <div class="form-group">
                                <label for="formFile" class="form-label">Module Thumbnail<span
                                        class="text-danger">*</span></label>
                                <input type="file" class="form-control"  name="module_thumbnail" id="module_thumbnail">
                                <p></p>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Module Page Overview<span class="text-danger">*</span></label>
                                <textarea  name="module_page_overview" id="module_page_overview" rows="4" class="form-control" placeholder="Module Page Overview"></textarea>
                               <p></p>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Video url <span class="text-danger">*</span></label>
                                <input type="text" name="video_url" id="video_url" class="form-control" placeholder="Video url">
                                <p></p>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection
@section('script-section')
<script src="{{ asset('assets/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.js') }}"></script>
<script>
        $('#module_page_overview').wysihtml5();
    </script>
<script>
$('#aimodulescreate').submit(function (event) {
    event.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        url: '{{ route("create-add") }}',
        type: 'POST',
        processData: false,
        contentType: false,
        data: formData,
        success: function (response) {
            if (response['status'] == true) {
                window.location.href = "{{  route('identifier.list') }}";
            } else {
                var errors = response['errors'];
                $.each(errors, function (key, value) {
                    var elementId = key.replace(/\./g, '_');
                    $('#' + elementId).next('p').addClass('text-danger').html(value[0]);
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