@extends('layout.main')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title"><!-- Package --></h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <!-- <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li> -->
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page">Avatar</li>
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
                        <h5 class="card-title mb-0">Avatar</h5>
                        <div class="card-actions float-end">
                            <div class="dropdown show">
                                 
                                <a href="{{ route('avatar-add') }}"
                                    class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Add Avatary</a>
                                 
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="yajra-table" class="table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Avatar Image</th>
                                        <th>Avatar Title</th>
                                        <!-- <th>Status</th> -->
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
                        <input type="hidden" id="remPackage" value="0" />
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
<script language="javascript" type="text/javascript">



$(document).on('click', '.change_status', function() {
            var id = $(this).attr('data-id');
            var status = $(this).attr('data-status');
            $.ajax({
                url: "{{ route('quiz-status') }}",
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





$(document).on('click', '.remove_school_data', function() {
    var packageId = $(this).attr("data-id");
    $('#remPackage').val(packageId);
    $('#bs-password-modal').modal('show');
});


$(document).on('click', '#verify_admin_password', function() {
            $("#error-list").html('').removeClass('text-danger text-success');
            $.ajax({
                url: "{{ route('avatar-verify') }}",
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
                var remId = $("#remPackage").val();
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
                            url: "{{ route('avatar-remove') }}",
                            data: {
                                quiz_id: remId,
                            },
                            success: function(response) {
                                    console.log(response,"response");
                                if (response == 'removed') {
                                    swal("Completed!",
                                        "Your Data has been deleted.",
                                        "success");
                                    setTimeout(() => {
                                        location.reload();
                                    }, 600);
                                } else {
                                    swal("Alert!", response.msg, "info");
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
</script>
<script type="text/javascript">
        $(function() {
            var table = $('#yajra-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('avatar-list') }}",
                columns: [
                    {
                        data: 'index',
                        name: 'index'
                    },
                    {
                        data: 'avatar_title_image',
                        name: 'avatar_title_image'
                    },
                    {
                        data: 'avatar_title',
                        name: 'avatar_title'
                    },
                    /* {
                        data: 'status',
                        name: 'status'
                    }, */
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
@endsection
