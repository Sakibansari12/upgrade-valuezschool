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
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page">Sorting Instructional Module</li>
                        </ol>
                    </nav>
                </div>
            </div>

        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-lg-4 col-12">
                <!-- Basic Forms -->
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Select Course</h4>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="form-group">
                            <select class="form-control select2" name="course_id" id="course_id" style="width: 100%;">
                                @foreach ($course_list as $course)
                                    <option value="{{ $course->id }}"
                                        {{ $course->id == old('course_id') ? 'selected' : '' }}>
                                        {{ $course->course_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="box-header with-border grade-box">
                        <h4 class="box-title">Grade list</h4>
                    </div>

                    <div class="box-body p-0" id="class_list">
                    </div>

                </div>
                <!-- /.box -->
            </div>

            <div class="col-lg-8 col-12">
                <!-- Basic Forms -->
                <div class="box" id="lesson-box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Drag and drop for arrange sequence</h4>
                    </div>
                    <!-- /.box-header -->

                    <div class="box-body p-0">
                        <div class="media-list media-list-hover media-list-divided" id="lesson-list">
                            <a class="media media-single" href="#">
                                <span class="title text-dark">Select grade from list</span>
                            </a>
                        </div>
                    </div>

                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection

@section('script-section')
    <script type="text/javascript">
        $(function() {
            $('.grade-box,#lesson-box').hide();
            $('#course_id').change(function() {
                var courseId = $(this).val();
                $.ajax({
                    url: "{{ route('lesson.plan.sorting') }}",
                    data: {
                        type: 'grade',
                        courseid: courseId
                    }
                }).done(function(res) {
                    // console.log(res);
                    var class_html = '';
                    $.each(res, function(index, value) {
                        // console.log(value);
                        class_html +=
                            '<div class="d-flex align-items-center p-3"><span class="bullet bullet-bar bg-success align-self-stretch"></span><div class="h-20 mx-20 flex-shrink-0"><input type="radio" name="grade" value="' +
                            value.id + '" id="cls' + value.id +
                            '" class="filled-in chk-col-success choose-grade"><label for="cls' +
                            value.id + '" class="h-20 text-dark hover-success fs-16">' +
                            value.class_name + '</label></div></div>';
                    });
                    $('#lesson-box').hide();
                    $('.grade-box').show();
                    $('#class_list').html(class_html);
                });
            });

            $('#course_id').change();

        });

        $(document).on('change', '.choose-grade', function() {
            var gradeId = $('input[name="grade"]:checked').val();
            var courseId = $('#course_id').val();
            $.ajax({
                url: "{{ route('lesson.plan.sorting') }}",
                data: {
                    type: 'lessonplan',
                    grade: gradeId,
                    courseid: courseId
                }
            }).done(function(res) {
                $('#lesson-box').show();
                $('#lesson-list').html(res);
            });
        });
    </script>


    <script type="text/javascript">
        $(".media-list").sortable({
            delay: 100,
            stop: function() {
                var selectedData = new Array();
                $('.media-list>a').each(function() {
                    selectedData.push($(this).attr("id"));
                });
                updateOrder(selectedData);
            }
        });

        function updateOrder(data) {
            var gradeId = $('input[name="grade"]:checked').val();
            var courseId = $('#course_id').val();
            $.ajax({
                url: "{{ route('lesson.plan.sorting.update') }}",
                type: 'post',
                data: {
                    position: data,
                    grade: gradeId,
                    courseid: courseId
                },
                success: function(res) {
                    console.log(res);
                    $('.choose-grade').change();
                }
            })
        }
    </script>
@endsection
