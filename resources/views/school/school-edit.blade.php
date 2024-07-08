@extends('layout.main')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title"><!-- Update School --></h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <!-- <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li> -->
                            <li class="breadcrumb-item alignment-text-new" aria-current="page"><a href="{{ route('school.list') }}"><i class="mdi mdi-home-outline"></i> - Manage
                                    School</a></li>
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page">Edit School</li>
                        </ol>
                    </nav>
                </div>
            </div>

        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-lg-12 col-12">
                <!-- Basic Forms -->
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Update School</h4>
                    </div>
                    <!-- /.box-header -->
                    <form action="{{ route('school.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">

                            <h4 class="box-title text-primary mb-0"><i class="ti-view-grid me-15"></i> School Info</h4>
                            <hr class="my-15">
                            <div class="row">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label class="form-label">School Name <span class="text-danger">*</span></label>
                                        <input type="text" name="title" value="{{ $school->school_name }}"
                                            class="form-control" placeholder="Enter School Name">
                                        @error('title')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">School Mobile <span class="text-danger">*</span></label>
                                        <input type="text" name="mobile"  maxlength="10" value="{{ $school->mobile }}"
                                            class="form-control" placeholder="Enter School Mobile">
                                        @error('mobile')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <!-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">School Username <span class="text-danger">*</span>(Use alphanumeric. No special characters)</label>
                                        <input type="text" name="school_username" value="{{ isset($school->school_username) ? $school->school_username : '' }}" id="school_username"
                                            class="form-control" placeholder="Enter School Username">
                                        @error('school_username')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div> -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">School Username <span class="text-danger">*</span>(Use alphanumeric. No special characters)</label>
                                        <input type="text" name="school_username" value="{{ isset($school->school_username) ? $school->school_username : '' }}" id="school_username"
                                            class="form-control" placeholder="Enter School Username">
                                        <span class="text-danger" id="schoolUsernameError"></span>
                                        @error('school_username')
                                             @if($message == 'The school username format is invalid.')
                                              <span class="text-danger">Use alphanumeric characters only</span>
                                            @else
                                               <span class="text-danger">{{ $message }}</span>
                                            @endif
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">School Address <span class="text-danger">*</span></label>
                                        <input type="text" name="address" value="{{ $school->address }}"
                                            class="form-control" placeholder="Enter School Address">
                                        @error('address')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="formFile" class="form-label">School Logo </label>
                                        <input class="form-control" type="file" name="image" id="formFile">
                                        <span  id="file-size-limit">(Max file size: 250 KB)</span>
                                        <img src="{{ url('uploads/school') }}/{{ $school->school_logo ? $school->school_logo : 'no_image.png' }}"
                                            width="50px">
                                        @error('image')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">School Remarks</label>
                                        <textarea name="school_desc" class="form-control" placeholder="Enter School specific details">{{ $school->school_desc }}</textarea>
                                        @error('school_desc')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Classroom Subscription Start Date <span
                                                class="text-danger">*</span></label>
                                        <input type="date"  name="package_start" class="form-control" value="{{ $school->package_start }}" placeholder="Enter Classroom Subscription Start date">
                                        @error('package_start')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Classroom Subscription End Date <span
                                                class="text-danger">*</span></label>
                                        <input type="date" name="package_end" class="form-control"
                                            value="{{ $school->package_end }}" placeholder="Classroom Subscription End Date">
                                        @error('package_end')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Student Subscription Start Date </label>
                                        <input type="date" name="student_package_start"
                                            class="form-control" value="{{ $school->student_package_start }}"
                                            placeholder="Enter Student Subscription Start date">
                                        @error('student_package_start')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Student Subscription End Date </label>
                                        <input type="date" name="student_package_end" class="form-control"
                                            value="{{ $school->student_package_end }}" placeholder="Student Subscription End Date">
                                        @error('student_package_end')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Student Subscription Licenses <span class="text-danger">*</span></label>
                                        <input type="number" value="{{ $school->student_licence }}" name="student_licence" class="form-control"
                                            placeholder="Enter Total Student Licence">
                                        @error('student_licence')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Classroom Subscription Licenses <span class="text-danger">*</span></label>
                                        <input type="text" name="licence" value="{{ $school->licence }}"
                                            class="form-control" placeholder="Enter Classroom Subscription Licenses">
                                        @error('licence')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Classroom Grades <span class="text-danger">*</span></label>
                                        <select class="form-control" name="grade_ids[]" style="width: 100%;"
                                            id="grade_ids" multiple="multiple">
                                            <option value="">Select Classroom Grades</option>
                                            @foreach ($grades as $grade)
                                                @php $gradeIds = ($grade_ids)?$grade_ids:old('grade_ids'); @endphp
                                                <option value="{{ $grade->id }}"
                                                    {{ !empty($gradeIds) && in_array($grade->id, $gradeIds) ? 'selected' : '' }}>
                                                    {{ $grade->class_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('grade_ids')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>




                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Student Grades <span class="text-danger">*</span></label>
                                        <select class="form-control" name="student_grade_id[]" style="width: 100%;"
                                            id="student_grade_id" multiple="multiple">
                                            <option value="">Select Student Grades</option>
                                            @foreach ($grades as $grade)
                                                @php $gradeIds = ($student_grade_id)?$student_grade_id:old('student_grade_id'); @endphp
                                                <option value="{{ $grade->id }}"
                                                    {{ !empty($gradeIds) && in_array($grade->id, $gradeIds) ? 'selected' : '' }}>
                                                    {{ $grade->class_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('student_grade_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>






                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Classroom Course</label>
                                        <select class="form-control" name="course_ids[]" style="width: 100%;"
                                            id="course_ids" multiple="multiple">
                                            <option value="">Select Course</option>
                                            @foreach ($courses as $course)
                                                @php $courseIds = ($course_ids)?$course_ids:old('course_ids'); @endphp
                                                <option value="{{ $course->id }}"
                                                    {{ !empty($courseIds) && in_array($course->id, $courseIds) ? 'selected' : '' }}>
                                                    {{ $course->course_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('course_ids')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Students Course</label>
                                        <select class="form-control" name="student_course_ids[]" style="width: 100%;"
                                            id="student_course_ids" multiple="multiple">
                                            <option value="">Select Course</option>
                                            @foreach ($courses as $course)
                                                @php $studentcourseIds = ($student_course_ids)?$student_course_ids:old('student_course_ids'); @endphp
                                                <option value="{{ $course->id }}"
                                                    {{ !empty($studentcourseIds) && in_array($course->id, $studentcourseIds) ? 'selected' : '' }}>
                                                    {{ $course->course_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('student_course_ids')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="student_status">School Level Student View <span class="text-danger">*</span></label>
                            <select name="student_status" id="student_status" class="form-control">
                                <option value="">Select School level Student View</option>
                                <option {{ ($school->school_student_status == 'Demo') ? 'selected' : '' }} value="demo">Demo</option>
                                <option {{ ($school->school_student_status == 'Paid') ? 'selected' : '' }} value="paid">Paid</option>
                                <option {{ ($school->school_student_status == 'Pending') ? 'selected' : '' }} value="pending">Pending</option>
                            </select>
                            <p></p>
                            @error('student_status')
                               <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                            </div>

                            <h4 class="box-title text-primary mb-0 mt-20"><i class="ti-envelope me-15"></i> Contact Info
                            </h4>
                            <hr class="my-15">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Primary Person Name<span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="primary_person"
                                            value="{{ $school->primary_person }}" class="form-control"
                                            placeholder="Enter Person Name">
                                        @error('primary_person')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Primary Email <span class="text-danger">*</span></label>
                                        <input type="text" name="primary_email" value="{{ $school->primary_email }}"
                                            class="form-control" placeholder="Enter Primary Email">
                                        @error('primary_email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Primary Mobile <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="primary_mobile"
                                            value="{{ $school->primary_mobile }}" maxlength="10" class="form-control"
                                            placeholder="Enter Primary Mobile">
                                        @error('primary_mobile')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Password</label>
                                        <input type="text" name="primary_password" value=""
                                            class="form-control" placeholder="Enter New Password">
                                        @error('primary_password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Secondary Email </label>
                                        <input type="text" name="secondary_email" value="{{ $school->second_email }}"
                                            class="form-control" placeholder="Enter Secondary Email">
                                        @error('secondary_email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Secondary Mobile </label>
                                        <input type="text" name="secondary_mobile"
                                            value="{{ $school->second_mobile }}" maxlength="10" class="form-control"
                                            placeholder="Enter Secondary Mobile">
                                        @error('secondary_mobile')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                            </div>

                            <hr class="my-15">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">State <span class="text-danger">*</span></label>
                                        <select class="form-control select2" name="state_id" style="width: 100%;"
                                            id="state_id">
                                            <option value="">Select State</option>
                                            @foreach ($states as $state)
                                                @php $stateIds = $school->state_id; @endphp
                                                <option value="{{ $state->id }}"
                                                    {{ !empty($stateIds) && $state->id == $stateIds ? 'selected' : '' }}>
                                                    {{ $state->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('state_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">City <span class="text-danger">*</span></label>
                                        <select class="form-control select2" name="city_id" style="width: 100%;"
                                            id="city_id">
                                            <option value="">Select City</option>
                                        </select>
                                        @error('city_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Pincode <span class="text-danger">*</span></label>
                                        <input type="text" name="pincode" value="{{ $school->pincode }}"
                                            class="form-control" placeholder="Enter Pincode">
                                        @error('pincode')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                            </div>

                            <hr>
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <div class="c-inputs-stacked">
                                    <input name="status" type="radio" id="active" value="1"
                                        {{ $school->status == 1 ? 'checked' : '' }}>
                                    <label for="active" class="me-30">Active</label>
                                    <input name="status" type="radio" id="inactive" value="0"
                                        {{ $school->status == 0 ? 'checked' : '' }}>
                                    <label for="inactive" class="me-30">Inactive</label>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <input type="hidden" name="id" value="{{ $school->id }}">
                            <input type="hidden" name="old_image" value="{{ $school->school_logo }}">
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
        $(document).ready(function() {
            var cityId = {{ $school->city_id }};
            $('#state_id').on('change', function() {
                var idState = this.value;
                $("#city_id").html('');
                $.ajax({
                    url: "{{ route('city.json') }}",
                    type: "POST",
                    data: {
                        state_id: idState,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(res) {
                        $('#city_id').html('<option value="">Select City</option>');
                        $.each(res.cities, function(key, value) {
                            var select_city = (cityId > 0 && value.id == cityId) ?
                                'selected' : '';
                            $("#city_id").append('<option value="' + value
                                .id + '" ' + select_city + '>' + value.city +
                                '</option>');
                        });
                    }
                });
            });

            $('#state_id').change();
            $('#grade_ids').select2({
                tags: true
            });
            $('#course_ids').select2({
                tags: true
            });
            $('#student_course_ids').select2({
                tags: true
            });
            $('#student_grade_id').select2({
                tags: true
            });
        });
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
    document.getElementById('school_username').addEventListener('keyup', function () {
        var schoolUsernameInput = this.value;
        var alphanumericPattern = /^[a-zA-Z0-9]+$/;

        var errorSpan = document.getElementById('schoolUsernameError');

        if (!alphanumericPattern.test(schoolUsernameInput)) {
            errorSpan.textContent = 'Use alphanumeric characters only';
        } else {
            errorSpan.textContent = '';
        }
    });
</script>
@endsection
