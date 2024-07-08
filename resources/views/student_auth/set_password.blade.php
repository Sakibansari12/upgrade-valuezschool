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
                                <h2 class="fw-600" style="color: #00205c;">Forgot Password?</h2>
                                <p class="mb-0 text-fade">Forgot password  to continue to Valuez School.</p>
                            </div> 
                            <div class="p-40">
                            <form name="studentForgotPassword" id="studentForgotPassword" method="post">
                                    @csrf 
                                    <div class="row">
                                        <!-- Password Input -->
                                        <div class="form-group">
                                            <label class="form-label">Set Password <span class="text-danger">*</span></label>
                                            <div class="controls">
                                               <div class="input-group" id="password">
                                                    <input type="password" id="set_password" name="password" class="form-control" placeholder="Password">
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
                                        <div class="form-group">
                                            <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                            <div class="controls" >
                                                <div class="input-group" id="confirm_password">
                                                    
                                                    <input type="password" id="set_confirm_password" name="confirm_password" class="form-control" placeholder="Confirm Password">
                                                    <p></p>
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
                                    <!-- Submit Button -->
                                    <input type="hidden" id="student_id" name="student_id" value="{{$student_id}}">
                                    <div class="row">
                                        <div class="col-md-4 text-center "></div>
                                        <div class="col-md-4 text-center m-5">
                                          <button type="submit" class="btn w-p100 mx-auto" style="background-color: #00205c; color: #fff;">Submit</button>
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

$('#studentForgotPassword').submit(function (event) {
    //alert(34);
    event.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        url: '{{ route("student-password-change") }}',
        type: 'POST',
        processData: false,
        contentType: false,
        data: formData,
        success: function (response) {
            if (response['status'] == true) { 
                var message = response['message'];
                $('#confirm_password').siblings('p').addClass('text-success').html(message);
                window.location.href="{{  route('student-login') }}";
                /* if (message) {
                    var studentid = response['student_id'];
                    window.location.href = "{{ route('set-password', ['student' => '']) }}" + studentid;
                } */
            } else {
                var errors = response['errors'];
                /* var phone_number_not_store = response['phone_number_not_store'];
                if (phone_number_not_store) {
                    var studentid = response['student_id'];
                    window.location.href = "{{ route('phone-number', ['student' => '']) }}" + studentid;
                } */

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




        $(document).ready(function() {
    $('#showPasswordToggle').click(function() {
        var passwordField = $('#set_password');
        var passwordFieldType = passwordField.attr('type');
        
        if (passwordFieldType === 'password') {
            passwordField.attr('type', 'text');
        } else {
            passwordField.attr('type', 'password');
        }
    });
    $('#showConfirmPasswordToggle').click(function() {
        var passwordField = $('#set_confirm_password');
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
        $('#set_password').on('input', function() {
            $('#password').siblings('p').removeClass('text-danger').html('');
        });
        $('#set_confirm_password').on('input', function() {
            $('#confirm_password').siblings('p').removeClass('text-danger').html('');
        });
    });
</script>
</body>
</html>