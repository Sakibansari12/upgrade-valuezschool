<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../images/favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta name="_token" content="{{ csrf_token() }}" />

    <title>Valuez School - Log in </title>

    <!-- Vendors Style-->
    <link rel="stylesheet" href="{{ asset('assets/src/css/vendors_css.css') }}">

    <!-- Style-->
    <link rel="stylesheet" href="{{ asset('assets/src/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/src/css/skin_color.css') }}">

    
</head>

<body class="hold-transition theme-primary bg-img"
    style="background-image: url({{ asset('assets/images/auth-bg/bg-20.png') }});background-size: contain;background-position: bottom; ">

    <div class="container h-p100">
        <div class="row align-items-center justify-content-md-center h-p100">

            <div class="col-12">
                <div class="row justify-content-center g-0">
                    <div class="col-lg-5 col-md-5 col-12">
                        <div class="bg-white rounded10 shadow-lg">
                            <div class="content-top-agile p-20 pb-0">
                                <h2 class="fw-600" style="color: #00205c;">Let's Get Started</h2>
                                <p class="mb-0 text-fade">Forgot password  to continue to Valuez School.</p>
                            </div> 
                            <div class="p-40">
                            <form name="studentForgotForm" id="studentForgotForm" method="post">
                                    @csrf <!-- CSRF token for security -->
                                    
                                    <!-- Username Input -->
                                    <div class="form-group" id="username_hide">
                                        <label class="form-label">Username <span class="text-danger">*</span></label>
                                        <div class="controls">
                                            <input type="text" name="username" id="username" class="form-control" placeholder="Username">
                                            <p></p>
                                        </div>
                                        @if ($errors->has('username'))
                                            <div class="form-control-feedback">
                                                <small class="text-danger">{{ $errors->first('username') }}</small>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Next Button -->
                                    <div class="row" id="nextbutton_hide">
                                        <div class="col-md-4 text-center"></div>
                                        <div class="col-md-4 text-center m-5">
                                            <input type="button" onclick="ForgotUsername()" id="ForgotPasswordUsername" value="Next" class="btn w-p100 mx-auto" style="background-color: #00205c; color: #fff;">
                                        </div>
                                        <div class="col-md-4 text-center"></div>
                                    </div>
                                    
                                    <!-- Phone Number Input -->
                                    
                                    <div class="row" id="PhoneNumber_hide">
                                        <div class="form-group col-md-6">
                                            <label class="form-label">Phone Number<span class="text-danger">*</span></label>
                                            <div class="controls" id="updatetimesucessmessage">
                                                <input type="hidden" id="mobile" name="mobile">
                                                <input type="text" name="phone_number" id="phone_number" class="form-control" placeholder="Phone Number">
                                                <p></p>
                                            </div>
                                            <p></p>
                                        </div>
                                        <div class="col-md-6 text-center mt-2">
                                            <input type="button" id="getotpButton" onclick="StudentForgotOtp()"  value="Get OTP" class="btn w-p100 mt-20 button-small" style="background-color: #00205c; color: #fff;">
                                        </div>
                                    </div>
                                    
                                    <!-- OTP Input -->
                                    <div class="row" id="otpfield_hide">
                                        <div class="form-group col-md-6">
                                            <label class="form-label">OTP <span class="text-danger">*</span></label>
                                            <div class="controls" id="otperrormeassage">
                                                <input type="text" name="otp" id="otp" value="" class="form-control" placeholder="OTP">
                                                <p></p> 
                                            </div>
                                            <p></p>
                                            @if ($errors->has('otp'))
                                                <div class="form-control-feedback">
                                                    <small class="text-danger">{{ $errors->first('otp') }}</small>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-6 text-center mt-2">
                                            <input type="button" onclick="ForgotVerifyOtp()" id="VerifyOtpbutton"  value="Verify OTP" class="btn w-p100 mt-20 button-small" style="background-color: #00205c; color: #fff;">
                                        </div>
                                    </div>

                                    <!-- Password and Confirm Password Inputs (Hidden by default) -->
                                    <div class="row" id="password_hide">
                                        <!-- Password Input -->
                                        <div class="form-group">
                                            <label class="form-label">Password <span class="text-danger">*</span></label>
                                            <div class="controls">
                                                <div class="input-group" id="passwordsibling">
                                                    <input type="password" id="password" name="password" class="form-control" placeholder="Password">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id="showPasswordToggle"><i class="fa fa-eye"></i></span>
                                                    </div>
                                                </div>
                                                <p></p>
                                            </div>
                                            @if ($errors->has('password'))
                                                <div class="form-control-feedback">
                                                    <small class="text-danger">{{ $errors->first('password') }}</small>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Confirm Password Input -->
                                        <div class="form-group" id="confirmpassword_hide">
                                            <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                            <div class="controls" id="confirm_password_dontmatch_error">
                                                <div class="input-group" id="confirmpasswordsibling">
                                                    
                                                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm Password">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id="showConfirmPasswordToggle"><i class="fa fa-eye" aria-hidden="true"></i></span>
                                                    </div>
                                                </div>
                                                <p></p>
                                            </div>
                                            <p></p>
                                            @if ($errors->has('password'))
                                                <div class="form-control-feedback">
                                                    <small class="text-danger">{{ $errors->first('password') }}</small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Content Insertion Point (Empty by default) -->
                                    <div id="forgot-insert-content-here"></div>
                                    
                                    <!-- Additional Inputs (Name of Class Teacher, School Name, Grade, Email) -->
                                    <div class="form-group" id="teacher_name_hide">
                                        <label class="form-label">Name of Class Teacher <span class="text-danger">*</span></label>
                                        <div class="controls">
                                            <input type="text" name="teacher_name" id="teacher_name" class="form-control" placeholder="Name of Class Teacher">
                                            <p></p>
                                        </div>
                                        @if ($errors->has('teacher_name'))
                                            <div class="form-control-feedback">
                                                <small class="text-danger">{{ $errors->first('teacher_name') }}</small>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="form-group" id="school_name_hide">
                                        <label class="form-label">School Name<span class="text-danger">*</span></label>
                                        <div class="controls">
                                            <input type="text" name="school_name" id="school_name" class="form-control" placeholder="School Name">
                                            <p></p>
                                        </div>
                                        @if ($errors->has('school_name'))
                                            <div class="form-control-feedback">
                                                <small class="text-danger">{{ $errors->first('school_name') }}</small>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="form-group" id="grade_hide">
                                        <label class="form-label">Grade<span class="text-danger">*</span></label>
                                        <select class="form-control select2" name="grade" style="width: 100%;" id="grade">
                                            <option value="">Select a Grade</option>
                                            @if($program_list->isNotEmpty())
                                                @foreach ($program_list as $prog)
                                                    <option value="{{ $prog->id }}">{{ $prog->class_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <p></p>
                                        @error('grade')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <input type="hidden" name="forgot_student" id="forgot_student">
                                    <div class="form-group" id="emailverifyotp">
                                        <label class="form-label">Email <span class="text-danger"></span></label>
                                        <div class="controls">
                                            
                                            <input type="text" name="email" id="email" class="form-control" placeholder="Enter Email">
                                            <p></p>
                                        </div>
                                        @if ($errors->has('email'))
                                            <div class="form-control-feedback">
                                                <small class="text-danger">{{ $errors->first('email') }}</small>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Submit Button -->
                                    <div class="row">
                                        <div class="col-md-4 text-center "></div>
                                        <div class="col-md-4 text-center m-5">
                                            <input type="button" onclick="ForgotPassword()" id="ForgotPassworddisable" value="Submit" class="btn w-p100 mx-auto" style="background-color: #00205c; color: #fff;">
                                        </div>
                                        <div class="col-md-4 text-center"></div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/src/js/vendors.min.js') }}"></script>
    <script src="{{ asset('assets/src/js/pages/chat-popup.js') }}"></script>
    <script src="{{ asset('assets/icons/feather-icons/feather.min.js') }}"></script>
    <script>
       var teacher_name_hide = $("#teacher_name_hide");
                         teacher_name_hide.toggle();
       var grade_hide = $("#grade_hide");
                         grade_hide.toggle();
       var emailverifyotp = $("#emailverifyotp");
                         emailverifyotp.toggle(); 
       var school_name_hide = $("#school_name_hide");
                         school_name_hide.toggle(); 
                         
       var PhoneNumber_hide = $("#PhoneNumber_hide");
                         PhoneNumber_hide.toggle();
       var otpfield_hide = $("#otpfield_hide");
                         otpfield_hide.toggle();
       var password_hide = $("#password_hide");
                         password_hide.toggle(); 
       var confirmpassword_hide = $("#confirmpassword_hide");
                         confirmpassword_hide.toggle();
      
    document.getElementById("ForgotPassworddisable").disabled = true;
    document.getElementById("VerifyOtpbutton").disabled = true;
    document.getElementById("otp").readOnly = true;

/* Username Check  */
function ForgotUsername(){
        var csrfToken = $('meta[name="_token"]').attr('content');
			$.ajax({
			url: '{{ route("student-forgot-username") }}',
			type:'post',
            data: {
                _token: csrfToken, 
                username: $('#username').val(),
            },
			dataType: 'json',
			success: function(response){
				if(response['status'] == true){
                        //$('#username').siblings('p').removeClass('text-danger').addClass('text-success').html(response['message']);
                        var PhoneNumber_hide = $("#PhoneNumber_hide");
                                               PhoneNumber_hide.show();
                        var otpfield_hide = $("#otpfield_hide");
                                             otpfield_hide.show();                           
                        const phonenumber = response['otpdata'];
                        $('#mobile').val(phonenumber.phone_number);
                        
                        const phoneNumberString = phonenumber.phone_number.toString();
                        const formattedPhoneNumber = phoneNumberString.slice(0, 2) + 'xxxxxxxx' + phoneNumberString.slice(-2);
                        $('#phone_number').val(formattedPhoneNumber);
                        document.getElementById("ForgotPasswordUsername").disabled = true; 
                        document.getElementById("phone_number").readOnly = true; 
                    

                        $('#username').siblings('p').removeClass('text-danger').html('');
                        
                        //let timeresetotpupdate =  response['timeResetOtp'];
                       // document.getElementById("otp").readOnly  = false;
				}else{
                    var errors = response['errors'];
                    var phone_number_not_store = response['phone_number_not_store'];
                    if(phone_number_not_store){
                        var PhoneNumber_hide = $("#PhoneNumber_hide");
                                               PhoneNumber_hide.show();
                        var otpfield_hide = $("#otpfield_hide");
                                             otpfield_hide.show();
                        document.getElementById("ForgotPasswordUsername").disabled = true;
                        document.getElementById("phone_number").readOnly = false;
                    }
                    if(errors['username']){
                      $('#username').siblings('p').addClass('text-danger').html(errors['username']);
                    }else{
                        $('#username').siblings('p').removeClass('text-danger').html('');
                    }
                }
			}, error: function(jqXHR, exception){
                    console.log("something went wrong");
            }
	     });		
}

	function StudentForgotOtp(){
        var csrfToken = $('meta[name="_token"]').attr('content');
			$.ajax({
			url: '{{ route("student-forgot-get-otp") }}',
			type:'post',
            data: {
                _token: csrfToken, 
                phone_number: $('#phone_number').val(), 
                username: $('#username').val(),
            },
			dataType: 'json',
			success: function(response){
				if(response['status'] == true){
                        $('#phone_number').siblings('p').removeClass('text-danger').addClass('text-success').html(response['message']);
                        let timeresetotpupdate =  response['timeResetOtp'];
                        function updateButtonValue() {
                        if (timeresetotpupdate > 0) {
                            document.getElementById("getotpButton").disabled = true;
                            document.getElementById("getotpButton").value = `Resend OTP (${timeresetotpupdate} seconds)`;
                            timeresetotpupdate--;
                            setTimeout(updateButtonValue, 1000); // Call the function again after 1 second
                        } else {
                            document.getElementById("getotpButton").disabled = false;
                            document.getElementById("getotpButton").value = "Resend OTP";
                            $('#phone_number').siblings('p').removeClass('text-success').html('');
                        }
                    }
                    updateButtonValue();
                    document.getElementById("otp").readOnly  = false;
				}else{
                    var errors = response['errors'];
                    var student_not_store = response['student_not_store'];
                    if(student_not_store){
                      $('#username').siblings('p').addClass('text-danger').html(student_not_store);
                    }else{
                        $('#username').siblings('p').removeClass('text-danger').html('');
                    }
                    if(errors['phone_number']){
                      $('#phone_number').siblings('p').addClass('text-danger').html(errors['phone_number']);
                    }else{
                        $('#phone_number').siblings('p').removeClass('text-danger').html('');
                    }
                }
			}, error: function(jqXHR, exception){
                    console.log("something went wrong");
            }
	     });		
}

/* Otp verified */
function ForgotVerifyOtp(){
       // alert(223);
    var csrfToken = $('meta[name="_token"]').attr('content');
    $.ajax({
      url: '{{ route("forgot-verify-otp") }}',
      type:'post',
      data: {
        _token: csrfToken, 
        otp: $('#otp').val(), 
        username: $('#username').val(),
        phone_number: $('#phone_number').val(),
        mobile: $('#mobile').val(),
      },
      dataType: 'json',
      success: function(response){
         if(response['status'] == true){
           // console.log(response['message'],"response['mgessae']");
           var sucess_message = response['message'];
           if(sucess_message){
            $('#otp').siblings('p').removeClass('text-danger').addClass('text-success').html(response['message']);
                var password_hide = $("#password_hide");
                                    password_hide.show(); 
                var confirmpassword_hide = $("#confirmpassword_hide");
                            confirmpassword_hide.show();
                document.getElementById("ForgotPassworddisable").disabled = false;
                document.getElementById("VerifyOtpbutton").disabled = true;

                /* Hide */
                var PhoneNumber_hide = $("#PhoneNumber_hide");
                                 PhoneNumber_hide.toggle();
                var otpfield_hide = $("#otpfield_hide");
                                 otpfield_hide.toggle(); 
                                 
                var nextbutton_hide = $("#nextbutton_hide");
                                    nextbutton_hide.toggle();
                var username_hide = $("#username_hide");
                                   username_hide.toggle();

                //var studentstatus = 'student_table';
                $('#forgot_student').val();
                
           }
            var forgot_password_table_message = response['forgot_password_table_message'];
            if(forgot_password_table_message){
                $('#otp').siblings('p').removeClass('text-danger').addClass('text-success').html(forgot_password_table_message);
                document.getElementById("ForgotPassworddisable").disabled = false;
              //  document.getElementById("VerifyOtpbutton").disabled = true;
              const forgotstudentdata = response['otpdata'];
                       // $('#phone_number').val(phonenumber.phone_number);
              var successMessage = `
                        <div class="row text-center" >
                            <div class="form-group col-md-12 mt-35">
                                <label class="form-label">
                                     <span class="text-bold">Your Username <span class="text-primary">${forgotstudentdata.username}</span> 
                                     has been shared with your School Name <span class="text-primary">${forgotstudentdata.studentschool.school_name}</span>
                                     Please kindly enquiry with your teacher politely</span></label>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-label">
                                    <span class="text-bold">OR</span></label>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-label">
                                    <span class="text-bold">Fill the form below ? we will help you</span></label>
                            </div>
                        </div>`;
                $('#forgot-insert-content-here').html(successMessage);
                var teacher_name_hide = $("#teacher_name_hide");
                                        teacher_name_hide.show();
                var grade_hide = $("#grade_hide");
                                        grade_hide.show();
                var emailverifyotp = $("#emailverifyotp");
                                       emailverifyotp.show();
                var school_name_hide = $("#school_name_hide");
                                       school_name_hide.show();

                /* hide */   
                var PhoneNumber_hide = $("#PhoneNumber_hide");
                                 PhoneNumber_hide.toggle();
                var otpfield_hide = $("#otpfield_hide");
                                 otpfield_hide.toggle(); 
                                 
                var nextbutton_hide = $("#nextbutton_hide");
                                    nextbutton_hide.toggle();
                var username_hide = $("#username_hide");
                                   username_hide.toggle();                 
                var studentstatus = 'forgot_student_table';  
                
                
                $('#forgot_student').val(studentstatus);                       
            }
         }else{
             var errors = response['errors'];
             var otpinvalid = response['queryfaild'];
             if(otpinvalid){
                $('#otp').siblings('p').addClass('text-danger').html(otpinvalid);
             }else{
                $('#otp').siblings('p').addClass('text-danger').html('');
             }
             if(errors['otp']){
                    $('#otp').siblings('p').addClass('text-danger').html(errors['otp']);
            }else{
                $('#otp').siblings('p').removeClass('text-danger').html('');
            }
            if(errors['username']){
                $('#username').siblings('p').addClass('text-danger').html(student_not_store);
            }else{
                $('#username').siblings('p').removeClass('text-danger').html('');
            }
            if(errors['phone_number']){
                $('#phone_number').siblings('p').addClass('text-danger').html(errors['phone_number']);
            }else{
                $('#phone_number').siblings('p').removeClass('text-danger').html('');
            }
            
        }
         
      }, error: function(jqXHR, exception){
         console.log("something went wrong");
      }
    });
 
}
/* Forgot password  */
function ForgotPassword(){
    //alert(12);
    var csrfToken = $('meta[name="_token"]').attr('content');
    var forgot_student = $('#forgot_student').val();
   // console.log(forgot_student,"forgot_student");
    $.ajax({
      url: '{{ route("forgot-password") }}',
      type:'post',
      data: {
        _token: csrfToken, 
        otp: $('#otp').val(), 
        username: $('#username').val(),
        phone_number: $('#phone_number').val(),
        mobile: $('#mobile').val(),
        password: $('#password').val(),
        confirm_password: $('#confirm_password').val(),
        email: $('#email').val(),
        teacher_name: $('#teacher_name').val(),
        school_name: $('#school_name').val(),
        grade: $('#grade').val(),
        forgot_student: $('#forgot_student').val(),
        
      },
      dataType: 'json',
      success: function(response){
         if(response['status'] == true){
            //console.log(response['status']);
            $('#confirmpassword_hide').siblings('p').removeClass('text-danger').addClass('text-success').html(response['message']);
            window.location.href="{{  route('student-login') }}"; 
        }else{
             var errors = response['errors'];
             var conform_password = response['conform_password'];

             if(conform_password){
                $('#confirmpasswordsibling').siblings('p').addClass('text-danger').html(conform_password);
             }else{
                $('#confirmpasswordsibling').siblings('p').addClass('text-danger').html('');
             }
            if(errors['otp']){
                    $('#otp').siblings('p').addClass('text-danger').html(errors['otp']);
            }else{
                $('#otp').siblings('p').removeClass('text-danger').html('');
            }
            if(errors['teacher_name']){
                    $('#teacher_name').siblings('p').addClass('text-danger').html(errors['teacher_name']);
            }else{
                $('#teacher_name').siblings('p').removeClass('text-danger').html('');
            }
            if(errors['school_name']){
                    $('#school_name').siblings('p').addClass('text-danger').html(errors['school_name']);
            }else{
                $('#school_name').siblings('p').removeClass('text-danger').html('');
            }
            if(errors['grade']){
                $('#grade').siblings('p').addClass('text-danger').html(errors['grade']);
            }else{
                $('#grade').siblings('p').removeClass('text-danger').html('');
            }
            if(errors['phone_number']){
                    $('#phone_number').siblings('p').addClass('text-danger').html(errors['phone_number']);
            }else{
                $('#phone_number').siblings('p').removeClass('text-danger').html('');
            }
            if(errors['username']){
                    $('#username').siblings('p').addClass('text-danger').html(errors['username']);
            }else{
                $('#username').siblings('p').removeClass('text-danger').html('');
            }

            if(errors['password']){
                    $('#password').siblings('p').addClass('text-danger').html(errors['password']);
            }else{
                $('#password').siblings('p').removeClass('text-danger').html('');
            }

            if(errors['confirm_password']){
                    $('#confirmpasswordsibling').siblings('p').addClass('text-danger').html(errors['confirm_password']);
            }else{
                $('#confirmpasswordsibling').siblings('p').removeClass('text-danger').html('');
            }
        }
         
      }, error: function(jqXHR, exception){
         console.log("something went wrong");
      }
    });
 
}




/* Email Otp */
/* function StudentForgotemailotp(){
    var csrfToken = $('meta[name="_token"]').attr('content');
    $.ajax({
      url: '{{ route("forgot-email-verify-otp") }}',
      type:'post',
      data: {
        _token: csrfToken, 
        otp: $('#otp').val(), 
        phone_number: $('#phone_number').val(),
        email: $('#email').val(),
      },
      dataType: 'json',
      success: function(response){
         if(response['status'] == true){
            $('#otp').siblings('p').removeClass('text-danger').addClass('text-success').html(response['message']);
            var emailverifyotp = $("#emailverifyotp");
                         emailverifyotp.show();
            // document.getElementById("VerifyOtpbutton").disabled = true;
           //  document.getElementById("otp").readOnly  = true;
            // document.getElementById("getotpButton").disabled = true;
         }else{
             var errors = response['errors'];
             var student_email_store = response['student_email_store'];
             if(student_email_store){
                $('#email').siblings('p').addClass('text-danger').html(student_email_store);
             }else{
                $('#email').siblings('p').addClass('text-danger').html('');
             }
             if(errors['phone_number']){
                    $('#phone_number').siblings('p').addClass('text-danger').html(errors['phone_number']);
            }else{
                $('#phone_number').siblings('p').removeClass('text-danger').html('');
            }
            if(errors['email']){
                    $('#email').siblings('p').addClass('text-danger').html(errors['email']);
            }else{
                $('#email').siblings('p').removeClass('text-danger').html('');
            }
        }
         
      }, error: function(jqXHR, exception){
         console.log("something went wrong");
      }
    });
 
} */

/* Phone Number Validation */
function toggleButtonState() {
    var phoneNumber = $('#phone_number').val();
    var button = $('#getotpButton');
    // Remove non-numeric characters and limit to 10 digits
    phoneNumber = phoneNumber.replace(/[^0-9]/g, '').slice(0, 10);
    // Check if the phone number is exactly 10 digits and does not start with '0'
     if(phoneNumber[0] == '0'){
        $('#phone_number').siblings('p').addClass('text-danger').html("Phone number can't start with 0.");
     }else{
        $('#phone_number').siblings('p').addClass('text-danger').html("");
     }
    if (phoneNumber.length === 10 && phoneNumber[0] !== '0') {
        button.prop('disabled', false);
    } else {
        button.prop('disabled', true);
    }
    $('#phone_number').val(phoneNumber);
}
$('#phone_number').on('input', function() {
    toggleButtonState();
});
function toggleButtonStateVerifyOtp() {
    var otpNumber = $('#otp').val();
    var button = $('#VerifyOtpbutton');
    otpNumber = otpNumber.replace(/[^0-9]/g, '').slice(0, 4);
    if (otpNumber.length === 4) {
        button.prop('disabled', false);
    } else {
        button.prop('disabled', true);
    }
    $('#otp').val(otpNumber);
}
$('#otp').on('input', function() {
    toggleButtonStateVerifyOtp();
});

$(document).ready(function() {
    $('#showPasswordToggle').click(function() {
        var passwordField = $('#password');
        var passwordFieldType = passwordField.attr('type');
        
        if (passwordFieldType === 'password') {
            passwordField.attr('type', 'text');
        } else {
            passwordField.attr('type', 'password');
        }
    });
    $('#showConfirmPasswordToggle').click(function() {
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
</body>
</html>