@extends('layout.main')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Payments</h4>
<!--                 <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">    
                            </li>
                        </ol>
                    </nav>
                </div> -->
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
                        <h5 class="card-title mb-0">School Payments</h5>
                        <!-- <div class="card-actions float-end">
                        </div> -->
                        <div class="card-actions float-end">
                            <div class="dropdown show">
                                    <a href="{{ route('export-all-school-payment') }}"
                                        id="export-link"
                                        onclick="Exportgetschoolid()"
                                        class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Export Data</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="yajra-table" class="table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>School Name</th>
                                        <!-- <th>Amount</th>
                                        <th>Payment Link</th> -->
                                        <th>Payment's Info</th>
                                        <th>Payment Failed Info</th>
                                        <th>Payment status</th>
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
                        <input type="hidden" id="remUser" value="0" />
                        <button class="btn btn-primary" type="submit" id="remove_user">Submit</button>
                    </div>

                    <div class="mb-3 text-center">
                        <p class="text-center" id="error-list"></p>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

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

    <div class="modal fade" id="bs-school-modal" tabindex="-1" role="dialog" aria-labelledby="modal-label"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-payment modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header py-3">
                    <h4 class="modal-title" id="modal-label-school">
                    School Payment Detail </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <div id="viewSchoolPayment">
                    

                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

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
                    <h4 class="modal-title" id="modal-label-pass">
                        Description </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <p id="payment_description_id"></p>
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

$(document).ready(function() {
    /* Deactivate Confirm Msg */
    $(document).on('click', '.deactivate_link', function() {
        var payment_link_id = $(this).attr('payment-link-id');
        var payment_id = $(this).attr('payment-id');
        $('#payments_id').val(payment_id);
    $('#deactivate-link-model').modal('show');
        // alert("Password : " + viewPass);
    });
});

/* Description show  */
$(document).on('click', '.description_popup', function() {
    var payment_description = $(this).attr('data-description');
   $('#payment_description_id').text(payment_description);
  $('#description-popup-model').modal('show');
});



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
             window.location.href = "{{ route('payment-list') }}";
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


 function SingleStudent(event, studentId) {
        event.preventDefault();
        $.ajax({
            url: "{{ route('forgot-single-student') }}", // Replace with the actual API endpoint
            type: 'get', // or 'POST' depending on your API
            data: {
                forgot_student_id: studentId,
            },
            dataType: 'json',
            success: function (response) {
                 if(response['status']== true){
                    var student_popup = response['single_student'];
                    var gradedata = response['single_student']['studentgrade'];
                   /// console.log(gradedata['class_name'],'gradedata');
                    var successMessage = `
                    <div class="card-body p-0">
                        <table class="table my-2">
                            <tbody>
                                <tr>
                                    <th>Name</th>
                                    <td class="text-fade">${student_popup['name']}</td>
                                </tr>
                                <tr>
                                    <th>Last Name</th>
                                    <td class="text-fade">${student_popup['last_name']}</td>
                                </tr>
                                <tr>
                                    <th>Teacher Name</th>
                                    <td class="text-fade">${student_popup['teacher_name']}</td>
                                </tr>
                                <tr>
                                    <th>School Name</th>
                                    <td class="text-fade">${student_popup['school_name']}</td>
                                </tr>
                                <tr>
                                    <th>Grade</th>
                                    <td class="text-fade">${gradedata['class_name']}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td class="text-fade">${student_popup['email']}</td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td class="text-fade">${student_popup['phone_number']}</td>
                                </tr>
                            </tbody>
                        </table>
                 </div>`;
                $('#viewSchool').html(successMessage);
                 }
            },
            error: function(jqXHR, exception){
         console.log("something went wrong");
      }
        });
    }

        function deleteProduct(id){
		var url = '{{ route("student.remove","ID")}}';
		var NewUrl = url.replace("ID", id);
		//alert(NewUrl)
		if(confirm("Are you sure went to delete")){
			$.ajax({
			url: NewUrl,
			type:'delete',
			data: {},
			dataType: 'json',
			headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
			success: function(response){
				if(response['status']){
				}
			}
	     });
		}
     }
</script>

     /* Password */
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
                        url: "{{ route('student.password') }}",
                        type: "POST",
                        data: {
                            studentid: $(this).attr("data-userid"),
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

            $(document).on('click', '.remove_user_data', function() {
                var userId = $(this).attr("data-userid");
                $('#remUser').val(userId);
                $("#error-list").html('');
            });

            $(document).on('click', '#remove_user', function() {
                $("#error-list").html('').removeClass('text-danger text-success');
                $.ajax({
                    url: "{{ route('student.remove') }}",
                    type: "POST",
                    data: {
                        studentid: $("#remUser").val(),
                        studentpass: $("#userpass").val(),
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
            });

            $(document).on('click', '.change_status', function() {
                var id = $(this).attr('data-id');
                var status = $(this).attr('data-status');
                $.ajax({
                    url: "{{ route('teacher.status') }}",
                    type: "POST",
                    data: {
                        userid: id,
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


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
//document.getElementById("setPasswordLink").disabled = true;
//document.getElementById("setPasswordLinkPending").disabled = true;
//document.getElementById("setPasswordLinkDisapprove").disabled = true;

        $('#class-filter').on('input', function () {
            var keyword = $(this).val();
            $.ajax({
                url: "{{ route('student-filter-grade') }}",
                type: 'GET',
                data: { grade: keyword },
                success: function (data) {
                    $('#class-filter').empty(); // Clear previous options
                    $.each(data, function (key, value) {
                        $('#class-filter').append($('<option>').text(value).attr('value', key));
                    });
                }
            });
        });
    });
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

<!-- Your DataTables initialization script -->
<script type="text/javascript">
       jQuery(function($) {
        var table = $('#yajra-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 50,
                order: [],
                ajax: "{{ route('payment-list') }}",
                columns: [
                    {
                        data: 'index',
                        name: 'index'
                    },
                    {
                        data: 'school_name_text',
                        name: 'school_name_text'
                    },
                    {
                        data: 'payment_url',
                        name: 'payment_url'
                    },
                    {
                        data: 'payment_failed_info',
                        name: 'payment_failed_info'
                    },
                    {
                        data: 'payment_status',
                        name: 'payment_status'
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

@endsection