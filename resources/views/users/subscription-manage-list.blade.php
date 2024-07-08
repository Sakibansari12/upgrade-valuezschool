@extends('layout.main')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title"><!-- Subscription --></h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                           <li class="breadcrumb-item active alignment-text-new" aria-current="page"><a href="{{ route('subscription-request-list') }}"><i class="mdi mdi-home-outline"></i> - Subscription Request</a></li>
                           <li class="breadcrumb-item active alignment-text-new" aria-current="page">Manage</li>   
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
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0" style="color: #00205c;">Manage List.</h5>
                                                <div class="card-actions float-end">
                           <!--  <div class="dropdown show">
                                <a href="{{ route('by-subscription') }}"
                                    class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Add Subscription</a>
                            </div> -->


                            <!-- <a href="' . route('school-payment', ['school_id' => $row->id]) . '"
                                class="waves-effect waves-light btn btn-sm btn-outline  mb-5"
                                title="Manage Payment"><i class="fa fa-user-o"></i> Payment</a> -->

                            <a href="{{ route('subscription-request-payment', ['subscription_id' => $subscriptions->id]) }}"
                                    class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Payment</a>


                            <a href="javascript:void(0);"
                                            class="add_school_licence waves-effect waves-light btn btn-sm btn-outline btn-info mb-5"
                                            data-school-id="{{$subscriptions->school_id}}"
                                            >Licence Quantity</a>
                            <a href="{{ route('create-classroom', ['subscription_id' => $subscriptions->id]) }}"
                                    class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Create Classroom</a>
                            <a href="{{ route('export-subscription-request', ['subscription_id' => $subscriptions->id]) }}"
                                        id="export-link"
                                        class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Export</a>                
                        </div>
                       
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example2" class="table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <!-- <th>School Name</th> -->
                                        <th>Classroom Subscription</th>
                                        <th>Change Classroom Licenses</th>
                                        <th>Payment Completed</th>
                                        <th>Share Login credentials</th>
                                    </tr>
                                </thead>
                                <tbody class="text-dark">
                                @if (!empty($subscriptions))
                                    <tr>
                                        <td>{{  1 }}</td>
                                        <td>
                                            <table class="custom-table">
                                                @foreach ($subscriptions->subscription_data->subscription as $key => $packagedata)
                                                    <tr>
                                                        <td>
                                                            <p class="custom-nowrap-ellipsis">
                                                                <b>{{ $key + 1 }}</b>
                                                            </p>
                                                        </td>
                                                        <td>
                                                            <p class="custom-nowrap-ellipsis">
                                                                <b>Grade: </b> {{ isset($packagedata->garde) ? $packagedata->garde : '' }}
                                                                <hr>
                                                                <b>Section: </b> {{ isset($packagedata->section) ? $packagedata->section : '' }}
                                                            </p>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);"
                                                class="change_sr_status text-white badge bg-{{ $subscriptions->change_classroom_status == 1 ? 'success' : 'warning' }}"
                                                id="status_change_classroom_status_{{ $subscriptions->id}}"
                                                data-sr-status-type="change_classroom_status"
                                                data-sr-id="{{$subscriptions->id}}"
                                                data-chnage-sr-status="{{$subscriptions->change_classroom_status}}"
                                            >
                                                {{ $subscriptions->change_classroom_status == 1 ? 'Done' : 'Pending' }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);"
                                                    class="change_sr_status text-white badge bg-{{ $subscriptions->change_payment_status == 1 ? 'success' : 'warning' }}"
                                                    id="status_change_payment_status_{{ $subscriptions->id}}"
                                                    data-sr-status-type="change_payment_status"
                                                    data-sr-id="{{$subscriptions->id}}"
                                                    data-chnage-sr-status="{{$subscriptions->change_payment_status}}"
                                                    >{{ $subscriptions->change_payment_status == 1 ? 'Done' : 'Pending' }}</a>
                                            
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);"
                                                    class="change_sr_status text-white badge bg-{{ $subscriptions->share_login_credential == 1 ? 'success' : 'warning' }}"
                                                    id="status_share_login_credential_{{ $subscriptions->id}}"
                                                    data-sr-status-type="share_login_credential"
                                                    data-sr-id="{{$subscriptions->id}}"
                                                    data-chnage-sr-status="{{$subscriptions->share_login_credential}}"
                                                    >{{ $subscriptions->share_login_credential == 1 ? 'Done' : 'Pending' }}</a>
                                            
                                        </td>
                                    </tr>
                                    
                                     @endif
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

    <div class="modal-status" id="confirmationModal">
    <div class="modal-content-status">
        <p style="font-size:17px;">Are you sure you want to make this change?</p>
        <div class="modal-body">
                    <div class="mb-3">
                        <label for="password" class="form-label">Classroom Subscription</label>
                        <div class="input-group">
                           <!--  <span class="input-group-text"><i class="ti-lock"></i></span> -->
                            <input type="text" name="school_licence" class="form-control" id="school_licence">
                        </div>
                    </div>
                </div>
        <div class="button-container-status">
            <button class="confirm-button-status btn-primary"  id="confirmButton">Update</button>
            <button class="cancel-button-status btn-primary" id="cancelButton">Cancel</button>
        </div>
    </div>
</div>

<div class="modal-status" id="srchangeStatus">
    <div class="modal-content-status">
        <p style="font-size:17px;">Are you sure you want to make this change?</p>
        <div class="modal-body">
                    <div class="mb-3">
                        <label for="password" class="form-label">Classroom Subscription</label>
                        <div class="input-group">
                           <!--  <span class="input-group-text"><i class="ti-lock"></i></span> -->
                            <input type="text" name="school_licence" class="form-control" id="school_licence">
                        </div>
                    </div>
                </div>
        <div class="button-container-status">
            <button class="confirm-button-status btn-primary"  id="srchangeStatusButton">Update</button>
            <button class="cancel-button-status btn-primary" id="cancelsrButton">Cancel</button>
        </div>
    </div>
</div>

@endsection

@section('script-section')
<script language="javascript" type="text/javascript">
$(document).on('click', '.remove_school_data', function() {
    var packageId = $(this).attr("data-id");
    $('#remPackage').val(packageId);
    $('#bs-password-modal').modal('show');
});


$(document).on('click', '#verify_admin_password', function() {
            $("#error-list").html('').removeClass('text-danger text-success');
            $.ajax({
                url: "{{ route('package.verify-admin') }}",
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
                            url: "{{ route('package.remove') }}",
                            data: {
                                package_id: remId,
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




            $(document).on('click', '.add_school_licence', function() {
    var school_id = $(this).attr('data-school-id');
    var status = $(this).attr('data-status');
    
    // Show the confirmation modal
    $('#confirmationModal').show();

    // Handle the confirm button click
    $('#confirmButton').on('click', function() {
        $.ajax({
            url: "{{ route('school-licence-update') }}",
            type: "POST",
            data: {
                school_id: school_id,
                school_licence: $("#school_licence").val(),
            },
            success: function(response){
         if(response['status'] == true){
               window.location.href="{{  route('subscription-request-list') }}";
            // window.location.href="{{  route('student.grade.list') }}";
         }else{
            var errors = response['errors'];
             $.each(errors, function(key,value){
             $(`#${key}`).siblings('p').addClass('text-danger').html(value);
          })
         
      }
    },
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


$(document).on('click', '.change_sr_status', function() {
    var sr_id = $(this).attr('data-sr-id');
    var sr_manage_status_type = $(this).attr('data-sr-status-type');
    var chnage_sr_status = $(this).attr('data-chnage-sr-status');
    $.ajax({
    url: "{{ route('subscription-request-status-update') }}",
    type: "POST",
    data: {
        sr_id: sr_id,
        status_type: sr_manage_status_type,
        sr_status_value: chnage_sr_status
    },
    success: function(data) {
        // Handle success
        var csts = (chnage_sr_status == 1) ? 0 : 1;
        $('#status_' + sr_manage_status_type + '_' + sr_id).text(data).attr('data-chnage-sr-status', csts);
        if (csts == 1) {
            $('#status_' + sr_manage_status_type + '_' + sr_id).addClass('bg-success').removeClass('bg-warning');
            
        } else {
            $('#status_' + sr_manage_status_type + '_' + sr_id).addClass('bg-warning').removeClass('bg-success');
        }
       // closeConfirmationModalSR();
    }
});

});
</script>
@endsection
