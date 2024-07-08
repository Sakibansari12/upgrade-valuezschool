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
                <!-- <h2 ><b>{{isset($aimodules->content->title) ? $aimodules->content->title : ''}}</b></h2>
                <hr > -->
                

    <section style="margin-top: 8px; width: 100%;">
    <h4><b>Fun with AI : {{ isset($formattedType) ? $formattedType : '' }}</b></h4>
    <hr>
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
                    @if (!empty($aimodules->visionData))
                    <div class="faq-section">
                        @foreach ($aimodules->visionData as $key => $cdata)
                        <div class="faq-item" style="background-color: #00205c; color: #fff;">
                            <!-- <div class="row">
                                <div class="col-md-3">
                                    <img 
                                    src="{{ url('uploads/vision') }}/{{ $cdata->vision_image_new ? $cdata->vision_image_new : '' }}"
                                    width="220" 
                                    height="220"
                                    style="border-radius: 20px; "
                                        />
                                </div>
                                <div class="col-md-3" style="margin-top: 75px;">
                                    <button type="button" class="btn" id="btncustom_{{$key+1}}" onclick="ChatGptPrompts({{ json_encode(isset($cdata->vision_text) ? $cdata->vision_text : '') }}, {{ json_encode(isset($cdata->vision_text) ? $cdata->vision_text : '') }}, {{$key + 1}})" style="background-color: #fee665; color: #00205c;">
                                    Generate&nbsp;<span id="spinner_{{$key+1}}" class="spinner-border spinner-border-sm" style="color: #00205c; display: none;" role="status" aria-hidden="true"></span>
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    <div class="vision_text_{{ $key + 1}} typing"></div>
                                    <p style="font-size: 16px; margin-top: 50px;"></p>
                                </div>
                                <div class="col-md-3" style="margin-top: 75px;">
                                    <audio id="music_{{ $key + 1 }}" style="display: none;" src="{{ url('uploads/vision') }}/{{ $cdata->vision_music_new ? $cdata->vision_music_new : '' }}" controls></audio>
                                </div>
                            </div> -->
                            <div class="row">
                                <div style="width: 30%; ">
                                        <img 
                                            src="{{ url('uploads/vision') }}/{{ $cdata->vision_image_new ? $cdata->vision_image_new : '' }}"
                                            width="220" 
                                            height="220"
                                            style="border-radius: 20px;"
                                        />
                                    </div>
                                    <div style="width: 10%;" class="d-flex align-items-center">
                                        <button type="button" class="btn" id="btncustom_{{$key+1}}" onclick="ChatGptPrompts({{ json_encode(isset($cdata->vision_text) ? $cdata->vision_text : '') }}, {{ json_encode(isset($cdata->vision_text) ? $cdata->vision_text : '') }}, {{$key + 1}})" style="background-color: #fee665; color: #00205c;">
                                            Generate&nbsp;<span id="spinner_{{$key+1}}" class="spinner-border spinner-border-sm" style="color: #00205c; display: none;" role="status" aria-hidden="true"></span>
                                        </button>
                                    </div>
                                    <div style="width: 40%;">
                                        <div class="vision_text_{{ $key + 1 }} typing"></div>
                                        <p style="font-size: 16px; margin-top: 50px; text-align: justify;"></p>
                                    </div>
                                    <div style="width: 20%;" class="d-flex align-items-center">
                                        <audio id="music_{{ $key + 1 }}" style="display: none;" src="{{ url('uploads/vision') }}/{{ $cdata->vision_music_new ? $cdata->vision_music_new : '' }}" controls></audio>
                                    </div>
                                </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                <!-- Add an input field and submit button here -->
                <div class="faq-section">
                     
                     <div class="faq-item">
                         <div class="faq-question">
                         <h3><b>Image to Narration</b></h3>
                         </div>
                     <div class="faq-question">
                     <span>{!! isset($aimodules->own_description) ? $aimodules->own_description : '' !!}</span>
                     </div>
                     <div id="Createform">
                     <form name="chatgptcreateform" id="chatgptcreateform" method="POST">
                             <div class="row" >
                                 <div class=" col-md-6 ">
                                     <!-- <div>
                                         <textarea  name="ask_question_chatGpt" rows="4" class="form-control" id="ask_question_chatGpt" required placeholder="Enter Question to ChatGpt"></textarea>
                                     </div> -->
                                     <div class="faq-item" style="background-color:  #00205c;  color: #fff;">
                                          <input type="file" name="ask_photo_video" id="ask_photo_video" required style="font-size: 16px;" onchange="plantImageOne(event)">
                                          <img id="plantOneImage" class="mt-2" src="#" style="max-width: 150px; max-height: 150px; display: none;">
                                          <br>
                                          
                                           <div class="image-to-narration-one" style="display: none;">
                                               <audio class="ImageNarrationaudioPlayer" controls></audio>
                                            </div>
                                            <br>
                                            <div class="prompt_one_photo_anime mt-2">
                                               
                                           </div>
                                          <!-- <div class="Own-Prompts-text" style="display: none;">
                                          </div> -->
                                          <p style="font-size: 16px;" class="api-answer-content mt-2"></p>
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
                 <!-- <section class="faq-section">
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
                                </div> 
                            </a>
                            </li>
                            @endforeach
                        @endif
                        </ul>
                    </div>
                    <hr>
                </section> -->

<!-- Slider Start-->
<section class="vision">
      <div class="container">
        <div class="row my-5">
          <div class="d-flex flex-column">
            <div class="row mt-2">
                <div class="faq-question">
                    <h3><b>Let’s see some funny and awesome creation by {{ isset($formattedType) ? $formattedType : '' }} AI</b></h3>                   
                </div>
              <div class=" row justify-content-center gap-3 py-5 vision_box" style="margin-top: 30px;">
              @if (!empty($aimodules->sliderData))
                  @foreach ($aimodules->sliderData as $key => $cdata)
                <div class="vision_box_text col-md-4 ">
                  <span>
                     <img class="video-btn" 
                     data-id-play="{{ $cdata->id }}"
                     data-bs-toggle="modal"
                     data-src="{{ $cdata->slider_video }}"
                     data-bs-target="#bs-video-modal"
                     style="border-radius: 20px; margin-top: -32px;" src="{{ url('uploads/hybrid') }}/{{ $cdata->slider_image_new ? $cdata->slider_image_new : 'no_image.png' }}" />
                  </span>
                  <p id="rcorners1" style="text-align:center" class="mt-3">{{ $cdata->slider_text }} <br>
                  </p>
                </div>
                @endforeach
                  @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
<!-- Slider End-->

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
                            <div class="row ">
                            
                            <!-- <div class="col-md-6 mt-2">
                                <div>
                                    <textarea name="ask_question_copy_paste" rows="4" class="form-control"
                                     id="ask_question_copy_paste_{{$key+1}}" placeholder="{{ isset($cdata->add_placeholder_text) ? $cdata->add_placeholder_text : '' }}"></textarea>
                                </div>
                            </div> -->
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
    <!-- Main Video Modal -->
<div class="modal fade" id="bs-video-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="ribbon-box video-close">
                    <div class="ribbon ribbon-danger float-end btn-close1" data-bs-dismiss="modal" aria-label="Close"
                        style="cursor: pointer;">
                        <i class="mdi mdi-close"></i>
                    </div>
                    <h5 class="text-danger float-start m-0 p-3" id="video-title">Video Player</h5>
                </div>
                <div class="modal-body pt-0">
                    <div id="video-loader" class="text-center"><i class="fa fa-spinner fa-spin fs-1"></i></div>
                    <!-- 16:9 aspect ratio -->
                    <div class="ratio ratio-16x9">
                        <iframe class="embed-responsive-item" frameborder="0" src="" id="video"
                            allowscriptaccess="always" allow="autoplay" webkitallowfullscreen mozallowfullscreen
                            allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- </div> -->

    <script src="{{ asset('assets/src/js/chatgpt_js.js') }}"></script>

    <!-- Your ChatGPT page content goes here -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function plantImageOne(event) {
      const input = event.target;
      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          const imagePreview = document.getElementById('plantOneImage');
          imagePreview.src = e.target.result;
          imagePreview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
      }else {
            imagePreview.style.display = 'none'; 
        }
    }
    function SecondplantImage(event, uniqueId) {
    const input = event.target;
    const imagePreview = document.getElementById('plantSecondImage_' + uniqueId);

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            imagePreview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }else {
            imagePreview.style.display = 'none'; // Hide the image if no file is selected
        }
}
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
                '<div class="row" >' +
                '<div class="col-md-6 mt-2">' +
                

                '<div class="faq-item" style="background-color:  #00205c;  color: #fff;">' +
                        '<input type="file" name="ask_photo_video" id="ask_photo_video_' + uniqueId + '" style="font-size: 16px;" onchange="SecondplantImage(event,' + uniqueId + ')" required>'+
                          '<img id="plantSecondImage_' + uniqueId + '" class="mt-2" src="#" style="max-width: 150px; max-height: 150px; display: none;"">' +  
                        '<br>'+


                        '<div class="image-to-narration-second_' + uniqueId + '" style="display: none;">' +
                                '<audio class="ImageNarrationaudioPlayer_' + uniqueId + '" controls></audio>' +
                         '</div>'+
                         '<br>'+



                        '<div class="prompt_one_photo_video_' + uniqueId + ' mt-2" style="display: none;">'+
                        
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
        var formData = new FormData($("#chatgptcreateform_" + id)[0]); 
            /* Add click time */
        var button_new = document.getElementById('chatgptsearchbutton_'+id);
        var spinner_new = document.getElementById('spinner_new_prompt_'+id);
         spinner_new.style.display = 'inline-block';
         button_new.disabled = true;
            $.ajax({
                url: '{{ route("image_narration") }}',
                type: 'post',
                data: formData,
                //dataType: 'json',
                processData: false, 
                contentType: false, 
                dataType: 'json',
                success: function(response) {
                     
                    /* var PhotoAnime = document.querySelector('.prompt_one_photo_video_'+ id);
                    if (response && response.response && response.response.body && response.response.body.imageUrl) {
                        var img = document.createElement('img');
                        img.src = response.response.body.imageUrl;
                        img.style.maxWidth = '150px'; 
                        img.style.height = '150px'; 
                        PhotoAnime.appendChild(img);
                        PhotoAnime.style.display = 'block';
                    } */  
                    var text_to_speech = response.choices[0].message.content;



                    $.ajax({
             url: '{{ route("search-text-to-speech") }}',
             type: 'post',
             data: {
                ask_question_chatGpt : text_to_speech,
            },
            xhrFields: {
                    responseType: 'blob' 
                },
             success: function(response){

                var PhotoAnime = document.querySelector('.prompt_one_photo_video_'+ id);
                var value = text_to_speech;
                var words = value.split(' '); 
                var index = 0;
                var interval = 100; 
                var displayNextWord = function() {
                    if (index < words.length) {
                        $(".prompt_one_photo_video_"+ id).siblings('p').addClass('text-black').html(words.slice(0, index + 1).join(' '));
                        index++;
                    } else {

                        clearInterval(wordDisplayInterval);
                    }
                };
                var wordDisplayInterval = setInterval(displayNextWord, interval);


                var audioUrl = URL.createObjectURL(response);
                var $audioElement = $(".image-to-narration-second_"+ id).find('.ImageNarrationaudioPlayer_'+ id);
                $audioElement.attr('src', audioUrl);
                $audioElement[0].play();
                $(".image-to-narration-second_"+ id).show();

                button_new.disabled = false;
                spinner_new.style.display = 'none';

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
                button_new.disabled = false;
                spinner_new.style.display = 'none';
             }
           });












                    spinner_new.style.display = 'none';
                    button_new.disabled = false;

                    },
                error: function() {                        
                    console.log("Something went wrong");
                    var error_msg = jqXHR.responseJSON.error;
                   $(".Own-Prompts-text_"+ id).siblings('p').addClass('text-danger').html(error_msg);
                   spinner_new.style.display = 'none';
                    button_new.disabled = false;
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
    event.preventDefault();
    var formData = new FormData();
    formData.append('ask_photo_video', $('#ask_photo_video')[0].files[0]);
    var spinner = document.getElementById('spinner_new_prompt');
    var button = document.getElementById('chatgptsearchbutton');
    spinner.style.display = 'inline-block';
    button.disabled = true;
    $.ajax({
        url: '{{ route("image_narration") }}',
        type: 'post',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            console.log(response.choices[0].message.content,"response one");
            var text_to_speech = response.choices[0].message.content;
            $.ajax({
             url: '{{ route("search-text-to-speech") }}',
             type: 'post',
             data: {
                ask_question_chatGpt : text_to_speech,
            },
            xhrFields: {
                    responseType: 'blob' 
                },
             success: function(response){


                var value = text_to_speech;
                var words = value.split(' '); 
                var index = 0;
                var interval = 100; 
                var displayNextWord = function() {
                    if (index < words.length) {
                        $(".prompt_one_photo_anime").siblings('p').addClass('text-black').html(words.slice(0, index + 1).join(' '));
                        index++;
                    } else {

                        clearInterval(wordDisplayInterval);
                    }
                };
                var wordDisplayInterval = setInterval(displayNextWord, interval);


                var audioUrl = URL.createObjectURL(response);
                var $audioElement = $(".image-to-narration-one").find('.ImageNarrationaudioPlayer');
                $audioElement.attr('src', audioUrl);
                $audioElement[0].play();
                $(".image-to-narration-one").show();

                button.disabled = false;
                spinner.style.display = 'none';

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
                button.disabled = false;
           spinner.style.display = 'none';
             }
           });
           button.disabled = false;
           spinner.style.display = 'none';
        },
        error: function(jqXHR, textStatus, errorThrown) {
        var error_msg = jqXHR.responseJSON.error;
        $(".Own-Prompts-text").siblings('p').addClass('text-danger').html(error_msg);
        button.disabled = false;
       spinner.style.display = 'none';
       }
    });
});

function ChatGptPrompts(file, responseanswer, key){
    var button = document.getElementById('btncustom_' + key);
    var spinner = document.getElementById('spinner_' + key);
    spinner.style.display = 'inline-block';
    button.disabled = true;

    setTimeout(function() {
    spinner.style.display = 'none';
    button.disabled = false;
        /* var audioDiv = document.getElementById('music_' + key);
        audioDiv.style.display = 'block';
        var audio = audioDiv.querySelector('audio');
        if (audio) {
            audio.play();
        } */
        var audioDiv = document.getElementById('music_' + key);
        if (audioDiv) {
            audioDiv.style.display = 'block'; 
            if (audioDiv.paused) {
                audioDiv.play(); 
            } 
        } 





            var value = responseanswer;
                  spinner.style.display = 'none';
                    var words = value.split(' '); 
                    var index = 0;
                    var interval = 200; 
                    var displayNextWord = function() {
                        if (index < words.length) {
                            $(".vision_text_"+key).siblings('p').addClass('text-black').html(words.slice(0, index + 1).join(' '));
                            index++;
                        } else {
                            clearInterval(wordDisplayInterval);
                         }
                    };
                    var wordDisplayInterval = setInterval(displayNextWord, interval);
                 }, 5000); 
};


/* function ChatGptPrompts(prompts, responseanswer, key){
       console.log(prompts,"prompts");
          var button = document.getElementById('btncustom_'+key);
        var spinner = document.getElementById('spinner_'+key);
        spinner.style.display = 'inline-block';
        button.disabled = true;
        
           $.ajax({
             url: '{{ route("generate-avatar") }}',
             type: 'post',
             data: {
                ask_question_chatGpt : prompts,
            },
            dataType: 'json',
             success: function(response){
            var requestId = response.asset_ids;
            var divElement = document.querySelector('.chat-gpt-api-answer_'+key);
            divElement.innerHTML = '';
            if (requestId && requestId.length > 0) {
                for (var i = 0; i < requestId.length; i++) {
                    var assetId = requestId[i]; 
                    var value = assetId.thumbnailSrc;  
                    var imgElement = document.createElement('img');
                    imgElement.src = value; 
                    imgElement.alt = 'Response Image'; 
                    imgElement.style.display = 'inline-block'; 
                    imgElement.style.marginRight = '10px'; 
                    imgElement.style.marginTop = '8px'; 
                    divElement.appendChild(imgElement);
                }
                divElement.style.display = 'block';
            } else {
                divElement.style.display = 'none';
            }

            spinner.style.display = 'none';
            button.disabled = false;
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
 */
$(document).ready(function() {
            var $videoSrc;
            $('.video-btn').click(function() {
                var vidType = $('input[name="vidType"]:checked').val();
                if (vidType == 'mayra') {
                    $videoSrc = $(this).data("msrc");
                } else {
                    $videoSrc = $(this).data("src");
                }
                $("#video-title").text($(this).data("title"));
                $("#video-loader").show();
            });
            $('#bs-video-modal').on('shown.bs.modal', function(e) {
                setTimeout(() => {
                    $("#video").attr('src', $videoSrc);
                    $("#video-loader").hide();
                }, 200);
            })
            $('#bs-video-modal').on('hide.bs.modal', function(e) {
                $("#video").attr('src', "");
            })
        });
</script>

@endsection
