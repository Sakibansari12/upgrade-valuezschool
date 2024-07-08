@extends('layout.main')
@section('content')
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        Student Details
                    </h5>
                        <div class="card-actions float-end-width">
                            <div class="dropdown show">
                                <a href="#" class="reset_password waves-effect waves-light btn btn-sm btn-outline btn-info mb-5"
                                student-id="{{$studentdata->id}}"
                                >Reset Password</a>
                                <!-- <a href="{{ route('student-pdf-download', ['student_id' => $studentdata->id]) }}" class=" waves-effect waves-light btn btn-sm btn-outline btn-info mb-5"
                                ><i class="fas fa-download"></i></a> -->
                            </div>
                            <div class="card-actions button-edit-width float-end-width">
                            <div class="dropdown show ">
                                    <a href="{{ route('student-login-edit', ['student_id' => $studentdata->id]) }}" class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5"
                                    student-id="{{$studentdata->id}}"
                                    >Edit Profile</a>
                            </div>
                           </div>
                        </div>
                </div>
                <div class="card-body">
                        <div aria-expanded="true" class="v-expansion-panel v-expansion-panel--active v-item--active">
                            <div class="v-expansion-panel-content" style="">
                                <div class="v-expansion-panel-content__wrap">
                                    <div class="row custom-content-container pt-0">
                                        <div class="col-md-5 col">
                                            <div class="p-10">
                                                <div class="v-image v-responsive custom-image-style">
                                                    <div class="v-image__image v-image__image--preload v-image__image--cover">
                                                    @if(!$studentdata->student_image) 
                                                        <img src="{{ asset('assets/images/no-profile-image.PNG') }}" width="100" height="100">
                                                        @endif
                                                        @if ($studentdata->student_image)
                                                         <!-- <img src="{{ url('uploads/avatar') }}/{{ $studentdata->student_image }}" width="100" height="100" style="border-radius: 50%; margin-left: 70px;"> -->
                                                         <img id="student-image" src="{{ url('uploads/avatar') }}/{{ $studentdata->student_image }}" width="100" height="100" style="border-radius: 50%; margin-left: 75px;">
                                                        @endif
                                                        <table width="100%">
                                                            <thead>
                                                            </thead>
                                                            <tbody>
                                                            <tr id="studentImageError">
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="image-upload-option mt-4">
                                                        <!-- <a href="#" class="waves-effect waves-light btn btn-sm btn-outline mb-5" style="background-color: #00205c; color: #fff;" onclick="StudentImageUpload(event, {{ $studentdata->id }})" id="uploadStudentButton">Upload Image</a>
                                                        <input type="file" id="fileInput" style="display: none;"> -->




<div class="modal-status" id="bs-adjust-image-popup" tabindex="-1" role="dialog" aria-labelledby="modal-label-demo"
        aria-hidden="true">
        <div class="modal-dialog" style="margin-top: 200px;">
            <div class="modal-content">
                <div class="modal-header" >
                    <h4 class="modal-title" id="modal-label-pass">
                            </h4>
                        <button type="button" class="btn-close" onclick="closeCongratulation()" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <img id="previewImage" src="" width="100" height="100" style="border-radius: 50%; margin-left: 70px; display: none;">
                    </div>
                    <div class="text-center" style="margin-top: 58px;">
                       <a href="#" class="waves-effect waves-light btn  btn-outline mt-2 mb-5" style="background-color: #00205c; color: #fff;" onclick="StudentImageUpload(event, {{ $studentdata->id }})">Save</a>
                    </div>
                </div>
                <!-- <a href="#" class="waves-effect waves-light btn btn-sm btn-outline mt-2 mb-5" style="background-color: #00205c; color: #fff;" onclick="StudentImageUpload(event, {{ $studentdata->id }})">Save</a> -->
            </div>
        </div>
    </div> 





                                                        <!-- <img id="previewImage" src="" width="100" height="100" style="border-radius: 50%; margin-left: 70px; display: none;"> -->
                                                        <a href="#" class="waves-effect waves-light btn btn-sm btn-outline mt-2 mb-5" style="background-color: #00205c; color: #fff;" onclick="openFileInput()" id="uploadStudentButton">Upload Image</a>
                                                        <input type="file" id="fileInput" style="display: none;">
                                                        <!-- <img id="previewImage" src="" width="100" height="100" style="border-radius: 50%; margin-left: 70px; display: none;"> -->
                                                        <!-- <a href="#" class="waves-effect waves-light btn btn-sm btn-outline mt-2 mb-5" style="background-color: #00205c; color: #fff;" onclick="StudentImageUpload(event, {{ $studentdata->id }})">Save</a> -->


                                                       <span>OR</span> 
                                                       <a href="#" class="avatar_image waves-effect waves-light btn btn-sm btn-outline mt-2 mb-5"
                                                       style="background-color: #00205c; color: #fff;"
                                                          student-id="{{$studentdata->id}}"
                                                            >Choose Avatar</a>
                                                            
                                                    </div>
                                                    
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pb-0 pl-4 col col-7">
                                            <div class="p-10">
                                        <section class="content">
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h2 class="card-title mb-0" style="color: #00205c;">Student Detail</h2>
                                                        </div>
                                                        <div class="card-body custom-font-size">
                                                            <p class="card-text"><strong>School Name :</strong> {{ isset($studentdata->student_school_name) ? $studentdata->student_school_name : '' }}</p>
                                                            <p class="card-text"><strong>First Name :</strong> {{ isset($studentdata->name) ? $studentdata->name : '' }}</p>
                                                            <p class="card-text"><strong>Last Name :</strong> {{ isset($studentdata->last_name) ? $studentdata->last_name : '' }}</p>
                                                            <p class="card-text"><strong>UserName :</strong> {{ isset($studentdata->username) ? $studentdata->username : '' }}</p>
                                                            <p class="card-text"><strong>Email :</strong> {{ isset($studentdata->email) ? $studentdata->email : '' }}</p>
                                                            <p class="card-text"><strong>Grade :</strong> {{ isset($studentdata->grade_class_name) ? $studentdata->grade_class_name : '' }}</p>
                                                            <p class="card-text"><strong>Student Status :</strong> {{ isset($studentdata->student_status) ? $studentdata->student_status : '' }}</p>
                                                            <p class="card-text"><strong>Package Duration :</strong> {{ isset($studentdata->duration_package) ? $studentdata->duration_package : '' }}</p>
                                                            <p class="card-text"><strong>Section :</strong> {{ isset($studentdata->section) ? $studentdata->section : '' }}</p>
                                                            @if($studentdata->start_date)
                                                            <p class="card-text"><strong>Student Subscription Start Date :</strong> {{ isset($studentdata->start_date) ? \Carbon\Carbon::parse($studentdata->start_date)->format('d/m/Y') : '' }}</p>
                                                            @endif
                                                            @if($studentdata->end_date)
                                                            <p class="card-text"><strong>Student Subscription End Date :</strong> {{ isset($studentdata->end_date) ? \Carbon\Carbon::parse($studentdata->end_date)->format('d/m/Y') : '' }}</p>
                                                            @endif
                                                        </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                            <section class="content">
                                                <div class="row">
                                                    <div class="col-xl-12">
                                                        <div class="card">
                                                            <div class="card-header">
                                                                <h5 class="card-title mb-0">Billing</h5>
                                                                <div class="card-actions float-end">
                                                                </div>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="table-responsive">
                                                                    <table id="yajra-table" class="table" style="width:100%">
                                                                        <thead  style="background-color: #00205c; color: #fff;">
                                                                            <tr>
                                                                                <th>#</th>
                                                                                <th>Invoice Number</th>
                                                                                <th>Payment status</th>
                                                                                <th>Date & Time Of Payment</th>
                                                                               <!--  <th>View Invoice</th> -->
                                                                                <th>Download Invoice</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody class="text-dark">
                                                                            @if(!empty($datas))
                                                                                @foreach ($datas as $key => $data)
                                                                                        <tr>
                                                                                            <td>
                                                                                                {{ $key + 1 }}
                                                                                            </td>
                                                                                            <td>
                                                                                                {{ $data->orderid }}
                                                                                            </td>
                                                                                            <!-- <td>
                                                                                            {{ $data->payment_status }}
                                                                                            </td> -->
                                                                                            <td><a href="javascript:void(0);"
                                                                                                    class="text-white badge bg-{{ $data->payment_status == 1 ? 'success' : 'danger' }}"
                                                                                                    data-status="{{ $data->payment_status }}">{{ $data->payment_status == 1 ? 'Success' : 'Pending' }}</a>
                                                                                            </td>
                                                                                            <td>
                                                                                                {{ isset($data->created_at) ? \Carbon\Carbon::parse($data->created_at)->format('d/m/Y') : '' }} <strong>|</strong>
                                                                                                {{ isset($data->created_at) ? \Carbon\Carbon::parse($data->created_at)->format('H:i') : '' }}
                                                                                            
                                                                                            </td>
                                                                                            <!-- <td>
                                                                                            <a href="{{ route('student-pdf-download', ['student_payment_id' => $data->id]) }}" target="_blank" class=" waves-effect waves-light btn btn-sm btn-outline btn-info mb-5"
                                                                                            ><i class="fas fa-download"></i></a>
                                                                                            </td> -->
                                                                                            <td>
                                                                                                <a href="{{ route('student-pdf-download', ['student_payment_id' => $data->id]) }}" class=" waves-effect waves-light btn btn-sm btn-outline btn-info mb-5"
                                                                                                ><i class="fas fa-download"></i></a>
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
                                        </div>
                                      </div>
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
    <div class="modal-dialog mt-100" style="max-width: 600px; ">
        <div class="modal-content">
            <form name="sent_email_payment_link" id="sent_email_payment_link" method="POST" enctype="multipart/form-data">
                @csrf
               <!--  <div class="modal-header">
                    <h5 class="modal-title">Reset Password</h5>
                </div> -->

                <div class="card-header">
                        <h5 class="card-title mb-0">Reset Password</h5>
                        <div class="card-actions float-end">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                        </div>
                    </div>


                <div class="modal-body">
                    <!-- <div class="controls">
                        <label for="old_password">Old Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" id="old_password" name="old_password" class="form-control" placeholder="Old Password" >
                                <div class="input-group-append">
                                    <span class="input-group-text" id="show_old_password"><i class="fa fa-eye"></i></span>
                                </div>
                                <p></p>
                            </div>
                    </div> -->
                    <div class="controls">
                    <label for="set_new_password">Set New Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" id="set_new_password" name="set_new_password" class="form-control" placeholder="Set New Password" >
                                
                                <div class="input-group-append">
                                    <span class="input-group-text" id="show_new_password"><i class="fa fa-eye"></i></span>
                                </div>
                                <p></p>
                            </div>
                    </div>
                    <div class="controls">
                    <label for="confirm_password">Confirm Password<span class="text-danger">*</span></label>
                            <div class="input-group" id="password_match"`>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm Password" >
                                
                                <div class="input-group-append">
                                    <span class="input-group-text" id="show_Confirm_Password"><i class="fa fa-eye"></i></span>
                                </div>
                                <p></p>
                            </div>
                    </div>
                    <!-- <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Email">
                    </div> -->
                    <input type="hidden" name="student_id" id="student_id" class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="button" id="ResetPasswordId" onclick="ResetPassword()" class="btn" style="background-color: #00205c; color: #fff;">Submit</button>
                    <div id="responseMessage"></div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fixed-right" id="bs-avatar-modal" tabindex="-1" role="dialog" aria-labelledby="modal-label" aria-hidden="true">
    <div class="modal-dialog mt-100" style="max-width: 700px;">
        <div class="modal-content">
            <form name="avatar_selection_form" id="avatar_selection_form" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Choose Your Avatar</h5>
                    <button type="button" class="btn btn-secondary" style="background-color: #00205c; color: #fff;" id="cancelButton">Close</button>
                    </button>
                </div>
                <div class="modal-body ">
    <div class="avatar-container text-center">
        <div class="row text-center">
            @if($avatar_images->isNotEmpty())
                @foreach ($avatar_images as $adata)
                <div class="col-md-2 ">
                        <img class="avatar-image" src="{{ url('uploads/avatar/') }}/{{ $adata->avatar_title_image ? $adata->avatar_title_image : '' }}" style="border-radius: 50%;" width="100" height="100">
                        <input type="hidden" name="avatarimage" id="avatarimage_{{ $adata->id }}" value="{{ $adata->avatar_title_image ? $adata->avatar_title_image : '' }}" class="form-control">
                        &nbsp;&nbsp;&nbsp;<input class="form-check-input" type="radio" name="avatar_selection" value="{{ $adata->id }}" id="avatar_{{ $adata->id }}">
                        <label class="form-check-label" for="avatar_{{ $adata->id }}"></label>
                    
                </div>
                @endforeach
            @endif
        </div>
    </div>
</div>


                <div class="modal-footer">
                     <input type="hidden" name="student_id" id="student_id" class="form-control">
                    <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                    <button type="submit" class="btn btn-primary" style="background-color: #00205c; color: #fff;">Save Avatar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@section('script-section')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">

<script>

    // Initialize Cropper.js instance
var cropper;

// Function to open file input when "Upload Image" button is clicked
function openFileInput() {
    
    document.getElementById('fileInput').click();
    
}

// Function to handle image upload
function handleImageUpload(event, studentId) {
    $('#bs-adjust-image-popup').show();
    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onload = function(e) {
        // Destroy previous Cropper instance if exists
        if (cropper) {
            cropper.destroy();
        }

        const img = new Image();
        img.onload = function() {
            // Create a new Cropper instance
            const previewImage = document.getElementById('previewImage');
            previewImage.src = e.target.result;
            cropper = new Cropper(previewImage, {
                aspectRatio: 1, // Set aspect ratio as needed (e.g., 1:1 for square)
                cropperType: 'circle', // Set cropper type to circle
                crop: function(event) {
                    
                }
            });
        };
        img.src = e.target.result;
    };

    reader.readAsDataURL(file);
}




// Add event listener to file input to handle image upload
document.getElementById('fileInput').addEventListener('change', function(event) {
    handleImageUpload(event);
});


</script>
<script>
    /* function StudentImageUpload(event, student_id) {
        event.preventDefault();
        $('#fileInput').click();
        $('#fileInput').change(function () {
            var fileInput = document.getElementById('fileInput');
            var file = fileInput.files[0];
            console.log(file,"file");
            var formData = new FormData();
           console.log(formData,"formData");
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
                    }
                },
                error: function (xhr, status, error) {
                    alert('File upload failed: ' + error); // Handle the error
                }
            });
        });
    } */

    function StudentImageUpload(event, student_id) {
    event.preventDefault();

    // Get the cropped image data from Cropper.js
    var croppedCanvas = cropper.getCroppedCanvas({
        width: 100, // Set the desired width of the cropped image
        height: 100, // Set the desired height of the cropped image
    });
    
    // Convert the cropped canvas to a Blob object
    croppedCanvas.toBlob(function(blob) {
        // Create a FormData object and append the cropped image data
        var formData = new FormData();
        formData.append('student', blob);
        formData.append('student_id', student_id);
        console.log(formData,"formData");
        // Send the FormData object to your API endpoint using AJAX
        $.ajax({
            url: "{{ route('student-upload-image') }}",
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response['status'] == true) {
                    $('#studentImageError').empty();
                    window.location.href = "{{  route('student-details') }}";
                } else {
                    var errors = response['errors'];
                    $('#studentImageError').empty();
                    $.each(errors['student'], function(key, value) {
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


                    
                }
            },
            error: function(xhr, status, error) {
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
$(document).on('click', '.avatar_image', function() {
        var student_id = $(this).attr('student-id');
        $('#student_id').val(student_id);
   $('#bs-avatar-modal').modal('show');
});

document.getElementById('cancelButton').addEventListener('click', function() {
    $('#bs-avatar-modal').modal('hide'); // Hide the modal when the button is clicked
});


function ResetPassword() {
    var csrfToken = $('meta[name="_token"]').attr('content');
        $.ajax({
            url: '{{ route("student-login-reset-password") }}',
            type: 'post',
            data: {
                set_new_password: $('#set_new_password').val(),
                confirm_password: $('#confirm_password').val(),
                //old_password: $('#old_password').val(), 
              //  email: $('#email').val(), 
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

$('#avatar_selection_form').submit(function (event) {
    event.preventDefault();
    
    // Get the selected avatar
    var selectedAvatar = $('input[name="avatar_selection"]:checked');

    // Check if an avatar is selected
    if (selectedAvatar.length > 0) {
        // Get the value of the selected avatar
        var avatarId = selectedAvatar.val();
        var avatarImage = $('#avatarimage_' + avatarId).val();
        var studentId = $('#student_id').val();
        // Create FormData with the selected avatar data
        var formData = new FormData();
        formData.append('avatar_id', avatarId);
        formData.append('avatar_image', avatarImage);
        formData.append('student_id', studentId); // Append student ID

        // Submit the form data via AJAX
        $.ajax({
            url: '{{ route("avatar-image-changes") }}',
            type: 'POST',
            processData: false,
            contentType: false,
            data: formData,
            success: function (response) {
                console.log(response, "response");
                if (response['status'] == true) {
                    window.location.href = "{{  route('student-details') }}";
                } else {
                    // Handle errors
                    button_new.disabled = false;
                    spinner_new.style.display = 'none';
                    var errors = response['errors'];
                    $.each(errors, function (key, value) {
                        var elementId = key.replace(/\./g, '_');
                        console.log(elementId, "elementId");
                        console.log(value[0], "value[0]");
                        $('#' + elementId).siblings('p').addClass('text-danger').html(value[0]);
                    });
                }
            },
            error: function () {
                console.log('Something went wrong');
            }
        });
    } else {
        // If no avatar is selected, display an error message or take appropriate action
        //ertify.set('notifier', 'position', 'top-center');
        alert('Please select an avatar.');
    }
});



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

function closeCongratulation() {
        $('#bs-adjust-image-popup').hide();
        
    }
</script>
@endsection
