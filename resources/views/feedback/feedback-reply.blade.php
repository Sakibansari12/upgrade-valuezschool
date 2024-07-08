@extends('layout.main')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Valuez's response</h4>
                

            </div>

        </div>
    </div>
    <section class="content">
    <div class="row">
        

        <!-- Right side with the contact form -->
        <div class="col-xl-9">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title mb-0" style="color: #00205c;"> We Valuez's response</h2>
                </div>
                <div class="card-body">
                    <!-- Your contact form goes here -->
                    <form name="feedbackemail" id="feedbackemail" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="feedback_reply" class="form-label">Valuez's response<span class="text-danger">*</span></label>
                            <textarea class="form-control" id="feedback_reply" name="feedback_reply" Placeholder="Valuez's response" rows="8" ></textarea>
                            <p></p>
                        </div>
                        <button type="submit" class="btn " id="confirmmessage" style="background-color: #00205c; color: #fff;">Submit</button>
                        <p></p>
                        <input type="hidden" name="feedback_id" id="feedback_id" value="{{ $feedback_id }}">
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
    <!-- /.content -->
@endsection
@section('script-section')
    <script src="{{ asset('assets/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.js') }}"></script>
    <script>
        $('#feedback_reply').wysihtml5();
    </script>
<script>
    $('#feedbackemail').submit(function(){
            event.preventDefault();
            var formArray = $(this).serializeArray();
           $.ajax({
             url: '{{ route("feedback-reply") }}',
             type: 'post',
             data: formArray,
             dataType: 'json',
             success: function(response){
                if(response['status'] == true){
                    window.location.href="{{  route('feedback.teacher') }}";
                    document.getElementById('feedbackemail').reset();
                    console.log(response['message']);
                    $('#confirmmessage').siblings('p').addClass('support_message').html(response['message']);
                }else{
                    var errors = response['errors'];
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
