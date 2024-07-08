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
                <div class="col-lg-8">
                        <h1 class="fw-600"><!-- PRIVACY POLICY --></h1>
                        @if($PrivacyPolicy->isNotEmpty())
                                @foreach ($PrivacyPolicy as $data)
                                <div class="modal-content" style="text-align: left; line-height: 2; font-size: 14px;">
                                    <div class="modal-header">
                                        <h2 class="modal-title"><b>{{ $data->title }}</b></h2>
                                    </div>
                                    <div class="modal-body">
                                        <p class="description-text">{!! $data->description !!}</p>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                </div>
            </div>
        </div>
    </section>

    <script src="{{ asset('assets/src/js/vendors.min.js') }}"></script>
    <script src="{{ asset('assets/src/js/pages/chat-popup.js') }}"></script>
    <script src="{{ asset('assets/icons/feather-icons/feather.min.js') }}"></script>

</body>

</html>
