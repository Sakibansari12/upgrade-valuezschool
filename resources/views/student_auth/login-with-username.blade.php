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
                            <div class="p-40 mb-5">
                                <form name="studentloginForm" id="studentloginForm" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label class="form-label">Username <span class="text-danger">*</span></label>
                                        <input type="text" name="username" id="username" class="form-control" placeholder="Username">
                                        <p></p>
                                    </div>
                                    <p></p>
                                    <div class="form-group" id="password">
                                        <label class="form-label" style="display: flex; align-items: center;">
                                            Password <span class="text-danger">*</span>
                                            <span style="flex-grow: 1;"></span> 
                                            <a style="font-size: 12px;" href="{{ route('student-forgot') }}"  class="text-bold">Forgot password?</a>
                                        </label>
                                        <div class="input-group">
                                            <input type="password" name="password" id="password_show_hide" class="form-control" placeholder="Password">
                                           
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="showPasswordToggle"><i class="fa fa-eye"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <p></p>
                               <div>
                                <div id="credentials_do_not"></div>
                                  <p></p>
                               </div>
                               
                                    <div class="row">
                                        <div class="col-md-4 text-center">  
                                        </div>
                                            <div class="col-md-4 text-center">
                                               <button type="submit" class="btn w-p100 mx-auto" style="background-color: #00205c; color: #fff;">Login</button>
                                            </div>
                                          <div class="col-md-4 text-center">
                                          </div>
                                    </div>
                                    <div class="divider-line-container">
                                        <div class="divider-line-or"></div>
                                        <div class="divider-text">Or</div>
                                        <div class="divider-line-or"></div>
                                    </div>

                                <div class="row mt-5 text-center">
                                    <div class="col-md-12">
                                        <!-- <button class="btn btn-block"  style="background-color: #00205c; color: #fff;">Login with OTP</button>    -->
                                          <a class="btn" style="background-color: #00205c; color: #fff;" href="{{route('login-with-otp')}}">Login with OTP</a>      
                                    </div>
                               </div>


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
                               <!-- Terms and Privacy -->
                               <div class="row mt-5 text-center" >
                                    <div class="form-group col-md-12 ">
                                        <label class="form-label">
                                        <span >By logging in, you agree that you have read and accepted <br> Valuez's <a href="{{route('terms-conditions')}}" style="color: #00205c; text-decoration: underline;">Terms of Use</a> and <a  href="{{route('privacy-policy')}}" style="color: #00205c; text-decoration: underline;">Privacy Policy</a></span></label>
                                        <!-- <label class="form-label">and
                                            <a class="text-bold" href="{{route('privacy-policy')}}" style="color: #00205c; text-decoration: underline;">Privacy Policy</a></label> -->
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
   </script>

<script>

$('#studentloginForm').submit(function (event) {
   // alert(2323);
    event.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        url: '{{ route("process-student-login") }}',
        type: 'POST',
        processData: false,
        contentType: false,
        data: formData,
        success: function (response) {
            if (response['status'] == true) { 
                var student_session = response['student_session'];
                if(student_session){
                    window.location.href="{{  route('access-student-denied') }}";
                }else{
                    window.location.href = "{{ route('student.grade.list') }}";
                }
            } else {
                
                var phonenumber_null = response['phonenumber_null'];
                if (phonenumber_null) {
                    var studentid = response['student_id'];
                    window.location.href = "{{ route('phone-number', ['student' => '']) }}" + studentid;
                }
                var otp_verify_at_null = response['otp_verify_at_null'];
                if (otp_verify_at_null) {
                  //  $('#password').siblings('p').addClass('text-danger').html(otp_verify_at_null);
                    var studentid = response['student_id'];
                    window.location.href = "{{ route('phone-number', ['student' => '']) }}" + studentid;
                }

                var access_code = response['access_code'];
                if (access_code) {
                    //$('#password').siblings('p').addClass('text-danger').html(access_code);
                    var studentid = response['student_id_accesscode_time'];
                    console.log(studentid,"studentid");
                    window.location.href = "{{ route('school.access.code', ['student' => '']) }}" + studentid;
                }

                var credentials_do_not = response['credentials_do_not'];

                if (credentials_do_not) {
                    $('#credentials_do_not').siblings('p').addClass('text-danger').html(credentials_do_not);
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
    //CKEDITOR.replace('description1')
</script>
<script>
$(document).ready(function() {
    $('#showPasswordToggle').click(function() {
        var passwordField = $('#password_show_hide');
        var passwordFieldType = passwordField.attr('type');
        
        if (passwordFieldType === 'password') {
            passwordField.attr('type', 'text');
        } else {
            passwordField.attr('type', 'password');
        }
    });
});

</script>

<script>
    $(document).ready(function() {
        $('#username').on('input', function() {
            $('#username').siblings('p').removeClass('text-danger').html('');
            $('#credentials_do_not').siblings('p').removeClass('text-danger').html('');
        });
        $('#password').on('input', function() {
            $('#password').siblings('p').removeClass('text-danger').html('');
            $('#credentials_do_not').siblings('p').removeClass('text-danger').html('');
        });
    });
</script>

</body>

</html>
