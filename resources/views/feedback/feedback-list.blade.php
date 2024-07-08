@extends('layout.main')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title"></h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page">Feedback</li>
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
                        <h5 class="card-title mb-0">Feedback</h5>
                        <!-- <div class="card-actions float-end">
                            <div class="dropdown show">
                                <a href="#"  class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Add Course</a>
                            </div>
                        </div> -->

                        <form action="{{ route('feedback.teacher') }}" method="GET">
                            
                            <div class="row">
                                <!-- <input type="hidden" name="student_id_select[]" onchange="this.form.submit()"  id="student_id_select"> -->
                                <div class="card-actions  col-md-8 ">
                                    <select name="school_id_filter" id="school_id_filter"   class="form-control filter-button">
                                        <option value="">School Filter</option>
                                        @if($school_all_data->isNotEmpty())
                                            @foreach ($school_all_data as $sdata)
                                                <option value="{{ $sdata->id }}" >{{ $sdata->school_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div> 


                                <div class="col-md-4">
                                        <div class="form-group">
                                        <select name="grade" id="grade_filter" class="form-control wide form-control-sm filter-button">
                                        <option value="">All Class</option>
                                        @if($class_list->isNotEmpty())
                                            @foreach ($class_list as $cdata)
                                                <option value="{{ $cdata->id }}">{{ $cdata->class_name }}</option>
                                            @endforeach
                                        @endif
                                       </select>
                                </div>


                            </div>
                           
                            </div>  
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="yajra-table" class="table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>School Name</th>
                                        <th>Teacher Name</th>
                                        <!-- <th>Name</th> -->
                                        <th>Grade</th>
                                        <th>Section</th>
                                        <th>FeedBack</th>
                                        <th>Valuez's response</th>
                                        <th>Date & Time</th>
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
<!-- The description modal popup -->
<div class="modal fade" id="description-popup-model" tabindex="-1" role="dialog" aria-labelledby="modal-label"
        aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-label-pass" style="color: #00205c;">
                       Feedback Description </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <p id="payment_description_id"></p>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- The feedback_reply description modal popup -->
<div class="modal fade" id="feedback-reply-popup-model" tabindex="-1" role="dialog" aria-labelledby="modal-label"
        aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-label-pass" style="color: #00205c;">
                      Valuez's response </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <p id="feedback_reply_description_id"></p>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


@endsection

@section('script-section')
    <script language="javascript" type="text/javascript">
$(document).on('click', '.remove_school_data', function() {
    var courseId = $(this).attr("data-id");
    $('#remSchool').val(courseId);
    $('#bs-password-modal').modal('show');
});

$(document).on('click', '.description_popup', function() {
    var payment_description = $(this).attr('data-description');
   $('#payment_description_id').text(payment_description);
  $('#description-popup-model').modal('show');
});
$(document).on('click', '.feedback_reply_popup', function() {
    var payment_description = $(this).attr('data-description-reply');
   $('#feedback_reply_description_id').text(payment_description);
  $('#feedback-reply-popup-model').modal('show');
});


$(document).on('click', '#verify_admin_password', function() {
            $("#error-list").html('').removeClass('text-danger text-success');
            $.ajax({
                url: "{{ route('verify-admin-feedback') }}",
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
        });

        function removeData() {
               // var remId = $(this).attr('data-id');
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
                        console.log(isConfirm,"isConfirm");
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('feedback.remove') }}",
                            data: {
                                feedback_id: remId,
                            },
                            success: function(response) {
                            
                                if (response == 'removed') {
                                    swal("Completed!",
                                        "Your Data has been deleted.",
                                        "success");
                                    setTimeout(() => {
                                        location.reload();
                                    }, 600);
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
                      //  console.log(isConfirm,"isConfirm");
                    }
                });
            };


        $(function() {
           /*  $('.remove-data').click(function() {
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
                            url: "{{ route('course.remove') }}",
                            data: {
                                course: remId,
                            },
                            success: function(response) {
                               
                                if (response == 'removed') {
                                    swal("Completed!",
                                        "Your Data has been deleted.",
                                        "success");
                                    setTimeout(() => {
                                        location.reload();
                                    }, 600);
                                } else {
                                    swal("Alert!", response, "info");
                                    
                                }
                            }
                        });

                    }
                });
            }); */

            $(document).on('click', '.change_status', function() {
                var id = $(this).attr('data-id');
                var status = $(this).attr('data-status');
                $.ajax({
                    url: "{{ route('course.status') }}",
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
        });
    </script>

<script type="text/javascript">
        /* $(function() {
            var table = $('#yajra-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                order: [],
                ajax: {
                    url: "{{ route('feedback.teacher') }}",
                    data: function (d) {
                    d.school_id_filter = $("#school_id_filter").val();
                    d.grade_filter = $("#grade_filter").val();
                 
                }
                },
                columns: [
                    {
                        data: 'index',
                        name: 'index'
                    },
                    {
                        data: 'school_name',
                        name: 'school_name'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'class_name',
                        name: 'class_name'
                    },
                    {
                        data: 'section',
                        name: 'section'
                    },
                    {
                        data: 'feedback_description',
                        name: 'feedback_description'
                    },
                    {
                        data: 'feedback_reply',
                        name: 'feedback_reply'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                    },
                    {
                        data: 'action',
                        name: 'action',
                    },

                ]
            });
            $("#school_id_filter").change(function() {
               table.ajax.reload(); 
            });
            $("#grade_filter").change(function() {
               table.ajax.reload(); 
            });
        }); */
        $(function() {
    var table = $('#yajra-table').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 25,
        order: [],
        ajax: {
            url: "{{ route('feedback.teacher') }}",
            data: function (d) {
                d.school_id_filter = $("#school_id_filter").val();
                d.grade_filter = $("#grade_filter").val();
            }
        },
        columns: [
            {
                data: 'index',
                name: 'index'
            },
            {
                data: 'school_name',
                name: 'school_name'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'class_name',
                name: 'class_name'
            },
            {
                data: 'section',
                name: 'section'
            },
            {
                data: 'feedback_description',
                name: 'feedback_description'
            },
            {
                data: 'feedback_reply',
                name: 'feedback_reply'
            },
            {
                data: 'created_at',
                name: 'created_at',
            },
            {
                data: 'action',
                name: 'action',
            }
        ],
        rowCallback: function(row, data) {
            // Adding conditional background color based on feedback_reply_noty value
            var backgroundColor = data.feedback_reply_noty == 1 ? '#f2f2f2' : 'transparent';
            $(row).css('cursor', 'pointer');
            $(row).css('background-color', backgroundColor);
        }
    });

    $("#school_id_filter, #grade_filter").change(function() {
        table.ajax.reload(); 
    });
});

    </script>
@endsection
