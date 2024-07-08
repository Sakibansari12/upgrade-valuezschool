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
                            <form name="studentForgotForm" id="studentForgotForm" method="post">
                                    @csrf 
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
                                    <div class="row">
                                        <div class="col-md-4 text-center"></div>
                                        <div class="col-md-4 text-center m-5">
                                            <button type="submit" class="btn w-p100 mx-auto" style="background-color: #00205c; color: #fff;">Next</button>
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
       
      
$('#studentForgotForm').submit(function (event) {
    //alert(34);
    event.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        url: '{{ route("student-forgot-username") }}',
        type: 'POST',
        processData: false,
        contentType: false,
        data: formData,
        success: function (response) {
            if (response['status'] == true) { 
                var message = response['message'];
                if (message) {
                    var studentid = response['student_id'];
                    window.location.href = "{{ route('set-password', ['student' => '']) }}" + studentid;
                }
            } else {
                var errors = response['errors'];
                var phone_number_not_store = response['phone_number_not_store'];
                if (phone_number_not_store) {
                    var studentid = response['student_id'];
                    window.location.href = "{{ route('forgot-phone-number', ['student' => '']) }}" + studentid;
                }

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
</script>
<script>
    $(document).ready(function() {
        $('#username').on('input', function() {
            $('#username').siblings('p').removeClass('text-danger').html('');
        });
    });
</script>
</body>
</html>