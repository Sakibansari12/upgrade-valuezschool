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
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page"> Instructional Module</li>
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
                @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        {{ $message }}
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Instructional Module</h5>

                        <div class="d-flex float-end">
                            <div id="class-filter" class="px-3"></div>
                            <div id="course-filter" class="px-3"></div>

                            <a href="{{ route('lesson.plan.sorting') }}"
                                class="waves-effect waves-light btn btn-sm btn-outline btn-primary mb-5 me-2">Instructional
                                Sorting
                            </a>
                            <a href="{{ route('lesson.plan.add') }}"
                                class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Add Instructional
                                Module
                            </a>
                        </div>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="yajra-table" class="table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Class Name</th>
                                        <th>Course</th>
                                        <th>Video Link</th>
                                        <th>Assessment</th>
                                        <th>Status</th>
                                        <th>Demo</th>
                                        <th>Action</th>
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


    <!-- Info modal -->
<div class="modal fade" id="bs-password-modal" tabindex="-1" role="dialog" aria-labelledby="modal-label"
        aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-label-pass">
                        Verify your Account </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ti-lock"></i></span>
                            <input type="text" name="userpass" class="form-control" id="userpass">
                        </div>
                    </div>

                    <div class="mb-3 text-center">
                        <input type="hidden" id="remSchool" value="0" />
                        <button class="btn btn-primary" type="submit" id="verify_admin_password">Submit</button>
                    </div>
                    <div class="mb-3 text-center">
                        <p class="text-center" id="error-list"></p>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

@endsection

@section('script-section')
    <script type="text/javascript">
        $(function() {

            var table = $('#yajra-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 50,
                order: [],
                ajax: "{{ route('lesson.plan.list') }}",
                columns: [{
                        data: 'lesson_image',
                        name: 'lesson_image',
                        orderable: false,
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'class_name',
                        name: 'class_id'
                    },
                    {
                        data: 'course_name',
                        name: 'master_course.course_name'
                    },
                    {
                        data: 'video_url',
                        name: 'video_url'
                    },
                    {
                        data: 'view_assessment',
                        name: 'view_assessment'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'is_demo',
                        name: 'is_demo'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                initComplete: function() {
                    this.api().column(2).every(function() {
                        var column = this;
                        var select = $(
                                '<select class="default-select form-control wide form-control-sm fw-bolder" id="class-filter"><option value="">All Class</option></select>'
                            )
                            .appendTo($('#class-filter').empty())
                            .on('change', function() {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
                                column.search(this.value).draw();
                            });
                        @foreach ($class_list as $cdata)
                            select.append(
                                '<option value="{{ $cdata->id }}" data-id="{{ $cdata->id }}">{{ $cdata->class_name }}</option>'
                            );
                        @endforeach
                    });

                    this.api().column(3).every(function() {
                        var column = this;
                        var select = $(
                                '<select class="default-select form-control wide form-control-sm fw-bolder" id="class-filter"><option value="">All Course</option></select>'
                            )
                            .appendTo($('#course-filter').empty())
                            .on('change', function() {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
                                column.search(this.value).draw();
                            });
                        @foreach ($course_list as $courseData)
                            select.append(
                                '<option value="{{ $courseData->course_name }}" data-id="{{ $courseData->id }}">{{ $courseData->course_name }}</option>'
                            );
                        @endforeach
                    });
                }
            });

        });


        $(document).on('click', '.change_status', function() {
            var id = $(this).attr('data-id');
            var status = $(this).attr('data-status');
            $.ajax({
                url: "{{ route('lesson.status') }}",
                type: "POST",
                data: {
                    sts_id: id,
                    status: status
                },
                success: function(data) {
                    var csts = (status == 1) ? 0 : 1;
                    $('#status_' + id).text(data).attr('data-status', csts);
                    if (csts == 1) {
                        $('#status_' + id).addClass('bg-success').removeClass('bg-danger');
                    } else {
                        $('#status_' + id).addClass('bg-danger').removeClass('bg-success');
                    }
                }
            });
        });

        $(document).on('click', '.change_demo_status', function() {
            console.log("hello");
            var id = $(this).attr('data-id');
            var status = $(this).attr('data-status');
            $.ajax({
                url: "{{ route('lesson.demo.status') }}",
                type: "POST",
                data: {
                    lessonid: id,
                    status: status
                },
                success: function(data) {
                    var csts = (status == 1) ? 0 : 1;
                    $('#status_demo_' + id).text(data).attr('data-status', csts);
                    if (csts == 1) {
                        $('#status_demo_' + id).addClass('bg-success').removeClass('bg-danger');
                    } else {
                        $('#status_demo_' + id).addClass('bg-danger').removeClass('bg-success');
                    }
                }
            });
        });
    </script>

    <script language="javascript" type="text/javascript">

$(document).on('click', '.remove_school_data', function() {
    var courseId = $(this).attr("data-id");
    $('#remSchool').val(courseId);
    $('#bs-password-modal').modal('show');
});

$(document).on('click', '#verify_admin_password', function() {
            $("#error-list").html('').removeClass('text-danger text-success');
            /* var confirmation = confirm('Are you sure you want to delete school?'); */
              /* if (confirmation) { */
            $.ajax({
                url: "{{ route('instruction-module.verify-admin') }}",
                type: "POST",
                data: {
                   // school: $("#remSchool").val(),
                    userpass: $("#userpass").val(),
                },
                success: function(res) {
                    if (res.success === true) {
                        $("#error-list").addClass('text-success').html(res.msg);
                        removeData();
                        /* setTimeout(() => {
                            window.location.reload();
                        }, 500); */
                    } else if (res.success === false) {
                        $("#error-list").addClass('text-danger').html(res.msg);
                    } else {
                        alert(res);
                    }
                }
            });
        /* } */
        });

        function removeData() {
                //var remId = $(this).attr('data-id');
                var remId = $("#remSchool").val();
               swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#fec801",
                    confirmButtonText: "Yes",
                    cancelButtonText: "Cancel",
                    closeOnConfirm: false,
                    closeOnCancel: true
                }, function(isConfirm) {
                    if (isConfirm) {
                        console.log(remId);
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('lesson.plan.remove') }}",
                            data: {
                                lessonplan: remId,
                            },
                            success: function(response) {
                             
                                if (response == 'removed') {
                                    swal("Completed!",
                                        "Your Data has been deleted.",
                                        "success");
                                    $('#yajra-table').DataTable().ajax.reload();
                                } else {
                                    swal("Alert!", response, "info");
                                    
                                }
                            }
                        });

                    }else{
                        $('#bs-password-modal').hide();
                        setTimeout(() => {
                            window.location.reload();
                        }, 500); 
                    }
                });
            };


        /* $(function() {
            $(document).on('click', '.remove-data', function() {
                var remId = $(this).attr('data-id');
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#fec801",
                    confirmButtonText: "Yes",
                    cancelButtonText: "Cancel",
                    closeOnConfirm: false,
                    closeOnCancel: true
                }, function(isConfirm) {
                    if (isConfirm) {
                        console.log(remId);
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('lesson.plan.remove') }}",
                            data: {
                                lessonplan: remId,
                            },
                            success: function(response) {
                             
                                if (response == 'removed') {
                                    swal("Completed!",
                                        "Your Data has been deleted.",
                                        "success");
                                    $('#yajra-table').DataTable().ajax.reload();
                                } else {
                                    swal("Alert!", response, "info");
                                    
                                }
                            }
                        });

                    }
                });
            });

        }); */
    </script>
@endsection
