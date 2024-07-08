@extends('layout.main')
@section('content')
<div class="content-header">
    <div class="row">
            <div class="card-custum" style="background-color: #00205c; color: #fff;">
                <div class="card-body">
                   <h6 >Total Classroom Subscriptions &nbsp;&nbsp;{{ isset($school_data->licence) ? $school_data->licence : '' }}</h6>
                </div>
            </div>
            <div class="card-custum" style="background-color: #00205c; color: #fff;">
                <div class="card-body">
                   <h6 >Active Classroom Subscriptions &nbsp;&nbsp;{{ isset($total_teacher) ? $total_teacher : '' }}</h6>
                </div>
            </div>
        </div>
    </div>
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                 
                <h4 class="page-title"></h4>
              
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            @if($user->usertype == 'superadmin')
                             <li class="breadcrumb-item active alignment-text-new" aria-current="page"><a href="{{ route('school.list') }}"><i class="mdi mdi-home-outline"></i> - Manage School</a></li>
                            @endif 
                            
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page">Manage Classroom</li>
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
                <div class="alert" id="license_status_error">
                       
                </div>
                <div class="card">
                <div class="card-header">
                @if($user->usertype != 'superadmin')
                    <h5 class="card-title mb-0">Share classroom credentials with teacher, Export list, View/Reset password, Assign teacher to classroom and view session details</h5>
                @endif
                </div>    
                    <div class="card-header" id="error_meassage">
                        @if($user->usertype == 'superadmin')
                        <h5 class="card-title mb-0">Manage Classroom</h5>
                        @endif
                        <h5 class="card-title mb-0"></h5>
                        <input type="hidden" name="user_type" id="user_type" value="{{ $user->usertype }}">
                        <input type="hidden" name="selectedSchoolId" id="selectedSchoolId" >
                        <p></p>

                        
                            <!-- <form action="{{ route('school.teacher.list') }}" method="GET"> -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                        <select name="grade" id="grade-filter" class="form-control wide form-control-sm filter-button">
                                        <option value="">All Class</option>
                                        @if($class_list->isNotEmpty())
                                            @foreach ($class_list as $cdata)
                                                <option value="{{ $cdata->id }}" @if($grade == $cdata->id) selected @endif >{{ $cdata->class_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                        </div>
                                    </div>
                                </div>  
                            <!-- </form> -->
                    
                        <div class="card-actions float-end">
                            <div class="dropdown show">
                                <input type="hidden" id="school_id" value="{{ $schoolid }}" name="school_id">
                                <a href="{{ route('teacher.add', ['school' => $schoolid]) }}"
                                title="Create User ID and Password for a teacher to access 21st Century LMS." 
                                    class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Add classroom</a>
                                <!-- <a href="{{ route('teacher.export', ['school' => $schoolid]) }}"
                                name="convert_select[]"
                                id="convert_select"
                                onclick="Exportgetschoolid()"
                                    class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Export</a> -->
                                    <!-- <a href="{{ route('teacher.export', ['school' => $schoolid]) }}"
                                    id="export-link"
                                    onclick="Exportgetschoolid();"
                                    class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Export</a> -->
                                <!-- <a href="{{ route('teacher.email-teacher', ['school' => $schoolid]) }}"
                                        id="email-sent-link"
                                        onclick="EmailsentallTeachers()"
                                        title="Share User ID and Password with selected teacher/teachers" 
                                        class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Sent Email to  classroom</a> -->
                                    <a href="{{ route('teacher.export', ['school' => $schoolid]) }}"
                                        id="export-link"
                                        onclick="Exportgetschoolid()"
                                        title="Use this button to export classroom information including… username and password in excel format. Proceed to share with teachers individually or collectively." 
                                        class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Export</a>

                                   <a href="#" id="teacherInfo"
                                   style="font-size: 15px;"
                                   title="Use this button to export classroom information including… username and password in excel format. Proceed to share with teachers individually or collectively." 
                                   class="waves-effect waves-light  btn-sm btn-outline btn-info mb-5"><i class="fas fa-info-circle"></i></a>
                                </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="yajra-table" class="table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Select <input class="custom-control-input" style="margin-top: 2px;" type="checkbox" id="checkboxAllCheck" ></th>
                                        <th>Classroom Login</th>
                                        <!-- <th></th> -->
                                        <th></th>
                                        
                                        <th>
                                          @if($user->usertype == 'superadmin')
                                            Status  
                                          @endif
                                        </th>
                                        <th>Grade/Section</th> 
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
                        <!-- <i class="fa fa-info-circle text-info fs-25"></i> -->Classroom login credentials
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>

                <div class="modal-body">
                    <div class="d-flex align-items-center mb-30">
                        <div class="me-15 h-40 w-40 rounded text-center">
                            <!-- <i class="fa fa-envelope-o text-primary fs-30"></i> -->
                            <i class="fas fa-user text-primary fs-30"></i>
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

<div class="modal-status" id="ipaddress_model" >
    <div class="modal-content-status" style="width: 100%;">
        <p style="font-size:17px;">Teacher Login History</p>
            <!-- <button class="confirm-button-status btn-primary"  id="confirmIpaddressButton">Confirm</button>
            <button class="cancel-button-status btn-primary" id="cancelIpadressButton">Cancel</button> -->
            <div class="mt-2" style="display: flex; align-items: center; justify-content: center;">
                <table class="table b-1" >
                    <thead style="background-color: #00205c; color: #fff;">
                        <tr>
                            <th>#</th>
                            <th>Teacher Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-dark" id="IP-address-all-data">
                            
                    </tbody>
                </table>
            </div>
    </div>
</div>


<div class="modal-status " id="teacherExportInfo">
    <div class="modal-content-status custom-rounded">
        <p style="font-size:19px; color: #00205c;">Use this button to export classroom information including username and password in excel format. Proceed to share with teachers individually or collectively.</p>
        <div class="button-container-status">
        <button class="btn" style="background-color: #00205c; color: #fff;" id="closePopup">Close</button>
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
var allchecked = "unchecked";  // Initialize with a default value

$("#checkboxAllCheck").change(function() {
    if ($(this).prop("checked")) {
        allchecked = "checked";
    } else {
        allchecked = "unchecked";
    }
    // console.log(allchecked, "hellosir");
});

 // console.log(allchecked, "hellosir");


            var table = $('#yajra-table').DataTable({
                
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('teacher.list') }}",
                    data: function (d) {
                    d.school = $("#school_id").val();
                    d.checked_variable = allchecked;
                    d.grade = $("#grade-filter").val();
                    d.user_type = $("#user_type").val();
                    d.query = $("#search-input-teacher").val();
                    //d.search_results_dashbord = $("#selectedSchoolId").val();
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
                        data: 'username',
                        name: 'username'
                    },
                    /* {
                        data: '',
                        name: ''
                    }, */
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'section',
                        name: 'section'
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
               table.ajax.reload(); // Reload the DataTable when the grade selection changes
            });
            function reloadDataTable() {
                table.ajax.reload();
            }
            $("#search-input-teacher").keypress(function(event) {
                if (event.which == 13) {
                    reloadDataTable();
                }
            });
            /* $("#search-input-teacher").change(function() {
               table.ajax.reload(); 
            }); */
        });
    </script>



    <script>
          var status_hide = $("#license_status_error");
         status_hide.toggle();
        $(document).on('click', '.section_popup', function() {
    var section_description = $(this).attr('data-description');
   $('#section_description_id').text(section_description);
  $('#section-popup-model').modal('show');
});


 $(document).ready(function() {
            // Show the popup when the link is clicked
            $('#teacherInfo').click(function() {
                $('#teacherExportInfo').show();
            });

            // Close the popup when the close button is clicked
            $('#closePopup').click(function() {
                $('#teacherExportInfo').hide();
            });
        });

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

            $(document).on('click', '.remove_user_data', function() {
                var userId = $(this).attr("data-userid");
                $('#remUser').val(userId);
                $("#error-list").html('');
            });
     $(document).on('click', '#verify_admin_password', function() {
            $("#error-list").html('').removeClass('text-danger text-success');
            $.ajax({
                url: "{{ route('teacher.verify-admin') }}",
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
                            url: "{{ route('teacher.remove') }}",
                            data: {
                                userid: $("#remUser").val(),
                                userpass: $("#userpass").val(),
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
                var confirmation = confirm('Are you sure you want to delete teacher?');
              if (confirmation) {
                $.ajax({
                    url: "{{ route('teacher.remove') }}",
                    type: "POST",
                    data: {
                        userid: $("#remUser").val(),
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
            }); */

           /*  $(document).on('click', '.change_status', function() {
                var id = $(this).attr('data-id');
                var status = $(this).attr('data-status');
                var confirmation = confirm('Are you sure you want to make this change?');
              if (confirmation) {
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
            }
            }); */
            $(document).on('click', '.ipaddress_show_data', function() {
                $('#ipaddress_model').show();
                $.ajax({
            url: "{{ route('ip-address-history') }}",
            type: "get",
            data: {
                user_id: $(this).attr('ipaddress_show_id'),
                //status: status
            },
            success: function(response) {
                // Handle success
                if(response['status'] == true){
                    var ipaddress_data = response['ipaddress_data'];
                    $('#IP-address-all-data').empty();
                        $.each(ipaddress_data, function (key, value) {
                            var row = $('<tr>');
                            row.append(
                                `<td>${key+1}</td>
                                <td>${value.name}</td>
                                <td>
                                <input type="button" style="background-color: #00205c; color: #fff;"
                                                         id="ipaddress_id" value="Logout" data-ipaddress-id="${value.ipaddresss_id}" class="btn w-p100 mt-10 button-small">
                                
                                
                           </td>`
                            );
                            $('#IP-address-all-data').append(row);
                        });
                    
                   }else{
                    var csts = (status == 1) ? 0 : 1;
                        
                }
                
            }
        });

            });


            $(document).on('click', '#ipaddress_id', function() {
           // $("#error-list").html('').removeClass('text-danger text-success');
           var csrfToken = $('meta[name="_token"]').attr('content');
           var id = $(this).attr('data-ipaddress-id');
            $.ajax({
                url: "{{ route('remove.ipaddress') }}",
                type: "POST",
                data: {
                   // school: $("#remSchool").val(),
                   _token: csrfToken,
                   ipaddess_id: id,
                },
                success: function(response) {
                    if (response == 'removed') {
                    setTimeout(() => {
                        location.reload();
                    }, 600);
                } else {
                    
                }
                }
            });
        });




            $(document).on('click', '.change_status', function() {
    var id = $(this).attr('data-id');
    var status = $(this).attr('data-status');
    
    // Show the confirmation modal
    $('#confirmationModal').show();
  
    // Handle the confirm button click
    $('#confirmButton').on('click', function() {
        $.ajax({
            url: "{{ route('teacher.status') }}",
            type: "POST",
            data: {
                userid: id,
                status: status
            },
            success: function(data) {
                // Handle success
                   console.log(data,"data");
                   if(data == 'error'){
                    
                    var errorDiv = document.getElementById('license_status_error');
                    errorDiv.innerHTML = 'Maximum licenses limit reached.';
                    var status_hide = $("#license_status_error");
                                       status_hide.show();
                    closeConfirmationModal();
                   }else{
                    var csts = (status == 1) ? 0 : 1;
                        $('#status_' + id).text(data).attr('data-status', csts);
                        if (csts == 1) {
                            $('#status_' + id).addClass('bg-success').removeClass('bg-danger');
                        } else {
                            $('#status_' + id).addClass('bg-danger').removeClass('bg-success');
                        }
                       closeConfirmationModal();
                }
                
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

    $('#cancelIpadressButton').on('click', function() {
        // Close the confirmation modal
        closeIpaddressModal();
    });

    function closeIpaddressModal() {
        // Hide the confirmation modal
        $('#ipaddress_model').hide();
    }
});

        });
    </script>
    <script>
    function Exportgetschoolid() {
        
            const selectedCheckboxes = document.querySelectorAll("#yajra-table tbody input[type='checkbox']:checked");
            //console.log(selectedCheckboxes,"1111");
            const selectedPhoneNumbers = Array.from(selectedCheckboxes).map(checkbox => checkbox.value);
            // alert(444);
            /* console.log(selectedPhoneNumbers,"selectedPhoneNumbers");
            if (selectedPhoneNumbers.length === 0) {
               
                $('#error_meassage').siblings('p').addClass('text-danger')
                .html('Please select at least one checkbox before exporting.');
              return false; 
            } */


           // console.log(selectedPhoneNumbers,"2222");
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

           
            
}
function EmailsentallTeachers() {
        
        const selectedCheckboxes = document.querySelectorAll("#yajra-table tbody input[type='checkbox']:checked");
        //console.log(selectedCheckboxes,"1111");
        const selectedPhoneNumbers = Array.from(selectedCheckboxes).map(checkbox => checkbox.value);
        // alert(444);
        /* console.log(selectedPhoneNumbers,"selectedPhoneNumbers");
        if (selectedPhoneNumbers.length === 0) {
           
            $('#error_meassage').siblings('p').addClass('text-danger')
            .html('Please select at least one checkbox before exporting.');
          return false; 
        } */


       // console.log(selectedPhoneNumbers,"2222");
        if (selectedCheckboxes.length === 0) {
            alert("Please select at least one checkbox before email sent to teacher.");
            return false;
        }else{
            const exportLink = document.getElementById('email-sent-link');
            const currentUrl = exportLink.href;
            const updatedUrl = currentUrl + (currentUrl.includes('?') ? '&' : '?') + 'all_teacher_id=' + selectedPhoneNumbers.join(',');
            exportLink.href = updatedUrl;
            return true;
        }

       
        
}
    document.addEventListener("DOMContentLoaded", function () {
        const checkboxAllCheck = document.querySelector("#checkboxAllCheck");
        const checkboxes = document.querySelectorAll("#yajra-table tbody input[type='checkbox']");
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
              //  checkboxAllCheck.checked = allChecked;
            }
            checkboxes.forEach(function (checkbox) {
                checkbox.addEventListener("change", function () {
                    updateSelectAllCheckbox();
                });
            });
            updateSelectAllCheckbox();
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

@endsection
