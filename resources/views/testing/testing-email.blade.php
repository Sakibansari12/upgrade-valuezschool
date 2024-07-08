@extends('layout.main')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title"><!-- Test Email --></h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <!-- <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li> -->
                            <li class="breadcrumb-item alignment-text-new" aria-current="page"><a href="{{ route('testing') }}"><i class="mdi mdi-home-outline"></i> - Testing</a></li>
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page">Test Email</li>
                        </ol>
                    </nav>
                </div>
                

            </div>

        </div>
    </div>
    <section class="content">
    <div class="row">
        

        <!-- Right side with the contact form -->
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title mb-0" style="color: #00205c;"> Test Email</h2>
                </div>
                <div class="card-body">
                    <!-- Your contact form goes here -->
                    <form name="feedbackemail" id="feedbackemail" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="email" name="email" Placeholder="Your Email" >
                            <p></p>
                        </div>
                        <button type="submit" class="btn " id="confirmmessage" style="background-color: #00205c; color: #fff;">Submit</button>
                        <p></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- /.content -->
@endsection
@section('script-section')
<script>
    $('#feedbackemail').submit(function(){
            event.preventDefault();
            var formArray = $(this).serializeArray();
           $.ajax({
             url: '{{ route("test-email-add") }}',
             type: 'post',
             data: formArray,
             dataType: 'json',
             success: function(response){
                if(response['status'] == true){
                   // console.log(response['message']);
                    $('#confirmmessage').siblings('p').addClass('support_message').html(response['message']);
                }else{
                    var errors = response['errors'];
                    var otp_errors = response['otp_errors'];
                    $('#email').siblings('p').addClass('text-danger').html(otp_errors);
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
</script>

@endsection
