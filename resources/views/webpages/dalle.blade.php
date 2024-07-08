@extends('layout.main')
@section('content')
    <!-- <div class="containerrr"> -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Instructional Module</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><i class="mdi mdi-home-outline"></i></li>
                            <li class="breadcrumb-item" aria-current="page"><a
                                    href="{{ route('teacher.class.list') }}">{{ $class_name->class_name }}</a></li>
                            <li class="breadcrumb-item" aria-current="page"><a
                                    href="{{ route('teacher.course.list', ['class' => $class_id]) }}">Course</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $aimodules->course_name_chatgpt }}</li>
                        </ol>
                    </nav>
                </div>
            </div>

        </div>
    </div>
    <section class="content">
        <div class="row">
<div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
            <div class="content-wrappertest  mx-auto" style="margin-top: 8px;">
                <section style="margin-top: 8px;">
                    <div >
                        <h4><b>Fun with AI : {{ isset($formattedType) ? $formattedType : '' }}</b></h4><div>
                            <hr>
                            <div>
                                <p><h5>{!! isset($aimodules->module_page_overview) ? $aimodules->module_page_overview : '' !!}</h5></p>
                            </div>
                        </div>
                    </div>
                    <hr >
                </section>
            <section>
                <h4 ><b>Introduction Video</b></h4>
                <div>
                    <div >
                           @if (isset($aimodules->video_url) && !empty($aimodules->video_url))
                            <iframe src="{{ $aimodules->video_url }}" width="640" height="360" 
                            frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                        @endif
                    </div>
                </div>
                <hr>

            </section>
            <div class="white-desk">
            <h3><b>Let's watch {{ isset($formattedType) ? $formattedType : '' }} in action! Try the prompts below:</b></h3>
                        <div class="faq-question">
                            <span>{!! isset($aimodules->description) ? $aimodules->description : '' !!}</span>
                        </div>
                @if (!empty($aimodules->promptsdata))
                    <div class="faq-section">
                        @foreach ($aimodules->promptsdata as $key => $cdata)
                                <div class="faq-item" style="background-color: #00205c; color: #fff;">
                                    <div class="faq-question">
                                        <!-- <h6><b>{{ $key + 1 }}. {{ isset($cdata->prompts) ? $cdata->prompts : '' }}</b></h6> -->

                                        
                                            <div style="width: 1%; ">
                                                <h5><b>{{ $key + 1 }} </b></h5>
                                            </div>
                                            <div style="width: 85%;">
                                            <h5><b>{!! isset($cdata->prompts) ? $cdata->prompts : '' !!}</b></h5>
                                            </div>
                                            
                                           

                                            <div style="width: 12%;">
                                                <form name="chatgptcreatedalle-{{ $cdata->id }}" id="chatgptcreatedalle-{{ $cdata->id }}" method="POST">
                                                        
                                                                <input type="hidden" name="ask_question_chatGpt" value="{{ isset($cdata->prompts) ? $cdata->prompts : '' }}" class="form-control" id="ask_question_chatGpt" placeholder="Enter Image Description to Dalle">
                                                                <input type="hidden" name="ask_question_dalle_file" value="{{ isset($cdata->file) ? $cdata->file : '' }}" class="form-control" id="ask_question_dalle_file">
                                                                                                                        <!-- <button type="submit" class="btn " id="chatgptsearchbuttondalle-{{ $cdata->id }}" style="background-color: #fee665; color: #00205c; width: 130px;">
                                                                Generate&nbsp; <span id="spinner_{{$key+1}}" class="spinner-border spinner-border-sm " style="color: #00205c; display: none;" role="status"  aria-hidden="true" ></span>
                                                            </button> -->
                                                            <button type="button" class="btn" id="btncustom_{{$key+1}}" onclick="ChatGptPrompts({{ json_encode(isset($cdata->file) ? $cdata->file : '') }}, {{ json_encode(isset($cdata->response) ? $cdata->response : '') }}, {{$key + 1}})" style="background-color: #fee665; color: #00205c; width: 120px;">
                                                                Generate&nbsp;<span id="spinner_{{$key+1}}" class="spinner-border spinner-border-sm" style="color: #00205c; display: none;" role="status" aria-hidden="true"></span>
                                                            </button>
                                                           
                                                </form>
                                            
                                            </div>
                                    </div>
                                    <img src="" alt="Response Image" id="dalleimage-{{ $cdata->id }}"  style="display: none; max-width: 50%;"> 
                                    <!-- <img src="" alt="Response Image" id="dalleimage-status-false-{{ $cdata->id }}" style="display: none;"> --> 
                                </div>
                         @endforeach
                         </div>
                    @endif
                
            <div>
                <div class="faq-section">
                     
                     <div class="faq-item">
                         <div class="faq-question">
                         <!-- <b>&nbsp;&nbsp;&nbsp;&nbsp;Enter Own Prompts</b> -->
                         <h3><b>Try your own prompts</b></h3>
                         </div>
                     <div class="faq-question">
                     <span>{!! isset($aimodules->own_description) ? $aimodules->own_description : '' !!}</span>
                     <input type="hidden" id="own_placeholder" name="own_placeholder" value="{{ isset($aimodules->own_placeholder) ? $aimodules->own_placeholder : '' }}">
                     </div>
                     <div id="Createform">
                     <form name="chatgptcreateform" id="chatgptcreateform" method="POST">
                             <div class="row" >
                                 <div class="col-md-6 ">
                                     <div>
                                         <textarea  name="ask_question_chatGpt" rows="4" class="form-control" id="ask_question_chatGpt" required placeholder="{{ isset($aimodules->own_placeholder) ? $aimodules->own_placeholder : '' }}"></textarea>
                                     </div>
                                 </div>
                                 <div class="col-md-2" style="width: 12.333333%">
                                    <button type="submit" class="btn chatgpt-search-button" id="chatgptsearchbutton" style="background-color: #00205c; color: #fff;">
                                    <span id="spinner_new_prompt" class="spinner-border spinner-border-sm " style="color: #fff; display: none;" role="status"  aria-hidden="true" ></span>
                                   
                                    Submit</button>
                                 </div>
                                 <div class="col-md-4  text-center" style="width: 15.333333%">
                                     <button type="button" class="btn " id="add-field-input" style="background-color: #00205c; color: #fff;">New Prompt</button>
                                 </div>
                             
                                 <div class="faq-answer">
                                     <div   id="chatgptanswer" style="display: none;">
                                         <div class="typing">
                                         </div>
                                         <hr>
                                     </div>    
                                     <!-- <p class="answer-content"></p>  -->
                                     <img src="" class="mt-2" alt="Response Image" id="dalleyimagesearch" style="display: none;">
                                 </div>
                         </div>
                     </form>
                 </div>
         </div>      
     </div>



                </div>
                 <hr>
                <section class="faq-section">
                    <div class="info-section">
                        <h3><b>Activities:</b></h3>
                        <div class="faq-question">
                            <span>Copy the following prompts, click on 'Open Website' and paste them in the input box.</span>
                        </div>
                        <ul>
                        
                        @if (!empty($aimodules->activitiesdata))
                            @foreach ($aimodules->activitiesdata as $key => $cdata)
                            <li>
                                <h5 class="mt-10" id="questionText"><b>{{ isset($cdata->activities_description) ? $cdata->activities_description : '' }}</b></h5>
                                <!-- <a href="#" target="_blank" class="btn-open-website" id="copyPrompt"><span class="fw-600"><i class="fa fa-copy"></i></span></a>
                                <a href="{{$cdata->website_url}}" target="_blank" class="btn-open-website">Open Website</a>
                                -->
                                <a href="#" class="waves-effect waves-light btn" id="copyPrompt" style="background-color: #00205c; color: #fff;" title="Copy"><i class="fa fa-copy"></i>&nbsp;Copy</a>
                                <a href="{{$cdata->website_url}}" target="_blank" class="waves-effect waves-light btn" style="background-color: #00205c; color: #fff;">Open Website</a>
                            
                                <a href="#" class="waves-effect waves-light btn" onclick="toggleImage({{$key+1}})" style="background-color: #00205c; color: #fff;" title="Copy">
                                <i class="info-icon">ℹ</i>
                                </a>
                                <div id="imageContainer_{{$key+1}}" class="mt-2" style="display: none;">
                                <img class="video-btn"
                                        src="{{ url('uploads/aimodule') }}/{{ $cdata->prompt_screenshot ? $cdata->prompt_screenshot : 'no_image.png' }}"
                                        alt="Image" id="imageToShow_{{$key+1}}" style="max-width: 50%;" />
                                </div>
                            </li>
                            @endforeach
                        @endif
                        </ul>
                    </div>
                    <hr>
                </section>
                @if (!empty($aimodules->AddOwnactivitiesdata[0]->add_placeholder_text))
                <div class="faq-section">
                    <div class="faq-item">
                    <div class="faq-question">
                            <h3><b>Let's get hands on with AI tools</b></h3>
                            
                        </div>
                        <div class="faq-question">
                            <span>{!! isset($aimodules->hello_there_description) ? $aimodules->hello_there_description : '' !!}</span>
                        </div>
                        @if (!empty($aimodules->AddOwnactivitiesdata))
                            @foreach ($aimodules->AddOwnactivitiesdata as $key => $cdata)
                            <div class="row">
                            <div class="col-md-6 mt-2">
                                <div>
                                    <textarea name="ask_question_copy_paste" rows="4" class="form-control"
                                     id="ask_question_copy_paste_{{$key+1}}" placeholder="{{ isset($cdata->add_placeholder_text) ? $cdata->add_placeholder_text : '' }}"></textarea>
                                </div>
                            </div>
                            <div class="col-md-2 mt-2" style="width: 12.333333%">
                                <a href="#" class="waves-effect waves-light btn" id="CopyPastebutton_{{$key+1}}" onclick="copyTextareaContent({{$key+1}})" style="background-color: #00205c; color: #fff;" title="Copy"><i class="fa fa-copy"></i>&nbsp;Copy</a> 
                            </div>
                            <div class="col-md-4 mt-2" style="width: 21.333333%">
                                <a href="{{ isset($cdata->add_ai_toll_url) ? $cdata->add_ai_toll_url : '' }}" target="_blank" class="waves-effect waves-light btn" 
                                 style="background-color: #00205c; color: #fff;">Open {{ isset($cdata->toll_name) ? $cdata->toll_name : '' }}</a> 
                            </div>
                            
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div> 
                @endif
                <!-- <section class="faq-section">
                    <div class="info-section">
                                <h3><b>Upload Activity</b></h3>
                                <p><b>Take screenshot of your prompts and/or output and upload them here in order to log your creativity.</b></p>
                        <label for="activity-upload" class="upload-button">Upload Activity</label>
                        {{-- <input type="file" id="activity-upload" name="activity-upload"> --}}



                    </div>
                    
                    <hr>
                </section> -->
                <!-- <section class="faq-section">
                    <div class="info-section">
                        <h3><b>Handpicked Results of Peers</b></h3>
                        <p><b>Our Team has chosen some of the best and creative prompts other students
                            came up with. Here is a small list.</b></p>

                        <div class="box-container">
                            <div class="box"></div>
                            <div class="box">
                                <div class="box-text"></div>
                            </div>
                            <div class="box">
                                <div class="box-text"></div>
                        </div>
                    </div>
                    <div class="text-container">
                        <div class="info-section-peers">
                            <h5>Prompt by:xyz</h5>
                            <p>Prompt: Lorem ipsum dolor sit amet consectetur adipisicing elit. Culpa placeat soluta reiciendis obcaecati eius distinctio dolorem. Magni magnam id quam nostrum. Saepe, dolores culpa vitae voluptas aliquam dolore similique eius.</p>
                        </div>
                        <div class="info-section-peers">
                            <h5>Prompt by:xyz</h5>
                            <p>Prompt: Lorem ipsum dolor, sit amet consectetur adipisicing elit. Cupiditate, veritatis. Facilis a enim fugit tempore debitis perferendis obcaecati ex rem quisquam ea, eligendi expedita dicta praesentium eaque voluptatibus omnis aperiam.</p>
                        </div>
                        <div class="info-section-peers">
                            <h5>Prompt by:xyz</h5>
                            <p>Prompt: Lorem ipsum dolor sit amet consectetur adipisicing elit. Similique incidunt animi sequi fugiat corrupti sint labore provident molestias consequatur cum dolor, nisi maiores voluptas culpa dolores veritatis, atque illum eos.</p>
                        </div>
                    </div>
                </section> -->


            </div>
            </div>
                </div>
            </div>
        </div>
    </section>
    <!-- </div> -->

    <script src="{{ asset('assets/src/js/chatgpt_js.js') }}"></script>

    <!-- Your ChatGPT page content goes here -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function copyTextareaContent(key) {
        // Get the textarea element
        var textarea = document.getElementById("ask_question_copy_paste_"+ key);

        // Select the text in the textarea
        textarea.select();
        textarea.setSelectionRange(0, 99999); // For mobile devices

        // Copy the selected text to the clipboard
        document.execCommand("copy");

        // Optionally, you can alert the user or perform other actions
        alert("Text copied to clipboard!");
    }


function toggleImage(key) {
    var imageContainer = document.getElementById('imageContainer_' + key);
    imageContainer.style.display = (imageContainer.style.display === 'none') ? 'block' : 'none';
}

$(document).ready(function () {
    $(".faq-question").click(function () {
        var answer = $(this).next(".faq-answer");
        var icon = $(this).find(".toggle-icon");
        if (answer.is(":visible")) {
            answer.slideUp();
            icon.text("+");
        } else {
            answer.slideDown();
            icon.text("-");
            
            var content = answer.find(".answer-content").text();
            var words = content.split(" ");
            var currentWord = 0;
            var interval = setInterval(function () {
                if (currentWord < words.length) {
                    answer.find(".answer-content").text(words.slice(0, currentWord + 1).join(" "));
                    currentWord++;
                } else {
                    clearInterval(interval);
                }
            }, 100); // Adjust the interval speed as needed
        }
    });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    var copyPromptButton = document.getElementById("copyPrompt");
    var questionText = document.getElementById("questionText");

    copyPromptButton.addEventListener("click", function (event) {
        event.preventDefault();

        // Create a temporary textarea element to hold the text
        var textArea = document.createElement("textarea");
        textArea.value = questionText.innerText;

        // Append the textarea to the document
        document.body.appendChild(textArea);

        // Select the text inside the textarea
        textArea.select();

        // Copy the selected text to the clipboard
        document.execCommand("copy");

        // Remove the temporary textarea
        document.body.removeChild(textArea);

        // You can provide some feedback to the user here
        alert("Prompt copied to clipboard!");
    });
});

$(document).ready(function() {
    var fieldinput = 1;

    $("#add-field-input").click(function() {
        var uniqueId = fieldinput;
        var own_placeholder =  $('#own_placeholder').val()
        var newRow =
            $(
                '<form name="chatgptcreateform_' + uniqueId + '" id="chatgptcreateform_' + uniqueId + '" method="POST">' +
                '<div class="row" >' +
                '<div class="col-md-6 mt-2">' +
                '<div>' +
                '<textarea name="ask_question_chatGpt" class="form-control" rows="4" id="ask_question_chatGpt_' + uniqueId + '" required placeholder="' + own_placeholder + '"></textarea>' +
                '</div>' +
                '</div>' +
                '<div class="col-md-2 mt-2" style="width: 12.333333%">' +
                '<button type="button" class="btn chatgpt-search-button" id="chatgptsearchbutton_' + uniqueId + '" data-id="' + uniqueId + '" style="background-color: #00205c; color: #fff; font-weight: bold;"><span id="spinner_new_prompt_' + uniqueId + '" class="spinner-border spinner-border-sm " style="color: #fff; display: none;" role="status"  aria-hidden="true" ></span>Submit</button>' +
                '</div>' +
                '<div class="col-md-4 mt-2 text-center" style="width: 15.333333%">' +
                '<button type="button" class="btn btn-danger remove-field" data-id="' + uniqueId + '">Remove</button>' +
                '</div>' +
                '<div class="faq-answer">' +
                '<div   id="chatgptanswer_' + uniqueId + '" style="display: none;">' +
                '<div class="typing">' +
                '</div>' +
                '<hr>' +
                '</div> ' +
                '<img src="" class="mt-2" alt="Response Image" id="dalleyimagesearch_' + uniqueId + '" style="display: none;">' +
                '</div>' +
                '</div>' +
                '</form>'
            );

        $("#Createform").append(newRow);

        newRow.find(".chatgpt-search-button").click(function() {
           // alert(23);
            var id = $(this).data("id");
            var formArray = $("#chatgptcreateform_" + id).serializeArray();

            var button = document.getElementById('chatgptsearchbutton_'+ id);
            var spinner = document.getElementById('spinner_new_prompt_'+ id);
            spinner.style.display = 'inline-block';
            button.disabled = true;

            $.ajax({
                url: '{{ route("search-dalle") }}',
                type: 'post',
                data: formArray,
                dataType: 'json',
                success: function(response) {
                    // Handle the response as needed
                    if(response['status'] == true){
                    spinner.style.display = 'none';
                    button.disabled = false;

                 //  console.log(response['message'],"sakibhello");
                   var value = response['message'];
                   if (value) {
                        // Set the image source and display it
                        var responseImage = document.getElementById('dalleyimagesearch_'+id);
                        responseImage.src = value;
                        responseImage.style.display = 'block';
                    } else {
                        // Hide the image if no image URL is provided in the response
                        var responseImage = document.getElementById('dalleyimagesearch_'+id);
                        responseImage.style.display = 'none';
                    }
                   
                }else{
                    var errors = response['errors'];
                     $.each(errors, function(key,value){
                        $(`#${key}`).siblings('p').addClass('text-danger').html(value);
                    })
                }
                /* if(response['status'] == 'success'){
                document.getElementById("chatgptsearchbutton_" + id).disabled = true;    
                var value = response['message'];
                var words = value.split(' '); 
                var index = 0;
                var interval = 100; 
                var displayNextWord = function() {
                    if (index < words.length) {
                        $("#chatgptanswer_" + id).siblings('p').addClass('text-black').html(words.slice(0, index + 1).join(' '));
                        index++;
                    } else {
                        clearInterval(wordDisplayInterval);
                    }
                };
                var wordDisplayInterval = setInterval(displayNextWord, interval);
                }else{
                    var errors = response['errors'];
                     $.each(errors, function(key,value){
                        $(`#${key}`).siblings('p').addClass('text-danger').html(value);
                    })
                } */
            },
                error: function() {
                    console.log("Something went wrong");
                }
            });
        });

        newRow.find(".remove-field").click(function() {
            var idToRemove = $(this).data("id");
            $("#chatgptcreateform_" + idToRemove).remove();
        });

        fieldinput++;
    });
});




        $('#chatgptcreateform').submit(function(){
            //alert(2433);
            event.preventDefault();
            var formArray = $(this).serializeArray();
            console.log(formArray,"formArray");
            document.getElementById("chatgptsearchbutton").disabled = true;
            var button = document.getElementById('chatgptsearchbutton');
            var spinner = document.getElementById('spinner_new_prompt');
            spinner.style.display = 'inline-block';
            button.disabled = true;
           $.ajax({
             url: '{{ route("search-dalle") }}',
             type: 'post',
             data: formArray,
             dataType: 'json',
             success: function(response){
              
                if(response['status'] == true){
                    spinner.style.display = 'none';
                    button.disabled = false;
                   //console.log(response['message'],"sakibhello");
                   var value = response['message'];
                   if (value) {
                        // Set the image source and display it
                        var responseImage = document.getElementById('dalleyimagesearch');
                        responseImage.src = value;
                        responseImage.style.display = 'block';
                    } else {
                        // Hide the image if no image URL is provided in the response
                        var responseImage = document.getElementById('dalleyimagesearch');
                        responseImage.style.display = 'none';
                    }
                   
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





/* $('form[id^="chatgptcreatedalle-"]').submit(function(event) {
    event.preventDefault();
    var formArray = $(this).serializeArray();
    console.log(formArray[1]['value'],"formArray");
    var api_status_false_image = formArray[1]['value'];
    var formId = $(this).attr('id');
    var dynamicId = formId.split('-')[1]; 
    document.getElementById("chatgptsearchbuttondalle-" + dynamicId).disabled = true;


    var button = document.getElementById('chatgptsearchbuttondalle-'+dynamicId);
    var spinner = document.getElementById('spinner_'+dynamicId);
    spinner.style.display = 'inline-block';

    $.ajax({
        url: '{{ route("search-dalle") }}',
        type: 'post',
        data: formArray,
        dataType: 'json',
        success: function(response) {
            if (response['status'] == true) {
                spinner.style.display = 'none';
                button.disabled = false;
                var value = response['message'];
                if (value) {
                    var responseImage = document.getElementById('dalleimage-' + dynamicId);
                    responseImage.src = value;
                    responseImage.style.display = 'block';
                } else {
                    var responseImage = document.getElementById('dalleimage-' + dynamicId);
                    responseImage.style.display = 'none';
                }
            } else {
                var errors = response['errors'];

                var value = formArray[1]['value'];
                if (value) {
                    var responseImage = document.getElementById('dalleimage-status-false-' + dynamicId);
                    var completeURL = "{{ url('uploads/aimodule') }}/" + value;
                    responseImage.src = completeURL;
                    responseImage.style.display = 'block';
                } else {
                    var responseImage = document.getElementById('dalleimage-status-false-' + dynamicId);
                    responseImage.style.display = 'none';
                }

                $.each(errors, function(key, value) {
                    $(`#${key}`).siblings('p').addClass('text-danger').html(value);
                });
            }
        },
        error: function() {
            console.log("Some things went wrong");
        }
    });
}); */
function ChatGptPrompts(file, responseanswer, key){
    var button = document.getElementById('btncustom_' + key);
    var spinner = document.getElementById('spinner_' + key);
    spinner.style.display = 'inline-block';
    button.disabled = true;
    
    setTimeout(function() {
        var responseImage = document.getElementById('dalleimage-' + key);
        responseImage.src = 'uploads/aimodule/' + file; // Concatenate correctly
        responseImage.style.display = 'block';
    
        spinner.style.display = 'none';
        button.disabled = false;
    }, 5000); // 5000 milliseconds = 5 seconds
}

</script>
@endsection
