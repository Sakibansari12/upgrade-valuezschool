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
                            <li class="breadcrumb-item alignment-text-new" aria-current="page"><a href="{{ route('lesson.plan.list') }}"><i class="mdi mdi-home-outline"></i> - Instructional Module</a></li>
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page">Edit Instructional Module</li>
                        </ol>
                    </nav>
                </div>
            </div>

        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-lg-10 col-xl-8 col-12">
                <!-- Basic Forms -->
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Edit Instructional Module</h4>
                    </div>
                    <!-- /.box-header -->
                    <form action="{{ route('lesson.plan.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="form-group">
                                <label class="form-label">Lesson Plan Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" value="{{ $lessonplan->title }}" class="form-control"
                                    placeholder="Enter Lesson Plan Title">
                                @error('title')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Grade <span class="text-danger">*</span></label>
                                <select class="form-control select21" name="class_id[]" style="width: 100%;"
                                    multiple="multiple" id="class_id">
                                    @foreach ($program_list as $prog)
                                        @php $classIds = explode(",",$lessonplan->class_id); @endphp
                                        <option value="{{ $prog->id }}"
                                            {{ in_array($prog->id, $classIds) ? 'selected' : '' }}>
                                            {{ $prog->class_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Course <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="course_id" style="width: 100%;">
                                    @foreach ($course_list as $course)
                                        <option value="{{ $course->id }}"
                                            {{ $course->id == $lessonplan->course_id ? 'selected' : '' }}>
                                            {{ $course->course_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Main Video Link</label>
                                <input type="text" name="video_url" class="form-control"
                                    value="{{ $lessonplan->video_url }}" placeholder="Enter Video Link">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Myra Video Link</label>
                                <input type="text" name="myra_video_url" class="form-control"
                                    value="{{ $lessonplan->myra_video_url }}" placeholder="Enter Video Link">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Instructions Video Link</label>
                                <input type="text" name="video_info_url" class="form-control"
                                    value="{{ $lessonplan->video_info_url }}" placeholder="Enter Instructions Video Link">
                            </div>


                            {{-- <div class="form-group">
                                <label class="form-label">Lesson No</label>
                                <input type="text" name="lesson_no" class="form-control"
                                    value="{{ $lessonplan->lesson_no }}" placeholder="Enter Lesson No">
                            </div> --}}

                            <div class="form-group">
                                <label for="upload_file" class="form-label"> Assessment PDF<span
                                        class="text-danger"></span></label>
                                <input class="form-control" type="file" name="upload_file" id="upload_file">
                                {{ isset($lessonplan->view_assessment) ? $lessonplan->view_assessment : '' }}
                                <input  type="hidden" name="old_upload_file" id="old_upload_file" value="{{ $lessonplan->view_assessment }}">
                                
                                <span  id="file-size-view-assessment">(Max file size: 800 KB)</span>
                                @if($lessonplan->view_assessment)
                                <!-- <input type="checkbox" name="checkbox" value="checkbox_remove" > -->
                                <!-- Your HTML checkbox with increased size and changed color -->
                                <input type="checkbox" class="mt-3" name="checkbox" value="checkbox_remove" style="transform: scale(1.5); background-color: blue;">
                                @endif
                                @error('upload_file')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Teacher Guidance</label>
                                <textarea id="lesson_inst" name="lesson_desc" class="form-control" placeholder="Enter Teacher Guidance">{{ $lessonplan->lesson_desc }}</textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Conversations</label>
                                <textarea id="conversation" name="conversation" class="form-control" placeholder="Enter Conversations">{{ $lessonplan->conversation }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="formFile" class="form-label">Lesson Plan Image <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" type="file" name="image" id="formFile" accept=".jpg, .png .webp">
                                <span  id="file-size-limit">(Max file size: 250 KB)</span>
                                <img src="{{ url('uploads/lessonplan') }}/{{ $lessonplan->lesson_image ? $lessonplan->lesson_image : 'no_image.png' }}"
                                    width="100px">
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <div class="c-inputs-stacked">
                                    <input name="status" type="radio" id="active" value="1"
                                        {{ $lessonplan->status == 1 ? 'checked' : '' }}>
                                    <label for="active" class="me-30">Active</label>
                                    <input name="status" type="radio" id="inactive" value="0"
                                        {{ $lessonplan->status == 0 ? 'checked' : '' }}>
                                    <label for="inactive" class="me-30">Inactive</label>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <input type="hidden" name="id" value="{{ $lessonplan->id }}">
                            <input type="hidden" name="old_image" value="{{ $lessonplan->lesson_image }}">
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
            tags: true
        });
        //Add text editor

        //bootstrap WYSIHTML5 - text editor
        $('#lesson_inst').wysihtml5();
        $('#conversation').wysihtml5();
    </script>
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    const maxSizeInBytes = 801 * 1024; // 250 KB
    const fileInput = document.getElementById('upload_file');
    const fileSizeLimitText = document.getElementById('file-size-view-assessment');

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
                const allowedFileExtensions = ['pdf'];

                if (allowedFileExtensions.includes(fileExtension)) {
                    fileSizeLimitText.textContent = `(Max file size: 800 KB)`;
                    fileSizeLimitText.style.color = 'initial';
                } else {
                    fileSizeLimitText.textContent = `(Invalid file type, allowed: pdf)`;
                    fileSizeLimitText.style.color = 'red';
                    fileInput.value = ''; // Clear the file input
                }
            }
        } else {
            fileSizeLimitText.textContent = '(Max file size: 800 KB)';
            fileSizeLimitText.style.color = 'initial';
        }
    });
});
</script>
@endsection
