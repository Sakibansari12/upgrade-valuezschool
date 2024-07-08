@extends('layout.main')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Contact Us</h4>
                
<!--                 <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Contact us</li>
                        </ol>
                    </nav>
                </div> -->
            </div>

        </div>
    </div>
    
    <!-- Main content -->
    <!-- <section class="content">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Contact Us</h5>
                        <div class="card-actions float-end">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                           
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section> -->
    <section class="content">
    <div class="row">
        <!-- Left side with static information -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Contact Information</h5>
                </div>
                <div class="card-body">
                    <p>Phone: 88267 08801</p>
                    <p>Email: support@valuezschool.com</p>
                </div>
            </div>
        </div>

        <!-- Right side with the contact form -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title mb-0" style="color: #00205c;">How can we help you?</h2>
                </div>
                <div class="card-body">
                    <!-- Your contact form goes here -->
                    <form name="supportemail" id="supportemail" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="school_name" class="form-label">School Name</label>
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <input type="text" class="form-control" id="school_name" name="school_name" Placeholder="School Name">
                            <p></p>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Your Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" Placeholder="Your Name" >
                            <p></p>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" Placeholder="Email">
                            <p></p>
                        </div>
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" Placeholder="Phone Number" >
                            <p></p>
                        </div>
                        <div class="mb-3">
                            <label for="query" class="form-label">Query <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="query" name="query" Placeholder="Querry" rows="4" ></textarea>
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

    <!-- /.content -->
    <section class="content">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Support List</h5>
                        <div class="card-actions float-end">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="yajra-table" class="table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Query</th>
                                        <th>Valuez's response</th>
                                        <th>Date &amp; Time</th>
                                    </tr>
                                </thead>
                                <tbody class="text-dark">
                                @if($supports->isNotEmpty())
                                        @foreach ($supports as $key => $data)
                                            <tr>
                                                <td>
                                                    <div   style="max-height: 3em; overflow: hidden; width: 100px;">
                                                        {{ $key + 1 }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feedbackDescription" style="max-height: 3em; overflow: hidden; width: 100px;">
                                                        {{ $data->name }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div id="feedbackDescription{{ $key }}" class="feedbackDescription" style="max-height: 3em; overflow: hidden; width: 250px;">
                                                        {{ strip_tags($data->query) }}
                                                    </div>
                                                    @if (str_word_count(strip_tags($data->query)) > 20)
                                                        <a href="#" class="readMoreLink" data-id="{{ $key }}" style="color: #00205c; text-decoration: underline;">more...</a>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div id="feedbackReply{{ $key }}" class="feedbackDescription" style="max-height: 3em; overflow: hidden; width: 250px;">
                                                        {{ strip_tags($data->support_reply) }}
                                                    </div>
                                                    @if (str_word_count(strip_tags($data->support_reply)) > 20)
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



@endsection
@section('script-section')
<script>
    $('#supportemail').submit(function(){
            event.preventDefault();
            var formArray = $(this).serializeArray();
            var spinner = document.getElementById('spinner_id');
            spinner.style.display = 'inline-block';
            var button_new = document.getElementById('confirmmessage');
            button_new.disabled = true;
           $.ajax({
             url: '{{ route("contact-support") }}',
             type: 'post',
             data: formArray,
             dataType: 'json',
             success: function(response){
                if(response['status'] == true){
                    spinner.style.display = 'none';
                    button_new.disabled = false;
                    document.getElementById('supportemail').reset();
                    //console.log(response['message']);
                    $('#confirmmessage').siblings('p').addClass('support_message').html(response['message']);
                    window.location.href="{{  route('support') }}";
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
