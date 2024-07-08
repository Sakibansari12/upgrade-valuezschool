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
                                <h2 class="fw-600" style="color: #00205c;">Student Login</h2>
                                <p class="mb-0 text-fade">Own your growth journey: <br> Be future-ready, Build your character!</p>
                            </div>
                                <!-- <div class="row">
                                    <div class="col-md-2 text-center">  
                                    </div>
                                        <div class="col-md-5 " style="width: 43.333333%">
                                            <button class="btn w-p85 mt-10 tablinks button-small" style="background-color: #00205c; color: #fff;"
                                                      onclick="openCity(event, 'sign_in')">Login With Username</button>
                                        </div>
                                        <div class=" text-center" style="width: 32.333333%">
                                             <button class="btn w-p85 mt-10 tablinks button-small" style="background-color: #00205c; color: #fff;"
                                                    onclick="openCity(event, 'login_with_otp')">Login With OTP</button>
                                        </div>
                                    <div class="col-md-2 text-center">  
                                    </div>
                                </div> -->
                    <!-- <div class="row mt-4 text-center">
                        <div class="col-md-12">
                            <button class="btn btn-block tablinks" style="background-color: #00205c; color: #fff;" onclick="openCity(event, 'sign_in')">Login With Username</button>
                            <button class="btn btn-block tablinks" style="background-color: #00205c; color: #fff;" onclick="openCity(event, 'login_with_otp')">Login With OTP</button>         
                        </div>
                    </div> -->
                            <div class="p-40 mb-5">
                                <form name="studentloginForm" id="studentloginForm" method="post">
                                    @csrf
                                    <input type="hidden" name="tabsvalue" id="tabsvalue" class="form-control">
                                <div id="sign_in" class="tabcontent">
                                    <div class="form-group">
                                        <label class="form-label">Username <span class="text-danger">*</span></label>
                                        <div class="controls">
                                        <input type="text" name="username" value="{{ isset($student_data->username) ? $student_data->username : ''}}" id="username" class="form-control"
                                            placeholder="Username">
                                        <p></p>
                                        </div>
                                        @if ($errors->has('username'))
                                            <div class="form-control-feedback">
                                                <small class="text-danger">{{ $errors->first('username') }}</small>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" style="display: flex; align-items: center;">
                                            Password <span class="text-danger">*</span>
                                            <span style="flex-grow: 1;"></span> 
                                            <a style="font-size: 12px;" href="{{ route('student-forgot') }}"  class="text-bold">Forgot password?</a>
                                        </label>

                                        
                                        <div class="controls">
                                            <div class="input-group" id="passwordsibling">
                                                <input type="password" id="password" name="password" value="{{ isset($student_data->view_password) ? $student_data->view_password : ''}}" class="form-control" placeholder="Password">
                                                
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
                                    <div class="row" id="directloginsignin">
                                        <div class="col-md-4 text-center">  
                                        </div>
                                            <div class="col-md-4 text-center">
                                              <button type="submit" class="btn w-p100 mt-10" style="background-color: #00205c; color: #fff;">Login</button>
                                            </div>
                                          <div class="col-md-4 text-center">
                                          </div>
                                    </div>
                                    <div class="divider-line-container" id="orlogin_with_second">
                                        <div class="divider-line-or"></div>
                                        <div class="divider-text divider-text-change">Or</div>
                                        <div class="divider-line-or"></div>
                                    </div>
                                </div>
                                <!-- Login with Otp start -->
                                <div id="login_with_otp" class="tabcontent">
                                    <!-- <div class="row">
                                        <div class="col-md-4 text-center">  
                                        </div>
                                            <div class="col-md-4 text-center">
                                            <input type="button" class="btn btn-primary w-p100 mt-10"
                                            onclick="showLoginfield()" value="Login With OTP" id="toggleLoginWithOtp">
                                        </div>
                                          <div class="col-md-4 text-center">
                                          </div>
                                    </div> -->
                                    <div class="row" id="phoneField">
                                        <div class="form-group col-md-6">
                                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                            <div class="controls" id="phone_number_not_exists_error">
                                                <input type="text" name="phone_number" id="phone_number" maxlength="10" value="" class="form-control" placeholder="Phone Number">
                                                <p></p>
                                            </div>
                                            <p></p>
                                            @if ($errors->has('phone'))
                                                <div class="form-control-feedback">
                                                    <small class="text-danger">{{ $errors->first('phone') }}</small>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-6 text-center mt-2">
                                            <input type="button" id="getotpButton" onclick="StudentLoginOpt()"  value="Get OTP" class="btn w-p100 mt-20 button-small" style="background-color: #00205c; color: #fff;">
                                            <input type="button" id="getotpButton_login_with_tabs" onclick="StudentLogin_with_Opt_tabs()"  value="Get OTP" class="btn w-p100 mt-20 button-small" style="background-color: #00205c; color: #fff;">
                                        </div>
                                    </div>
                                    <div class="row" id="otpField">
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




                                    <!-- <div class="row" id="access_code_field">
                                        <div class="form-group col-md-6">
                                            <label class="form-label">Access code<span class="text-danger">*</span></label>
                                            <div class="controls">
                                            <input type="text" name="access_code" id="access_code" value="" class="form-control"
                                                        placeholder="Access Code">
                                            <p></p> 
                                            </div>
                                        </div>
                                    </div> -->

                                    <div class="form-group" id="access_code_field">
                                        <label class="form-label">Enter School Access code <span class="text-danger">*</span></label>
                                        <div class="controls">
                                            <div class="input-group" id="access_codes">
                                                <input type="text" id="access_code" name="access_code" class="form-control" placeholder="Access code">
                                                &nbsp;&nbsp;&nbsp;<label class="form-label">
                                                <span class="text-bold">Don't have an access code ?  
                                                    <!-- <a href="{{ route('access.code') }}" class="text-bold">Click here</a> -->
                                                    <a href="{{ route('access.code', ['student_id' => '']) }}" class="text-bold" id="accessCodeLink">Click here</a>
                                                     </span></label>
                                            </div>
                                            <p></p>
                                        </div>
                                        @if ($errors->has('access_code'))
                                            <div class="form-control-feedback">
                                                <small class="text-danger">{{ $errors->first('access_code') }}</small>
                                            </div>
                                        @endif
                                    </div>
                                    <p></p>



                                    <!-- Second button Login Login -->
                                    <div class="row" id="schoolidchecksignin">
                                        <div class="col-md-4 text-center">  
                                        </div>
                                            <div class="col-md-4 text-center">
                                            <input type="button" onclick="CompleteLogin()" id="signinlogin" value="Login" class="btn w-p100 mx-auto" style="background-color: #00205c; color: #fff;">  
                                        </div>
                                          <div class="col-md-4 text-center">
                                          </div>
                                    </div>
                                    <div class="divider-line-container" id="orlogin_with_number">
                                        <div class="divider-line-or"></div>
                                        <div class="divider-text">Or</div>
                                        <div class="divider-line-or"></div>
                                    </div>
                                </div>
                                    
<!-- Login with Otp end -->
                                    
                                </form>
                                <div class="row mt-5 text-center">
                                    <div class="col-md-12">
                                        <button class="btn btn-block tablinks" id="Login_With_Username" style="background-color: #00205c; color: #fff;" onclick="openCity(event, 'sign_in')">Login with Username</button>
                                        <button class="btn btn-block tablinks" id="login_with_number" style="background-color: #00205c; color: #fff;" onclick="openCity(event, 'login_with_otp')">Login with OTP</button>         
                                    </div>
                               </div>
                                    <div class="row mt-5 text-center" >
                                        <div class="form-group col-md-12 ">
                                            <label class="form-label">
                                            <span class="text-bold">Don't have an account ? <a href="{{route('login-student')}}" style="color: #00205c; text-decoration: underline;">Register here</a></span></label>
                                            <br><label class="form-label">
                                                <a class="text-bold" href="{{route('login')}}" style="color: #00205c; text-decoration: underline;">I am a teacher</a></label>
                                        </div>
                                    </div>
                                    <!-- <div class="row  text-center" >
                                        <div class="form-group col-md-12 ">
                                            <label class="form-label">
                                                <a class="text-bold" href="{{route('login')}}" style="color: #00205c;">I am a teacher</a></label>
                                        </div>
                                    </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Vendor JS -->
    <script src="{{ asset('assets/src/js/vendors.min.js') }}"></script>
    <script src="{{ asset('assets/src/js/pages/chat-popup.js') }}"></script>
    <script src="{{ asset('assets/icons/feather-icons/feather.min.js') }}"></script>
   <script>
     
     $('#studentloginForm').submit(function(){
        //alert(23);
            event.preventDefault();
            var formArray = $(this).serializeArray();
           $.ajax({
             url: '{{ route("student.login") }}',
             type: 'post',
             data: formArray,
             dataType: 'json',
             success: function(response){
                if(response['status'] == true){
                    var student_session = response['student_session'];
                    if(student_session){
                        window.location.href="{{  route('access-student-denied') }}";
                    }else{
                        $('#passwordsibling').siblings('p').removeClass('text-danger').addClass('text-success').html(response['message']);
                        window.location.href="{{  route('student.grade.list') }}";
                    }
                    

                }else{
                    var errors = response['errors'];

                    var access_code = response['access_code'];
                   // var student_id_accesscode_time = response['student_id_accesscode_time'];

                    /* access_code school id does't exixts table */
                    if(access_code){
                        

                        var student_id_accesscode_time = response['student_id_accesscode_time'];
                        var url = "{{ route('access.code', ['student_id' => '']) }}" + student_id_accesscode_time;
                        document.getElementById('accessCodeLink').href = url;  

                        document.getElementById("username").readOnly  = true;
                        document.getElementById("password").readOnly  = true;

                        var access_code_field = $("#access_code_field");
                                              access_code_field.show();
                        var schoolidchecksignin = $("#schoolidchecksignin");
                                         schoolidchecksignin.show();
                        var orlogin_with_number = $("#orlogin_with_number");
                                         orlogin_with_number.show();

                        var orlogin_with_second = $("#orlogin_with_second");
                                         orlogin_with_second.toggle();

                        var directloginsignin = $("#directloginsignin");
                                         directloginsignin.toggle();
                        $('#passwordsibling').siblings('p').addClass('text-danger').html(access_code);
                     }else{
                        $('#passwordsibling').siblings('p').removeClass('text-danger').html('');
                     }
                   // var phonenumber_invalid = response['phonenumber_invalid'];

                   /* jab phone number nhi hai */
                    var phonenumber_null = response['phonenumber_null'];
                     if(phonenumber_null){
                        var otpField = $("#otpField");
                                        otpField.show();
                        var phoneField = $("#phoneField");
                                         phoneField.show();
                        var directloginsignin = $("#directloginsignin");
                                         directloginsignin.toggle();
                        var schoolidchecksignin = $("#schoolidchecksignin");
                                         schoolidchecksignin.show();                 
                        document.getElementById("otp").readOnly  = true;
                        document.getElementById("VerifyOtpbutton").disabled = true;                 
                        document.getElementById("signinlogin").disabled = true;                 
                       // console.log(phonenumber_null,'phonenumber_null');
                        $('#passwordsibling').siblings('p').addClass('text-danger').html(phonenumber_null);

                        var login_with_number = $("#login_with_number");
                            login_with_number.toggle();
                        var dividerTextElement = document.querySelector('.divider-text-change');
                        if (dividerTextElement.textContent.trim() === 'Or') {
                            dividerTextElement.textContent = 'Verify your phone number to continue';
                        }
                     }else{
                        $('#passwordsibling').siblings('p').removeClass('text-danger').html('');
                     } 

                     
                     /* jab phone hai or verifed nhi hai */
                     
                     var otp_verify_at_null = response['otp_verify_at_null'];
                     if(otp_verify_at_null){
                        var studentdata = response['studentdata'];
                        $('#phone_number').val(studentdata.phone_number);
                        var otpField = $("#otpField");
                                        otpField.show();
                        var phoneField = $("#phoneField");
                                         phoneField.show();
                        var directloginsignin = $("#directloginsignin");
                                         directloginsignin.toggle();
                        var schoolidchecksignin = $("#schoolidchecksignin");
                                         schoolidchecksignin.show();                 
                        document.getElementById("otp").readOnly  = true;
                        document.getElementById("VerifyOtpbutton").disabled = true;                 
                        document.getElementById("signinlogin").disabled = true;     

                        //$('#passwordsibling').siblings('p').addClass('text-danger').html(otp_verify_at_null);
                     }else{
                        $('#passwordsibling').siblings('p').removeClass('text-danger').html('');
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

/* GET OTP */
function StudentLoginOpt(){
        var csrfToken = $('meta[name="_token"]').attr('content');
			$.ajax({
			url: '{{ route("student-login-otp") }}',
			type:'post',
            data: {
                _token: csrfToken, 
                phone_number: $('#phone_number').val(),
                password: $('#password').val(),
                username: $('#username').val(),
            },
			dataType: 'json',
			success: function(response){
                //console.log(response,"response");
				if(response['status'] == true){
                    $('#phone_number').siblings('p').removeClass('text-danger').html('');
                    $('#phone_number').siblings('p').addClass('text-success').html(response['message']);
                        let timeresetotpupdate =  response['timeResetOtpUpdatetime'];
                    function updateButtonValue() {
                        if (timeresetotpupdate > 0) {
                            console.log(timeresetotpupdate,"timeresetotpupdate");
                            document.getElementById("getotpButton").disabled = true;
                            document.getElementById("getotpButton").value = `Resend OTP (${timeresetotpupdate} seconds)`;
                            timeresetotpupdate--;
                            setTimeout(updateButtonValue, 1000); 
                        } else {
                            document.getElementById("getotpButton").disabled = false;
                            document.getElementById("getotpButton").value = "Resend OTP";
                            $('#phone_number').siblings('p').removeClass('text-success').html(''); 
                        }
                        
                    }
                    updateButtonValue();
                    document.getElementById("otp").readOnly  = false;
                    document.getElementById("VerifyOtpbutton").disabled = false;
                    /* var otpField = $("#otpField");
                       otpField.show(); */
                    /* document.getElementById("getotpButton").disabled = true;
                    document.getElementById("name").readOnly  = true;
                    document.getElementById("phone_number").readOnly  = true;
                    document.getElementById("VerifyOtpbutton").disabled = false; */
                     
				}else{
                    var errors = response['errors'];
                    let username_password__not_exists = response['username_password__not_exists'];
                    
                    if(username_password__not_exists){
                        $('#phone_number').siblings('p').removeClass('text-danger').html('');
                      $('#phone_number_not_exists_error').siblings('p').addClass('text-danger').html(username_password__not_exists);
                    }else{
                        $('#phone_number_not_exists_error').siblings('p').removeClass('text-danger').html('');
                    }
                    let phone_number_all_exists = response['phone_number_all_exists'];
                    if(phone_number_all_exists){
                        $('#phone_number_not_exists_error').siblings('p').removeClass('text-danger').html('');
                      $('#phone_number').siblings('p').addClass('text-danger').html(phone_number_all_exists);
                    }else{
                        $('#phone_number').siblings('p').removeClass('text-danger').html('');
                    }
                    if(errors['phone_number'] && errors['phone_number']){
                        $('#phone_number_not_exists_error').siblings('p').removeClass('text-danger').html('');
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
                }
			}, error: function(jqXHR, exception){
                    console.log("something went wrong");
            }
	     });		
}

/* Login with otp tabs ke liye  */

function StudentLogin_with_Opt_tabs(){
        var csrfToken = $('meta[name="_token"]').attr('content');
			$.ajax({
			url: '{{ route("student-login-with-otp") }}',
			type:'post',
            data: {
                _token: csrfToken, 
                phone_number: $('#phone_number').val(),
               // password: $('#password').val(),
               // username: $('#username').val(),
            },
			dataType: 'json',
			success: function(response){
                //console.log(response,"response");
				if(response['status'] == true){
                    $('#phone_number').siblings('p').removeClass('text-danger').html('');
                    $('#phone_number_not_exists_error').siblings('p').removeClass('text-danger').html('');
                    $('#phone_number').siblings('p').addClass('text-success').html(response['message']);
                        let timeresetotpupdate =  response['timeResetOtpUpdatetime'];
                    function updateButtonValue() {
                        if (timeresetotpupdate > 0) {
                            console.log(timeresetotpupdate,"timeresetotpupdate");
                            document.getElementById("getotpButton_login_with_tabs").disabled = true;
                            document.getElementById("getotpButton_login_with_tabs").value = `Resend OTP (${timeresetotpupdate} seconds)`;
                            timeresetotpupdate--;
                            setTimeout(updateButtonValue, 1000); 
                        } else {
                            document.getElementById("getotpButton_login_with_tabs").disabled = false;
                            document.getElementById("getotpButton_login_with_tabs").value = "Resend OTP";
                            $('#phone_number').siblings('p').removeClass('text-success').html(''); 
                        }
                        
                    }
                    updateButtonValue();
                    document.getElementById("otp").readOnly  = false;
                    document.getElementById("VerifyOtpbutton").disabled = false;
                    /* var otpField = $("#otpField");
                       otpField.show(); */
                    /* document.getElementById("getotpButton").disabled = true;
                    document.getElementById("name").readOnly  = true;
                    document.getElementById("phone_number").readOnly  = true;
                    document.getElementById("VerifyOtpbutton").disabled = false; */
                     
				}else{
                    var errors = response['errors'];
                    let phone_number_not_exists = response['phone_number_not_exists'];
                    if(phone_number_not_exists){
                        //$('#phone_number').siblings('p').removeClass('text-danger').html(phone_number_not_exists);
                      $('#phone_number_not_exists_error').siblings('p').addClass('text-danger').html(phone_number_not_exists);
                    }else{
                        $('#phone_number_not_exists_error').siblings('p').removeClass('text-danger').html('');
                    }
                    
                    if(errors['phone_number'] && errors['phone_number']){
                        $('#phone_number_not_exists_error').siblings('p').removeClass('text-danger').html('');
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











/* OTP Verify */
function VerifyOtp(){
       // alert(223);
    //var csrfToken = $('meta[nVerifyOtpame="_token"]').attr('content');
    var csrfToken = $('meta[name="_token"]').attr('content');
    $.ajax({
      url: '{{ route("student-verify-otp") }}',
      type:'post',
      data: {
        _token: csrfToken, 
        otp: $('#otp').val(),
        phone_number: $('#phone_number').val(),
         
      },
      dataType: 'json',
      success: function(response){
         if(response['status'] == true){
            $('#otp').siblings('p').removeClass('text-danger').addClass('text-success').html(response['message']);
            document.getElementById("getotpButton").disabled = true;
           
            if(response['access_code']){
                document.getElementById("signinlogin").disabled = false;
                var access_code_field = $("#access_code_field");
                             access_code_field.show();
                var student_id_accesscode_time = response['student_id_accesscode_time'];
                if(student_id_accesscode_time){
                 var url = "{{ route('access.code', ['student_id' => '']) }}" + student_id_accesscode_time;
                 document.getElementById('accessCodeLink').href = url;
                }
                



            }else{
                //console.log('sakibsakibsakibsakib');
               // window.location.href="{{  route('student-login') }}";
                //window.location.href="{{  route('student.grade.list') }}";
                //document.getElementById("signinlogin").disabled = false;
               var otpdata = response['otpdata'];
                $.ajax({
                url: '{{ route("student.login") }}',
                type: 'post',
                data: {
                    _token: csrfToken, 
                    password: otpdata['password'],
                    username: otpdata['username'], 
                },
                success: function(response){
                    if(response['status'] == true){
                        var student_session = response['student_session'];
                        if(student_session){
                            window.location.href="{{  route('access-student-denied') }}";
                        }else{
                            
                            window.location.href="{{  route('student.grade.list') }}";
                        }

                    }else{
                        var errors = response['errors'];
                        $.each(errors, function(key,value){
                            $(`#${key}`).siblings('p').addClass('text-danger').html(value);
                        })
                    }
                },
                error: function(){
                    console.log("Some things went wrong");
                }
            });



                
            }
           
         }else{
             var errors = response['errors'];
             var otpinvalid = response['queryfaild'];
             if(otpinvalid){
                $('#otp').siblings('p').addClass('text-danger').html(otpinvalid);
             }
             if(errors['otp']){
                    $('#otp').siblings('p').addClass('text-danger').html(errors['otp']);
                }
            }
         
      }, error: function(jqXHR, exception){
         console.log("something went wrong");
      }
    });
 
}


/* Complete Access Code Login */
function CompleteLogin(){
       // alert(223);
    //var csrfToken = $('meta[nVerifyOtpame="_token"]').attr('content');
    var csrfToken = $('meta[name="_token"]').attr('content');
    $.ajax({
      url: '{{ route("access-code-login") }}',
      type:'post',
      data: {
        _token: csrfToken, 
        otp: $('#otp').val(),
        phone_number: $('#phone_number').val(),
        password: $('#password').val(),
        username: $('#username').val(),
        access_codes: $('#access_code').val(),
         
      },
      dataType: 'json',
      success: function(response){
         if(response['status'] == true){
            $('#access_code').siblings('p').removeClass('text-danger').addClass('text-success').html(response['message']);
           // window.location.href="{{  route('student-login') }}";
          // console.log("hello sakib");
          var student_data = response['otpdata'];
          if (student_data) {
           $.ajax({
             url: '{{ route("student.login") }}',
             type: 'post',
             data: {
                _token: csrfToken, 
                password: student_data.view_password,
                username: student_data.username, 
            },
             success: function(response){
                if(response['status'] == true){
                    var student_session = response['student_session'];
                    if(student_session){
                        window.location.href="{{  route('access-student-denied') }}";
                    }else{
                        
                        window.location.href="{{  route('student.grade.list') }}";
                    }
                }else{
                    var errors = response['errors'];
                     $.each(errors, function(key,value){
                        $(`#${key}`).siblings('p').addClass('text-danger').html(value);
                    })
                }
             },
             error: function(){
                console.log("Some things went wrong");
             }
           });
        }
           //  window.location.href="{{  route('student.grade.list') }}";
         }else{
            var errors = response['errors'];
            var student_licence = response['student_licence'];
            if(student_licence == 'error'){
                $('#access_code_field').siblings('p').addClass('text-danger').html('Maximum Student licences limit reached.');
            }
             $.each(errors, function(key,value){
             $(`#${key}`).siblings('p').addClass('text-danger').html(value);
          })
         
      }
    },
      error: function(jqXHR, exception){
         console.log("something went wrong");
      }
    
    });
 
}





   </script>
<script>
   /* const  loginbuttonvalue = $('#LoginWithOtp').val();
   console.log(loginbuttonvalue,"hello"); */
   var phoneField = $("#phoneField");
        phoneField.toggle();
    var getotpButton_login_with_tabs = $("#getotpButton_login_with_tabs");
        getotpButton_login_with_tabs.toggle();
        
    var otpField = $("#otpField");
    otpField.toggle();
    var schoolidchecksignin = $("#schoolidchecksignin");
    schoolidchecksignin.toggle();
    var orlogin_with_number = $("#orlogin_with_number");
    orlogin_with_number.toggle();

    var Login_With_Username = $("#Login_With_Username");
    Login_With_Username.toggle();


    var access_code_field = $("#access_code_field");
                 access_code_field.toggle();
    
     /* function showLoginfield(){
        var phoneField = $("#phoneField");
            phoneField.show();
     } */



     function toggleButtonState() {
        var phoneNumber = $('#phone_number').val();
        var button = $('#getotpButton');
        
        phoneNumber = phoneNumber.replace(/[^0-9]/g, '').slice(0, 10);
            if(phoneNumber[0] == '0'){
                $('#phone_number').siblings('p').addClass('text-danger').html("Phone number can't start with 0.");
            }else{
                $('#phone_number').siblings('p').addClass('text-danger').html("");
            }

        if (phoneNumber.length == 10 && phoneNumber[0] !== '0') {
            button.prop('disabled', false);
        } else {
            button.prop('disabled', true);
        }
        
    //phoneNumber = phoneNumber.slice(0, 10);
    $('#phone_number').val(phoneNumber);
    }
    
    // Attach input event listener to the phone number input
    $('#phone_number').on('input', function() {
        toggleButtonState();
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
});

/* tabs js */
function openCity(evt, cityName) {
   // console.log(evt,"evt");
    console.log(cityName,"cityName");
    if(cityName == 'login_with_otp'){
        var phoneField = $("#phoneField");
            phoneField.show();
        var otpField = $("#otpField");
            otpField.show();
        
        var getotpButton_login_with_tabs = $("#getotpButton_login_with_tabs");
            getotpButton_login_with_tabs.show();
            var getotpButton = $("#getotpButton");
            getotpButton.toggle();
        
        var schoolidchecksignin = $("#schoolidchecksignin");
            schoolidchecksignin.show();
            var orlogin_with_number = $("#orlogin_with_number");
            orlogin_with_number.show();                     
        document.getElementById("otp").readOnly  = true;
        document.getElementById("VerifyOtpbutton").disabled = true;                 
        document.getElementById("signinlogin").disabled = true;

        var Login_With_Username = $("#Login_With_Username");
            Login_With_Username.show();
        var login_with_number = $("#login_with_number");
            login_with_number.toggle();    
    }
    if(cityName == 'sign_in'){
        var getotpButton = $("#getotpButton");
            getotpButton.toggle();
        var Login_With_Username = $("#Login_With_Username");
            Login_With_Username.toggle();
        var login_with_number = $("#login_with_number");
            login_with_number.show();

    }
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}

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

</script>

</body>

</html>
