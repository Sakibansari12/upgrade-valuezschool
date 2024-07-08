@extends('layout.main')
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xl-9 col-12">
                <div class="box bg-success">
                    <div class="box-body d-flex p-0">
                        <div class="flex-grow-1 p-30 flex-grow-1 bg-img bg-none-md"
                            style="background-position: right bottom; background-size: auto 100%; background-image: url(../../../images/svg-icon/color-svg/custom-30.svg)">
                            <div class="row">
                                <div class="col-12 col-xl-7">
                                    <h1 class="mb-0 fw-600">NEP compliant Values and Near-Future-Tech courses</h1>
                                    {{-- <p class="my-10 fs-16 text-white-70">Get 30% off every course on january.</p> --}}
                                    <div class="mt-45 d-md-flex align-items-center">
                                        <div class="me-30 mb-30 mb-md-0">
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="me-15 text-center fs-24 w-50 h-50 l-h-50 bg-danger b-1 border-white rounded-circle">
                                                    <i class="fa fa-graduation-cap"></i>
                                                </div>
                                                <div>
                                                    <h5 class="mb-0">School</h5>
                                                    <p class="mb-0 text-white-70">{{ isset($school) ? $school : '' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="me-30 mb-30 mb-md-0">
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="me-15 text-center fs-24 w-50 h-50 l-h-50 bg-warning b-1 border-white rounded-circle">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                                <div>
                                                    <h5 class="mb-0">Classrooms</h5>
                                                    <p class="mb-0 text-white-70">{{ isset($teacher) ? $teacher : '' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="me-30 mb-30 mb-md-0">
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="me-15 ml-6 text-center fs-24 w-50 h-50 l-h-50 bg-warning b-1 border-white rounded-circle">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                                <div>
                                                    <h5 class="mb-0">Students</h5>
                                                    <p class="mb-0 text-white-70">{{ isset($student) ? $student : '' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="col-12 col-xl-5"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-12">
                <div class="box bg-transparent no-shadow">
                    <div class="box-body p-xl-0 text-center">
                        <h3 class="px-30 mb-20">Have More<br>Knowledge to share?</h3>
                        <a href="{{ route('lesson.plan.add') }}" class="waves-effect waves-light w-p100 btn" style="background-color: #00205c; color: #fff;"><i
                                class="fa fa-plus me-15"></i> Create New Instructional Module</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <a class="box box-link-shadow text-center pull-up" href="javascript:void(0)">
                            <div class="box-body py-5 bg-primary-light px-5">
                                <p class="fw-500 text-primary text-overflow">Courses</p>
                            </div>
                            <div class="box-body p-10">
                                <h1 class="countnm fs-40 m-0">{{ $course }}</h1>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xl-12 col-12">
                <div class="row">
                    <div class="col-xl-3 col-4">
                        <div class="box bg-info">
                            <div class="box-body">
                                <h2 class="my-0 fw-600 text-white">{{ $school }}</h2>
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="mb-10 text-white-80">School</p>
                                    <button type="button"
                                        class="waves-effect waves-circle btn btn-circle btn-warning-light"><i
                                            class="mdi mdi-arrow-top-right"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-4">
                        <div class="box bg-warning">
                            <div class="box-body">
                            <a href="javascript:void(0);" class="view_password"
                            data-active-teacher="{{$total_teacher_active}}"
                            data-number-teacher="{{$teacher}}"
                            data-teacher-compelte="{{$total_teacher_compelte}}"
                            >
                                <h2 class="my-0 fw-600 text-white">{{ $teacher }}</h2>
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="mb-10 text-white-80">Classrooms</p>
                                    <button type="button" class="waves-effect waves-circle btn btn-circle btn-warning-light">
                                        <i class="mdi mdi-arrow-top-right"></i>
                                    </button>
                                </div>
                            </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-4">
                        <div class="box bg-danger">
                            <div class="box-body">
                                <h2 class="my-0 fw-600 text-white">{{ $student }}</h2>
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="mb-10 text-white-80">Students</p>
                                    <button type="button"
                                        class="waves-effect waves-circle btn btn-circle btn-warning-light"><i
                                            class="mdi mdi-arrow-top-right"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-4">
                        <div class="box bg-success">
                            <div class="box-body">
                                <h2 class="my-0 fw-600 text-white">{{ $program }}</h2>
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="mb-10 text-white-80">Grades</p>
                                    <button type="button"
                                        class="waves-effect waves-circle btn btn-circle btn-warning-light"><i
                                            class="mdi mdi-arrow-top-right"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-4">
                        <div class="box bg-primary">
                            <div class="box-body">
                                <h2 class="my-0 fw-600 text-white">{{ $lessonplan }}</h2>
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="mb-10 text-white-80">Lesson Plan</p>
                                    <button type="button"
                                        class="waves-effect waves-circle btn btn-circle btn-warning-light"><i
                                            class="mdi mdi-arrow-top-right"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <!-- /.content -->

    <div class="modal fade" id="bs-viewpass-modal" tabindex="-1" role="dialog" aria-labelledby="modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 300px;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-label-pass">
                    <i class="fa fa-info-circle text-info fs-18"></i> Teacher Details
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>

            <div class="modal-body">
                <div class="d-flex align-items-center ">
                    <label>Total Number of Teacher</label>
                    <h4 class="p-10 mt-5 text-primary" id="viewNumberTeacher">0</h4>
                </div>
                <div class="d-flex align-items-center ">
                    <label>Total Number of Active Teacher</label>
                    <h4 class="p-10 mt-5 text-primary" id="viewActiveTeacher">0</h4>
                </div>
                <div class="d-flex align-items-center">
                    <label>Number of Modules Completed</label>
                    <h4 class="p-10 mt-5 text-primary" id="viewModulesCompleted">0</h4>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@endsection
@section('script-section')
<script>
$(document).ready(function() {

    $(document).on('click', '.view_password', function() {
        var viewNumberTeacher = $(this).attr('data-number-teacher');
        var viewActiveTeacher = $(this).attr('data-active-teacher');
        var viewTeacherCompelete = $(this).attr('data-teacher-compelte');
        $("#viewNumberTeacher").text(viewNumberTeacher);
        $("#viewActiveTeacher").text(viewActiveTeacher);
        $("#viewModulesCompleted").text(viewTeacherCompelete);
        $('#bs-viewpass-modal').modal('show');
        // alert("Password : " + viewPass);
    });
});

        function teacherpopup() {
    document.getElementById('teacherPopup').style.display = 'block';
}

function closeTeacherPopup() {
    document.getElementById('teacherPopup').style.display = 'none';
}

    </script>
@endsection
