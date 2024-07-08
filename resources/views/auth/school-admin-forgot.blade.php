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
                                <h2 class="fw-600" style="color: #00205c;">Forgot password</h2>
                                <p class="mb-0 text-fade">Enter your details bellow. We will get back to you.</p>
                            </div> 
                            <div class="p-40">
                            <form name="studentForgotForm" id="studentForgotForm" method="post">
                                    @csrf 
                                    
                                    <div class="form-group" >
                                        <label class="form-label">Name<span class="text-danger">*</span></label>
                                        <div class="controls">
                                            <input type="text" name="name" id="name" class="form-control" placeholder="Name">
                                            <p></p>
                                        </div>
                                        @if ($errors->has('name'))
                                            <div class="form-control-feedback">
                                                <small class="text-danger">{{ $errors->first('name') }}</small>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">School Name<span class="text-danger">*</span></label>
                                        <div class="controls">
                                            <input type="text" name="school_name" id="school_name" class="form-control" placeholder="School Name">
                                            <p></p>
                                        </div>
                                        @if ($errors->has('school_name'))
                                            <div class="form-control-feedback">
                                                <small class="text-danger">{{ $errors->first('school_name') }}</small>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <div class="controls">
                                            
                                            <input type="text" name="email" id="email" class="form-control" placeholder="Enter Email">
                                            <p></p>
                                        </div>
                                        @if ($errors->has('email'))
                                            <div class="form-control-feedback">
                                                <small class="text-danger">{{ $errors->first('email') }}</small>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Submit Button -->
                                    <div class="row">
                                        <div class="col-md-4 text-center "></div>
                                        <div class="col-md-4 text-center m-5">
                                            <input type="button" onclick="ForgotPassword()" id="ForgotPassworddisable" value="Submit" class="btn w-p100 mx-auto" style="background-color: #00205c; color: #fff;">
                                            <br><label class="form-label mt-5">
                                                <a class="text-bold " href="{{route('login')}}" style="color: #00205c; text-decoration: underline;">Login here</a></label>
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
/* Forgot password  */
function ForgotPassword(){
    //alert(12);
    var csrfToken = $('meta[name="_token"]').attr('content');
    var forgot_student = $('#forgot_student').val();
   // console.log(forgot_student,"forgot_student");
    $.ajax({
      url: '{{ route("school-admin-forgot-password") }}',
      type:'post',
      data: {
        _token: csrfToken, 
        name: $('#name').val(),
        email: $('#email').val(),
        school_name: $('#school_name').val(),
      },
      dataType: 'json',
      success: function(response){
         if(response['status'] == true){
            window.location.href="{{  route('login') }}";
        }else{
             var errors = response['errors'];
             $.each(errors, function(key,value){
                $(`#${key}`).siblings('p').addClass('text-danger').html(value);
            })
        }
         
      }, error: function(jqXHR, exception){
         console.log("something went wrong");
      }
    });
 
}









</script>
</body>
</html>