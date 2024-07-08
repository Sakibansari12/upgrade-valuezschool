@extends('layout.main')
@section('content')
{{-- <head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head> --}}
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Students</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">Manage Students</li>
                            <li class="breadcrumb-item active" aria-current="page">Update Student</li>
                        </ol>
                    </nav>
                </div>
            </div>

        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-lg-8 col-12">
                @if ($studentdata)
                    <!-- Basic Forms -->
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title">Update Student</h4>
                        </div>
                        <!-- /.box-header -->
                        <form action=""
                        name="studentUpdateForm" id="studentUpdateForm"
                        method="post" enctype="multipart/form-data">

                            @csrf
                            <div class="box-body">

                                <!-- <div class="form-group">
                                    <label class="form-label">Phone <span class="text-danger"></span></label>
                                    <input type="text" name="phone_number" id="phone_number" value="{{ $studentdata->phone_number}}" class="form-control"
                                        placeholder="Enter Phone">

                                        <p></p>
                                    @error('phone')
                                        <span class="text-danger">id="getotpButton"{{ $message }}</span>
                                    @enderror
                                </div> -->
                               <input type="hidden" name="student_id" value="{{ $studentdata->id}}" id="student_id">
                                <div class="row" id="phone_number_hide">
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                        <div class="controls" id="updatetimesucessmessage">
                                            <input type="text" name="phone_number" maxlength="10" value="{{ $studentdata->phone_number}}" id="phone_number" 
                                             class="form-control" placeholder="Phone Number">
                                            <p></p>
                                        </div>
                                        <p></p>
                                    </div>
                                        <div class="col-md-6  mt-2">
                                            <input type="button" id="getotpButton" onclick="StudentLoginOpt()"  value="Get OTP" class="btn w-p100 mt-20 button-small" style="background-color: #00205c; color: #fff;">
                                        </div>
                                </div>
                                <div class="row" id="otp_hide">
                                        <div class="form-group col-md-6">
                                            <label class="form-label">OTP <span class="text-danger">*</span></label>
                                            <div class="controls">
                                            <input type="text" name="otp" id="otp" value="" class="form-control"
                                                        placeholder="OTP">
                                            <p></p> 
                                            </div>
                                            @if ($errors->has('otp'))
                                                <div class="form-control-feedback">
                                                    <small class="text-danger">{{ $errors->first('otp') }}</small>
                                                </div>
                                            @endif
                                        </div>
                                            <div class="col-md-6 text-center mt-2">
                                                <input type="button" onclick="VerifyOtp()" id="VerifyOtpbutton"  value="Verify OTP" class="btn w-p100 mt-20 button-small" style="background-color: #00205c; color: #fff;">
                                                
                                            </div>
                                    </div>
                                    <p></p>



                                <div class="form-group">
                                    <label for="grade" class="form-label">Grade:<span class="text-danger">*</span></label>
                                    <select name="grade" id="grade" class="form-control">
                                        <option value="">Select a Category</option>
                                                @if($programs->isNotEmpty())
                                                  @foreach($programs as $program)
                                                <option {{ ($studentdata->grade == $program->grade_id) ? 'selected' : ''  }} value="{{ $program->grade_id }}">{{ $program->class_name }}</option>
                                                @endforeach
                                                @endif
                                    </select>
                                    <p></p>
                                    @error('grade')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Email <span class="text-danger"></span></label>
                                    <input type="email" name="email" id="email" value="{{ $studentdata->email}}" class="form-control"
                                        placeholder="Enter Email" >
                                        <p></p>
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                
                                <div class="form-group">
                                    <label class="form-label">Section</label>
                                    <textarea class="form-control" id="section" name="section" Placeholder="Section" rows="2" >{{ $studentdata->section }}</textarea>
                                    @error('section')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <input type="hidden" name="id" value="{{ $studentdata->id }}">
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.box -->
                @else
                    <h1>Something went wrong.</h1>
                @endif
            </div>

        </div>
    </section>
    <!-- /.content -->
@endsection
<!-- {{ route('student.update' , ['userid' => $studentdata->id] )}} -->
@section('script-section')
<script>
    var otp_hide = $("#otp_hide");
                        otp_hide.toggle();
</script>
<script>
document.getElementById("VerifyOtpbutton").disabled = true;
    document.getElementById("otp").readOnly = true;
    document.getElementById("submitButton").disabled = true;
    

	function StudentLoginOpt(){
      //  console.log("hello");
        var csrfToken = $('meta[name="_token"]').attr('content');
			$.ajax({
			url: '{{ route("student-detail-login-otp") }}',
			type:'post',
            data: {
                _token: csrfToken, 
                phone_number: $('#phone_number').val(),
                student_id: $('#student_id').val(),
            },
			dataType: 'json',
			success: function(response){
                //console.log(response,"response");
				if(response['status'] == true){
                    if(response['message']){
                        $('#phone_number').siblings('p').removeClass('text-danger').addClass('text-success').html(response['message']);
                        var otp_hide = $("#otp_hide");
                        otp_hide.show();
                    }
                    let successmessage = response['message_update'];
                    //console.log(successmessage,"successmessage");
                    if(successmessage){
                        let timeresetotpupdate =  response['timeResetOtpUpdatetime'];
                        $('#updatetimesucessmessage').siblings('p').addClass('text-success').html(successmessage);
                        function updateButtonValue() {
                        if (timeresetotpupdate > 0) {
                            document.getElementById("getotpButton").disabled = true;
                            document.getElementById("getotpButton").value = `Resend OTP (${timeresetotpupdate} seconds)`;
                            timeresetotpupdate--;
                            setTimeout(updateButtonValue, 1000); // Call the function again after 1 second
                        } else {
                            document.getElementById("getotpButton").disabled = false;
                            document.getElementById("getotpButton").value = "Resend OTP"; 
                            $('#updatetimesucessmessage').siblings('p').removeClass('text-success').html('');
                        }
                    }
                    updateButtonValue();
                    }
                    $('#name').siblings('p').addClass('text-danger').html('');
                    $('#last_name').siblings('p').addClass('text-danger').html('');
                    
                    let timeresetotp =  response['timeResetOtp'];
                    console.log(timeresetotp,"timeresetotp");
                    /* console.log(timeresetotp,"timeresetotp");
                    if(timeresetotp){
                      document.getElementById("getotpButton").value = `Resend OTP (${timeresetotp})`;
                    } */
                    if(response['student_allexixts']){
                        var loginRoute = "{{ route('student-login') }}";
                        var html = `${response['student_allexixts']}<a href="${loginRoute}" class=" btn-blue">here</a>`;
                        $("#phone_number").siblings('p').addClass('text-success').append(html);
                     // $('#phone_number').siblings('p').addClass('text-success').html(response['student_allexixts']);
                     // window.location.href="{{  route('student-login') }}";
                    }else{
                        function updateButtonValue() {
                        if (timeresetotp > 0) {
                            document.getElementById("getotpButton").disabled = true;
                            document.getElementById("getotpButton").value = `Resend OTP (${timeresetotp} seconds)`;
                            timeresetotp--;
                            setTimeout(updateButtonValue, 1000); // Call the function again after 1 second
                        } else {
                            document.getElementById("getotpButton").disabled = false;
                            document.getElementById("getotpButton").value = "Resend OTP"; 
                            $('#phone_number').siblings('p').removeClass('text-success').html('');
                        }
                    }
                    updateButtonValue();
                    }
                    //document.getElementById("getotpButton").disabled = true;
                   // document.getElementById("name").readOnly  = true;
                    document.getElementById("otp").readOnly  = false;
                   // document.getElementById("VerifyOtpbutton").disabled = false;
                     
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







    <script>
    $('#studentUpdateForm').submit(function(){
            event.preventDefault();
            var formArray = $(this).serializeArray();
           $.ajax({
             url: '{{ route("student-update") }}',
             type: 'post',
             data: formArray,
             dataType: 'json',
             success: function(response){
                if(response['status'] == true){
                    window.location.href="{{  route('student-details') }}";
                }else{
                    var errors = response['errors'];
                    var student_verified = response['student_verified'];
                    $('#phone_number').siblings('p').addClass('text-danger').html(student_verified);
                     $.each(errors, function(key,value){
                        $(`#${key}`).siblings('p').addClass('text-danger').html(value);
                    })
                }
             },
             error: function(){
                console.log("Some things went wrong");
             }
           });
        });


        function toggleButtonState() {
    var phoneNumber = $('#phone_number').val();
    var button = $('#getotpButton');
    // Remove non-numeric characters and limit to 10 digits
    phoneNumber = phoneNumber.replace(/[^0-9]/g, '').slice(0, 10);
    // Check if the phone number is exactly 10 digits and does not start with '0'
     if(phoneNumber[0] == '0'){
        $('#phone_number').siblings('p').addClass('text-danger').html("Phone number can't start with 0.");
     }else{
        $('#phone_number').siblings('p').addClass('text-danger').html("");
     }
    if (phoneNumber.length === 10 && phoneNumber[0] !== '0') {
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
</script>

<!-- otp verify -->
<script>
    function VerifyOtp(){
       // alert(223);
    var csrfToken = $('meta[name="_token"]').attr('content');
    $.ajax({
      url: '{{ route("verify-st-login-detail-otp") }}',
      type:'post',
      data: {
        _token: csrfToken, 
        otp: $('#otp').val(), 
        phone_number: $('#phone_number').val(),
      },
      dataType: 'json',
      success: function(response){
         if(response['status'] == true){
            $('#otp_hide').siblings('p').removeClass('text-danger').addClass('text-success').html(response['message']);
             const phonenumber = response['otpdata'];
             //const usernametextstatic = 'Auto-genarate, change later from "My Pro file"';
            // $('#username').val(phonenumber.username); 
            // $('#mobile').val(phonenumber.phone_number); 
             document.getElementById("VerifyOtpbutton").disabled = true;
             document.getElementById("otp").readOnly  = true;
             document.getElementById("getotpButton").disabled = true;
             /* Hide */
             
            /* var phone_number_hide = $("#phone_number_hide");
                        phone_number_hide.toggle();
            var otp_hide = $("#otp_hide");
                        otp_hide.toggle(); */
            

             /* Show  */
             
            
            var submit_hide = $("#submit_hide");
                        submit_hide.show();
             document.getElementById("submitButton").disabled = false;
             $('#otp').siblings('p').addClass('text-danger').html('');
         }else{
             var errors = response['errors'];
             var otpinvalid = response['queryfaild'];
             if(otpinvalid){
                $('#otp').siblings('p').addClass('text-danger').html(otpinvalid);
             }else{
                $('#otp').siblings('p').addClass('text-danger').html('');
             }
             if(errors['otp']){
                    $('#otp').siblings('p').addClass('text-danger').html(errors['otp']);
                }else{
                    $('#otp').siblings('p').removeClass('text-danger').html('');
                }
            }
         
      }, error: function(jqXHR, exception){
         console.log("something went wrong");
      }
    });
 
}
</script>
@endsection