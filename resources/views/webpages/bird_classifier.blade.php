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
    <section class="content ">
        <div class="row">
<div class="col-xl-12">
    <div class="card">
        <div class="card-header">
            <div class="content-wrappertest" style="margin-top: 8px; width: 100%;">
                <h2 ><b>{{isset($aimodules->content->title) ? $aimodules->content->title : ''}}</b></h2>
                <hr >
                

    <section style="margin-top: 8px; width: 100%;">
    <h4><b>Overview</b></h4>
    <div>
        <p><h5 style="width: 100%;">
        {!! isset($aimodules->module_page_overview) ? $aimodules->module_page_overview : '' !!}
            
        </h5></p>
    </div>
    <hr>
</section>


                  <!-- Embedded Video Section -->
            <section >
                <h4 ><b>Introduction Video</b></h4>
                <div>
                    <div>
                        @if (isset($aimodules->content->video_url) && !empty($aimodules->content->video_url))
                            <iframe src="{{ $aimodules->content->video_url }}" width="640" height="360" 
                            frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                        @endif
                    </div>
                </div>
                <hr>

            </section>
            <div class="white-desk">
            <h3><b>Prompts</b></h3>
                  <p style="font-size: 15px;">Click on "Generate" button to listen to the power of text to voice AI!</p>
                    @if (!empty($aimodules->promptsdata))
                    <div class="faq-section">
                        @foreach ($aimodules->promptsdata as $key => $cdata)
                        <div class="faq-item" style="background-color: #00205c; color: #fff;">
                            <!-- <div class="faq-question">
                                <h5><b>{{ $key + 1 }}. {{ isset($cdata->prompts) ? $cdata->prompts : '' }}</b></h5>
                                
                                <button type="button" class="btn" id="btncustom_{{$key+1}}" onclick="ChatGptPrompts({{ json_encode(isset($cdata->prompts) ? $cdata->prompts : '') }}, {{ json_encode(isset($cdata->response) ? $cdata->response : '') }}, {{$key + 1}})" style="background-color: #fee665; color: #00205c;">
                                Generate&nbsp;<span id="spinner_{{$key+1}}" class="spinner-border spinner-border-sm " style="color: #00205c; display: none;" role="status" style="display: none;" aria-hidden="true" ></span>
                                </button>
                            </div> -->
                           
                            <img class="video-btn"
                                        src="{{ url('uploads/aimodule') }}/{{ $cdata->file ? $cdata->file : '' }}"
                                        width="100" 
                                        height="100"
                                         />
                           


                            <div class="faq-question" style="display: flex; justify-content: space-between;">
                                <h5 style="margin-right: 10px;"><b>{{ $key + 1 }}. {{ isset($cdata->prompts) ? $cdata->prompts : '' }}</b></h5>
                                <button type="button" class="btn" id="btncustom_{{$key+1}}" onclick="ChatGptPrompts({{ json_encode(isset($cdata->file) ? $cdata->file : '') }}, {{ json_encode(isset($cdata->response) ? $cdata->response : '') }}, {{$key + 1}})" style="background-color: #fee665; color: #00205c;">
                                    Generate&nbsp;<span id="spinner_{{$key+1}}" class="spinner-border spinner-border-sm" style="color: #00205c; display: none;" role="status" aria-hidden="true"></span>
                                </button>
                            </div>
                            <div>
                                <div class="api-false-time-answer_{{ $key + 1}} typing" >
                                </div>
                                <p style="font-size: 16px;" class="api-answer-content"></p>
                            </div>
                                <div class="chat-gpt-api-ansawer_{{ $key + 1}}" style="display: none;">
                                    <video class="mediaPlayer" controls autoplay loop></video>
                                </div>
                            <p style="font-size: 16px;" class="api-answer-content"></p>
                        </div>
                        @endforeach
                    </div>
                    @endif
                <!-- Add an input field and submit button here -->
              <div class="faq-section">
                     
                        <div class="faq-item">
                            <div class="faq-question">
                            <h3><b>&nbsp;&nbsp;&nbsp;Enter Own Prompts</b></h3>
                            </div>
                        <div class="faq-question">
                        <span>&nbsp;&nbsp;&nbsp;&nbsp;Now that you have an idea of how prompts work let's have fun with own prompts.</span>
                        </div>
                        <div id="Createform">
                        <form name="chatgptcreateform" id="chatgptcreateform" method="POST">
                                <div class="row m-2" >
                                    <div class=" col-md-6 ">
                                        <!-- <div>
                                            <textarea  name="ask_question_chatGpt" rows="4" class="form-control" id="ask_question_chatGpt" required placeholder="Enter Question to ChatGpt"></textarea>
                                        </div> -->
                                        <div class="faq-item" style="background-color:  #00205c;  color: #fff;">
                                             <input type="file" name="ask_photo_video" id="ask_photo_video" required style="font-size: 16px;">
                                             <br>
                                             <div class="prompt_one_photo_video" style="display: none;">
                                                  <!-- <video class="mediaPlayerOwn" controls autoplay loop></video> -->
                                              </div>
                                              <p style="font-size: 16px;" class="api-answer-content mt-2"></p>
                                             <div style="display: none;">
                                                 <p style="font-size: 16px;" id="Own-Prompts-text" class="api-answer-content mt-2"></p>
                                             </div>
                                             
                                        </div>
                                    </div>
                                    <div class="col-md-2" style="width: 12.333333%">
                                       <button type="submit" class="btn chatgpt-search-button" id="chatgptsearchbutton" style="background-color: #00205c; color: #fff; font-weight: bold;">
                                       <span id="spinner_new_prompt" class="spinner-border spinner-border-sm " style="color: #fff; display: none;" role="status"  aria-hidden="true" ></span>
                                       Submit</button>
                                    </div>
                                    <div class="col-md-4" style="width: 15.333333%">
                                        <button type="button" class="btn " id="add-field-input" style="background-color: #00205c; color: #fff;">New Prompt</button>
                                    </div>
                                
                                    <div class="faq-answer mt-2"> 
                                        <div id="chatgptanswer" style="display: none;">
                                           <audio class="player_Own_Prompts" controls></audio>
                                        </div>
                                        <p class="answer-content"></p> 
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
                        <p>Copy the following prompts, click on 'Open Website' and paste them in the input box.</p>
                        <ul class="p-5">
                       
                        @if (!empty($aimodules->activitiesdata))
                            @foreach ($aimodules->activitiesdata as $key => $cdata)
                            <li>
                                <h5 class="mt-10" id="questionText"><b>{{ isset($cdata->activities_description) ? $cdata->activities_description : '' }}</b></h5>
                                <a href="#" class="waves-effect waves-light btn" id="copyPrompt" style="background-color: #00205c; color: #fff;" title="Copy"><i class="fa fa-copy"></i>&nbsp;Copy</a>
                                <a href="{{$cdata->website_url}}" target="_blank" class="waves-effect waves-light btn" style="background-color: #00205c; color: #fff;">Open Website</a>
                                <a href="#" class="waves-effect waves-light btn" onclick="toggleImage({{$key+1}})" style="background-color: #00205c; color: #fff;" title="Copy">
                                <i class="info-icon">ℹ</i>
                                </a>

                                <div id="imageContainer_{{$key+1}}" style="display: none;">
                                <audio class="video-btn mt-2" class="audioPlayer" controls="" src="{{ url('uploads/aimodule') }}/{{ $cdata->prompt_screenshot ? $cdata->prompt_screenshot : '' }}"></audio>
                                <!-- <img class="video-btn"
                                        src="{{ url('uploads/aimodule') }}/{{ $cdata->prompt_screenshot ? $cdata->prompt_screenshot : 'no_image.png' }}"
                                        alt="Image" id="imageToShow_{{$key+1}}" />-->
                                </div> 
                            </a>
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
                            <h3><b>&nbsp;&nbsp;&nbsp;&nbsp;Hello there!</b></h3>
                        </div>
                        <div class="faq-question">
                            <span>&nbsp;&nbsp;&nbsp;&nbsp;Enter your own thoughts below and let ChatGPT add it's magic to them!</span>
                        </div>
                        @if (!empty($aimodules->AddOwnactivitiesdata))
                            @foreach ($aimodules->AddOwnactivitiesdata as $key => $cdata)
                            <div class="row m-2">
                            
                            <div class="col-md-6 mt-2">
                                <div>
                                    <textarea name="ask_question_copy_paste" rows="4" class="form-control"
                                     id="ask_question_copy_paste_{{$key+1}}" placeholder="{{ isset($cdata->add_placeholder_text) ? $cdata->add_placeholder_text : '' }}"></textarea>
                                </div>
                            </div>
                            <div class="col-md-2  mt-2" style="width: 12.333333%">
                                <a href="#" class="waves-effect waves-light btn" id="CopyPastebutton_{{$key+1}}" onclick="copyTextareaContent({{$key+1}})" style="background-color: #00205c; color: #fff;" title="Copy"><i class="fa fa-copy"></i>&nbsp;Copy</a> 
                            </div>
                            <div class="col-md-4 mt-2" style="width: 18.333333%">
                                <a href="{{ isset($cdata->add_ai_toll_url) ? $cdata->add_ai_toll_url : '' }}" target="_blank" class="waves-effect waves-light btn" 
                                 style="background-color: #00205c; color: #fff;">Open {{ isset($cdata->toll_name) ? $cdata->toll_name : '' }}</a> 
                            </div>
                            
                            </div>
                            @endforeach
                        @endif
                        
                    </div>
                </div> 
                @endif
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
function toggleImage(key) {
    var imageContainer = document.getElementById('imageContainer_' + key);
    imageContainer.style.display = (imageContainer.style.display === 'none') ? 'block' : 'none';
}
$(document).ready(function() {
    var fieldinput = 1;

    $("#add-field-input").click(function() {
        var uniqueId = fieldinput;

        var newRow =
            $(
                '<form name="chatgptcreateform_' + uniqueId + '" id="chatgptcreateform_' + uniqueId + '" method="POST">' +
                '<div class="row m-2" >' +
                '<div class="col-md-6 mt-2">' +
                

                '<div class="faq-item" style="background-color:  #00205c;  color: #fff;">' +
                        '<input type="file" name="ask_photo_video" id="ask_photo_video_' + uniqueId + '" required style="font-size: 16px;">'+
                            '<br>'+
                        '<div class="prompt_one_photo_video_' + uniqueId + '" style="display: none;">'+
                        
                        '</div>'+
                        
                        '<div class="Own-Prompts-text_' + uniqueId + '" style="display: none;">'+
                        '</div>'+
                        '<p style="font-size: 16px;" class="api-answer-content mt-2"></p>'+
                '</div>'+




                


                '</div>' +
                '<div class="col-md-2 mt-2" style="width: 12.333333%">' +
                '<button type="button" class="btn chatgpt-search-button" id="chatgptsearchbutton_' + uniqueId + '" data-id="' + uniqueId + '" style="background-color: #00205c; color: #fff; font-weight: bold;"><span id="spinner_new_prompt_' + uniqueId + '" class="spinner-border spinner-border-sm " style="color: #fff; display: none;" role="status"  aria-hidden="true" ></span> Submit</button>' +
                '</div>' +
                '<div class="col-md-4 mt-2 text-center" style="width: 15.333333%">' +
                '<button type="button" class="btn btn-danger remove-field" data-id="' + uniqueId + '">Remove</button>' +
                '</div>' +
                '<div class="faq-answer mt-2">' +
                    '<div   id="chatgptanswer_' + uniqueId + '" style="display: none;">' +
                       '<audio class="player_Own_Prompts" controls></audio>' +
                    '</div>' +
                    '<p class="answer-content"></p>' +
                '</div> ' +
                '</div>' +
                '</form>'
            );

        $("#Createform").append(newRow);

        newRow.find(".chatgpt-search-button").click(function() {
            var id = $(this).data("id");
          //  var formArray = $("#chatgptcreateform_" + id).serializeArray();

            var formData = new FormData($("#chatgptcreateform_" + id)[0]); 

            /* Add click time */
//var create_one = 1;
var button_new = document.getElementById('chatgptsearchbutton_'+id);
//alert(433);
//console.log(button_new,"button_new");
        var spinner_new = document.getElementById('spinner_new_prompt_'+id);
        spinner_new.style.display = 'inline-block';
        button_new.disabled = true;


            $.ajax({
                url: '{{ route("bird-classifier") }}',
                type: 'post',
                data: formData,
                //dataType: 'json',
                processData: false, 
                contentType: false, 
                success: function(response) {
                console.log(response,"response");
                console.log(response[0]['scientificName'],"response123");
                spinner_new.style.display = 'none';
                button_new.disabled = false;
if (response && response.length > 0 && response[0]['scientificName']) {
    console.log("Scientific name found:", response[0]['scientificName']);
    $(".Own-Prompts-text_"+ id).siblings('p').addClass('text-white').html(response[0]['scientificName']);
} else {
}


            },
                error: function() {                        
                    console.log("Something went wrong");
                    var error_msg = jqXHR.responseJSON.error;
                   $(".Own-Prompts-text_"+ id).siblings('p').addClass('text-danger').html(error_msg);
                    spinner.style.display = 'none';
                    console.log("Something went wrong");
                    /* var errors = response['errors'];
                     $.each(errors, function(key,value){
                        $(`#${key}`).siblings('p').addClass('text-danger').html(value);
                    }) */
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



$(document).ready(function () {
    $(".faq-question").click(function () {
        var answer = $(this).next(".faq-answer");
        var icon = $(this).find(".toggle-icon button");

        if (answer.is(":visible")) {
            answer.slideUp();
            icon.text("Generate");
        } else {
            answer.slideDown();
            icon.text("Generate");

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
$('#chatgptcreateform').submit(function(event) {
  //  alert(345);
    event.preventDefault();
    var formData = new FormData();
    formData.append('ask_photo_video', $('#ask_photo_video')[0].files[0]);
    var spinner = document.getElementById('spinner_new_prompt');
    var button = document.getElementById('chatgptsearchbutton');
    spinner.style.display = 'inline-block';
    button.disabled = true;

    $.ajax({
        url: '{{ route("bird-classifier") }}',
        type: 'post',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            spinner.style.display = 'none';
           button.disabled = false;
            /* var paragraphElement = document.getElementById('Own-Prompts-text');
            if (paragraphElement) {
                paragraphElement.innerHTML = response.messages;
            } */
            if (response && response.length > 0 && response[0]['scientificName']) {
                $(".prompt_one_photo_video").siblings('p').addClass('text-white').html(response[0]['scientificName']);
            }
           // console.log(imageText);

           // console.log(response[0]['scientificName'],"response");
           // spinner.style.display = 'none';
           // var audioUrl = URL.createObjectURL(response);
          //  var $audioElement = $("#chatgptanswer");
         //   $audioElement.attr('src', audioUrl);
         //   $audioElement[0].play();
         //   $("#chatgptanswer").show();


        /*  var  requestId = response.data;   
            setTimeout(function() {
            $.ajax({
             url: '{{ route("output-photo-video") }}',
             type: 'post',
             data: {
                taskId : requestId,
            },
            dataType: 'json',
             success: function(response){

            var videoUrl = response.data.previewUrl;
            var videoElement = document.querySelector('.prompt_one_photo_video .mediaPlayerOwn');
            videoElement.src = videoUrl;
            videoElement.addEventListener('loadedmetadata', function() {
                videoElement.play();
            });
            var chatElement = document.querySelector('.prompt_one_photo_video');
            chatElement.style.display = 'block';
              spinner_new.style.display = 'none';
              button_new.disabled = false;
             },
             error: function(){
                var value = responseanswer;
                    var words = value.split(' '); 
                    var index = 0;
                    var interval = 100; 
                    var displayNextWord = function() {
                        if (index < words.length) {
                            $(".api-false-time-answer_"+key).siblings('p').addClass('text-black').html(words.slice(0, index + 1).join(' '));
                            index++;
                        } else {
                            clearInterval(wordDisplayInterval);
                         }
                    };
                    var wordDisplayInterval = setInterval(displayNextWord, interval);
                console.log("Someggggg things went wrong");
             }
           }); 
        }, 7000);  */
        },
        error: function(jqXHR, textStatus, errorThrown) {
    console.log(jqXHR,"jqXHR");
    console.log(textStatus,"textStatus");
    console.log(errorThrown,"errorThrown");
     var error_msg = jqXHR.responseJSON.error;
     $(".Own-Prompts-text").siblings('p').addClass('text-danger').html(error_msg);
   // console.log(jqXHR.responseJSON.error);
    spinner.style.display = 'none';
    console.log("Something went wrong");
}
    });
});


function ChatGptPrompts(file, responseanswer, key){
       console.log(file,"file");
          var button = document.getElementById('btncustom_'+key);
        var spinner = document.getElementById('spinner_'+key);
        spinner.style.display = 'inline-block';
        button.disabled = true;
        
           $.ajax({
             url: '{{ route("generate-photo-video") }}',
             type: 'post',
             data: {
                ask_question_chatGpt : file,
            },
            dataType: 'json',
             success: function(response){
            console.log(response,"responseww");
            console.log(response.data,"response333"); 

            var  requestId = response.data;   
            setTimeout(function() {
            $.ajax({
             url: '{{ route("output-photo-video") }}',
             type: 'post',
             data: {
                taskId : requestId,
            },
            dataType: 'json',
             success: function(response){
            var videoUrl = response.data.previewUrl;
            var videoElement = document.querySelector('.chat-gpt-api-ansawer_' + key + ' .mediaPlayer');
            videoElement.src = videoUrl;
            videoElement.addEventListener('loadedmetadata', function() {
                videoElement.play();
            });
            var chatElement = document.querySelector('.chat-gpt-api-ansawer_' + key);
            chatElement.style.display = 'block';
              spinner_new.style.display = 'none';
              button_new.disabled = false;
             },
             error: function(){
                var value = responseanswer;
                    var words = value.split(' '); 
                    var index = 0;
                    var interval = 100; 
                    var displayNextWord = function() {
                        if (index < words.length) {
                            $(".api-false-time-answer_"+key).siblings('p').addClass('text-black').html(words.slice(0, index + 1).join(' '));
                            index++;
                        } else {
                            clearInterval(wordDisplayInterval);
                         }
                    };
                    var wordDisplayInterval = setInterval(displayNextWord, interval);
                console.log("Someggggg things went wrong");
             }
           }); 
        }, 7000); 

 
             }, 
              error: function(){
                var value = responseanswer;
                  spinner.style.display = 'none';
                    var words = value.split(' '); 
                    var index = 0;
                    var interval = 100; 
                    var displayNextWord = function() {
                        if (index < words.length) {
                            $(".api-false-time-answer_"+key).siblings('p').addClass('text-black').html(words.slice(0, index + 1).join(' '));
                            index++;
                        } else {
                            clearInterval(wordDisplayInterval);
                         }
                    };
                    var wordDisplayInterval = setInterval(displayNextWord, interval);
                console.log("Someggggg things went wrong");
             }
           });    
        }; 


</script>

@endsection
