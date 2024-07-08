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

    <title>Valuez School - Maximum Device Login </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Vendors Style-->
    <link rel="stylesheet" href="{{ asset('assets/src/css/vendors_css.css') }}">

    <!-- Style-->
    <link rel="stylesheet" href="{{ asset('assets/src/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/src/css/skin_color.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/src/css/feedback.css') }}">
</head>

<body class="hold-transition light-skin theme-primary bg-img">

    <section class="error-page h-p100">
        <div class="container h-p100">
            <div class="row h-p100 align-items-center justify-content-center text-center">
                <div class="col-lg-7 col-md-10 col-12">
                    <div class="rounded30 p-50">
                        <h1 class="fs-180 fw-bold text-danger"> <i class="fa fa-ban"></i></h1>
                        <h1 class="fw-600">Sorry! You have reached the maximum device login limit.</h1>
                        <h4 class="text-fade">Please try a different student login or logout from the previous device.
                        </h4>
                        <div class="my-30"><a href="{{ route('student-login') }}" class="btn btn-primary">Back to
                                Login</a></div>
                        <h5 class="mb-15 text-fade">-- OR --</h5>
                        <h4 class="text-fade">Please try after some time</h4>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="{{ asset('assets/src/js/vendors.min.js') }}"></script>
    <script src="{{ asset('assets/src/js/pages/chat-popup.js') }}"></script>
    <script src="{{ asset('assets/icons/feather-icons/feather.min.js') }}"></script>

</body>

</html>
