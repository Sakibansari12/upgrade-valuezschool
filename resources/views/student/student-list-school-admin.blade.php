@extends('layout.main')
@section('content')
    <!-- Content Header (Page header) -->
     

    <!-- </div> -->
    <div class="content-header">

    <div class="col-xl-12">
        <div class="row">
            <div class="card-custum-student" style="background-color: #00205c; color: #fff;">
                <div class="card-body">
                   <h6 >All Student &nbsp;&nbsp;{{ isset($total_student) ? $total_student : '' }}</h6>
                </div>
            </div>
            <div class="card-custum-student" style="background-color: #00205c; color: #fff;">
                <div class="card-body">
                   <h6 >Demo Student &nbsp;&nbsp;{{ isset($demoCount) ? $demoCount : '' }}</h6>
                </div>
            </div>
            <div class="card-custum-student" style="background-color: #00205c; color: #fff;">
                <div class="card-body">
                   <h6 >Paid Student &nbsp;&nbsp;{{ isset($paidCount) ? $paidCount : '' }}</h6>
                </div>
            </div>
            <div class="card-custum-student" style="background-color: #00205c; color: #fff;">
                <div class="card-body">
                   <h6 >Pending Student &nbsp;&nbsp;{{ isset($pendingCount) ? $pendingCount : '' }}</h6>
                </div>
            </div>
        </div>
    </div>
        <div class="d-flex align-items-center mt-3">
            <div class="me-auto">
              
                <h4 class="page-title"><!-- Students --></h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <!-- @if($user->usertype == 'superadmin')
                             <li class="breadcrumb-item active alignment-text-new" aria-current="page"><a href="{{ route('school.list') }}"><i class="mdi mdi-home-outline"></i> - Manage School</a></li>
                            @endif  -->
                            
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page">Manage Student</li>
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
                    <h5 class="card-title mb-0">Manage Student </h5>
                    


                    @if(($user->usertype != 'superadmin'))
                    <form action="{{ route('student-list') }}" method="GET">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <select name="grade" id="grade-filter" onchange="this.form.submit()" class="form-control wide form-control-sm filter-button">
                                        <option value="">All Class</option>
                                        @if($class_list->isNotEmpty())
                                            @foreach ($class_list as $cdata)
                                                <option value="{{ $cdata->id }}" @if($grade == $cdata->id) selected @endif >{{ $cdata->class_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="school_id" onchange="this.form.submit()" id="school_id" value="{{$schoolid}}">
                        </div>  
                    </form>
                @endif




                        <div class="card-actions float-end">
                            <div class="dropdown show">
                                 <!-- <a href="{{ route('student.bulkUploadForm',['school_id' => $schoolid]) }}"
                                    class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Upload CSV</a>
                                <a href="{{ route('student.add',['school_id' => $schoolid]) }}"
                                    class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Add Student</a> -->
                                
                                    <a href="{{ route('student.export', ['school_id' => $schoolid]) }}"
                                        id="export-link"
                                        onclick="Exportgetschoolid()"
                                        class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Export</a>
                            </div>
                        </div>
                   </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example2" class="table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Select <input class="custom-control-input"   type="checkbox" id="checkboxAllCheck" style="margin-top: 5px;"></th>
                                        <th>Student's Info</th>
                                        <th>Subscription Status</th>
                                        <th>Payments</th>
                                    </tr>
                                </thead>
                                <tbody class="text-dark">
                                    @if($student->isNotEmpty())
                                    @foreach ($student as $data)
                                        <tr>
                                        <td>
                                            <input class="custom-control-input " type="checkbox" value="{{ $data->id }}" id="checkboxCheck">
                                        </td>
                                        <td class="simple-table-td" style="max-width: 300px;">
                                            <div class="engineer-listing">
                                                <p class="m-0 custom-nowrap-ellipsis"><b>Name: </b> {{ $data->name }} </p>
                                                <p class="m-0 custom-nowrap-ellipsis"><b>Last Name: </b> {{ $data->name }} </p>
                                                <p class="m-0 custom-nowrap-ellipsis" id="copyContainer_{{$data->id}}">
                                                    <b>Username: </b>
                                                    <span id="usernameParagraph_{{$data->id}}">{{ $data->username }}</span><br>
                                                   <!--  <b>Password: </b>
                                                    <span id="passwordParagraph_{{$data->id}}" data-password="{{ $data->view_password }}">xxxxx</span> -->
                                                </p>
                                                <p class="m-0 custom-nowrap-ellipsis"><b>Grade: </b> {{ $data->grade_class_name }} </p>
                                                <p class="m-0 custom-nowrap-ellipsis"><b>Section: </b> {{ $data->section }} </p>
                                                <p class="m-0 custom-nowrap-ellipsis"><b>Phone: </b>{{ $data->phone_number }} 
                                                @if($data->otp_verified_at && $data->school_id>0)
                                                <i class="fas fa-check verified-icon"></i>
                                                @endif
                                            </p>
                                                <p class="m-0 custom-nowrap-ellipsis"><b>Email: </b>{{ $data->email }}</p>
                                            </div>
                                           </td>
                                            <td>
                                                @if($data->student_status == 'Demo')
                                                <a href="#"
                                                    class=" text-white badge bg-success"
                                                    data-status="{{ $data->status }}">{{ $data->student_status }}
                                                </a>
                                                @endif
                                                @if($data->student_status == 'Paid')
                                                <a href="#"
                                                    class=" text-white badge bg-danger"
                                                    data-status="{{ $data->status }}">{{ $data->student_status }}
                                                </a>
                                                @endif
                                                @if($data->student_status == 'Pending')
                                                <a href="#"
                                                    class=" text-white badge bg-warning"
                                                    data-status="{{ $data->status }}">{{ $data->student_status }}
                                                </a>
                                                @endif
                                            </td>
                                           
                                            <td>
                                            <!-- @if(($user->usertype == 'superadmin'))
                                                <a href="{{ route('student.edit', ['studentid' => $data->id]) }}"
                                                    class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Edit</a>

                                                    <a href="javascript:void(0)" data-bs-toggle="modal"
                                                    data-bs-target="#bs-password-modal"
                                                    class="remove_user_data waves-effect  waves-light btn btn-sm btn-outline btn-danger mb-5"
                                                    data-userid="{{ $data->id }}">Delete</a>
                                                <a href="{{ route('student.logs.list', ['userid' => $data->id]) }}"
                                                    class="waves-effect waves-light btn btn-sm btn-outline btn-danger mb-5">Student Logs</a>
                                            @endif -->
                                            <a href="{{ route('student-payment-history', ['student_id' => $data->id]) }}"
                                                    class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Payments</a>
                                               <!-- <a href="{{ route('student-logs-history', ['userid' => $data->id]) }}"
                                                    class="waves-effect waves-light btn btn-sm btn-outline btn-danger mb-5">Student Logs</a> -->
                                                
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
                        <button class="btn btn-primary" type="submit" id="verify_admin_password">Submit</button>
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

<!-- The description modal popup -->
<div class="modal fade" id="section-popup-model" tabindex="-1" role="dialog" aria-labelledby="modal-label"
        aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-label-pass" style="color: #00205c;">
                        Section </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <p id="section_description_id"></p>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection

@section('script-section')
<!-- <script>
    document.addEventListener("DOMContentLoaded", function () {
        const checkboxAllCheck = document.querySelector("#checkboxAllCheck");
        const checkboxes = document.querySelectorAll("#example2 tbody input[type='checkbox']");

        checkboxAllCheck.addEventListener("change", function () {
            checkboxes.forEach(function (checkbox) {
                console.log(checkbox,"checkbox");
                console.log(checkboxAllCheck,"checkboxAllCheck");
                if (checkbox !== checkboxAllCheck) {
                    checkbox.checked = checkboxAllCheck.checked;
                }
            });
        });
    });
</script> -->


<script>
$(document).on('click', '.section_popup', function() {
    var section_description = $(this).attr('data-description');
   $('#section_description_id').text(section_description);
  $('#section-popup-model').modal('show');
});


    function StatusUpdate(){
   //  var  checkboxdata = $('#checkboxCheck').val();
  //   const inputValue = document.getElementById("checkboxCheck").value;
   //  console.log(inputValue,"data");

   const selectedStatus = document.getElementById("student_update_status_filter").value;
    const selectedCheckboxes = document.querySelectorAll("#example2 tbody input[type='checkbox']:checked");
    const selectedPhoneNumbers = Array.from(selectedCheckboxes).map(checkbox => checkbox.value);
    $('#convert_select').val(selectedPhoneNumbers);
   // console.log(selectedPhoneNumbers,"data");
    

    }
    function SchoolChangeUpdate(){
   //  var  checkboxdata = $('#checkboxCheck').val();
  //   const inputValue = document.getElementById("checkboxCheck").value;
   //  console.log(inputValue,"data");

   const selectedStatus = document.getElementById("student_id_filter").value;
    const selectedCheckboxes = document.querySelectorAll("#example2 tbody input[type='checkbox']:checked");
    const selectedPhoneNumbers = Array.from(selectedCheckboxes).map(checkbox => checkbox.value);
    $('#student_id_select').val(selectedPhoneNumbers);
   // console.log(selectedPhoneNumbers,"data");
    

    }


    document.addEventListener("DOMContentLoaded", function () {
        const checkboxAllCheck = document.querySelector("#checkboxAllCheck");
        
        const checkboxes = document.querySelectorAll("#example2 tbody input[type='checkbox']");
           // console.log(checkboxAllCheck,"checkboxAllCheck");
          //  console.log(checkboxes,"checkboxes");
        // Initially, all checkboxes are checked
        checkboxes.forEach(function (checkbox) {
            checkbox.checked = false;
            checkboxAllCheck.checked = false;
        });

        checkboxAllCheck.addEventListener("change", function () {
            checkboxes.forEach(function (checkbox) {
                checkbox.checked = checkboxAllCheck.checked;
            });
        });
            function updateSelectAllCheckbox() {
                const allChecked = Array.from(checkboxes).every(function (checkbox) {
                    return checkbox.checked;
                });

                checkboxAllCheck.checked = allChecked;
            }

            checkboxes.forEach(function (checkbox) {
                checkbox.addEventListener("change", function () {
                    updateSelectAllCheckbox();
                });
            });
            updateSelectAllCheckbox();
        });


/* Export Code */        
function Exportgetschoolid() {
            const selectedCheckboxes = document.querySelectorAll("#example2 tbody input[type='checkbox']:checked");
            const selectedPhoneNumbers = Array.from(selectedCheckboxes).map(checkbox => checkbox.value);
            /* console.log(selectedPhoneNumbers,"selectedPhoneNumbers");
            if (selectedPhoneNumbers.length === 0) {
               
                $('#error_meassage').siblings('p').addClass('text-danger')
                .html('Please select at least one checkbox before exporting.');
              return false; 
            } */

            if (selectedCheckboxes.length === 0) {
                alert("Please check at least one checkbox before exporting.");
                return false;
            }else{
                const exportLink = document.getElementById('export-link');
                const currentUrl = exportLink.href;
                const updatedUrl = currentUrl + (currentUrl.includes('?') ? '&' : '?') + 'all_teacher_id=' + selectedPhoneNumbers.join(',');
                exportLink.href = updatedUrl;
                return true;
            }


            /* const exportLink = document.getElementById('export-link');
            const currentUrl = exportLink.href;
            const updatedUrl = currentUrl + (currentUrl.includes('?') ? '&' : '?') + 'all_teacher_id=' + selectedPhoneNumbers.join(',');
            exportLink.href = updatedUrl;
            return true; */
}
</script>









    <script>
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

            $(document).on('click', '#verify_admin_password', function() {
            $("#error-list").html('').removeClass('text-danger text-success');
            $.ajax({
                url: "{{ route('student.verify-admin') }}",
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
                            url: "{{ route('student.remove') }}",
                            data: {
                                studentid: $("#remUser").val(),
                                studentpass: $("#userpass").val(),
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

            /* $(document).on('click', '#remove_user', function() {
                $("#error-list").html('').removeClass('text-danger text-success');
              var confirmation = confirm('Are you sure you want to delete Student?');
              if (confirmation) {
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
            }
            });
 */
            $(document).on('click', '.change_status', function() {
    var id = $(this).attr('data-id');
    var status = $(this).attr('data-status');
    
    // Show the confirmation modal
    $('#confirmationModal').show();

    // Handle the confirm button click
    $('#confirmButton').on('click', function() {
        $.ajax({
            url: "{{ route('student-status') }}",
            type: "POST",
            data: {
                studentid: id,
                status: status
            },
            success: function(data) {
                // Handle success
                var csts = (status == 1) ? 0 : 1;
                        $('#status_' + id).text(data).attr('data-status', csts);
                        if (csts == 1) {
                            $('#status_' + id).addClass('bg-success').removeClass('bg-danger');
                        } else {
                            $('#status_' + id).addClass('bg-danger').removeClass('bg-success');
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



        });
    </script>

<script>
    function copyToClipboard(elementId) {
        var usernameSpan = document.getElementById('usernameParagraph_' + elementId);
        var passwordSpan = document.getElementById('passwordParagraph_' + elementId);
        var textarea = document.createElement("textarea");
        textarea.value = "Username: " + (usernameSpan.innerText || usernameSpan.textContent) + "\nPassword: " + passwordSpan.getAttribute('data-password');
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand("copy");
        document.body.removeChild(textarea);
    }
</script>
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->

@endsection