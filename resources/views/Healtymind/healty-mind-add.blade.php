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
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page">Add Healthy Mind</li>
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
                        <h4 class="box-title">Add Healthy Mind</h4>
                    </div>
                    <!-- /.box-header -->
                    <form action="" id="healthyMindcreate"  method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="form-group">
                                <label class="form-label">Healthy Mind Module Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}" class="form-control"
                                    placeholder="Healthy Mind Module Title">
                                    <p></p>
                                @error('title')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Main Video Link<span class="text-danger">*</span></label>
                                <input type="text" name="video_url" id="video_url" value="{{ old('video_url') }}" class="form-control"
                                    placeholder="Enter Video Link">
                                    <p></p>
                                @error('video_url')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <!-- <div class="form-group">
                                <label for="formUploadFile" class="form-label">Healthy Mind Upload File <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" type="file" name="upload_file" id="formUploadFile">
                                <span  id="file-size-limit-xls">(Max file size: 250 KB)</span>
                                @error('upload_file')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div> -->
<div class="form-group" id="file-upload-container">
    <label for="formUploadFile" class="form-label">Upload pdf/xlsx File <span class="text-danger">*</span></label>
    <div class="file-upload">
        <input class="form-control" type="file" name="upload_file[]" id="upload_file_0_file" >
        <p></p>
        <span class="file-size-limit">(Max file size: 250 KB)</span>
        <button type="button" class="btn btn-success add-file-upload"><i class="fas fa-plus"></i></button>
    </div>
    @error('upload_file.*')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>
                            <div class="form-group" id="red_guidance">
                                <label class="form-label">Read Guidance <span class="text-danger">*</span></label>
                                <textarea  rows="3" name="red_guidance" id="description" class="form-control"
                                    placeholder="Read Guidance">{{ old('red_guidance') }}</textarea>
                                
                                @error('red_guidance')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <p></p>

                            <div class="form-group">
                                <label for="image" class="form-label">Healthy Mind Module Image <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" type="file" name="image" id="image" accept=".jpg, .png .webp">
                                <p></p>
                                <span  id="file-size-limit">(Max file size: 250 KB)</span>
                                
                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <hr>
                            <!-- <div class="form-group">
                                <label class="form-label">Status</label>
                                <div class="c-inputs-stacked">
                                    <input name="status" type="radio" id="active" value="1" checked>
                                    <label for="active" class="me-30">Active</label>
                                    <input name="status" type="radio" id="inactive" value="0">
                                    <label for="inactive" class="me-30">Inactive</label>
                                </div>
                            </div> -->
                        </div>
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
    <!-- /.content -->
@endsection

@section('script-section')
    <script src="{{ asset('assets/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.js') }}"></script>
    <script>
        $('#description').wysihtml5();
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
    /* $(document).ready(function() {
        
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
$('#healthyMindcreate').submit(function (event) {
    event.preventDefault();
    var formData = new FormData();
    formData.append('title', $('#title').val());
    formData.append('video_url', $('#video_url').val());
    formData.append('image', $('#image')[0].files[0]);
    formData.append('red_guidance', $('#description').val());
    var id = 1;
    $('.file-upload').each(function () {
        formData.append('upload_file[' + (id - 1) + '][file]', $(this).find('input[name="upload_file[]"]')[0].files[0]);
        id++;
    });
    $.ajax({
        url: '{{ route("healty-mind-store") }}',
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
