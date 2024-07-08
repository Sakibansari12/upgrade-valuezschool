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
                <!-- <h2 ><b>{{isset($aimodules->module_page_title) ? $aimodules->module_page_title : ''}}</b></h2>
                <hr > -->
                

    <section style="margin-top: 8px; width: 100%;">
    <h4><b>Fun with AI : {{ isset($formattedType) ? $formattedType : '' }}</b></h4>
    <div>
        <p><h5 style="width: 100%;">
            {!! isset($aimodules->module_page_overview) ? $aimodules->module_page_overview : '' !!}
            
        </h5></p>
    </div>
    <hr>
</section>
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
                    @if (!empty($aimodules->promptsdata))
                    <div class="faq-section">
                        @foreach ($aimodules->promptsdata as $key => $cdata)
                        <div class="faq-item" style="background-color: #00205c; color: #fff;">
                            <div class="row">
                                <div style="width: 1%; ">
                                    <h5><b>{{ $key + 1 }}</b></h5>
                                </div>
                                <div style="width: 40%;">
                                    <audio class="audioPlayer" id="audioPlayer_{{$key+1}}"  src="{{ url('uploads/aimodule') }}/{{ isset($cdata->file) ? $cdata->file : '' }}" controls></audio>
                                    <input type="hidden" id="musicfile_{{$key+1}}" name="musicfile" value="{{ json_encode($cdata->file_all_data) }}">
                                </div>
                                <div style="width: 46%" class="custom-response">
                                   {!! $cdata->response ? $cdata->response : '' !!}
                                </div>
                                <div style="width: 2%;" class="faq-question">
                                <button type="button" class="btn" id="btncustom_{{$key+1}}" onclick="ChatGptPrompts({{ json_encode(isset($cdata->prompts) ? $cdata->prompts : '') }}, {{ json_encode(isset($cdata->response) ? $cdata->response : '') }}, {{$key + 1}})" style="background-color: #fee665; color: #00205c; width: 120px;">
                                    Generate&nbsp;<span id="spinner_{{$key+1}}" class="spinner-border spinner-border-sm" style="color: #00205c; display: none;" role="status" aria-hidden="true"></span>
                                </button>
                                </div>
                            </div>
                            <div class="row">
                                <div style="width: 1%; ">
                                </div>
                                <div  style="width: 85%;">
                                    <div class="chat-gpt-api-ansawer_{{ $key + 1}}" style="display: none;">
                                       <div class="typing">
                                       </div>
                                    </div>
                                     <p style="font-size: 16px; text-align: justify;" class="api-answer-content"></p>
                                    <div>
                                        <div class="api-false-time-answer_{{ $key + 1}} typing" >
                                        </div>
                                        <p style="font-size: 16px;" class="api-answer-content"></p>
                                    </div>
                                </div>
                                <div style="width: 2%;">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
              <div class="faq-section">
                     
                        <div class="faq-item">
                            <div class="faq-question">
                            <h3><b>Try your own prompts</b></h3>
                            </div>
                        <div class="faq-question">
                          <span>{!! isset($aimodules->own_description) ? $aimodules->own_description : '' !!}</span>
                        </div>
                        <div id="Createform">
                        <form name="chatgptcreateform" id="chatgptcreateform" method="POST">
                                <div class="row" >
                                    <div class=" col-md-6 ">
                                        <div class="faq-item" style="background-color:  #00205c;  color: #fff;">
                                             <input type="file" name="ask_speech_to_text" id="ask_speech_to_text" style="font-size: 16px;">
                                             <br>
                                             <audio class="mt-2" controls id="audioPlayer" style="display: none;">
    
                                             </audio>

                                             <div class="Own-Prompts-text" style="display: none;">
                                             </div>
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
                                <img class="video-btn mt-2" class="audioPlayer" controls="" src="{{ url('uploads/aimodule') }}/{{ $cdata->prompt_screenshot ? $cdata->prompt_screenshot : '' }}" />
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
                '<div class="row" >' +
                '<div class="col-md-6 mt-2">' +
                '<div class="faq-item" style="background-color:  #00205c;  color: #fff;">' +
                '<input type="file" name="ask_speech_to_text" id="ask_speech_to_text_' + uniqueId + '" style="font-size: 16px;">' +
                '<br>' +
                '<audio class="mt-2" controls id="audio_second_Player_' + uniqueId + '" style="display: none;">' +
                '</audio>' +  

                '<div id="chatgptanswer_' + uniqueId + '" style="display: none;">' +
                '</div>' +
                '<p style="font-size: 16px;" class="answer-content mt-2"></p>' +
                '</div>' +
                '</div>' +
                '<div class="col-md-2 mt-2" style="width: 10.333333%">' +
                '<button type="button" class="btn chatgpt-search-button" id="chatgptsearchbutton_' + uniqueId + '" data-id="' + uniqueId + '" style="background-color: #00205c; color: #fff; font-weight: bold;"><span id="spinner_new_prompt_' + uniqueId + '" class="spinner-border spinner-border-sm " style="color: #fff; display: none;" role="status"  aria-hidden="true" ></span> Submit</button>' +
                '</div>' +
                '<div class="col-md-4 mt-2 text-center" style="width: 15.333333%">' +
                '<button type="button" class="btn btn-danger remove-field" data-id="' + uniqueId + '">Remove</button>' +
                '</div>' +
                '</div>' +
                '</form>'
            );
        $("#Createform").append(newRow);

// Attach event listener AFTER appending to the DOM
document.getElementById('ask_speech_to_text_' + uniqueId).addEventListener('change', function(e) {
    const file = e.target.files[0];
    console.log(file, "file");
    const audioPlayerSecond = document.getElementById('audio_second_Player_' + uniqueId);
    if (file) {
        const url = URL.createObjectURL(file);
        audioPlayerSecond.src = url;
        audioPlayerSecond.style.display = 'block';
    } else {
        audioPlayerSecond.src = '';
        audioPlayerSecond.style.display = 'none';
    }
});


    newRow.find(".chatgpt-search-button").click(function() {
    var id = $(this).data("id");
    var formData = new FormData($("#chatgptcreateform_" + id)[0]); 

    var button_new = document.getElementById('chatgptsearchbutton_' + id);
    var spinner_new = document.getElementById('spinner_new_prompt_' + id);
    spinner_new.style.display = 'inline-block';
    button_new.disabled = true;
    var audioElement_Play = document.getElementById('audio_second_Player_' + id);
    $.ajax({
        url: '{{ route("search-own-prompts-speech-text") }}',
        type: 'post',
        data: formData,
        processData: false, 
        contentType: false, 
        success: function(response) {
            spinner_new.style.display = 'none';
            button_new.disabled = false;
            if (response.translated_audio) {
                spinner_new.style.display = 'none';
                button_new.disabled = false;
                var value = response.translated_audio;
               // console.log(value,"value");
                var words = value.split(' ');
                var index = 0;
                var interval = 300;
                var displayNextWord = function() {
                    if (index < words.length) {
                        $("#chatgptanswer_" + id).siblings('p').addClass('text-black').html(words.slice(0, index + 1).join(' '));
                        index++;
                    } else {
                        clearInterval(wordDisplayInterval);
                    }
                };
                var wordDisplayInterval = setInterval(displayNextWord, interval);
                audioElement_Play.play();
            } else {
                console.log("No translated text available.");
            }
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
            }, 100); 
        }
    });
});

</script>

<script>
    document.getElementById('ask_speech_to_text').addEventListener('change', function(e) {
      const file = e.target.files[0];
      const audioPlayer = document.getElementById('audioPlayer');
      if (file) {
        const url = URL.createObjectURL(file);
        audioPlayer.src = url;
        audioPlayer.style.display = 'block';
      } else {
        audioPlayer.src = '';
        audioPlayer.style.display = 'none';
      }
    });
</script>


<script>
document.addEventListener("DOMContentLoaded", function () {
    var copyPromptButton = document.getElementById("copyPrompt");
    var questionText = document.getElementById("questionText");

    copyPromptButton.addEventListener("click", function (event) {
        event.preventDefault();
        var textArea = document.createElement("textarea");
        textArea.value = questionText.innerText;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand("copy");
        document.body.removeChild(textArea);
        alert("Prompt copied to clipboard!");
    });
});
function copyTextareaContent(key) {
        var textarea = document.getElementById("ask_question_copy_paste_"+ key);
        textarea.select();
        textarea.setSelectionRange(0, 99999); 
        document.execCommand("copy");
        alert("Text copied to clipboard!");
    }
$('#chatgptcreateform').submit(function(){
        event.preventDefault();
        var formData = new FormData();
        formData.append('ask_speech_to_text', $('#ask_speech_to_text')[0].files[0]);
        document.getElementById("chatgptsearchbutton").disabled = true;
        var button = document.getElementById('chatgptsearchbutton');
        var audioElement = document.getElementById('audioPlayer');
        var spinner = document.getElementById('spinner_new_prompt');
        spinner.style.display = 'inline-block';
        button.disabled = true;
           $.ajax({
             url: '{{ route("speech-to-text-own-prompts") }}',
             type: 'POST',
             processData: false,
             contentType: false,
              data: formData,
             success: function(response){
                if (response.translated_audio) {
                spinner.style.display = 'none';
                button.disabled = false;
                var value = response.translated_audio;
                console.log(value,"value");
                var words = value.split(' ');
                var index = 0;
                var interval = 300;
                var displayNextWord = function() {
                    if (index < words.length) {
                        $(".Own-Prompts-text").siblings('p').addClass('text-black').html(words.slice(0, index + 1).join(' '));
                        index++;
                    } else {
                        clearInterval(wordDisplayInterval);
                    }
                };
                var wordDisplayInterval = setInterval(displayNextWord, interval);
                audioElement.play();
            } else {
                console.log("No translated text available.");
            }
          },
             error: function(){
                console.log("Some OWn things went wrong");
             }
           });
        });
        
        
        
function getFileFormat(url) {
    var index = url.lastIndexOf('.');
    if (index !== -1) {
        return url.substr(index + 1);
    } else {
        return ''; 
    }
}
/* function ChatGptPrompts(prompts, responseanswer, key) {
    var audioElement = document.getElementById('audioPlayer_' + key);
    var audioPlayerUrl = $('#musicfile_' + key).val(); 
    var button = document.getElementById('btncustom_' + key);
    var spinner = document.getElementById('spinner_' + key);
    spinner.style.display = 'inline-block';
    var formData = new FormData();
    formData.append('ask_question_chatGpt', prompts);
    formData.append('audioPlayer', audioPlayerUrl);
    $.ajax({
        url: '{{ route("search-speech-to-text") }}',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            if (response.translated_audio) {
                spinner.style.display = 'none';
                button.disabled = false;
                var value = response.translated_audio;
                var words = value.split(' ');
                var index = 0;
                var interval = 300;
                var displayNextWord = function() {
                    if (index < words.length) {
                        $(".chat-gpt-api-ansawer_" + key).siblings('p').addClass('text-black').html(words.slice(0, index + 1).join(' '));
                        index++;
                    } else {
                        clearInterval(wordDisplayInterval);
                    }
                };
                var wordDisplayInterval = setInterval(displayNextWord, interval);
                audioElement.play();
            } else {
                console.log("No translated text available.");
            }
        },
        error: function() {
            spinner.style.display = 'none';
            var value = responseanswer;
            var words = value.split(' ');
            var index = 0;
            var interval = 100;
            var displayNextWord = function() {
                if (index < words.length) {
                    $(".api-false-time-answer_" + key).siblings('p').addClass('text-black').html(words.slice(0, index + 1).join(' '));
                    index++;
                } else {
                    clearInterval(wordDisplayInterval);
                }
            };
            var wordDisplayInterval = setInterval(displayNextWord, interval);
            console.log("Something went wrong");
        }
    });
} */
function ChatGptPrompts(prompts, responseanswer, key) {
    var audioElement = document.getElementById('audioPlayer_' + key);
    var audioPlayerUrl = $('#musicfile_' + key).val(); 
    var button = document.getElementById('btncustom_' + key);
    var spinner = document.getElementById('spinner_' + key);
    spinner.style.display = 'inline-block';
  //  var formData = new FormData();
   // formData.append('ask_question_chatGpt', prompts);
   // formData.append('audioPlayer', audioPlayerUrl);

   setTimeout(function() {
    var value = prompts;
    var words = value.split(' '); 
    var index = 0;
    var interval = 100; 
    var displayNextWord = function() {
        if (index < words.length) {
            $(".chat-gpt-api-ansawer_" + key).siblings('p').addClass('text-black').html(words.slice(0, index + 1).join(' '));
            index++;
        } else {
            clearInterval(wordDisplayInterval);
        }
        audioElement.play();
    };
    var wordDisplayInterval = setInterval(displayNextWord, interval);
    spinner.style.display = 'none';
    button.disabled = false;
}, 5000); // 5000 milliseconds = 5 seconds
   
}
</script>

@endsection
