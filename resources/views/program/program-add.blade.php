@extends('layout.main')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title"><!-- Grade --></h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item alignment-text-new" aria-current="page"><a href="{{ route('program.list') }}"><i class="mdi mdi-home-outline"></i> - Grade</a></li>
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page">Add Grade</li>
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
                        <h4 class="box-title">Add New Grade</h4>
                    </div>
                    <!-- /.box-header -->
                    <form action="{{ route('program.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="form-group">
                                <label class="form-label">Grade Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" placeholder="Enter Grade Title">
                                @error('title')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="formFile" class="form-label">Grade Image <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" type="file" name="image" id="formFile" accept=".jpg, .png .webp">
                                <span  id="file-size-limit">(Max file size: 250 KB)</span>
                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <div class="c-inputs-stacked">
                                    <input name="status" type="radio" id="active" value="1" checked>
                                    <label for="active" class="me-30">Active</label>
                                    <input name="status" type="radio" id="inactive" value="0">
                                    <label for="inactive" class="me-30">Inactive</label>
                                </div>
                            </div>
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    const maxSizeInBytes = 251 * 1024; // 250 KB
    const fileInput = document.getElementById('formFile');
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

@endsection