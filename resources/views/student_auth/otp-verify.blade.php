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
                                <form name="OtpVerifyForm" id="OtpVerifyForm" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="form-label">OTP <span class="text-danger">*</span></label>
                                            <div class="controls">
                                            <input type="text" name="otp" id="otp" value="" class="form-control"
                                                        placeholder="OTP">
                                            <p></p> 
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="button" id="getotpButton_login_with_tabs" onclick="StudentLogin_with_Opt_tabs()" value="" class="btn w-p100 mt-25 button-small" style="background-color: #00205c; color: #fff;">
                                        
                                        </div>
                                        </div>


                                        <div>
                                            <div id="credentials_do_not"></div>
                                            <p></p>
                                        </div>

                                        <div class="row">
                                      
                                        <div class="col-md-4 text-center">  
                                        </div>
                                             <input type="hidden" id="student_id" name="student_id" value="{{$student_id}}">
                                            <div class="col-md-4 text-center">
                                               <button type="submit" class="btn w-p100 mx-auto" style="background-color: #00205c; color: #fff;">Verify OTP</button>
                                            </div>
                                          <!-- <div class="col-md-4 text-center">
                                            <input type="button" id="getotpButton_login_with_tabs" onclick="StudentLogin_with_Opt_tabs()" value="" class="btn w-p100 mx-auto" style="background-color: #00205c; color: #fff;">
                                         </div> -->

                                    </div>
                                </form>
                                    <div class="row mt-5 text-center" >
                                        <div class="form-group col-md-12 ">
                                            <label class="form-label">
                                            <span class="text-bold">Don't have an account ? <a href="{{route('login-student')}}" style="color: #00205c; text-decoration: underline;">Register here</a></span></label>
                                            <br><label class="form-label">
                                                <a class="text-bold" href="{{route('login')}}" style="color: #00205c; text-decoration: underline;">I am a teacher</a></label>
                                        </div>
                                    </div>
                                   
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

$('#OtpVerifyForm').submit(function (event) {
   // alert(2323);
   var csrfToken = $('meta[name="_token"]').attr('content');
    event.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        url: '{{ route("otp-verify-login") }}',
        type: 'POST',
        processData: false,
        contentType: false,
        data: formData,
        success: function (response) {
            if (response['status'] == true) { 
                console.log(response,"response");
                var message = response['message'];
                $('#otp').siblings('p').removeClass('text-danger').addClass('text-success').html(response['message']);
                var studentid = response['student_id'];
                if (studentid) {
                        $.ajax({
                            url: '{{ route("student-login-verify-otp") }}',
                            type: 'post',
                            data: {
                                _token: csrfToken, 
                                student_id: studentid
                            },
                            success: function(response) {
                                if (response['status'] == true) {

                                    var student_session = response['student_session'];
                                    if(student_session){
                                        window.location.href="{{  route('access-student-denied') }}";
                                    }else{
                                        window.location.href = "{{ route('student.grade.list') }}";
                                    }

                                } else {

                                    var credentials_do_not = response['credentials_do_not'];
                                    if (credentials_do_not) {
                                        $('#credentials_do_not').siblings('p').addClass('text-danger').html(credentials_do_not);
                                    }

                                    var errors = response['errors'];
                                    $.each(errors, function(key, value) {
                                        $(`#${key}`).siblings('p').addClass('text-danger').html(value);
                                    });
                                }
                            },
                            error: function() {
                                console.log("Some things went wrong");
                            }
                        });
                    } 
                var access_code = response['access_code'];
                if (access_code) {
                    //$('#password').siblings('p').addClass('text-danger').html(access_code);
                    var studentid = response['student_id_accesscode_time'];
                    console.log(studentid,"studentid");
                    window.location.href = "{{ route('school.access.code', ['student' => '']) }}" + studentid;
                }


                //window.location.href="{{  route('student-login') }}";
            } else {
                var otpinvalid = response['queryfaild'];
                if(otpinvalid){
                   $('#otp').siblings('p').addClass('text-danger').html(otpinvalid);
                }
                var errors = response['errors'];
                console.log(errors,"errors");
                $.each(errors, function (key, value) {
                    var elementId = key.replace(/\./g, '_');
                    $('#' + elementId).next('p').addClass('text-danger').html(value[0]);
                });
            }
        },
        error: function () {
            console.log('Something went wrong');
        }
    });
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

</script>
<script>
    $(document).ready(function() {
        $('#otp').on('input', function() {
            $('#otp').siblings('p').removeClass('text-danger').html('');
        });
    });
</script>
<script>
    let timereset = {{ $timereset }};
    function updateButtonValue() {
        if (timereset > 0) {
            document.getElementById("getotpButton_login_with_tabs").disabled = true;
            document.getElementById("getotpButton_login_with_tabs").value = `Resend OTP (${timereset} seconds)`;
            timereset--; 
            setTimeout(updateButtonValue, 1000); 
        } else {
            document.getElementById("getotpButton_login_with_tabs").disabled = false;
            document.getElementById("getotpButton_login_with_tabs").value = "Resend OTP"; 
        }
    }
    updateButtonValue();


    function StudentLogin_with_Opt_tabs(){
        var csrfToken = $('meta[name="_token"]').attr('content');
			$.ajax({
			url: '{{ route("reset-otp") }}',
			type:'post',
            data: {
                _token: csrfToken, 
                student_id: $('#student_id').val(),
            },
			dataType: 'json',
			success: function(response){
				if(response['status'] == true){
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
                             
                        }
                        
                    }
                    updateButtonValue();
				}else{
                    var errors = response['errors'];
                }
			}, error: function(jqXHR, exception){
                    console.log("something went wrong");
            }
	     });		
}




</script>
</body>

</html>
