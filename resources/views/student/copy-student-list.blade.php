@extends('layout.main')
@section('content')
    <!-- Content Header (Page header) -->
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

    </div>
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Students</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page">Manage School - 
                                <a href="{{ route('school.list') }}">
                                    @if(!empty($schooldata->school_name))
                                        {{ $schooldata->school_name }}
                                    @endif    
                                </a>
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
                    
                    <div class="card-header" id="error_meassage">
                    <h5 class="card-title mb-0">Manage Student </h5>
                    
                    <!-- <form action="{{ route('student.list') }}" method="GET"> -->
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select name="grade" id="grade-filter"  class="form-control filter-button">
                                        <option value="">All Class</option>
                                        @if($class_list->isNotEmpty())
                                            @foreach ($class_list as $cdata)
                                                <option value="{{ $cdata->id }}"  >{{ $cdata->class_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select name="student_status" id="student_status_filter" class="form-control filter-button">
                                        <option value=""> Student Status</option>
                                        <option value="demo">Demo</option>
                                        <option value="paid">Paid</option>
                                        <option value="pending">Pending</option>
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="school_id" id="school_id" value="{{$schoolid}}">
                            <input type="hidden" name="convert_select[]"   id="convert_select" >
                            <div class="card-actions  col-md-5">
                                <select name="student_update_status" id="student_update_status_filter" onchange="StatusUpdate(); "  class="form-control filter-button">
                                        <option value="">Convert Select to</option>
                                        <option value="demo">Demo</option>
                                        <option value="paid">Paid</option>
                                        <option value="pending">Pending</option>
                                    </select>
                            </div> 
                            @if(!empty($schooldata) && $schooldata->access_code == 'vz1234')
                            <input type="hidden" name="student_id_select[]"   id="student_id_select">
                            <div class="card-actions float-end col-md-3">
                                <select name="school_id_update" id="student_id_filter"  class="form-control filter-button">
                                        <option value="">School Change</option>
                                        @if($school_all_data->isNotEmpty())
                                            @foreach ($school_all_data as $sdata)
                                                <option value="{{ $sdata->id }}" >{{ $sdata->school_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                            </div> 
                            @endif
                        </div>  
                        <!-- @if(!empty($schooldata) && $schooldata->access_code == 'vz1234')
                        <div class="row">
                        <input type="hidden" name="student_id_select[]" onchange="this.form.submit()"  id="student_id_select">
                            <div class="card-actions float-end col-md-10 mt-4">
                                <select name="school_id_update" id="student_id_filter" onchange="SchoolChangeUpdate(); this.form.submit();"  class="form-control filter-button">
                                        <option value="">School Change</option>
                                        @if($school_all_data->isNotEmpty())
                                            @foreach ($school_all_data as $sdata)
                                                <option value="{{ $sdata->id }}" >{{ $sdata->school_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                            </div> 
                           
                        </div>
                        @endif -->
                        <!-- </form> -->
                        <div class="card-actions float-end">
                        <input type="hidden" id="school_id" value="{{ $schoolid }}" name="school_id">
                            <div class="dropdown show">
                                 <a href="{{ route('student.bulkUploadForm',['school_id' => $schoolid]) }}"
                                    class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Upload CSV</a>
                                <a href="{{ route('student.add',['school_id' => $schoolid]) }}"
                                    class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Add Student</a>
                                    <a href="{{ route('student.export', ['school_id' => $schoolid]) }}"
                                        id="export-link"
                                        onclick="Exportgetschoolid()"
                                        class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Export</a>
                            </div>
                        </div>
                   </div>
                   <p></p>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="yajra-table" class="table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Select <input class="custom-control-input"   type="checkbox" id="checkboxAllCheck" style="margin-top: 5px;"></th>
                                        <th>Student's Info</th>
                                         <th>Section</th>
                                        <th>Student Status</th>
                                        <th>Status</th>
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
<script type="text/javascript">

$(function() {
            var allchecked = "unchecked";  
            $("#checkboxAllCheck").change(function() {
                if ($(this).prop("checked")) {
                    allchecked = "checked";
                } else {
                    allchecked = "unchecked";
                }
            });
            var table = $('#yajra-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('student.list') }}",
                    data: function (d) {
                    d.school = $("#school_id").val();
                    d.checked_variable = allchecked;
                    d.grade = $("#grade-filter").val();
                    d.student_id_select = $("#student_id_select").val();
                    d.student_id_filter = $("#student_id_filter").val();
                    d.student_status = $("#student_status_filter").val();
                    d.student_update_status_filter = $("#student_update_status_filter").val();
                    d.convert_select = $("#convert_select").val();
                }
                },
                columns: [
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'section',
                        name: 'section'
                    },
                    {
                        data: 'student_status',
                        name: 'student_status'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
            $("#grade-filter").change(function() {
               table.ajax.reload(); 
            });
            $("#student_status_filter").change(function() {
               table.ajax.reload(); 
            });
            $("#student_update_status_filter").change(function() {
               table.ajax.reload(); 
            });
            $("#student_id_filter").change(function() {
               table.ajax.reload(); 
            });
        });
    </script>




<script>
$(document).on('click', '.section_popup', function() {
    var section_description = $(this).attr('data-description');
   $('#section_description_id').text(section_description);
  $('#section-popup-model').modal('show');
});


    function StatusUpdate(){
       // alert(32);
   //  var  checkboxdata = $('#checkboxCheck').val();
  //   const inputValue = document.getElementById("checkboxCheck").value;
   //  console.log(inputValue,"data");

   const selectedStatus = document.getElementById("student_update_status_filter").value;
    const selectedCheckboxes = document.querySelectorAll("#yajra-table tbody input[type='checkbox']:checked");
    const selectedPhoneNumbers = Array.from(selectedCheckboxes).map(checkbox => checkbox.value);
    console.log(selectedPhoneNumbers,"selectedPhoneNumbers");
    if (selectedPhoneNumbers.length === 0) {
        /* $('#error_meassage').siblings('p').addClass('text-danger')
        .html('Please select at least one checkbox before exporting.'); */
        alert('Please select at least one checkbox before exporting.');
        return false; 
    }
    
    $('#convert_select').val(selectedPhoneNumbers);
    }
    function SchoolChangeUpdate(){
   //  var  checkboxdata = $('#checkboxCheck').val();
  //   const inputValue = document.getElementById("checkboxCheck").value;
   //  console.log(inputValue,"data");

   const selectedStatus = document.getElementById("student_id_filter").value;
    const selectedCheckboxes = document.querySelectorAll("#yajra-table tbody input[type='checkbox']:checked");
    const selectedPhoneNumbers = Array.from(selectedCheckboxes).map(checkbox => checkbox.value);
    $('#student_id_select').val(selectedPhoneNumbers);
   // console.log(selectedPhoneNumbers,"data");
    

    }


    document.addEventListener("DOMContentLoaded", function () {
        const checkboxAllCheck = document.querySelector("#checkboxAllCheck");
        const checkboxes = document.querySelectorAll("#yajra-table tbody input[type='checkbox']");
           // console.log(checkboxes,"checkboxesgggg");
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

               // checkboxAllCheck.checked = allChecked;
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
            const selectedCheckboxes = document.querySelectorAll("#yajra-table tbody input[type='checkbox']:checked");
            const selectedPhoneNumbers = Array.from(selectedCheckboxes).map(checkbox => checkbox.value);
            /* console.log(selectedPhoneNumbers,"selectedPhoneNumbers");
            if (selectedPhoneNumbers.length === 0) {
               
                $('#error_meassage').siblings('p').addClass('text-danger')
                .html('Please select at least one checkbox before exporting.');
              return false; 
            } */
            if (selectedPhoneNumbers.length === 0) {
                alert('Please select at least one checkbox before exporting.');
                return false; 
            }
            const exportLink = document.getElementById('export-link');
            const currentUrl = exportLink.href;
            const updatedUrl = currentUrl + (currentUrl.includes('?') ? '&' : '?') + 'all_teacher_id=' + selectedPhoneNumbers.join(',');
            exportLink.href = updatedUrl;
    return true;
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


<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->

@endsection