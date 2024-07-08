<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../images/favicon.ico">
    <meta name="_token" content="{{ csrf_token() }}" />

    <title>Valuez School - Log in </title>

    <!-- Vendors Style-->
    <link rel="stylesheet" href="{{ asset('assets/src/css/vendors_css.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Style-->
    <link rel="stylesheet" href="{{ asset('assets/src/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/src/css/skin_color.css') }}">
    <style>


/* .steps {
  display: flex;
  margin-left: 81px;
}

.step {
  width: 20px;
  height: 20px;
  border-radius: 50%;
  background-color: #ccc;
  margin-right: 75px;
}

.step.active {
  background-color: #00205c;
  
}
 .step.active .horizontal-line {
  height: 2px; 
  width: 90px; 
  background-color: #00205c; 
  margin: auto; 
  margin-top: 9px;
  margin-left: 20px;
} 
.step .horizontal-line {
  height: 2px; 
  width: 90px; 
  background-color: #ccc; 
  margin: auto; 
  margin-top: 9px;
  margin-left: 20px;
}
.content-top-agile {
    text-align: center;
} */
/* Styles for labels */


/* Styles for steps */
.steps {
    display: flex;
    justify-content: center; /* Center align the steps */
}
.steps-text {
    display: flex;
    justify-content: center; /* Center align the steps */
    margin-right: 74px
}

.step {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background-color: #ccc;
    margin: 0 73px;
    margin-top: -15px;
}

.step-text {
    margin: 0 25px;
    margin-bottom: -32px;
    color: #ccc;
}
.step-text.active {
    margin: 0 25px;
    margin-bottom: -32px;
    color: #00205c;
}

.step.active {
    background-color: #00205c;
}

/* Styles for horizontal lines */
.step .horizontal-line {
    height: 2px;
    width: 167px;
    background-color: #ccc;
    margin: auto;
    margin-top: 9px;
}
.step.active .horizontal-line {
    height: 2px;
    width: 167px;
    background-color: #00205c;
    margin: auto;
    margin-top: 9px;
}
.step.active .horizontal-line {
    background-color: #00205c;
}


    </style>
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
                                <h2 class="fw-600" style="color: #00205c;">Student Registration</h2>
                                <p class="mb-0 text-fade">Access your growth journey: <br> Be future-ready, build your character.</p> 
                            </div>
                            <div class="p-20">
                                    <div class="steps-text " style="width:100%;">
                                        <div class="step-text" id="step-text1">
                                           <p class="register-login">1. Personal info</p>
                                        </div>
                                        <div class="step-text" id="step-text2">
                                           <p class="register-login">2. Set password</p>
                                        </div>
                                        <div class="step-text" id="step-text3">
                                        <p class="register-login">3. Enter school code</p>
                                        </div>
                                       
                                </div>
                            </div>
                            <div class="p-20">
                                    <div class="steps" style="width:100%;">
                                        <div class="step" id="step1">
                                                <div class="col-1 text-center">
                                                    <div class="horizontal-line"></div>
                                                </div>
                                        </div>
                                        <div class="step" id="step2">
                                                <div class="col-1 text-center">
                                                    <div class="horizontal-line"></div>
                                                </div>
                                        </div>
                                        <div class="step" id="step3">
                                                <div class="col-1 text-center">
                                                </div>
                                        </div>
                                </div>
                            </div>
                        <div class="p-40"> 
                                <form name="studentForm" id="studentForm" method="post">
                                    @csrf
                                    <div class="form-group" id="name_hide">
                                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                                        <div class="controls">
                                            <input type="text" name="name" id="name" class="form-control"
                                                placeholder="First Name" value="">
                                                <p></p>
                                        </div>

                                        @if ($errors->has('name'))
                                            <div class="form-control-feedback">
                                                <small class="text-danger">{{ $errors->first('name') }}</small>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group" id="last_name_hide">
                                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                        <div class="controls">
                                            <input type="text" name="last_name" id="last_name" class="form-control"
                                                placeholder="Last Name" value="">
                                                <p></p>
                                        </div>

                                        @if ($errors->has('last_name'))
                                            <div class="form-control-feedback">
                                                <small class="text-danger">{{ $errors->first('last_name') }}</small>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="row" id="phone_number_hide">
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                        <div class="controls" id="updatetimesucessmessage">
                                            <input type="text" name="phone_number" maxlength="10" id="phone_number" 
                                             class="form-control" placeholder="Phone Number">
                                            <p></p>
                                        </div>
                                        <p></p>
                                        <!-- @if ($errors->has('phone'))
                                            <div class="form-control-feedback">
                                                <small class="text-danger">{{ $errors->first('phone') }}</small>
                                            </div>
                                        @endif -->
                                    </div>
                                        <div class="col-md-6  mt-2">
                                            <input type="button" id="getotpButton" onclick="StudentOpt()"  value="Get OTP" class="btn w-p100 mt-20 button-small" style="background-color: #00205c; color: #fff;">
                                        </div>
                                    </div>
                                    <div class="row" id="otp_hide">
                                        <div class="form-group col-md-6">
                                            <label class="form-label">OTP <span class="text-danger">*</span></label>
                                            <div class="controls">
                                            <input type="text" name="otp" id="otp" value="" class="form-control"
                                                        placeholder="OTP">
                                            <p></p> 
                                            </div>
                                            @if ($errors->has('otp'))
                                                <div class="form-control-feedback">
                                                    <small class="text-danger">{{ $errors->first('otp') }}</small>
                                                </div>
                                            @endif
                                        </div>
                                            <div class="col-md-6 text-center mt-2">
                                                <input type="button" onclick="VerifyOtp()" id="VerifyOtpbutton"  value="Verify OTP" class="btn w-p100 mt-20 button-small" style="background-color: #00205c; color: #fff;">
                                                
                                            </div>
                                    </div>
                                     <p></p>
                                    <div class="form-group" id="username_hide">
                                        <label class="form-label">Username <span class="text-danger">*
                                        </span></label>
                                        
                                        <div class="controls">
                    
                                <!-- <div class="alert alert-success mt-2">{{Session::get('successOpt')}}</div> -->
                                        <input type="text" name="username" id="username" readonly  class="form-control"
                                                        placeholder="Username">
                                                        <p></p>
                                                
                                        </div>
                                        @if ($errors->has('username'))
                                            <div class="form-control-feedback">
                                                <small class="text-danger">{{ $errors->first('username') }}</small>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group" id="password_hide">
                                        <label class="form-label">Set Password <span class="text-danger">*</span></label>
                                        <div class="controls">
                                            <div class="input-group" id="passwordsibling">
                                                <input type="password" id="password" name="password" class="form-control" placeholder="Set Password">
                                                
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

                                    <!-- <div class="form-group">
                                        <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                        <div class="controls">
                                            <input type="hidden" id="mobile" name="mobile">
                                            <input type="password" id="confirm_password" name="confirm_password" class="form-control"
                                                placeholder="Confirm Password">
                                                <p></p>
                                        </div>
                                        @if ($errors->has('password'))
                                            <div class="form-control-feedback">
                                                <small class="text-danger">{{ $errors->first('password') }}</small>
                                            </div>
                                        @endif
                                    </div> -->
                                    <div class="form-group" id="confirm_password_hide">
                                        <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                        <div class="controls" id="confirm_password_dontmatch_error">
                                            <div class="input-group" id="confirmpasswordsibling">
                                            <input type="hidden" id="mobile" name="mobile">
                                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm Password">
                                                
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="showConfirmPasswordToggle"><i class="fa fa-eye" aria-hidden="true"></i>
</span>
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
                                    <div class="form-group" id="grade_hide">
                                        <label class="form-label">Grade<span class="text-danger">*</span></label>
                                        <select class="form-control select2" name="grade" style="width: 100%;"
                                             id="grade">
                                             <option value="">Select a Grade</option>
                                            @if($program_list->isNotEmpty())
                                            @foreach ($program_list as $prog)
                                                <option value="{{ $prog->id }}">
                                                    {{ $prog->class_name }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <p></p>
                                        @error('grade')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                    </div>
                                    <div class="row" id="submit_hide">
                                        <div class="col-12 text-center mt-2">
                                            <button type="submit" id="submitButton" class="btn w-p100 mt-10" style="background-color: #00205c; color: #fff;">Submit</button>
                                        </div>
                                    </div>
                                    
                                    
                                    <!-- <div class="form-group">
                                        <label class="form-label">Password <span class="text-danger">*</span></label>
                                        <div class="controls">
                                            <input type="password" id="password" name="password" class="form-control"
                                                placeholder="Password">
                                                <p></p>
                                                <div class="input-group-append">
                                                   <span class="input-group-text" id="showPasswordToggle"><i class="fa fa-eye"></i></span>
                                                </div>
                                            
                                        </div>
                                        @if ($errors->has('password'))
                                            <div class="form-control-feedback">
                                                <small class="text-danger">{{ $errors->first('password') }}</small>
                                            </div>
                                        @endif

                                    </div> -->
                                    
                                </form>
                               </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <!-- Vendor JS -->
    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="{{ asset('assets/src/js/vendors.min.js') }}"></script>
    <script src="{{ asset('assets/src/js/pages/chat-popup.js') }}"></script>
    <script src="{{ asset('assets/icons/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.js') }}"></script>
    <!-- <script>
        $("input").ke5454q  ypress(function(){
   if ( $(this).val().length > 1){
    $(this).addClass('active');
    }
  });

    </script> -->
<script>
    
    document.getElementById("VerifyOtpbutton").disabled = true;
    document.getElementById("otp").readOnly = true;
    document.getElementById("submitButton").disabled = true;
    var username_hide = $("#username_hide");
                username_hide.toggle();
    var password_hide = $("#password_hide");
                password_hide.toggle();
    var confirm_password_hide = $("#confirm_password_hide");
                confirm_password_hide.toggle();
    var grade_hide = $("#grade_hide");
                grade_hide.toggle();
    var submit_hide = $("#submit_hide");
                submit_hide.toggle();

</script>
<script>
	function StudentOpt(){
      //  console.log("hello");
        var csrfToken = $('meta[name="_token"]').attr('content');
			$.ajax({
			url: '{{ route("student-otp") }}',
			type:'post',
            data: {
                _token: csrfToken, 
                name: $('#name').val(), 
                phone_number: $('#phone_number').val(),
                last_name: $('#last_name').val(),
            },
			dataType: 'json',
			success: function(response){
                //console.log(response,"response");
				if(response['status'] == true){
                    if(response['message']){
                        $('#phone_number').siblings('p').removeClass('text-danger').addClass('text-success').html(response['message']);
                       // var condition = true; 
                        
                    }
                    let successmessage = response['message_update'];
                    console.log(successmessage,"successmessage");
                    if(successmessage){
                        let timeresetotpupdate =  response['timeResetOtpUpdatetime'];
                        $('#updatetimesucessmessage').siblings('p').addClass('text-success').html(successmessage);
                        function updateButtonValue() {
                        if (timeresetotpupdate > 0) {
                            document.getElementById("getotpButton").disabled = true;
                            document.getElementById("getotpButton").value = `Resend OTP (${timeresetotpupdate} seconds)`;
                            timeresetotpupdate--;
                            setTimeout(updateButtonValue, 1000); // Call the function again after 1 second
                        } else {
                            document.getElementById("getotpButton").disabled = false;
                            document.getElementById("getotpButton").value = "Resend OTP"; 
                            $('#updatetimesucessmessage').siblings('p').removeClass('text-success').html('');
                        }
                    }
                    updateButtonValue();
                    }
                    $('#name').siblings('p').addClass('text-danger').html('');
                    $('#last_name').siblings('p').addClass('text-danger').html('');
                    
                    let timeresetotp =  response['timeResetOtp'];
                    console.log(timeresetotp,"timeresetotp");
                    /* console.log(timeresetotp,"timeresetotp");
                    if(timeresetotp){
                      document.getElementById("getotpButton").value = `Resend OTP (${timeresetotp})`;
                    } */
                    if(response['student_allexixts']){
                        var loginRoute = "{{ route('student-login') }}";
                        var html = `${response['student_allexixts']} <a href="${loginRoute}" class=" btn-blue"  style="text-decoration: underline;">here</a>`;
                        $("#phone_number").siblings('p').addClass('text-success').append(html);
                     // $('#phone_number').siblings('p').addClass('text-success').html(response['student_allexixts']);
                     // window.location.href="{{  route('student-login') }}";
                    }else{
                        function updateButtonValue() {
                        if (timeresetotp > 0) {
                            document.getElementById("getotpButton").disabled = true;
                            document.getElementById("getotpButton").value = `Resend OTP (${timeresetotp} seconds)`;
                            timeresetotp--;
                            setTimeout(updateButtonValue, 1000); // Call the function again after 1 second
                        } else {
                            document.getElementById("getotpButton").disabled = false;
                            document.getElementById("getotpButton").value = "Resend OTP"; 
                            $('#phone_number').siblings('p').removeClass('text-success').html('');
                        }
                    }
                    updateButtonValue();
                    }
                    //document.getElementById("getotpButton").disabled = true;
                   // document.getElementById("name").readOnly  = true;
                    document.getElementById("otp").readOnly  = false;
                   // document.getElementById("VerifyOtpbutton").disabled = false;
                     
				}else{
                    var errors = response['errors'];
                    if(errors['name']){
                        $('#name').siblings('p').addClass('text-danger').html(errors['name']);
                    }else{
                        $('#name').siblings('p').removeClass('text-danger').addClass('text-success').html('');
                    }
                    if(errors['last_name']){
                      $('#last_name').siblings('p').addClass('text-danger').html(errors['last_name']);
                    }else{
                        $('#last_name').siblings('p').removeClass('text-danger').addClass('text-success').html('');
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
  </script>

<!-- otp verify -->
<script>
    function VerifyOtp(){
       // alert(223);
    var csrfToken = $('meta[name="_token"]').attr('content');
    $.ajax({
      url: '{{ route("verify-otp") }}',
      type:'post',
      data: {
        _token: csrfToken, 
        otp: $('#otp').val(), 
        phone_number: $('#phone_number').val(),
        name: $('#name').val(), 
        last_name: $('#last_name').val(), 
      },
      dataType: 'json',
      success: function(response){
         if(response['status'] == true){
            $('#otp_hide').siblings('p').removeClass('text-danger').addClass('text-success').html(response['message']);
           // var condition = true; 
            $('#step1').addClass('active');
            $('#step-text1').addClass('active');
             const phonenumber = response['otpdata'];
             //const usernametextstatic = 'Auto-genarate, change later from "My Pro file"';
             $('#username').val(phonenumber.username); 
             $('#mobile').val(phonenumber.phone_number); 
             document.getElementById("VerifyOtpbutton").disabled = true;
             document.getElementById("otp").readOnly  = true;
             document.getElementById("getotpButton").disabled = true;
             /* Hide */
             var name_hide = $("#name_hide");
                name_hide.toggle();
            var last_name_hide = $("#last_name_hide");
                        last_name_hide.toggle();
            var phone_number_hide = $("#phone_number_hide");
                        phone_number_hide.toggle();
            var otp_hide = $("#otp_hide");
                        otp_hide.toggle();
            

             /* Show  */
             var username_hide = $("#username_hide");
                username_hide.show();
            var password_hide = $("#password_hide");
                        password_hide.show();
            var confirm_password_hide = $("#confirm_password_hide");
                        confirm_password_hide.show();
            var grade_hide = $("#grade_hide");
                        grade_hide.show();
            var submit_hide = $("#submit_hide");
                        submit_hide.show();
             document.getElementById("submitButton").disabled = false;
             $('#otp').siblings('p').addClass('text-danger').html('');
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
            }
         
      }, error: function(jqXHR, exception){
         console.log("something went wrong");
      }
    });
 
}
</script>
    
<script>
    $('#studentForm').submit(function(){
            event.preventDefault();
            var formArray = $(this).serializeArray();
           $.ajax({
             url: '{{ route("login-students") }}',
             type: 'post',
             data: formArray,
             dataType: 'json',
             success: function(response){
                if(response['status'] == true){
                    $('#studentForm')[0].reset();
                    $('#otp_hide').siblings('p').removeClass('text-danger').removeClass('text-success').html('');
                    var register_student_id = response['register_student_id'];
                    console.log(register_student_id,"register_student_id");
                    if(register_student_id){
                        /* var condition = true; 
                        var progressBar = document.getElementById('progressBar');
                        var width = condition ? '90%' : '0%'; 
                        progressBar.style.width = width; */
                        $('#step2').addClass('active');
                        $('#step-text2').addClass('active');
                        window.location.href = "{{ route('student-login-access-code', ['student_id' => '']) }}".replace('student_id=', 'student_id=' + register_student_id);
                    }/* else{
                        window.location.href="{{  route('student-login') }}";
                    } */
                    


                }else{
                    var errors = response['errors'];
                    var phonenumber = response['phonenumber'];
                    var conform_password = response['conform_password'];
                   // console.log(conform_password,"conform_password");
                    if(phonenumber){
                        $('#phone_number').siblings('p').addClass('text-danger').html(phonenumber);
                     }else{
                        $('#phone_number').siblings('p').removeClass('text-danger').html('');
                     }
                     if(conform_password){
                        $('#confirm_password_dontmatch_error').siblings('p').removeClass('text-danger').html('');
                        $('#confirmpasswordsibling').siblings('p').removeClass('text-danger').html('');
                        $('#confirm_password_dontmatch_error').siblings('p').addClass('text-danger').html(conform_password);
                     }else{
                        $('#confirm_password_dontmatch_error').siblings('p').removeClass('text-danger').html('');
                     }

                     if(errors['confirm_password']){
                        $('#confirm_password_dontmatch_error').siblings('p').removeClass('text-danger').html('');
                        $('#confirmpasswordsibling').siblings('p').addClass('text-danger').html(errors['confirm_password']);
                     }else{
                        $('#confirmpasswordsibling').siblings('p').removeClass('text-danger').html('');
                     }
                     if(errors['grade']){
                      $('#grade').siblings('p').addClass('text-danger').html(errors['grade']);
                    }else{
                        $('#grade').siblings('p').removeClass('text-danger').html('');
                    }

                     if(errors['password']){
                        $('#passwordsibling').siblings('p').addClass('text-danger').html(errors['password']);
                     }else{
                        $('#passwordsibling').siblings('p').removeClass('text-danger').html('');
                     }

                     $.each(errors, function(key,value){
                        $(`#${key}`).siblings('p').addClass('text-danger').html(value);
                    })
                }
             },
             error: function(){
                console.log("Some things went wrong");
             }
           });
        });
</script>
<script>
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
