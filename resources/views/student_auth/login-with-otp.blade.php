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
                                <form name="studentloginForm" id="studentloginForm" method="post">
                                    @csrf
                                <div  >
                                    
                                        <div class="form-group">
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
                                     <div class="row">
                                        <div class="col-md-4 text-center">  
                                        </div>
                                            <div class="col-md-4 text-center">
                                               <button type="submit" class="btn w-p100 mx-auto" style="background-color: #00205c; color: #fff;">Get OTP</button>
                                            </div>
                                          <div class="col-md-4 text-center">
                                          </div>
                                    </div>
                                    <div class="divider-line-container">
                                        <div class="divider-line-or"></div>
                                        <div class="divider-text">Or</div>
                                        <div class="divider-line-or"></div>
                                    </div>
                                </div>
                                </form>
                                <div class="row mt-5 text-center">
                                    <div class="col-md-12">
                                        <a class="btn" style="background-color: #00205c; color: #fff;" href="{{route('student-login')}}">Login with Username</a>       
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
    $('#phone_number').val(phoneNumber);
    }
    $('#phone_number').on('input', function() {
        toggleButtonState();
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


$('#studentloginForm').submit(function (event) {
   // alert(2323);
    event.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        url: '{{ route("login-with-get-otp") }}',
        type: 'POST',
        processData: false,
        contentType: false,
        data: formData,
        success: function (response) {
            if (response['status'] == true) { 
                var message = response['message'];

                $('#phone_number').siblings('p').removeClass('text-danger').addClass('text-success').html(response['message']);

                 if (message) {
                    var studentid = response['student_id'];
                     window.location.href = "{{ route('otp-verify', ['student' => '']) }}" + studentid;
                }
 
                   // console.log(message,"message");
            } else {
                
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


</script>

</body>

</html>
