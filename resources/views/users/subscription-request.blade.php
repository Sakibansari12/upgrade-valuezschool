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
                           
                           <li class="breadcrumb-item active alignment-text-new" aria-current="page">Subscription Request</li>   
                           
                            
                            
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
                        <h5 class="card-title mb-0" style="color: #00205c;">Subscription Request List.</h5>
                        <!-- <div class="card-actions float-end">
                            <div class="input-group mt-3">
                                <input type="text" class="form-control" id="searchInput" placeholder="Search">
                            </div>
                        </div> -->
                       
                       <!--  <div class="card-actions float-end">
                            <div class="dropdown show">
                                <a href="{{ route('by-subscription') }}"
                                    class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Add Subscription</a>
                            </div>
       
                        </div> -->
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="subscritionrequest" class="table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>SR ID</th>
                                        <th>School Name</th>
                                        <th>Classroom View</th>
                                        <th>Classroom Subscription</th>
                                        <th>Service Request Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-dark">
                                @foreach ($subscriptions as $keys=> $cdata)
                                    <tr style="cursor: pointer; background-color: {{ $cdata->notify_subscription_status == 1 ? '#f2f2f2' : 'transparent' }};">
                                        
                                        <td>
                                            @if($cdata->notify_subscription_status == 1)
                                                                <b>{{ $keys + 1 }}</b>
                                            @else
                                                {{ $keys + 1 }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($cdata->notify_subscription_status == 1)
                                                                <b><b>{{ isset($cdata->sr_number) ? $cdata->sr_number : '' }}</b>
                                            @else
                                                {{ isset($cdata->sr_number) ? $cdata->sr_number : '' }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($cdata->notify_subscription_status == 1)
                                                <b>{{ isset($cdata->school_name) ? $cdata->school_name : '' }}</b>
                                            @else
                                                {{ isset($cdata->school_name) ? $cdata->school_name : '' }}
                                            @endif
                                        </td>
                                        <td>
                                           @if($cdata->notify_subscription_status == 1)
                                           <b><a href="javascript:void(0);"
                                                class="text-white badge bg-{{ $cdata->is_demo == 1 ? 'success' : 'danger' }}"
                                                id="demo_status_{{ $cdata->id }}" data-id="{{ $cdata->id }}"
                                                data-status="{{ $cdata->is_demo }}">{{ $cdata->is_demo == 1 ? 'Demo Subscriber' : 'Paid Subscriber' }}</a></b>
                                            @else 
                                            <a href="javascript:void(0);"
                                                class="text-white badge bg-{{ $cdata->is_demo == 1 ? 'success' : 'danger' }}"
                                                id="demo_status_{{ $cdata->id }}" data-id="{{ $cdata->id }}"
                                                data-status="{{ $cdata->is_demo }}">{{ $cdata->is_demo == 1 ? 'Demo Subscriber' : 'Paid Subscriber' }}</a>
                                            @endif       
                                        </td>
                                        <td>
                                            @if($cdata->notify_subscription_status == 1)
                                                <b><div class="engineer-listing">
                                                    <p class="m-0 custom-nowrap-ellipsis"><b>Total Subscriptions: </b>{{ isset($cdata->licence) ? $cdata->licence : '' }}</p>
                                                    <p class="m-0 custom-nowrap-ellipsis"><b>SR Subscriptions: </b>{{ isset($cdata->package_row_count) ? $cdata->package_row_count : '' }}</p>
                                                </div></b>
                                            @else  
                                                <div class="engineer-listing">
                                                    <p class="m-0 custom-nowrap-ellipsis">Total Subscriptions: {{ isset($cdata->licence) ? $cdata->licence : '' }}</p>
                                                    <p class="m-0 custom-nowrap-ellipsis">SR Subscriptions: {{ isset($cdata->package_row_count) ? $cdata->package_row_count : '' }}</p>
                                                </div> 
                                            @endif     
                                        </td>
                                        <td>
                                        @if($cdata->notify_subscription_status == 1)
                                           @if($cdata->change_classroom_status == 1 && $cdata->change_payment_status == 1 && $cdata->share_login_credential == 1)
                                           <!-- <b> <a href="javascript:void(0);"
                                            class=" text-white badge bg-{{ $cdata->subscription_status == 1 ? 'success' : 'warning' }}"
                                            id="status_{{ $cdata->id }}" data-id="{{ $cdata->id }}"
                                            data-subscription_status="{{ $cdata->subscription_status }}">{{ $cdata->subscription_status == 1 ? 'Complete' : 'Pending' }}</a></b> -->
                                            <b> <a href="javascript:void(0);"
                                            class=" text-white badge bg-success"
                                            id="status_{{ $cdata->id }}" data-id="{{ $cdata->id }}">Complete</a></b>
                                            @else
                                                <b> <a href="javascript:void(0);"
                                                class="text-white badge bg-warning">Pending</a></b>
                                            @endif
                                        @else 
                                           @if($cdata->change_classroom_status == 1 && $cdata->change_payment_status == 1 && $cdata->share_login_credential == 1)
                                           <!-- <b> <a href="javascript:void(0);"
                                            class=" text-white badge bg-{{ $cdata->subscription_status == 1 ? 'success' : 'warning' }}"
                                            id="status_{{ $cdata->id }}" data-id="{{ $cdata->id }}"
                                            data-subscription_status="{{ $cdata->subscription_status }}">{{ $cdata->subscription_status == 1 ? 'Complete' : 'Pending' }}</a></b> -->
                                            <b> <a href="javascript:void(0);"
                                            class=" text-white badge bg-success"
                                            id="status_{{ $cdata->id }}" data-id="{{ $cdata->id }}">Complete</a></b>
                                            @else
                                                <b> <a href="javascript:void(0);"
                                                class="text-white badge bg-warning">Pending</a></b>
                                            @endif
                                        
                                        @endif
                                        </td>
                                       <td>
                                       @if($cdata->notify_subscription_status == 1)
                                        <b><a href="{{ route('subscription-manage-list', ['subscription_id' => $cdata->id]) }}"
                                                class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Manage</a></b>
                                       @else 
                                       <a href="{{ route('subscription-manage-list', ['subscription_id' => $cdata->id]) }}"
                                                class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Manage</a>
                                       @endif

                                       @if($cdata->notify_subscription_status == 1)
                                        <b>
                                                <a href="javascript:void(0)" data-bs-toggle="modal"
                                                    data-bs-target="#bs-password-modal"
                                                    class="remove_user_data waves-effect  waves-light btn btn-sm btn-outline btn-danger mb-5"
                                                    data-sr-id="{{ $cdata->id }}" >Delete</a>
                                            </b>
                                       @else 
                                        <a href="javascript:void(0)" data-bs-toggle="modal"
                                                    data-bs-target="#bs-password-modal"
                                                    class="remove_user_data waves-effect  waves-light btn btn-sm btn-outline btn-danger mb-5"
                                                    data-sr-id="{{ $cdata->id }}" >Delete</a>
                                       @endif
                                       </td>
                                    </tr>
                                @endforeach
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

                    <!-- <div class="mb-3 text-center">
                        <input type="hidden" id="remPackage" value="0" />
                        <button class="btn btn-primary" type="submit" id="verify_admin_password">Submit</button>
                    </div>
                    <div class="mb-3 text-center">
                        <p class="text-center" id="error-list"></p>
                    </div> -->
                </div>
        <div class="button-container-status">
            <button class="confirm-button-status btn-primary"  id="confirmButton">Update</button>
            <button class="cancel-button-status btn-primary" id="cancelButton">Cancel</button>
        </div>
    </div>
</div>

@endsection

@section('script-section')
<script language="javascript" type="text/javascript">
$(document).on('click', '.remove_user_data', function() {
    var packageId = $(this).attr("data-sr-id");
    console.log(packageId,"packageId");
    $('#remPackage').val(packageId);
    $('#bs-password-modal').modal('show');
});


$(document).on('click', '#verify_admin_password', function() {
            $("#error-list").html('').removeClass('text-danger text-success');
            $.ajax({
                url: "{{ route('sr-verify-admin') }}",
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
                            url: "{{ route('sr-remove') }}",
                            data: {
                                sr_id: remId,
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
               window.location.href="{{  route('subscription-list') }}";
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



</script>

<script>
    $(document).ready(function () {
        $('#searchInput').on('keyup', function (e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
    });

    function performSearch() {
        var searchValue = $('#searchInput').val();
        var url = "{{ route('subscription-request-list') }}";

        if (searchValue) {
            // If search value is not empty, add it to the URL as a query parameter
            url += '?search=' + encodeURIComponent(searchValue);
        }

        window.location.href = url;
    }
</script>
@endsection
