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
                            <!-- <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li> -->
                            <!-- <li class="breadcrumb-item alignment-text-new" aria-current="page">
                                <a href="{{ route('school.list') }}">
                                    @if(!empty($schoodata->school_name))
                                        {{ $schoodata->school_name }}
                                    @endif    
                                </a>
                            </li> -->

                            <li class="breadcrumb-item active alignment-text-new" aria-current="page">
                                <a href="{{ route('school.list') }}"><i class="mdi mdi-home-outline"></i> - Manage School</a>
                            </li>
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page">School Payment</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
<!-- Add school payment list data -->

<section class="content">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">School Payments List</h5>
                        <!-- <div class="card-actions float-end">
                        </div> -->
                        <div class="card-actions float-end">
                            <div class="dropdown show">
                                 <a href="{{ route('student-payment-list', ['school_id' => $schoodata->id]) }}"
                                        id="export-link"
                                        class="btn btn-sm btn-outline mb-1">Student Payment List</a>
                                    <a href="{{ route('export-school-payment', ['school_id' => $schoodata->id]) }}"
                                        id="export-link"
                                        class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Export Data</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table  class="table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>SR</th>
                                        <th>Amount</th>
                                        <th>Description</th>
                                        <th>Payment Info</th>
                                        <th>Payment Failed Info</th>
                                        <!-- <th>Link_Create_at</th>
                                        <th>Link_Expiry_at</th>
                                        <th>Payment_Made_at</th>
                                        <th>Email_Sent_at</th> 
                                        <th>Payment Due</th>  -->
                                        <th>Payment Status</th> 
                                        <!-- <th>Sms_Sent_at</th>  -->
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-dark">
                                @if(count($paymentlists) > 0)
                                    @foreach ($paymentlists  as $key => $data)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $data->sr_number }}</td>
                                            <td>
                                                <a href="#" class="fw-bold preview_school_data"
                                                title="Payment Amount"
                                                >
                                                {{ $data->payment_amount }}
                                                </a>
                                            </td>
                                            <td>
                                                
                                                {{ substr($data->description, 0, 15) }}
                                                    @if (strlen($data->description) > 20)
                                                        <a href="javascript:void(0);" data-description="{{ $data->description }}" class="description_popup btn btn-sm btn-success">i</a>
                                                    @endif
                                            </td>
                                          
                                            <!-- <td >
                                              {{ substr($data->description, 0, 20) }}
                                                @if (strlen($data->description) > 20)
                                                    <a href="javascript:void(0);" data-description="{{ $data->description }}" class="description_popup btn btn-sm btn-success">i</a>
                                                @endif
                                            </td> -->
                                            
                                            <!-- <td>{{ $data->payment_url }}</td> -->
                                            <!-- <td>
                                                <a href="#" class="fw-bold preview_school_data"
                                                title="Payment url"
                                                >
                                                {{ $data->payment_url }}
                                                </a>
                                            </td> -->
                                           
                                            <!-- <td>{{ $data->link_created_at }}</td>
                                            <td>{{ $data->link_expiry_at }}</td>
                                            <td>{{ $data->payment_made_at }}</td>
                                            <td>{{ $data->email_sent_at }}</td>
                                            <td>
                                            @if($data->payment_due>0)
                                                <button type="button" class="waves-effect waves-circle btn btn-circle btn-primary-light">
                                                <i class="mdi mdi-arrow-top-right">{{ $data->payment_due }}D</i>
                                                </button>
                                                @endif  
                                            </td> -->
                                            <!-- <td class="simple-table-td" style="max-width: 300px;">
                                            <div class="engineer-listing">
                                                <p class="m-0 custom-nowrap-ellipsis"><b>Link Created: </b> {{ $data->link_created_at }} </p>
                                                <p class="m-0 custom-nowrap-ellipsis"><b>Link Expiry: </b> {{ $data->link_expiry_at }} </p>
                                                <p class="m-0 custom-nowrap-ellipsis"><b>Email Sent: </b> {{ $data->email_sent_at }} </p>
                                                <p class="m-0 custom-nowrap-ellipsis"><b>Payment Due: </b>
                                                @if($data->payment_due>0)
                                                <button type="button" class="waves-effect waves-circle btn btn-circle btn-primary-light">
                                                <i class="mdi mdi-arrow-top-right">{{ $data->payment_due }}D</i>
                                                </button>
                                                @endif  
                                                </p>
                                                <p class="m-0 custom-nowrap-ellipsis"><b>Payment Done: </b> {{ $data->payment_made_at }} </p>
                                                <p class="m-0 custom-nowrap-ellipsis"><b>Description: </b> 
                                                    {{ substr($data->description, 0, 15) }}
                                                    @if (strlen($data->description) > 20)
                                                        <a href="javascript:void(0);" data-description="{{ $data->description }}" class="description_popup btn btn-sm btn-success">i</a>
                                                    @endif
                                               </p>
                                            </div>
                                        </td> -->

                                        <td> <a href="javascript:void(0)" data-bs-toggle="modal"
                                                    data-bs-target="#bs-school-modal" class="fw-bold preview_school_data "
                                                    data-school="{{ $data->id }}"
                                                    title="Payment Info"><i class="fas fa-info-circle info-icon-payment"></i></a></td>
                                        
                                        
                                      <td> 
                                      @if($data->payment_failed_info)
                                      <a href="javascript:void(0)" data-bs-toggle="modal"
                                                    data-bs-target="#bs-sr-modal" class="fw-bold sr_payment_data"
                                                    data-payment-sr="{{ $data->id }}"
                                                    title="Payment Info"><i class="fas fa-info-circle info-icon-payment"></i></a></td>
                                        @endif
                                           
                                        <!-- <td>
                                            <a href="#"
                                                    class="change_school_demo_status text-white badge bg-{{ $data->payment_status == 1 ? 'success' : 'danger' }}"
                                                    >{{ $data->payment_status == 1 ? 'Activated' : 'Deactivate' }}</a>
                                            </td> -->

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


                                            <!-- <td>{{ $data->sms_sent_at }}</td> -->
                                            <td> 
                                            
                                            <!-- <a href="{{ route('payment-removie', ['payment_id' => $data->id, 'school_id' => $data->school_id ]) }}"
                                                    title="Delete Payment"
                                                    class=" waves-effect waves-light btn btn-sm btn-outline btn-danger mb-5"
                                                    ><i class="fa fa-trash-o"></i></a> -->
                                           <!--  <a href="javascript:void(0);" 
                                                 payment-link-id="{{$data->payment_link_id}}"
                                                 payment-id="{{$data->id}}"
                                                class="deactivate_link waves-effect waves-light btn btn-sm btn-outline btn-info mb-5" id="DeactivateLink">Deactivate</a>
                                                 -->
                                            @if($data->payment_status != 1)
                                            <a href="javascript:void(0);" 
                                                 payment-id="{{$data->id}}"
                                                class="sent_email_sms waves-effect waves-light btn btn-sm btn-outline btn-info mb-5" id="uploadButton">Send Email</a>
                                            @endif
                                            <a href="#" class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5" onclick="SchoolPayment(event, {{ $data->id }})" id="uploadButton">Upload Invoice</a>
                                                <input type="file" id="fileInput" style="display: none;">
                                                <!-- <a href="http://localhost/valuez-hut-staging/uploads/upload_invoice/upload_invoice_20231020_Xb1vdQKImmW3PftiMZhtvDtw0j28Gdbh8muPrK39.pdf" download>
                                                    Download PDF</a> -->
                                               
                                            @if($data->upload_invoice)        
                                            <a href="{{$data->upload_invoice}}" 
                                                class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5" download>Download PDF</a>
                                            @endif    

                                           <!--  <a href="{{ route('payment-removie', ['payment_id' => $data->id, 'school_id' => $data->school_id]) }}" 
                                                    class="remove_user_data waves-effect waves-light btn btn-sm btn-outline btn-danger mb-5"
                                                    >Delete</a> -->
                                                    <a href="javascript:void(0)" data-bs-toggle="modal"
                                                    data-bs-target="#bs-password-modal"
                                                    class="remove_user_data waves-effect  waves-light btn btn-sm btn-outline btn-danger mb-5"
                                                    data-paymentid="{{ $data->id }}" data-schoolid="{{ $data->school_id }}">Delete</a>

                                            </td>
                                           
                                            <!-- <td>
                                            <a href="#" class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5" onclick="SchoolPayment(event, {{ $data->id }})" id="uploadButton">Upload Invoice</a>
                                                <input type="file" id="fileInput" style="display: none;">
                                                <a href="{{ route('download-invoice', ['payment_id' => $data->id]) }}" 
                                                    class="remove_user_data waves-effect waves-light btn btn-sm btn-outline btn-success mb-5"
                                                    >View Invoice</a>
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
<!-- End school Payment link -->

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-lg-8 col-12">
                <!-- Basic Forms -->
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">School Payment</h4>
                    </div>
                    <!-- /.box-header -->
<!-- <form action="{{ route('school-payment.store') }}" method="POST"> -->
    <form name="studentcreate" id="studentcreate" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="box-body" >
        <div class="form-group">
            <label class="form-label">School Name <span class="text-danger">*</span></label>
            <input type="text" name="school_name" id="school_name" readonly  value="{{$schoodata->school_name}}" class="form-control" placeholder="School Name">
            <p></p>
            @error('school_name')
                <span class="text-danger">{{ $school_name }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label class="form-label">School Name Billing<span class="text-danger">*</span></label>
            <input type="text" name="school_name_billing" id="school_name_billing"  class="form-control" placeholder="School Name Billing">
            <p></p>
            @error('school_name_billing')
                <span class="text-danger">{{ $school_name_billing }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label class="form-label">Number of Subscription<span class="text-danger">*</span></label>
            <input type="number" name="number_of_subscription" id="number_of_subscription" class="form-control" placeholder="Number of Subscription">
            <p></p>
            @error('price')
                <span class="text-danger">{{ $price }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label class="form-label">Payment Amount <span class="text-danger">*</span></label>
            <input type="number" name="payment_amount" id="payment_amount" class="form-control" placeholder="Payment Amount">
            <p></p>
            @error('price')
                <span class="text-danger">{{ $price }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label class="form-label">Description <span class="text-danger">*</span></label>
            <textarea name="description" id="description" class="form-control" placeholder="Enter Description"></textarea>
            <p></p>
            @error('description')
                <span class="text-danger">{{ $description }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
            <input type="text" name="phone_numbers" id="phone_numbers" readonly value="9045269853" class="form-control" placeholder="Phone Number">
            <p></p>
            @error('phone_numbers')
                <span class="text-danger">{{ $phone_numbers }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label class="form-label">Email<span class="text-danger">*</span></label>
            <input type="text" name="emails" id="emails" readonly value="mr.sakib011@gmail.com" class="form-control" placeholder="Email">
            <p></p>
            @error('email')
                <span class="text-danger">{{ $email }}</span>
            @enderror
        </div>
    </div>
    <input type="hidden" name="school_id" id="school_id" value="{{ $school_id }}">
    <button type="submit" class="btn btn-success">Submit For Razorpay Link</button>
</form>

                </div>
                
            </div>

        </div>
    </section>
    <!-- /.content -->

<!-- Popup -->

<div class="modal fixed-right" id="bs-sent-email-sms-modal" tabindex="-1" role="dialog" aria-labelledby="modal-label" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 600px;">
        <div class="modal-content">
            <form name="sent_email_payment_link" id="sent_email_payment_link" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <!-- <h4 class="modal-title" id="modal-label-pass">
                    Email </h4> -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="box-body">
                     <label class="form-label">Email <span class="text-danger"></span></label>
                    <div class="row" id="additional-fields-container">
                        
                        <div class="form-group col-md-8 email-field">
                            <input type="text" name="email" id="email" class="form-control" placeholder="Email">
                            <p></p>
                        </div>
                        <div class="form-group col-md-4 text-center">
                            <button type="button" class="btn " style="background-color: #00205c; color: #fff;" id="add-fields">Add Email</button>
                        </div>
                    </div>
                    <div class="form-group">
                                <label class="form-label">Email Template</label>
                                <textarea id="lesson_inst"  rows="3" name="lesson_desc" class="form-control"
                                    placeholder="Email Template">
                                    {{$school_email_template->school_body}}
                                   
                                </textarea>

                    </div>
                <input type="hidden" name="email_template_id" value="{{$school_email_template->id}}" id="email_template_id" class="form-control">
                <input type="hidden" name="payments_id" id="payments_id" class="form-control">
                <div class="col-md-4  m-10">
                    <input type="button" onclick="SentEmail()" id="SentEmailId" value="Sent Email" style="background-color: #00205c; color: #fff;" class="btn">
                </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- popup deactivate -->
<div class="modal fade" id="deactivate-link-model" tabindex="-1" role="dialog" aria-labelledby="modal-label"
        aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-label-pass">
                        Aru you this payment link deactivate </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <!-- <div class="mb-3 text-center">
                        <input type="hidden" id="remSchool" value="0" />
                        <button class="btn btn-primary" type="submit" id="deactivate_payment_link">Submit</button>
                    </div> -->
                    <input type="hidden" name="payments_id" id="payments_id" class="form-control">
                <div class="mb-3 text-center">
                    <input type="button" onclick="DeactivatePaymentLink()" id="SentEmailId" value="Submit" class="btn btn-primary">
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
                        Description </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <p id="payment_description_id"></p>
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

<!-- Info modal -->
<div class="modal fade" id="bs-school-modal" tabindex="-1" role="dialog"  aria-labelledby="modal-label"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-payment modal-dialog-centered ">
            <div class="modal-content">
                <div class="modal-header py-3">
                    <h4 class="modal-title" id="modal-label-school">
                        School Payment Detail </h4>
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

@endsection
@section('script-section')

<script>
$(document).ready(function() {

$(document).on('click', '.sent_email_sms', function() {
    var payments_id = $(this).attr('payment-id');
    $('#payments_id').val(payments_id);
   $('#bs-sent-email-sms-modal').modal('show');
});

/* Deactivate Confirm Msg */
$(document).on('click', '.deactivate_link', function() {
    var payment_link_id = $(this).attr('payment-link-id');
    var payment_id = $(this).attr('payment-id');
    $('#payments_id').val(payment_id);
  $('#deactivate-link-model').modal('show');
});

/* Description show  */
$(document).on('click', '.description_popup', function() {
    var payment_description = $(this).attr('data-description');
   $('#payment_description_id').text(payment_description);
  $('#description-popup-model').modal('show');
});


$(document).on('click', '.preview_school_data', function() {
            var schoolPaymentId = $(this).attr("data-school");
            $.ajax({
                url: "{{ route('single-school-payment-list') }}",
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
                url: "{{ route('sr-payment-list') }}",
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
                url: "{{ route('payment.verify-admin') }}",
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
                            url: "{{ route('payment-removie') }}",
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

    document.addEventListener('DOMContentLoaded', function () {
        function addFieldSet() {
            const container = document.getElementById('additional-fields-container');
            const emailFieldSet = container.querySelector('.form-group.col-md-8'); // Select the email input field
            const clonedEmailFieldSet = emailFieldSet.cloneNode(true); // Clone the email field

            // Clear the value of the cloned email field
            clonedEmailFieldSet.querySelector('input').value = '';

            // Create a remove button within a <div> element
            const removeDiv = document.createElement('div');
            removeDiv.className = 'form-group col-md-4 text-center';
            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'btn btn-danger';
            removeButton.textContent = 'Remove';

            // Add an event listener to remove the field set when the remove button is clicked
            removeButton.addEventListener('click', function () {
                container.removeChild(clonedEmailFieldSet);
                container.removeChild(removeDiv);
            });

            // Append the cloned email field and remove button to the container
            container.appendChild(clonedEmailFieldSet);
            removeDiv.appendChild(removeButton);
            container.appendChild(removeDiv);
        }

        document.getElementById('add-fields').addEventListener('click', addFieldSet);
    });

/* deactivate payment link api */
function DeactivatePaymentLink() {
    var csrfToken = $('meta[name="_token"]').attr('content');
    $.ajax({
      url: '{{ route("deactivate-payment-link") }}',
      type: 'post',
      data: {
        _token: csrfToken,
        payment_id: $('#payments_id').val(),
      },
      dataType: 'json',
      success: function(response){
        if (response['status'] == true) {
            console.log(response, "response");
             window.location.href = "{{ route('school-payment',['school_id' => $school_id]) }}";
            // Redirect to another page or perform further actions
        } else {
            var errors = response['errors'];
            console.log(errors, "errors");
            $.each(errors, function (key, value) {
                $(`#${key}`).siblings('p').addClass('text-danger').html(value);
            });
        }
         
      }, error: function(jqXHR, exception){
         console.log("something went wrong");
      }
    });
};





   /*  $('#sent_email_payment_link').submit(function (event) { */
    function SentEmail() {
    var csrfToken = $('meta[name="_token"]').attr('content');
    var emailFields = [];
    $('.email-field input[name="email"]').each(function () {
        emailFields.push($(this).val());
    });
    var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    var isValid = true; // Flag to track email validation
    
    emailFields.forEach(function (email) {
        if (!emailRegex.test(email) && email) {
            $('#email').siblings('p').addClass('text-danger').html("Please correct the invalid email addresses.");
            isValid = false; // Set the flag to false
        } else if (!email) {
            $('#email').siblings('p').addClass('text-danger').html("The email field is required.");
            isValid = false; // Set the flag to false
        }
    });
    
    if (isValid) {
        // If all emails are valid, proceed with the API call
        $('#email').siblings('p').removeClass('text-danger').html('');
        var data = {
            _token: csrfToken,
            payments_id: $('#payments_id').val(),
            email_template_id: $('#email_template_id').val(),
            email_template: $('#lesson_inst').val(),
            email: JSON.stringify(emailFields),
        };
        $.ajax({
            url: '{{ route("sent-email-payment-link") }}',
            type: 'post',
            data: data,
            dataType: 'json',
            success: function (response) {
                if (response['status'] == true) {
                    console.log(response, "response");
                    window.location.href = "{{ route('school-payment',['school_id' => $school_id]) }}";
                    // Redirect to another page or perform further actions
                } else {
                    var errors = response['errors'];
                    console.log(errors, "errors");
                    $.each(errors, function (key, value) {
                        $(`#${key}`).siblings('p').addClass('text-danger').html(value);
                    });
                }
            },
            error: function () {
                console.log("Something went wrong");
            }
        });
    }
};




   $('#studentcreate').submit(function (event) {
    event.preventDefault();
    var csrfToken = $('meta[name="_token"]').attr('content');
    var emailFields = [];

    // Iterate through email input fields and collect their values
    $('.email-field input[name="email"]').each(function () {
        emailFields.push($(this).val());
    });

    // Create a data object with 'milestone' as a JSON string
    var data = {
        _token: csrfToken,
        payment_amount: $('#payment_amount').val(),
        number_of_subscription: $('#number_of_subscription').val(),
        school_id: $('#school_id').val(),
        description: $('#description').val(),
        phone_numbers: $('#phone_numbers').val(),
        school_name_billing: $('#school_name_billing').val(),
        emails: $('#emails').val(),
     //   email: emailFields,
      //  milestone: JSON.stringify(emailFields), 
    };

    $.ajax({
        url: '{{ route("school-payment.store") }}',
        type: 'post',
        data: data,
        dataType: 'json',
        success: function (response) {
            if (response['status'] == true) {
                console.log(response, "response");
                window.location.href="{{  route('school-payment',['school_id' => $school_id]) }}";
                // Redirect to another page or perform further actions
            } else {
                var errors = response['errors'];
                console.log(errors, "errors");
                $.each(errors, function (key, value) {
                    $(`#${key}`).siblings('p').addClass('text-danger').html(value);
                });
            }
        },
        error: function () {
            console.log("Some things went wrong");
        }
    });
});

</script>
<script>
    function SchoolPayment(event, payment_id) {
        event.preventDefault();
        $('#fileInput').click();
        $('#fileInput').change(function () {
            var fileInput = document.getElementById('fileInput');
            var file = fileInput.files[0];
            var formData = new FormData();
            formData.append('invoice', file);
            formData.append('payment_id', payment_id);
//console.log(formData,"formData");
            $.ajax({
                url: "{{ route('upload-invoice') }}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    //alert(response.message); // Handle the success response
                },
                error: function (xhr, status, error) {
                    alert('File upload failed: ' + error); // Handle the error
                }
            });
        });
    }

        
    
</script>
<script src="{{ asset('assets/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
$('#lesson_inst').wysihtml5();
});
</script>










@endsection