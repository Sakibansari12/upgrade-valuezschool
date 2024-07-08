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
                                <p class="mb-0 text-fade">Access your growth journey: Be future-ready, build your character.</p>
                            </div>
                            <div class="p-40">
                            <!-- <div class="justify-content-around d-flex">
                                <div class="progress progress-xl" style="width:100%;height:12px;">
                                    <div id="progressBar" class="progress-bar"
                                        role="progressbar" 
                                        aria-valuenow="" aria-valuemin="0"
                                        aria-valuemax="100">
                                    </div>
                                </div>
                            </div> -->


                                <form name="studentloginForm" id="studentloginForm" method="post">
                                    @csrf
                                   
                                    <div class="form-group">
                                        <label class="form-label">School Name <span class="text-danger">*</span></label>
                                        <div class="controls">
                                            <input type="text" name="school_name" id="school_name" class="form-control"
                                                placeholder="School Name">
                                            <p></p>
                                        </div>
                                        @if ($errors->has('school_name'))
                                            <div class="form-control-feedback">
                                                <small class="text-danger">{{ $errors->first('school_name') }}</small>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Class Teacher Name <span class="text-danger">*</span></label>
                                        <div class="controls">
                                            <input type="text" name="teacher_name" id="teacher_name" class="form-control"
                                                placeholder="Teacher Name">
                                            <p></p>
                                        </div>
                                        @if ($errors->has('teacher_name'))
                                            <div class="form-control-feedback">
                                                <small class="text-danger">{{ $errors->first('teacher_name') }}</small>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Roll No / Registration No <span class="text-danger">*</span></label>
                                        <div class="controls">
                                            <input type="text" name="roll_no" id="roll_no" class="form-control"
                                                placeholder="Teacher Name">
                                            <p></p>
                                        </div>
                                        @if ($errors->has('roll_no'))
                                            <div class="form-control-feedback">
                                                <small class="text-danger">{{ $errors->first('roll_no') }}</small>
                                            </div>
                                        @endif
                                    </div>



                                      <div class="form-group">
                                        <label class="form-label">Username <span class="text-danger">*</span></label>
                                        <div class="controls">
                                        <input type="text" name="username" id="username" value="{{ $student_data->username }}" class="form-control"
                                            placeholder="Username" readonly>
                                        <p></p>
                                        </div>
                                        @if ($errors->has('username'))
                                            <div class="form-control-feedback">
                                                <small class="text-danger">{{ $errors->first('username') }}</small>
                                            </div>
                                        @endif
                                        </div>
                                        <input type="hidden" id="student_id" name="student_id" value="{{ $student_data->id }}" class="form-control">
                                    <!-- <div class="form-group" id="student_license">
                                        <label class="form-label">Password <span class="text-danger">*</span></label>
                                        <div class="controls">
                                            <div class="input-group" id="passwords">
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
                                    </div> -->
                                    <div class="form-group" id="student_license">
                                    </div>
                                   <p></p>

                                 
                                      <!-- <div class="form-group">
                                        <label class="form-label">Access Code<span class="text-danger">*</span></label>
                                        <div class="controls">
                                            <input type="text" id="access_code" name="access_code" class="form-control" placeholder="Enter School Access code">
                                            <p></p>
                                        </div>
                                        @if ($errors->has('access_code'))
                                            <div class="form-control-feedback">
                                                <small class="text-danger">{{ $errors->first('access_code') }}</small>
                                            </div>
                                        @endif
                                    </div> -->
                                


                                    <div class="row" id="directloginsignin">
                                        <div class="col-md-4 text-center">  
                                        </div>
                                            <div class="col-md-4 text-center">
                                              <button type="submit" class="btn w-p100 mt-10" style="background-color: #00205c; color: #fff;">Login</button>
                                            </div>
                                            
                                          <div class="col-md-4 text-center">
                                          
                                          </div>
                                    </div><div class="row mt-3">
                                        <div class="col-md-3 text-center">  
                                        </div>
                                            <div class="col-md-6 text-center">
                                               <h5><a href="{{route('student-login')}}" style="color: #00205c; text-decoration: underline;">Go back to Login</a></h5>
                                            </div>
                                          <div class="col-md-3 text-center">
                                          
                                          </div>
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


    <!-- Vendor JS -->
    <script src="{{ asset('assets/src/js/vendors.min.js') }}"></script>
    <script src="{{ asset('assets/src/js/pages/chat-popup.js') }}"></script>
    <script src="{{ asset('assets/icons/feather-icons/feather.min.js') }}"></script>
   <script>
    /* var widthValue = '90%'; 
var progressBar = document.getElementById('progressBar');
progressBar.style.width = widthValue; */
/* Complete Access Code Login */
$('#studentloginForm').submit(function(){
     event.preventDefault();
            var formArray = $(this).serializeArray();
    var csrfToken = $('meta[name="_token"]').attr('content');
    $.ajax({
      url: '{{ route("access-code-get") }}',
      type:'post',
      data: {
        _token: csrfToken, 
       // otp: $('#otp').val(),
       // phone_number: $('#phone_number').val(),
       student_id: $('#student_id').val(),
       school_name: $('#school_name').val(),
       teacher_name: $('#teacher_name').val(),
       roll_no: $('#roll_no').val(),
       passwords: $('#password').val(),
       username: $('#username').val(),
       access_code: $('#access_code').val(),
         
      },
      dataType: 'json',
      success: function(response){
         if(response['status'] == true){
            //console.log(response,"response");
            $('#passwords').siblings('p').removeClass('text-danger').addClass('text-success').html(response['message']);
           //  window.location.href="{{  route('student.grade.list') }}";
                   // var student_data = response['otpdata'];

                    /* var widthValue = '100%'; 
                    var progressBar = document.getElementById('progressBar');
                    progressBar.style.width = widthValue; */


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

          // window.location.href="{{  route('student-login') }}";
         }else{
            var errors = response['errors'];
            var student_licence = response['student_licence'];
            if(student_licence){
                $('#student_license').siblings('p').addClass('text-danger').html(student_licence);
            }
            $('#passwords').siblings('p').addClass('text-danger').html(response['message']);
             $.each(errors, function(key,value){
             $(`#${key}`).siblings('p').addClass('text-danger').html(value);
          })
         
      }
    },
      error: function(jqXHR, exception){
         console.log("something went wrong");
      }
    
    });
});

   </script>


</body>

</html>
