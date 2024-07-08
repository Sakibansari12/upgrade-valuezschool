@extends('layout.main')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title"><!-- Course --></h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><i class="mdi mdi-home-outline"></i></li>
                            <li class="breadcrumb-item" aria-current="page"><a
                                    href="{{ route('teacher.class.list') }}" id="grade-name">{{ $class_name->class_name; }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Course</li>
                        </ol>
                    </nav>
                </div>
            </div>

        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    @if ($course->first() || $aicourse->first())
                        @foreach ($course as $cdata)
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="box">
                                    <div class="box-body">
                                        <div class="overlay position-relative">
                                            <img src="{{ url('uploads/course') }}/{{ $cdata->course_image ? $cdata->course_image : 'no_image.png' }}"
                                                alt="" class="img-fluid">
                                        </div>
                                        <div class="mt-30 pro-dec text-center">
                                            <h5 class="fw-500"><a href="#">{{ $cdata->course_name }}</a></h5>
                                            <div class="price-dle d-flex justify-content-center align-items-center">
                                                <a href="{{ route('teacher.lesson.list', ['classid' => $classId, 'course' => $cdata->course_id]) }}"
                                                style="background-color: #00205c; color: #fff; "  class="btn btn-sm  me-2">View Instructional Modules</a>
                                            </div>

                                        </div>
                                    </div>
                                    @php
                                        $course_coverge = App\Models\LessonPlan::join('reports as r', 'r.lesson_plan', '=', 'lesson_plan.id')
                                            // ->whereRaw('FIND_IN_SET("' . $classId . '", class_id)')
                                            ->where(['lesson_plan.status' => 1, 'course_id' => $cdata->course_id, 'classId' => $classId, 'userid' => $userId])
                                            ->selectRaw('count(lesson_plan.id) as total_atmpt_plan')
                                            ->first();
                                        
                                        $percentage_course = ($course_coverge->total_atmpt_plan / $cdata->total_plan) * 100;
                                        $percentage_val = $percentage_course >= 100 ? 100 : $percentage_course;
                                    @endphp
                                    <div class="box-footer">
                                        <div class="justify-content-around d-flex">
                                            <div class="progress progress-xl" style="width:100%;height:12px;">
                                                <div class="progress-bar"
                                                    role="progressbar" style="width: {{ intval($percentage_val) }}%;"
                                                    aria-valuenow="{{ intval($percentage_val) }}" aria-valuemin="0"
                                                    aria-valuemax="100">
                                                    {{-- <strong>{{ $course_coverge->total_atmpt_plan }}/{{ $cdata->total_plan }}</strong> --}}
                                                </div>
                                            </div>
                                            <div class="ms-2">
                                                <button class="btn btn-xs  getProgress"
                                                style="background-color: #00205c; color: #fff;"
                                                data-bs-toggle="modal"
                                                    data-bs-target="#bs-progress-modal"
                                                    data-id="{{ $cdata->course_id }}">View</button>
                                            </div>
                                        </div>
                                        <p class="text-center">
                                            <strong>{{ $course_coverge->total_atmpt_plan }}/{{ $cdata->total_plan }}</strong>
                                        </p>
                                    </div>

                                </div>
                            </div>
                        @endforeach
                        
                        
                        <!-- AI Modules Start Code -->
                        @foreach ($aicourse as $cdata)
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="box">
                                    <div class="box-body">
                                        <div class="overlay position-relative">
                                            <img src="{{ url('uploads/course') }}/{{ $cdata->course_image ? $cdata->course_image : 'no_image.png' }}"
                                                alt="" class="img-fluid">
                                        </div>
                                        <div class="mt-30 pro-dec text-center">
                                            <h5 class="fw-500"><a href="#">{{ $cdata->course_name }}</a></h5>
                                            <div class="price-dle d-flex justify-content-center align-items-center">
                                                
                                                <a href="{{ route('chat-gpt-modules', ['grade_id' => $class_name->id, 'courses_id' => $cdata->course_id]) }}"
                                                style="background-color: #00205c; color: #fff; "  class="btn btn-sm  me-2">View AI Modules</a>
                                            </div>

                                        </div>
                                    </div>
                                    @php
                                        $course_coverge = App\Models\LessonPlan::join('reports as r', 'r.lesson_plan', '=', 'lesson_plan.id')
                                            // ->whereRaw('FIND_IN_SET("' . $classId . '", class_id)')
                                            ->where(['lesson_plan.status' => 1, 'course_id' => $cdata->course_id, 'classId' => $classId, 'userid' => $userId])
                                            ->selectRaw('count(lesson_plan.id) as total_atmpt_plan')
                                            ->first();
                                        
                                        $percentage_course = ($course_coverge->total_atmpt_plan / $cdata->total_plan) * 100;
                                        $percentage_val = $percentage_course >= 100 ? 100 : $percentage_course;
                                    @endphp
                                    <div class="box-footer">
                                        <div class="justify-content-around d-flex">
                                            <div class="progress progress-xl" style="width:100%;height:12px;">
                                                <div class="progress-bar "
                                                    role="progressbar" style="width: {{ intval($percentage_val) }}%;"
                                                    aria-valuenow="{{ intval($percentage_val) }}" aria-valuemin="0"
                                                    aria-valuemax="100">
                                                    {{-- <strong>{{ $course_coverge->total_atmpt_plan }}/{{ $cdata->total_plan }}</strong> --}}
                                                </div>
                                            </div>
                                            <div class="ms-2">
                                                <button class="btn btn-xs  getProgress"
                                                style="background-color: #00205c; color: #fff;"
                                                data-bs-toggle="modal"
                                                    data-bs-target="#bs-progress-modal"
                                                    data-id="{{ $cdata->course_id }}">View</button>
                                            </div>
                                        </div>
                                        <p class="text-center">
                                            <strong>{{ $course_coverge->total_atmpt_plan }}/{{ $aicount }}</strong>
                                        </p>
                                    </div>

                                </div>
                            </div>
                        @endforeach
                        
         <!-- AI Modules End Code -->

                    @else
                        <div class="col-sm-6">
                            <div class="card card-body">
                                <h5 class="card-title fw-600">Course not found.</h5>
                                <a href="{{ route('teacher.class.list') }}" class="btn" style="background-color: #00205c; color: #fff;">Go Back</a>
                            </div> <!-- end card-->
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->

    <!-- Progress modal -->
    <div class="modal fade" id="bs-progress-modal" tabindex="-1" role="dialog" aria-labelledby="modal-label-progress"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-label-">View History</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <table id="yajra-table-login" class="table b-1" style="width:100%">
                        <thead style="background-color: #00205c; color: #fff; ">
                            <tr>
                                <th>#</th>
                                <th>Instructional Module</th>
                                <th>Date & time</th>
                            </tr>
                        </thead>
                        <tbody class="text-dark" id="view-history">

                        </tbody>
                    </table>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection

@section('script-section')
    <script language="javascript" type="text/javascript">
        $(function() {
            $('.getProgress').click(function() {
                $('#view-history').html('<tr><td class="text-center" colspan=2>No Completed Module Found</td></tr>');
                var courseId = $(this).attr('data-id');
                var gradeId = {{ $classId }};
                $.ajax({
                    type: 'POST',
                    url: "{{ route('teacher.grade.course.history') }}",
                    data: {
                        courseId: courseId,
                        classId: gradeId,
                    },
                    success: function(data) {
                        var html_row;
                        $(data).each(function(i, k) {
                            i++;
                            html_row += '<tr><td>' + i + '</td><td>' + k.title +
                                '</td><td>' + k.created_report + '</td></tr>';
                        })
                        $('#view-history').html(html_row);
                    }
                });
            });
        });
    </script>
@endsection
{{-- {{ $classId }} --}}
