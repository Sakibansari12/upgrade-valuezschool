@extends('layout.main')
@section('content')
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title"></h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page"><a href="{{ route('school.list') }}"><i class="mdi mdi-home-outline"></i> - Manage School</a></li>
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page"><a href="{{ route('report.school.view',  ['school' => $school]) }}"><i class="mdi mdi-home-outline"></i> - View Analytics</a></li>
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page">Module Access Report</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                   
    <div class="card-header">
    <h5 class="card-title mb-0">Module Access Report</h5>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <select name="grade" id="grade-filter" class="form-control wide form-control-sm filter-button">
                    <option value="">All Class</option>
                    @if($class_list->isNotEmpty())
                        @foreach ($class_list as $cdata)
                            <option value="{{ $cdata->id }}">{{ $cdata->class_name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <select name="course" id="course-filter" class="form-control wide form-control-sm filter-button">
                    <option value="">All Courses</option>
                    @if($Course_list->isNotEmpty())
                        @foreach ($Course_list as $cdata)
                            <option value="{{ $cdata->id }}">{{ $cdata->course_name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <select name="lessonplan" id="lessonplan-filter" class="form-control wide form-control-sm filter-button">
                    <option value="">All Instructional Module</option>
                    @if($LessonPlan_list->isNotEmpty())
                        @foreach ($LessonPlan_list as $cdata)
                            <option value="{{ $cdata->id }}">{{ $cdata->title }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
    </div>
    <input type="hidden" name="school_id" value="{{ $school }}" id="school_id">
    <div class="card-actions float-end">
        <div class="dropdown show">
            <!-- Add any additional actions or buttons here if needed -->
        </div>
    </div>
</div>




                    
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="yajra-table" class="table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Grade</th>
                                        <th>Section</th>
                                        <th>Course</th>
                                        <th>Instructional Module</th>
                                        <th>Classroom ID</th>
                                        <th>Date & time</th>
                                    </tr>
                                </thead>
                                <tbody class="text-dark">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <!-- /.content -->
@endsection


@section('script-section')
     <script type="text/javascript">
        $(function() {
            var table = $('#yajra-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                order: [],
                ajax: {
                    url: "{{ route('module-access-report-list') }}",
                    data: function (d) {
                    d.grade = $("#grade-filter").val();
                    d.course = $("#course-filter").val();
                    d.lessonplan = $("#lessonplan-filter").val();
                    d.school_id = $("#school_id").val();
                }
                },
                columns: [/* {
                        data: 'DT_RowIndex',
                        orderable: false,
                    }, */
                    {
                        data: 'index',
                        name: 'index'
                    },
                    {
                        data: 'grade',
                        name: 'grade'
                    },
                    {
                        data: 'user_section',
                        name: 'user_section'
                    },
                    {
                        data: 'course',
                        name: 'course'
                    },
                    {
                        data: 'lesson_plan_title',
                        name: 'lesson_plan_title'
                    },
                    {
                        data: 'user_name',
                        name: 'user_name'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                    },

                ]
            });
            $("#grade-filter").change(function() {
               table.ajax.reload(); 
            });
            $("#course-filter").change(function() {
               table.ajax.reload(); 
            });
            $("#lessonplan-filter").change(function() {
               table.ajax.reload(); 
            });
        });
    </script>
@endsection
