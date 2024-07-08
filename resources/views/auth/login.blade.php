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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Vendors Style-->
    <link rel="stylesheet" href="{{ asset('assets/src/css/vendors_css.css') }}">

    <!-- Style-->
    <link rel="stylesheet" href="{{ asset('assets/src/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/src/css/skin_color.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/src/css/feedback.css') }}">
</head>

<body class="hold-transition theme-primary bg-img"
    style="background-image: url({{ asset('assets/images/auth-bg/B2B-lms.webp') }});background-size: contain;background-position: bottom; ">

    <div class="container h-p100 mt-100">
        <div class="row align-items-center justify-content-md-center form-top-increse h-p100">

            <div class="col-12">
                <div class="row justify-content-center g-0">
                    <div class="col-lg-5 col-md-5 col-12">
                        <div class="bg-white rounded10 shadow-lg">
                            <div class="content-top-agile p-20 pb-0">
                                <h2 class="fw-600" style="color: #00205c;">21st Century Classroom</h2>

                                <p class="mb-0 text-fade">Access your growth journey: Be future-ready, build your
                                    character.</p>
                                @if ($errors->has('errortechers'))
                                    <div class="form-control-feedback">
                                        <h6 class="text-danger">{{ $errors->first('errortechers') }}</h6>
                                    </div>
                                @endif

                            </div>
                            <div class="p-60">
                                <form method="POST" action="{{ route('login.process') }}">
                                    @csrf

                                    <div class="form-group">
                                        <label class="form-label">Username or Email <span
                                                class="text-danger"></span></label>
                                        <div class="controls">
                                            <input type="text" name="email" class="form-control"
                                                placeholder="Username or Email ">
                                        </div>
                                        @if ($errors->has('email'))
                                            <div class="form-control-feedback">
                                                <small class="text-danger">{{ $errors->first('email') }}</small>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" style="display: flex; align-items: center;">
                                            Password <span class="text-danger"></span>
                                            <span style="flex-grow: 1;"></span>
                                            <!-- This will push the "Forgot password?" text to the right -->
                                            <a style="font-size: 12px;" href="#" id="forgotPopup"
                                                class="text-bold">Forgot password?</a>
                                        </label>

                                        <div class="controls">
                                            <div class="input-group">
                                                <input type="password" name="password" id="password"
                                                    class="form-control" placeholder="Password">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="showPasswordToggle"><i
                                                            class="fa fa-eye"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($errors->has('password'))
                                            <div class="form-control-feedback">
                                                <small class="text-danger">{{ $errors->first('password') }}</small>
                                            </div>
                                        @endif

                                    </div>
                                    <div class="row">
                                        @if ($errors->has('error'))
                                            <div class="form-control-feedback">
                                                <small class="text-danger">{{ $errors->first('error') }}</small>
                                            </div>
                                        @endif
                                        @if ($errors->has('errors'))
                                            <div class="form-control-feedback">
                                                <span class="text-bold" style="color: #00205c;">You seem to be a teacher
                                                    and this is a student login page. Please login here <a
                                                        href="{{ route('student-login') }}"
                                                        style="color: #00205c; text-decoration: underline;">Click
                                                        here</a></span></label>
                                            </div>
                                        @endif
                                        <div class="row" id="directloginsignin">
                                            <div class="col-md-4 text-center">
                                            </div>
                                            <div class="col-4 text-center mt-2">
                                                <input type="hidden" name="deviceid" id="deviceid" value=""/>
                                                <button type="submit" class="btn w-p100 mt-10"
                                                    style="background-color: #00205c; color: #fff; font-weight: bold;">Login</button>
                                            </div>
                                            <div class="col-md-4 text-center">
                                            </div>
                                        </div>
                                        <div class="row mt-5 text-center">
                                            <div class="form-group col-md-12 mt-2">
                                                <label class="form-label">
                                                    <a class="text-bold" href="{{ route('student-login') }}"
                                                        style="color: #00205c; text-decoration: underline;">I am a
                                                        student</a></label>
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
    <div class="modal-status" id="chousePopup">
        <div class="modal-content-status-login" style="width: 38%; height: 28%">
            <div class="row align-items-center">

                <div class="col text-center">
                    <button class="btn" style="background-color: #00205c; color: #fff;" id="teacherPopup">I am a
                        teacher</button>
                </div>

                <div class="col-1 text-center">
                    <div class="vertical-line"
                        style="height: 220px; width: 2px; background-color: #ccc; margin: auto;"></div>
                    <button type="button" style="margin-top: 26px; margin-left: -90px;"
                        class="btn btn-sm btn-danger position-absolute top-0 start-100 translate-middle-y"
                        id="closechousePopup">Close</button>
                </div>


                <div class="col text-center position-relative">

                    <a href="{{ route('school-admin-forgot') }}">
                        <button class="btn" style="background-color: #00205c; color: #fff;">I am a school
                            admin</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-status" id="forgotPasswordPopup">
        <div class="modal-content-status">
            <p style="font-size:20px; color: #00205c;">Contact your school admin or write to us at
                <b>support@valuezschool.com</b>
            </p>
            <div class="button-container-status">
                <button class="btn" style="background-color: #00205c; color: #fff;"
                    id="closeteacherPopup">Close</button>
            </div>
        </div>
    </div>
    <div class="modal-status" id="schoolAdminCreateData">
        <div class="modal-content-status">

            <div class="button-container-status">
                <button class="btn" style="background-color: #00205c; color: #fff;"
                    id="schoolAdminClosePopup">Close</button>
            </div>
        </div>
    </div>
    <!-- Vendor JS -->
    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="{{ asset('js/devicedetect.js') }}"></script>
    <script>


        $(document).on('click', '#ipaddress_id', function() {
            // $("#error-list").html('').removeClass('text-danger text-success');
            var csrfToken = $('meta[name="_token"]').attr('content');
            var id = $(this).attr('data-ipaddress-id');
            $.ajax({
                url: "{{ route('remove.ipaddress') }}",
                type: "POST",
                data: {
                    // school: $("#remSchool").val(),
                    _token: csrfToken,
                    ipaddess_id: id,
                },
                success: function(response) {
                    if (response == 'removed') {
                        setTimeout(() => {
                            location.reload();
                        }, 600);
                    } else {

                    }
                }
            });
        });
    </script>
    <script>
        // Your jQuery code here
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
            $('#forgotPopup').show();
        });
    </script>
    <script>
        $(document).ready(function() {
            // Show the popup when the link is clicked
            $('#teacherPopup').click(function() {
                $('#forgotPasswordPopup').show();
            });

            // Close the popup when the close button is clicked
            $('#closeteacherPopup').click(function() {
                $('#forgotPasswordPopup').hide();
                $('#chousePopup').hide();
            });
        });


        $(document).ready(function() {
            $('#forgotPopup').click(function() {
                $('#chousePopup').show();
            });
            $('#closechousePopup').click(function() {
                $('#chousePopup').hide();
            });
        });

        $(document).ready(function() {
            // Show the popup when the link is clicked
            $('#schoolAdminPopup').click(function() {
                $('#schoolAdminCreateData').show();
            });

            // Close the popup when the close button is clicked
            $('#schoolAdminClosePopup').click(function() {
                $('#schoolAdminCreateData').hide();
                $('#chousePopup').hide();
            });
        });
    </script>
    <script src="{{ asset('assets/src/js/vendors.min.js') }}"></script>
    <script src="{{ asset('assets/src/js/pages/chat-popup.js') }}"></script>
    <script src="{{ asset('assets/icons/feather-icons/feather.min.js') }}"></script>



</body>

</html>
