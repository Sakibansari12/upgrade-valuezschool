@extends('layout.main')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title"><!-- Forgot password --></h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <!-- <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li> -->
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page">Forgot password
                              
                            </li>
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
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Forgot password school admin</h5>
                        <div class="card-actions float-end">
                        </div>
                    </div>
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-actions float-end">
                                    <div class="dropdown show">
                                        <a href="{{ route('student.forgot.list') }}"
                                            class="waves-effect waves-light btn btn-sm  mb-5" style="background-color: #00205c; color: #ffff;">Student Forgot Password List</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example2" class="table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>School Name</th>
                                        <th>Email</th>
                                        <th>Date & Time</th>
                                        <th>Action</th> 
                                    </tr>
                                </thead>
                                <tbody class="text-dark">
                                    @if($schooladminlist->isNotEmpty())
                                    @foreach ($schooladminlist as $key => $data)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $data->name }}</td>
                                            <td>{{ $data->school_name }}</td>
                                            <td>{{ $data->email }}</td>
                                            <td>
                                            {{ isset($data->created_at) ? \Carbon\Carbon::parse($data->created_at)->format('d/m/Y') : '' }} <strong>|</strong>
                                            {{ isset($data->created_at) ? \Carbon\Carbon::parse($data->created_at)->format('H:i') : '' }}
                                            </td>
                                            <td>
                                                <!-- <a href="javascript:void(0);"
                                                    class="change_status text-white badge bg-{{ $data->status == 1 ? 'success' : 'danger' }}"
                                                    id="status_{{ $data->id }}" data-id="{{ $data->id }}"
                                                    data-status="{{ $data->status }}">{{ $data->status == 1 ? 'Active' : 'Inactive' }}</a> -->
                                                    <button class="btn btn-sm btn-outline btn-primary mb-5 dropdown-toggle"
                                                    type="button" data-bs-toggle="dropdown"><i
                                                        class="icon ti-settings"></i>
                                                    Password</button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <!-- <a class="reset_password dropdown-item" href="javascript:void(0);"
                                                        data-userid="{{ $data->id }}"><i class="fa fa-refresh"></i>
                                                        Reset</a>
                                                    <div class="dropdown-divider"></div> -->
                                                    <a class="view_password dropdown-item" data-email="{{ $data->email }}"
                                                        data-pass="{{ $data->view_pass }}" href="javascript:void(0);"><i
                                                            class="fa fa-eye"></i> View</a>
                                                </div>
                                                <a href="{{ route('school-admin-forgot-edit', ['forgot_password_id' => $data->id]) }}"
                                                    class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Edit</a>
                                                <a href="javascript:void(0);" data-id="{{ $data->id }}"
                                                        class="waves-effect waves-light remove_school_data btn btn-sm btn-outline btn-danger mb-5">Delete</a>
                                            
                                            </td>
                                        </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <div class="modal fade" id="bs-viewpass-modal" tabindex="-1" role="dialog" aria-labelledby="modal-label"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-label-pass">
                        <i class="fa fa-info-circle text-info fs-25"></i> User Account Login Info
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>

                <div class="modal-body">
                    <div class="d-flex align-items-center mb-30">
                        <div class="me-15 h-40 w-40 rounded text-center">
                            <i class="fa fa-envelope-o text-primary fs-30"></i>
                        </div>
                        <div class="d-flex flex-column flex-grow-1 me-2">
                            <span class="text-dark hover-primary mb-1 fs-16" id="viewEmail"></span>
                        </div>
                        <a href="javascript:void(0);" class="badge badge-xl badge-primary-light click2copy"
                            data-copy="email"><span class="fw-600"><i class="fa fa-copy"></i></span></a>
                    </div>

                    <div class="d-flex align-items-center mb-30">
                        <div class="me-15 h-40 w-40 rounded text-center">
                            <i class="fa fa-lock text-primary fs-30"></i>
                        </div>
                        <div class="d-flex flex-column flex-grow-1 me-2">
                            <span class="text-dark hover-primary mb-1 fs-16" id="viewPass"></span>
                        </div>
                        <a href="javascript:void(0);" class="badge badge-xl badge-primary-light click2copy"
                            data-copy="pass"><span class="fw-600"><i class="fa fa-copy"></i></span></a>
                    </div>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
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
                        <input type="hidden" id="remUser" value="0" />
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

<script language="javascript" type="text/javascript">
    $(document).on('click', '.remove_school_data', function() {
        var courseId = $(this).attr("data-id");
        console.log(courseId,"courseId");
        $('#remSchool').val(courseId);
        $('#bs-password-modal').modal('show');
    });


    $(document).on('click', '#verify_admin_password', function() {
            $("#error-list").html('').removeClass('text-danger text-success');
            $.ajax({
                url: "{{ route('forgot-pass-verify-admin') }}",
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
                console.log(remId,"remId");
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
                            url: "{{ route('forgot-pass-sa-remove') }}",
                            data: {
                                forgot_password_id: remId,
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
</script>
    <script>
        $(document).on('click', '.click2copy', function() {
            var copyType = $(this).attr('data-copy');
            if (copyType == "pass") {
                copytext = $("#viewPass").text();
            } else if (copyType == "email") {
                copytext = $("#viewEmail").text();
            }
            //Copy to clipboard
            var temp = document.createElement('input');
            var texttoCopy = "copytextfff";
            temp.type = 'input';
            temp.setAttribute('value', texttoCopy);
            document.body.appendChild(temp);
            temp.select();
            temp.setSelectionRange(0, 99999); // For mobile devices
            // Copy the text inside the text field
            navigator.clipboard.writeText(copytext);
            temp.remove();
            alert('Copy to Clipboard');
            return false;
        });


$(document).ready(function() {

$(document).on('click', '.view_password', function() {
    var viewPass = $(this).attr('data-pass');
    var viewEmail = $(this).attr('data-email');
    $("#viewPass").text(viewPass);
    $("#viewEmail").text(viewEmail);
    $('#bs-viewpass-modal').modal('show');
    // alert("Password : " + viewPass);
});

$(document).on('click', '.reset_password', function() {
    let text;
    if (confirm("Press Ok for Reset Password!") == true) {
        $.ajax({
            url: "{{ route('user.password') }}",
            type: "POST",
            data: {
                userid: $(this).attr("data-userid"),
            },
            success: function(data) {
                alert("Your Password Reset");
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            }
        });
    } else {
        text = "You canceled!";
        console.log(text);
    }
});


$(document).on('click', '.change_status', function() {
    var id = $(this).attr('data-id');
    var status = $(this).attr('data-status');
    console.log(id);
    var confirmation = confirm('Are you sure you want to make this change?');
    if (confirmation) {
        $.ajax({
            url: "{{ route('admint.status') }}",
            type: "POST",
            data: {
                user_id: id,
                status: status
            },
            success: function(data) {
                var csts = (status == 1) ? 0 : 1;
                $('#status_' + id).text(data).attr('data-status', csts);
                if (csts == 1) {
                    $('#status_' + id).addClass('bg-success').removeClass(
                        'bg-danger');
                } else {
                    $('#status_' + id).addClass('bg-danger').removeClass(
                        'bg-success');
                }
            }
        });
    }
});
});
 
</script>

@endsection