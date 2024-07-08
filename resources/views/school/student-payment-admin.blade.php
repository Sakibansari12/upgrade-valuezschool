@extends('layout.main')
@section('content')
<head>
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> --}}
</head>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title"><!-- School Name --></h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            
                            
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page">Students list</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
<section class="content">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Students list</h5>
                        <div class="card-actions float-end">
                            
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table  class="table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Student Name</th>
                                        <th>Grade/Section</th> 
                                        <th>amount</th>
                                        <th>Duration Package</th>
                                        <th>Start Date</th>
                                        <th>End Date</th> 
                                        <th>Payment Status</th> 
                                        <!-- <th>Action</th> -->
                                    </tr>
                                </thead>
                                <tbody class="text-dark">
                                @if(count($student_payment_data) > 0)
                                    @foreach ($student_payment_data  as $key => $data)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $data->name }}</td>
                                            <td>
                                                <div class="engineer-listing">
                                                        <p class="m-0 custom-nowrap-ellipsis"><b>Grade: </b> {{ isset($data->class_name) ? $data->class_name : '' }}</p>
                                                        <p class="m-0 custom-nowrap-ellipsis"><b>Section: </b> {{ isset($data->section) ? $data->section : '' }}</p>
                                                </div>
                                            </td>
                                            <td>{{ number_format($data->amount, 0, '', '') }}</td>
                                            <td>{{ $data->duration_package }}</td>
                                            <td>
                                              <a href="#" class="fw-bold">{{ isset($data->start_date_sub) ? \Carbon\Carbon::parse($data->start_date_sub)->format('d/m/Y') : '' }}</a>
                                            </td>
                                            <td>
                                              <a href="#" class="fw-bold">{{ isset($data->start_end_sub) ? \Carbon\Carbon::parse($data->start_end_sub)->format('d/m/Y') : '' }}</a>
                                            </td>
                                           

                                        <!-- <td> <a href="javascript:void(0)" data-bs-toggle="modal"
                                                    data-bs-target="#bs-school-modal" class="fw-bold preview_school_data "
                                                    data-school="{{ $data->id }}"
                                                    title="Payment Info"><i class="fas fa-info-circle info-icon-payment"></i></a></td>
                                        
                                         -->
                                         <!-- <td> 
                                              @if($data->payment_failed_info)
                                                <a href="javascript:void(0)" data-bs-toggle="modal"
                                                    data-bs-target="#bs-sr-modal" class="fw-bold sr_payment_data"
                                                    data-payment-sr="{{ $data->id }}"
                                                    title="Payment Info"><i class="fas fa-info-circle info-icon-payment"></i></a></td>
                                        
                                                    @endif
                                            -->
                                        
                                            <td>
                                                @if($data->payment_failed_info)
                                                <a href="#"
                                                        class="text-white badge bg-danger"
                                                        >Failed</a>
                                                @else
                                                <a href="#"
                                                        class="text-white badge bg-{{ $data->payment_status == 1 ? 'success' : 'danger' }}"
                                                        >{{ $data->payment_status == 1 ? 'Done' : 'Pending' }}</a>        
                                                @endif
                                            </td>
                                            <!-- <td>
                                            <a href="#" class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5" onclick="SchoolPayment(event, {{ $data->id }})" id="uploadButton">Upload Invoice</a>
                                                <input type="file" id="fileInput" style="display: none;">
                
                                                 <a href="javascript:void(0)" data-bs-toggle="modal"
                                                    data-bs-target="#bs-password-modal"
                                                    class="remove_user_data waves-effect  waves-light btn btn-sm btn-outline btn-danger mb-5"
                                                    data-paymentid="{{ $data->id }}" data-schoolid="{{ $data->school_id }}">Delete</a>

                                            </td> -->
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
    <div class="modal fade" id="bs-school-modal" tabindex="-1" role="dialog"  aria-labelledby="modal-label"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-payment modal-dialog-centered ">
            <div class="modal-content">
                <div class="modal-header py-3">
                    <h4 class="modal-title" id="modal-label-school">
                        Student Payment Detail </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <div id="viewSchoolPayment"></div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal fade" id="bs-sr-modal" tabindex="-1" role="dialog"  aria-labelledby="modal-label"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-payment modal-dialog-centered ">
            <div class="modal-content">
                <div class="modal-header py-3">
                    <h4 class="modal-title" id="modal-label-sr">
                        Subscription Request Payment Failed Detail </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <div id="viewSRPayment"></div>
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
                        <input type="hidden" id="schoolid" value="0" />
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
<script>
    $(document).ready(function() {
        $(document).on('click', '.preview_school_data', function() {
            var schoolPaymentId = $(this).attr("data-school");
            $.ajax({
                url: "{{ route('single-student-payment-list') }}",
                type: "POST",
                data: {
                    schoolPaymentId: schoolPaymentId,
                },
                success: function(res) {
                    console.log(res,"res");
                    $("#viewSchoolPayment").html(res);
                }
            });
        });
        $(document).on('click', '.sr_payment_data', function() {
            var schoolPaymentId = $(this).attr("data-payment-sr");
            $.ajax({
                url: "{{ route('student-payment-faild-list') }}",
                type: "POST",
                data: {
                    schoolPaymentId: schoolPaymentId,
                },
                success: function(res) {
                    console.log(res,"res");
                    $("#viewSRPayment").html(res);
                }
            });
        });
        $(document).on('click', '.remove_user_data', function() {
                var userId = $(this).attr("data-paymentid");
                var schoolId = $(this).attr("data-schoolid");
                $('#remUser').val(userId);
                $('#schoolid').val(schoolId);
                $("#error-list").html('');
            });
            $(document).on('click', '#verify_admin_password', function() {
            $("#error-list").html('').removeClass('text-danger text-success');
            $.ajax({
                url: "{{ route('student-payment-verify-admin') }}",
                type: "POST",
                data: {
                   // school: $("#remSchool").val(),
                    userpass: $("#userpass").val(),
                },
                success: function(res) {
                    if (res.success === true) {
                        console.log(res.msg,"res.msg");
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
                            url: "{{ route('student-payment-removie') }}",
                            data: {
                                payment_id: $("#remUser").val(),
                                school_id: $("#schoolid").val(),
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
    });
</script>
@endsection