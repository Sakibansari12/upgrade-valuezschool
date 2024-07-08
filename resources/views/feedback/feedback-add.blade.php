@extends('layout.main')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Feedback</h4>
                

            </div>

        </div>
    </div>
    <section class="content">
    <div class="row">
        

        <!-- Right side with the contact form -->
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title mb-0" style="color: #00205c;"> We value your feedback</h2>
                </div>
                <div class="card-body">
                    <!-- Your contact form goes here -->
                    <form name="feedbackemail" id="feedbackemail" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="your_name" class="form-label">Your Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="your_name" name="your_name" Placeholder="Your Name" >
                            <p></p>
                        </div>

                        <div class="form-group">
                            <label for="grade">Grade <span class="text-danger">*</span></label>
                            <select name="grade" id="grade" class="form-control">
                                <option value="">Select a Grade</option>
                                <option value="1">Grade 1</option>
                                <option value="2">Grade 2</option>
                                <option value="3">Grade 3</option>
                                <option value="4">Grade 4</option>
                                <option value="5">Grade 5</option>
                                <option value="6">Grade 6</option>
                                <option value="7">Grade 7</option>
                                <option value="8">Grade 8</option>
                                <option value="9">Grade 9</option>
                                <option value="10">Grade 10</option>
                                <option value="11">Pre School</option>
                            </select>
                            <p></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Section <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="section" name="section" Placeholder="Section" >
                            <p></p>
                            @error('section')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="your_feedback" class="form-label">Your feedback <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="your_feedback" name="your_feedback" Placeholder="Your feedback" rows="4" ></textarea>
                            <p></p>
                        </div>
                        <button type="submit" class="btn " id="confirmmessage" style="background-color: #00205c; color: #fff;">Submit
                        <span id="spinner_id" class="spinner-border spinner-border-sm " style="color: #fff; display: none;" role="status" style="display: none;" aria-hidden="true" ></span>
                    </button>
                        <p></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="content">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Feedback List</h5>
                        <div class="card-actions float-end">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="yajra-table" class="table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Your Feedback</th>
                                        <th>Valuez's response</th>
                                        <th>Date &amp; Time</th>
                                    </tr>
                                </thead>
                                <tbody class="text-dark">
                                @if($feedbacks->isNotEmpty())
                                        @foreach ($feedbacks as $key => $data)
                                            <tr>
                                                <td>
                                                    <div   style="max-height: 3em; overflow: hidden; width: 100px;">
                                                        {{ $key + 1 }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div id="feedbackDescription{{ $key }}" class="feedbackDescription" style="max-height: 3em; overflow: hidden; width: 250px;">
                                                        {{ strip_tags($data->feedback_description) }}
                                                    </div>
                                                    @if (str_word_count(strip_tags($data->feedback_description)) > 20)
                                                        <a href="#" class="readMoreLink" data-id="{{ $key }}" style="color: #00205c; text-decoration: underline;">more...</a>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div id="feedbackReply{{ $key }}" class="feedbackDescription" style="max-height: 3em; overflow: hidden; width: 250px;">
                                                        {{ strip_tags($data->feedback_reply) }}
                                                    </div>
                                                    @if (str_word_count(strip_tags($data->feedback_reply)) > 20)
                                                        <a href="#" class="readMoreLink" data-id="{{ $key }}" style="color: #00205c; text-decoration: underline;">more...</a>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ isset($data->created_at) ? \Carbon\Carbon::parse($data->created_at)->format('d/m/Y') : '' }} <strong>|</strong>
                                                    {{ isset($data->created_at) ? \Carbon\Carbon::parse($data->created_at)->format('H:i') : '' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif 
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <!-- The description modal popup -->
<div class="modal fade" id="description-popup-model" tabindex="-1" role="dialog" aria-labelledby="modal-label"
        aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-label-pass" style="color: #00205c;">
                       Feedback Description </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <p id="payment_description_id"></p>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
<!-- The feedback_reply description modal popup -->
<div class="modal fade" id="feedback-reply-popup-model" tabindex="-1" role="dialog" aria-labelledby="modal-label"
        aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-label-pass" style="color: #00205c;">
                    Valuez's response </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <p id="feedback_reply_description_id"></p>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <!-- /.content -->
@endsection
@section('script-section')

<script>
    $('#feedbackemail').submit(function(){
            event.preventDefault();
            var formArray = $(this).serializeArray();
            var spinner = document.getElementById('spinner_id');
            spinner.style.display = 'inline-block';
            var button_new = document.getElementById('confirmmessage');
            button_new.disabled = true;
            
           $.ajax({
             url: '{{ route("feedback-teacher") }}',
             type: 'post',
             data: formArray,
             dataType: 'json',
             success: function(response){
                if(response['status'] == true){
                    spinner.style.display = 'none';
                    button_new.disabled = false;
                    window.location.href="{{  route('teacher.feedback') }}";
                    document.getElementById('feedbackemail').reset();
                    console.log(response['message']);
                    $('#confirmmessage').siblings('p').addClass('support_message').html(response['message']);
                }else{
                    var errors = response['errors'];
                     $.each(errors, function(key,value){
                        $(`#${key}`).siblings('p').addClass('text-danger').html(value);
                    })
                    spinner.style.display = 'none';
                    button_new.disabled = false;
                }
             },
             error: function(){
                console.log("Some things went wrong");
             }
           });
        });

$(document).on('click', '.feedback_reply_popup', function() {
    var payment_description = $(this).attr('data-description-reply');
   $('#feedback_reply_description_id').text(payment_description);
  $('#feedback-reply-popup-model').modal('show');
});
$(document).on('click', '.description_popup', function() {
    var payment_description = $(this).attr('data-description');
   $('#payment_description_id').text(payment_description);
  $('#description-popup-model').modal('show');
});
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var readMoreLinks = document.querySelectorAll('.readMoreLink');

        readMoreLinks.forEach(function(link) {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                var feedbackId = this.getAttribute('data-id');
                var feedbackDescription = document.getElementById('feedbackDescription' + feedbackId);
                var feedbackReply = document.getElementById('feedbackReply' + feedbackId);

                if (this.innerText === 'more...') {
                    feedbackDescription.style.maxHeight = 'none';
                    feedbackReply.style.maxHeight = 'none';
                    this.innerText = 'less...';
                } else {
                    feedbackDescription.style.maxHeight = '3em';
                    feedbackReply.style.maxHeight = '3em';
                    this.innerText = 'more...';
                }
            });
        });
    });
</script>



@endsection
