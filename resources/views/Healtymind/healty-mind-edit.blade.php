@extends('layout.main')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title"><!-- Instructional Module --></h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <!-- <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li> -->
                            <li class="breadcrumb-item alignment-text-new" aria-current="page"><a href="{{ route('healty-mind-list') }}"><i class="mdi mdi-home-outline"></i> - Healthy Mind</a></li>
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page">Edit Healthy Mind</li>
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
                        <h4 class="box-title">Update Healthy Mind</h4>
                    </div>
                    <!-- /.box-header -->
                    <form action="" id="healthyMindUpdate" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="form-group">
                                <label class="form-label">Healthy Mind Module Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" value="{{ $healtymind_data->title }}" class="form-control"
                                    placeholder="Healthy Mind Module Title">
                                    <p></p>
                                @error('title')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Main Video Link<span class="text-danger">*</span></label>
                                <input type="text" name="video_url" id="video_url" value="{{ $healtymind_data->video_url }}" class="form-control"
                                    placeholder="Enter Video Link">
                                <p></p>
                                @error('video_url')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- <div class="form-group">
                                <label for="formUploadFile" class="form-label">Healthy Mind Upload File <span
                                        class="text-danger"></span></label>
                                <input class="form-control" type="file" name="upload_file" id="formUploadFile">
                                <span  id="file-size-limit-xls">(Max file size: 250 KB)</span>
                                <img src="{{ url('uploads/healty_mind') }}/{{ $healtymind_data->healty_mind_file ? $healtymind_data->healty_mind_file : 'no_image.png' }}"
                                    width="100px">
                                @error('upload_file')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div> -->
                                <div class="form-group" id="file-upload-container">
                                <label for="formUploadFile" class="form-label">Upload pdf/xlsx File <span class="text-danger">*</span></label>
                                @if (!empty($healtymind_data->healty_mind_upload_file->upload_file))
                                    @foreach ($healtymind_data->healty_mind_upload_file->upload_file as $key => $cdata)
                                <div class="file-upload">
                                    <input class="form-control" type="file" name="upload_file[]" id="upload_file__{{ $key}}_file" >
                                    {{ isset($cdata->file) ? $cdata->file : '' }}
                                    <input type="hidden" name="old_upload_file[]" id="upload_file__{{ $key}}_file" value="{{ $cdata->file }}">
                                    <span class="file-size-limit">(Max file size: 250 KB)</span>
                                    @if ($key + 1 == 1)
                                    <button type="button" class="btn btn-success add-file-upload"><i class="fas fa-plus"></i></button>
                                    @else
                                    <button type="button" class="btn btn-danger remove-file-upload"><i class="fas fa-minus"></i></button>
                                    @endif
                                </div>
                                
                            @endforeach
                            @endif
                            </div>



                            <div class="form-group" id="red_guidance">
                                <label class="form-label">Read Guidance <span class="text-danger">*</span></label>
                                <textarea  rows="3" id="description" name="red_guidance" class="form-control"
                                    placeholder="Read Guidance">{{ $healtymind_data->red_guidance_desc }}</textarea>
                                @error('red_guidance')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                             <p></p>
                            <div class="form-group">
                                <label for="image" class="form-label">Healthy Mind Module Image <span
                                        class="text-danger"></span></label>
                                <input class="form-control" type="file" name="image" id="image" accept=".jpg, .png .webp">
                                <p></p>
                                <span  id="file-size-limit">(Max file size: 250 KB)</span>
                                <img src="{{ url('uploads/healty_mind') }}/{{ $healtymind_data->healty_mind_image ? $healtymind_data->healty_mind_image : 'no_image.png' }}"
                                    width="100px">
                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <hr>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <input type="hidden" name="old_image" id="old_image" value="{{ $healtymind_data->healty_mind_image }}">
                            <input type="hidden" name="healtymind_id" id="healtymind_id" value="{{ $healtymind_data->id }}">
                            <!-- <input type="hidden" name="old_upload_file" value="{{ $healtymind_data->healty_mind_file }}"> -->
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
        $('#class_id').select2({
            tags: true,
            placeholder: "Select a Grade",
        });
        //Add text editor
        //bootstrap WYSIHTML5 - text editor
        $('#description').wysihtml5();
        $('#conversation').wysihtml5();
    </script>

<script>
   /*  $(document).ready(function() {
        errorfileupload = 1;
        $(".add-file-upload").click(function() {
            var fileUpload = '<div class="file-upload"><input class="form-control" type="file" name="upload_file[]" id="upload_file_' + errorfileupload + '_file" ><p></p><span class="file-size-limit">(Max file size: 250 KB)</span><button type="button" class="btn btn-danger remove-file-upload"><i class="fas fa-minus"></i></button></div>';
            errorfileupload++
            $("#file-upload-container").append(fileUpload);
        });
        $(document).on('click', '.remove-file-upload', function() {
            $(this).parent('.file-upload').remove();
        });
    }); */
    $(document).ready(function() {
            var errorfileupload = 1;

            // Add file upload input field
            $(".add-file-upload").click(function() {
                var fileUploadCount = $("#file-upload-container .file-upload").length;

                if (fileUploadCount >= 2) {
                    $("#error-message").text("You can only upload a maximum of 2 files.");
                } else {
                    var fileUpload = '<div class="file-upload mt-2"><input class="form-control" type="file" name="upload_file[]" id="upload_file_' + errorfileupload + '_file"><p></p><span class="file-size-limit">(Max file size: 250 KB)</span><button type="button" class="btn btn-danger remove-file-upload"><i class="fas fa-minus"></i></button></div>';
                    errorfileupload++;
                    $("#file-upload-container").append(fileUpload);
                    $("#error-message").text(""); // Clear any previous error messages
                }
            });

            // Remove file upload input field
            $(document).on('click', '.remove-file-upload', function() {
                $(this).parent('.file-upload').remove();
                $("#error-message").text(""); // Clear any previous error messages
            });
        });
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const maxSizeInBytes = 251 * 1024; // 250 KB
    const fileInput = document.getElementById('image');
    const fileSizeLimitText = document.getElementById('file-size-limit');

    fileInput.addEventListener('change', function() {
        const files = fileInput.files;
        if (files.length > 0) {
            const fileSize = files[0].size; // Size in bytes
            const fileSizeKB = fileSize / 1024; // Size in KB
            const fileExtension = files[0].name.split('.').pop().toLowerCase(); // File extension

            // Check file size
            if (fileSize > maxSizeInBytes) {
                fileSizeLimitText.textContent = `(File size exceeds ${Math.round(fileSizeKB)} KB)`;
                fileSizeLimitText.style.color = 'red';
                fileInput.value = ''; // Clear the file input
            } else {
                // Check file extension
                const allowedFileExtensions = ['jpg',  'png', 'webp'];

                if (allowedFileExtensions.includes(fileExtension)) {
                    fileSizeLimitText.textContent = `(Max file size: 250 KB)`;
                    fileSizeLimitText.style.color = 'initial';
                } else {
                    fileSizeLimitText.textContent = `(Invalid file type, allowed: jpg,  png, webp)`;
                    fileSizeLimitText.style.color = 'red';
                    fileInput.value = ''; // Clear the file input
                }
            }
        } else {
            fileSizeLimitText.textContent = '(Max file size: 250 KB)';
            fileSizeLimitText.style.color = 'initial';
        }
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const maxSizeInBytes = 251 * 1024; // 250 KB
    const fileInput = document.getElementById('formUploadFile');
    const fileSizeLimitText = document.getElementById('file-size-limit-xls');

    fileInput.addEventListener('change', function() {
        const files = fileInput.files;
        if (files.length > 0) {
            const fileSize = files[0].size; // Size in bytes
            const fileSizeKB = fileSize / 1024; // Size in KB
            const fileExtension = files[0].name.split('.').pop().toLowerCase(); // File extension

            // Check file size
            if (fileSize > maxSizeInBytes) {
                fileSizeLimitText.textContent = `(File size exceeds ${Math.round(fileSizeKB)} KB)`;
                fileSizeLimitText.style.color = 'red';
                fileInput.value = ''; // Clear the file input
            } else {
                // Check file extension
                const allowedFileExtensions = ['pdf', 'xlsx'];

                if (allowedFileExtensions.includes(fileExtension)) {
                    fileSizeLimitText.textContent = `(Max file size: 250 KB)`;
                    fileSizeLimitText.style.color = 'initial';
                } else {
                    fileSizeLimitText.textContent = `(Invalid file type, allowed: pdf, xlsx)`;
                    fileSizeLimitText.style.color = 'red';
                    fileInput.value = ''; // Clear the file input
                }
            }
        } else {
            fileSizeLimitText.textContent = '(Max file size: 250 KB)';
            fileSizeLimitText.style.color = 'initial';
        }
    });
});
</script>

<script>
$('#healthyMindUpdate').submit(function (event) {
    event.preventDefault();
    var formData = new FormData();
    formData.append('title', $('#title').val());
    formData.append('old_image', $('#old_image').val());
    formData.append('healtymind_id', $('#healtymind_id').val());
    formData.append('video_url', $('#video_url').val());
    formData.append('image', $('#image')[0].files[0]);
    formData.append('red_guidance', $('#description').val());
    var id = 1;
    $('.file-upload').each(function () {
        formData.append('upload_file[' + (id - 1) + '][file]', $(this).find('input[name="upload_file[]"]')[0].files[0]);
        formData.append('upload_file[' + (id - 1) + '][old_upload_file]', $(this).find('input[name="old_upload_file[]"]').val());
        id++;
    });
    $.ajax({
        url: '{{ route("healty-mind-update") }}',
        type: 'POST',
        processData: false,
        contentType: false,
        data: formData,
        success: function (response) {
            if (response['status'] == true) {
                window.location.href="{{  route('healty-mind-list') }}";
            } else {
                var errors = response['errors'];
                $.each(errors, function (key, value) {
                    console.log(value[0],"value[0]");
                    var elementId = key.replace(/\./g, '_');
                    console.log(elementId,"key");
                    //$('#' + key).siblings('p').addClass('text-danger').html(value[0]);
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
@endsection
