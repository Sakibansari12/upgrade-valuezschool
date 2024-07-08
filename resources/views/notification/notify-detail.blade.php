@extends('layout.main')
@section('content')
<!-- Main content -->
<div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title"></h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('notify.teacherview') }}"><i class="mdi mdi-home-outline"></i>What's New</a></li>
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page">Notification Details</li>
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
                    <h5 class="card-title mb-0">
                    Notification Details
                    </h5>
                    <div class="card-actions float-end">
                            <div class="dropdown show">
                               <!--  <a href="#" class="reset_password waves-effect waves-light btn btn-sm btn-outline btn-info mb-5"
                                
                                >Reset Password</a> -->
                            </div>
                        </div>
                </div>

                <div class="card-body">
                        <div aria-expanded="true" class="v-expansion-panel v-expansion-panel--active v-item--active">
                            <div class="v-expansion-panel-content" style="">
                                <div class="v-expansion-panel-content__wrap">
                                    <div class="row custom-content-container pt-0">
                                        <div class="col col-8 custom-light-gray-background">
                                            <div class="p-10" style="position: relative;">
                                                <table class="width-100 custom-table">
                                                    <tr>
                                                        <td class="p-10 noty-detail fw-bold font-size-20">Subject :</td>
                                                        <td class="p-5 descriptipn-noty font-size-18 font-weight-600">{{ isset($notification_data->title) ? $notification_data->title : '' }}</td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <td class="p-10 noty-detail fw-bold font-size-20">Date & Time :</td>
                                                        <td class="p-5 descriptipn-noty font-size-18 font-weight-600 text-capitalize">
                                                        {{ isset($notification_data->created_at) ? $notification_data->created_at->format('d/m/Y') : '' }} |
                                                            {{ isset($notification_data->created_at) ? $notification_data->created_at->format('H:i') : '' }}</td>
                                                    </tr>
                                                    <tr class="description-row">
                                                        <td class="p-10 noty-detail fw-bold font-size-20">Description :</td>
                                                        <td class="p-5 descriptipn-noty font-size-18 font-weight-600  ">{{ isset($notification_data->description) ? strip_tags($notification_data->description) : '' }}</td>
                                                    </tr>
                                                   <!--  <tr>
                                                        <td width="250" class="font-size-18 font-weight-500 py-2"> Description </td>
                                                        <td class="font-weight-600 font-size-18 py-2">{{ isset($notification_data->description) ? $notification_data->description : '' }}</td>
                                                    </tr> -->
                                                </table>
                                            </div>
                                        </div>


                                        <!-- <div class="col-md-4 col">
                                            <div class="p-10">
                                                <div class="v-image v-responsive custom-image-style">
                                                    
                                                   
                                                    
                                                </div>
                                            </div>
                                        </div> -->

                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->
<!-- Popup -->

<div class="modal fixed-right" id="bs-reset-password-modal" tabindex="-1" role="dialog" aria-labelledby="modal-label" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 600px;">
        <div class="modal-content">
            <form name="sent_email_payment_link" id="sent_email_payment_link" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Reset Password</h5>
                    <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button> -->
                </div>
                <div class="modal-body">
                    <!-- <div class="form-group">
                        <label for="old_password">Old Password <span class="text-danger">*</span></label>
                        <input type="password" id="old_password" name="old_password" class="form-control" placeholder="Old Password" >
                        <div class="input-group-append">
                            <span class="form-group-text" id="show_old_password"><i class="fa fa-eye"></i></span>
                        </div>
                     <p></p>
                    </div> -->


                    <div class="controls">
                        <label for="old_password">Old Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" id="old_password" name="old_password" class="form-control" placeholder="Old Password" >
                                <p></p>
                                <div class="input-group-append">
                                    <span class="input-group-text" id="show_old_password"><i class="fa fa-eye"></i></span>
                                </div>
                            </div>
                    </div>


                    <!-- <div class="form-group">
                        <label for="set_new_password">Set New Password <span class="text-danger">*</span></label>
                        <input type="password" id="set_new_password" name="set_new_password" class="form-control" placeholder="Set New Password" >
                        <p></p>
                    </div> -->



                    <div class="controls">
                    <label for="set_new_password">Set New Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" id="set_new_password" name="set_new_password" class="form-control" placeholder="Set New Password" >
                                <p></p>
                                <div class="input-group-append">
                                    <span class="input-group-text" id="show_new_password"><i class="fa fa-eye"></i></span>
                                </div>
                            </div>
                    </div>





                    <!-- <div class="form-group" id="password_match">
                        <label for="confirm_password">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm Password" >
                        <p></p>
                    </div> -->

                    <div class="controls">
                    <label for="confirm_password">Confirm Password<span class="text-danger">*</span></label>
                            <div class="input-group" id="password_match"`>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm Password" >
                                <p></p>
                                <div class="input-group-append">
                                    <span class="input-group-text" id="show_Confirm_Password"><i class="fa fa-eye"></i></span>
                                </div>
                            </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Email">
                    </div>
                    <input type="hidden" name="student_id" id="student_id" class="form-control">
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                    <button type="button" id="ResetPasswordId" onclick="ResetPassword()" class="btn btn-primary">Submit</button>
                    <div id="responseMessage"></div>
                </div>
            </form>
        </div>
    </div>
</div>




@endsection
@section('script-section')

<script>
    function StudentImageUpload(event, student_id) {
    //console.log(student_id,"student_id");
        event.preventDefault();
        $('#fileInput').click();
        $('#fileInput').change(function () {
            var fileInput = document.getElementById('fileInput');
            var file = fileInput.files[0];
           // console.log(file,"file");
            var formData = new FormData();
           // console.log(formData,"formData");
            formData.append('student', file);
            formData.append('student_id', student_id);

            
            $.ajax({
                url: "{{ route('student-upload-image') }}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response['status'] == true) {
                        //console.log(response, "response");
                        $('#studentImageError').empty();
                        window.location.href="{{  route('student-details') }}";
                    } else {
                        var errors = response['errors'];
                        console.log(errors['student'], "errors");

                        $('#studentImageError').empty();
                        $.each(errors['student'], function (key, value) {
                            var errorRow = $('<tr>');
                            errorRow.append(
                                `<td>
                                    <span class="font-size-16 font-weight-500 text-danger">
                                        ${value}
                                    </span>
                                </td>`
                            );
                            $('#studentImageError').append(errorRow);
                        });


                       /*  $.each(errors, function (key, value) {
                            $(`#${key}`).siblings('p').addClass('text-danger').html(value);
                        }); */
                    }
                },
                error: function (xhr, status, error) {
                    alert('File upload failed: ' + error); // Handle the error
                }
            });
        });
    }
</script>
<script>
    $(document).on('click', '.reset_password', function() {
        var student_id = $(this).attr('student-id');
        $('#student_id').val(student_id);
   $('#bs-reset-password-modal').modal('show');
});
function ResetPassword() {
    var csrfToken = $('meta[name="_token"]').attr('content');
        $.ajax({
            url: '{{ route("student-login-reset-password") }}',
            type: 'post',
            data: {
                set_new_password: $('#set_new_password').val(),
                confirm_password: $('#confirm_password').val(),
                old_password: $('#old_password').val(), 
                email: $('#email').val(), 
                student_id: $('#student_id').val(), 
            },
            dataType: 'json',
            success: function (response) {
                if (response['status'] == true) {
                    console.log(response, "response");
                    $('#responseMessage').addClass('text-success').html('Password Reset Successful');
                    window.location.href="{{  route('student-details') }}";
                } else {
                    var errors = response['errors'];
                    var NotMatchError = response['password_match_error'];
                    var OldPasswordError = response['old_password_incorrect'];
                    //console.log(errors, "errors");
                    if(NotMatchError){
                        $('#confirm_password').siblings('p').addClass('text-danger').html(NotMatchError);
                    }else{
                        $('#confirm_password').siblings('p').removeClass('text-danger').html('');
                    }
                    if(OldPasswordError){
                        $('#old_password').siblings('p').addClass('text-danger').html(OldPasswordError);
                    }else{
                        $('#old_password').siblings('p').removeClass('text-danger').html('');
                        
                    }
                    

                    if(errors['old_password']){
                        $('#old_password').siblings('p').addClass('text-danger').html(errors['old_password']);
                    }else{
                        $('#old_password').siblings('p').removeClass('text-danger').html('');
                    }
                    if(errors['set_new_password']){
                        $('#set_new_password').siblings('p').addClass('text-danger').html(errors['set_new_password']);
                    }else{
                        $('#set_new_password').siblings('p').removeClass('text-danger').html('');
                    }
                    if(errors['confirm_password']){
                        $('#confirm_password').siblings('p').addClass('text-danger').html(errors['confirm_password']);
                    }else{
                        $('#confirm_password').siblings('p').removeClass('text-danger').html('');
                    }

                    /* $.each(errors, function (key, value) {
                        $(`#${key}`).siblings('p').addClass('text-danger').html(value);
                    }); */
                }
            },
            error: function () {
                console.log("Something went wrong");
            }
        });
    
};


$(document).ready(function() {
    $('#show_old_password').click(function() {
        var passwordField = $('#old_password');
        var passwordFieldType = passwordField.attr('type');
        
        if (passwordFieldType === 'password') {
            passwordField.attr('type', 'text');
        } else {
            passwordField.attr('type', 'password');
        }
    });

    $('#show_new_password').click(function() {
        var passwordField = $('#set_new_password');
        var passwordFieldType = passwordField.attr('type');
        
        if (passwordFieldType === 'password') {
            passwordField.attr('type', 'text');
        } else {
            passwordField.attr('type', 'password');
        }
    });
    $('#show_Confirm_Password').click(function() {
        var passwordField = $('#confirm_password');
        var passwordFieldType = passwordField.attr('type');
        
        if (passwordFieldType === 'password') {
            passwordField.attr('type', 'text');
        } else {
            passwordField.attr('type', 'password');
        }
    });
});


</script>
@endsection
