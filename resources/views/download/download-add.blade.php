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
                            <li class="breadcrumb-item alignment-text-new" aria-current="page"><a href="{{ route('download-list') }}"><i class="mdi mdi-home-outline"></i> - School Ads</a></li>
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page">Add Image</li>
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
                        <h4 class="box-title">Add Image</h4>
                    </div>
                    <!-- /.box-header -->
                    <form action="{{ route('download-store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="form-group">
                                <label for="formFile" class="form-label">Upload Image <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" type="file" name="image" id="formFile" accept=".jpg, .png .webp">
                                <span  id="file-size-limit">(Max file size: 1024 KB)</span>
                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Description</label>
                                <textarea id="lesson_inst" rows="3" name="description" class="form-control"
                                    placeholder="Description">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                                  <div class="form-group">
                                        <label class="form-label">Select Schools <span class="text-danger">*</span></label>
                                        <select class="form-control" name="school_ids[]" style="width: 100%;"
                                            id="school_ids" multiple="multiple">
                                            <option value="">Select Schools</option>
                                            @foreach ($school_all_data as $school)
                                                @php $schoolIds = old('school_ids'); @endphp
                                                <option value="{{ $school->id }}"
                                                    {{ !empty($schoolIds) && in_array($school->id, $schoolIds) ? 'selected' : '' }}>
                                                    {{ $school->school_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('school_ids')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                            <hr>
                            
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
        $('.select2').select2();
        $('#class_id').select2({
            tags: true,
            placeholder: "Select a Grade",
        });
        $('#lesson_inst').wysihtml5();
        $('#conversation').wysihtml5();
    </script>
   
<script>
document.addEventListener('DOMContentLoaded', function() {
    const maxSizeInBytes = 1025 * 1024; // 250 KB
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
                    fileSizeLimitText.textContent = `(Max file size: 1024 KB)`;
                    fileSizeLimitText.style.color = 'initial';
                } else {
                    fileSizeLimitText.textContent = `(Invalid file type, allowed: jpg,  png, webp)`;
                    fileSizeLimitText.style.color = 'red';
                    fileInput.value = ''; // Clear the file input
                }
            }
        } else {
            fileSizeLimitText.textContent = '(Max file size: 1024 KB)';
            fileSizeLimitText.style.color = 'initial';
        }
    });
});
</script>
<script>
        $(document).ready(function() {

            $('.select2').select2();
            $('#school_ids').select2({
                tags: true
            });

            
        });
    </script>
@endsection
