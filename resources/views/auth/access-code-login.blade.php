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
<style>

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
    margin: 0 60px;
    margin-top: -15px;
}

.step-text {
    margin: 0 13px;
    margin-bottom: -32px;
    color: #ccc;
}
.step-text.active {
    margin: 0 13px;
    margin-bottom: -32px;
    color: #00205c;
}

.step.active {
    background-color: #00205c;
}

/* Styles for horizontal lines */
.step .horizontal-line {
    height: 2px;
    width: 140px;
    background-color: #ccc;
    margin: auto;
    margin-top: 9px;
}
.step.active .horizontal-line {
    height: 2px;
    width: 140px;
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
                <div class="row justify-content-center g-0" >
                    <div class="col-lg-5 col-md-5 col-12">
                        <div class="bg-white rounded10 shadow-lg">
                            <div class="content-top-agile p-20 pb-0" style="margin-bottom: -34px">
                                <h2 class="fw-600" style="color: #00205c;">Student Registration</h2>
                                <p class="mb-0 text-fade">Access your growth journey: <br> Be future-ready, build your character.</p>
                            </div>
                            <div class="p-40 mb-5">
                            <!-- <div class="justify-content-around d-flex">
                                <div class="progress progress-xl" style="width:100%;height:12px;">
                                    <div id="progressBar" class="progress-bar"
                                        role="progressbar" 
                                        aria-valuenow="" aria-valuemin="0"
                                        aria-valuemax="100">
                                    </div>
                                </div>
                            </div> -->
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
                                        <div class="step " id="step1">
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
                            <form name="studentloginFormAcessCode" id="studentloginFormAcessCode" method="post">
                                @csrf
                                <div class="form-group" id="access_code_field">
                                    <label class="form-label">Enter School Access code <span class="text-danger">*</span></label>
                                    <div class="controls">
                                        <div class="input-group">
                                            <input type="text" id="access_code" name="access_code" class="form-control" placeholder="Access code">
                                            &nbsp;&nbsp;&nbsp;<label class="form-label">
                                            <span class="text-bold">Don't have an access code ?  
                                                <a href="{{ route('access.code', ['student_id' => $student_id]) }}" class="text-bold">Click here</a>
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
                                <input type="hidden" id="student_id" value="{{ $student_data->id }}" name="student_id" class="form-control">
                                <div class="row">
                                    <div class="col-md-4 text-center">  
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <input type="button" onclick="AccessCodeLogin()" id="signinlogin" value="Login" class="btn w-p100 mx-auto" style="background-color: #00205c; color: #fff;">  
                                    </div>
                                    <div class="col-md-4 text-center">
                                    </div>
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

/* var widthValue = '90%'; 
var progressBar = document.getElementById('progressBar');
progressBar.style.width = widthValue; */
$('#step1').addClass('active');
$('#step-text1').addClass('active');
$('#step2').addClass('active');
$('#step-text2').addClass('active');

    function AccessCodeLogin() {
        var csrfToken = $('meta[name="_token"]').attr('content');
        $.ajax({
            url: '{{ route("login-with-access-code") }}',
            type: 'post',
            data: {
                _token: csrfToken, 
                student_id: $('#student_id').val(),
                access_codes: $('#access_code').val(),
            },
            dataType: 'json',
            success: function(response) {
                if (response['status'] == true) {
                    //var student_data = response['student_data'];

                    $('#step3').addClass('active');
                    $('#step-text3').addClass('active');


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









                    /* if (student_data) {
                        $.ajax({
                            url: '{{ route("student.login") }}',
                            type: 'post',
                            data: {
                                _token: csrfToken, 
                                password: student_data.view_password,
                                username: student_data.username, 
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
                    } */
                } else {
                    var errors = response['errors'];
                    var student_licence = response['student_licence'];
                    if(student_licence){
                        $('#access_code_field').siblings('p').addClass('text-danger').html(student_licence);
                    }
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
</script>


</body>

</html>
