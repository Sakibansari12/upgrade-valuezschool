@extends('layout.main')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title"><!-- Manage School --></h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <!-- <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li> -->
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page">Manage School</li>
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

                @if (session('error'))
                    <div class="alert alert-success">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">School</h5>
                        <div class="card-actions float-end">
                            <div class="dropdown show">
                                <a href="{{ route('school.add') }}"
                                    class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Add School</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="yajra-table" class="table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>School</th>
                                        {{-- <th>Name</th> --}}
                                        {{-- <th>Email</th> --}}
                                        {{-- <th>Contact</th> --}}
                                        <th>Classroom Licence</th>
                                        <th>Student Licence</th>
                                        <!-- <th>Status</th> -->
                                        <th>Classroom View</th>
                                        <th>Student View</th>
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
                        <button class="btn btn-primary" type="submit" id="remove_school_user">Submit</button>
                    </div>
                    <div class="mb-3 text-center">
                        <p class="text-center" id="error-list"></p>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Info modal -->
    <div class="modal fade" id="bs-school-modal" tabindex="-1" role="dialog" aria-labelledby="modal-label"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header py-3">
                    <h4 class="modal-title" id="modal-label-school">
                        School Detail </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <div id="viewSchool"></div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
     <!-- Modal dialog for confirmation -->
<div class="modal-status" id="confirmationModal">
    <div class="modal-content-status">
        <p style="font-size:17px;">Are you sure you want to make this change?</p>
        <div class="button-container-status">
            <button class="confirm-button-status btn-primary"  id="confirmButton">Confirm</button>
            <button class="cancel-button-status btn-primary" id="cancelButton">Cancel</button>
        </div>
    </div>
</div>
@endsection
@section('script-section')
<script type="text/javascript">
        $(function() {
            var table = $('#yajra-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('school.list') }}",
                columns: [
                    {
                        data: 'school_logo',
                        name: 'school_logo'
                    },
                    {
                        data: 'school_name',
                        name: 'school_name'
                    },
                    {
                        data: 'teacher_licence',
                        name: 'teacher_licence'
                    },
                    {
                        data: 'student_licence',
                        name: 'student_licence'
                    },
                    {
                        data: 'is_demo',
                        name: 'is_demo'
                    },
                    {
                        data: 'student_status',
                        name: 'student_status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

        });
    </script>


    <script>

$(document).on('click', '.change_student_view_status', function() {
            
            var id = $(this).attr('data-id');
            var status = $(this).attr('data-student-status');
            console.log(status, "hello");
            $.ajax({
                url: "{{ route('student.view.status') }}",
                type: "POST",
                data: {
                    school: id,
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




        $(document).on('click', '.remove_school_data', function() {
            var schoolId = $(this).attr("data-schoolid");
            $('#remSchool').val(schoolId);
            $("#error-list").html('');
        });

        $(document).on('click', '.preview_school_data', function() {
            var schoolId = $(this).attr("data-school");
            $.ajax({
                url: "{{ route('school.preview') }}",
                type: "POST",
                data: {
                    school: schoolId,
                },
                success: function(res) {
                    console.log(res,"res");
                    $("#viewSchool").html(res);
                }
            });
        });

        $(document).on('click', '#remove_school_user', function() {
            $("#error-list").html('').removeClass('text-danger text-success');
            var confirmation = confirm('Are you sure you want to delete school?');
              if (confirmation) {
            $.ajax({
                url: "{{ route('school.remove') }}",
                type: "POST",
                data: {
                    school: $("#remSchool").val(),
                    userpass: $("#userpass").val(),
                },
                success: function(res) {
                    if (res.success === true) {
                        $("#error-list").addClass('text-success').html(res.msg);
                        setTimeout(() => {
                            window.location.reload();
                        }, 500);
                    } else if (res.success === false) {
                        $("#error-list").addClass('text-danger').html(res.msg);
                    } else {
                        alert(res);
                    }
                }
            });
        }
        });


    $(document).on('click', '.change_school_demo_status', function() {
    var id = $(this).attr('data-id');
    var status = $(this).attr('data-status');
    
    // Show the confirmation modal
    $('#confirmationModal').show();

    // Handle the confirm button click
    $('#confirmButton').on('click', function() {
        $.ajax({
            url: "{{ route('school.demo.status') }}",
            type: "POST",
            data: {
                school: id,
                status: status
            },
            success: function(data) {
                // Handle success
                var csts = (status == 1) ? 0 : 1;
                        $('#demo_status_' + id).text(data).attr('data-status', csts);
                        if (csts == 1) {
                            $('#demo_status_' + id).addClass('bg-success').removeClass('bg-danger');
                        } else {
                            $('#demo_status_' + id).addClass('bg-danger').removeClass('bg-success');
                        }
                closeConfirmationModal();
            }
        });
    });

    // Handle the cancel button click
    $('#cancelButton').on('click', function() {
        // Close the confirmation modal
        closeConfirmationModal();
    });

    function closeConfirmationModal() {
        // Hide the confirmation modal
        $('#confirmationModal').hide();
    }
});


        $(document).ready(function() {
            $(document).on('click', '.change_status', function() {
                var id = $(this).attr('data-id');
                var status = $(this).attr('data-status');
            var confirmation = confirm('Are you sure you want to make this change?');
              if (confirmation) {
                $.ajax({
                    url: "{{ route('school.status') }}",
                    type: "POST",
                    data: {
                        school: id,
                        status: status
                    },
                    success: function(data) {
                        var csts = (status == 1) ? 0 : 1;
                        $('#demo_status_' + id).text(data).attr('data-status', csts);
                        if (csts == 1) {
                            $('#demo_status_' + id).addClass('bg-success').removeClass(
                                'bg-danger');
                        } else {
                            $('#demo_status_' + id).addClass('bg-danger').removeClass(
                                'bg-success');
                        }
                    }
                });
            }
            });

            /* $(document).on('click', '.change_school_demo_status', function() {
                var id = $(this).attr('data-id');
                var status = $(this).attr('data-status');
                console.log(id);
              var confirmation = confirm('Are you sure you want to make this change?');
              if (confirmation) {
                $.ajax({
                    url: "{{ route('school.demo.status') }}",
                    type: "POST",
                    data: {
                        school: id,
                        status: status
                    },
                    success: function(data) {
                        var csts = (status == 1) ? 0 : 1;
                        $('#demo_status_' + id).text(data).attr('data-status', csts);
                        if (csts == 1) {
                            $('#demo_status_' + id).addClass('bg-success').removeClass(
                                'bg-danger');
                        } else {
                            $('#demo_status_' + id).addClass('bg-danger').removeClass(
                                'bg-success');
                        }
                    }
                });
            }
            }); */
        });
    </script>
@endsection
