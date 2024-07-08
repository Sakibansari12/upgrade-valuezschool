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
            {{isset($aimodules->content->module_page_overview) ? $aimodules->content->module_page_overview : ''}}
            
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
                    <div class="faq-section">

                    
                    <div class="faq-item" style="background-color: #00205c; color: #fff;">
                        <div class="faq-question" style="display: flex; justify-content: space-between;">
                            <h5 style="margin-right: 10px;"><b>1. Create a soundtrack that captures the wonder and excitement of exploring a magical forest filled with friendly creatures and hidden treasures.</b></h5>
                            <button type="button" class="btn" onclick="generateSoundtrack('assets/music/create_1.mp3', 'audioPlayer_1')" style="background-color: #fee665; color: #00205c;">
                                Generate&nbsp;<span class="spinner-border spinner-border-sm" style="color: rgb(0, 32, 92); display: none;" role="status" aria-hidden="true"></span>
                            </button>
                        </div>
                        <div class="chat-gpt-api-ansawer_1" style="display: none;">
                            <audio class="audioPlayer" id="audioPlayer_1" controls=""></audio>
                        </div>
                        <p style="font-size: 16px;" class="api-answer-content"></p>
                    </div>







                    <div class="faq-item" style="background-color: #00205c; color: #fff;">
                        <div class="faq-question" style="display: flex; justify-content: space-between;">
                            <h5 style="margin-right: 10px;"><b>2. Generate music that takes young listeners on an intergalactic journey through space, complete with twinkling stars, alien encounters, and epic adventures.</b></h5>
                            <button type="button" class="btn" onclick="generateSoundtrack('assets/music/generate-music_2.mp3', 'audioPlayer_2')" style="background-color: #fee665; color: #00205c;">
                                Generate&nbsp;<span class="spinner-border spinner-border-sm" style="color: rgb(0, 32, 92); display: none;" role="status" aria-hidden="true"></span>
                            </button>
                        </div>
                        <div class="chat-gpt-api-ansawer_2" style="display: none;">
                            <audio class="audioPlayer" id="audioPlayer_2" controls=""></audio>
                        </div>
                        <p style="font-size: 16px;" class="api-answer-content"></p>
                    </div>

                    <div class="faq-item" style="background-color: #00205c; color: #fff;">
                        <div class="faq-question" style="display: flex; justify-content: space-between;">
                            <h5 style="margin-right: 10px;"><b>3. Produce energetic and festive tunes to set the mood for a birthday party, with catchy rhythms and joyful melodies.</b></h5>
                            <button type="button" class="btn" onclick="generateSoundtrack('assets/music/produce_3.mp3', 'audioPlayer_3')" style="background-color: #fee665; color: #00205c;">
                                Generate&nbsp;<span class="spinner-border spinner-border-sm" style="color: rgb(0, 32, 92); display: none;" role="status" aria-hidden="true"></span>
                            </button>
                        </div>
                        <div class="chat-gpt-api-ansawer_3" style="display: none;">
                            <audio class="audioPlayer" id="audioPlayer_3" controls=""></audio>
                        </div>
                        <p style="font-size: 16px;" class="api-answer-content"></p>
                    </div>

                    <div class="faq-item" style="background-color: #00205c; color: #fff;">
                        <div class="faq-question" style="display: flex; justify-content: space-between;">
                            <h5 style="margin-right: 10px;"><b>4. Create cheerful and upbeat tunes perfect for a fun-filled day of playing and picnicking in the park.</b></h5>
                            <button type="button" class="btn" onclick="generateSoundtrack('assets/music/create_cheerful_4.mp3', 'audioPlayer_4')" style="background-color: #fee665; color: #00205c;">
                                Generate&nbsp;<span class="spinner-border spinner-border-sm" style="color: rgb(0, 32, 92); display: none;" role="status" aria-hidden="true"></span>
                            </button>
                        </div>
                        <div class="chat-gpt-api-ansawer_4" style="display: none;">
                            <audio class="audioPlayer" id="audioPlayer_4" controls=""></audio>
                        </div>
                        <p style="font-size: 16px;" class="api-answer-content"></p>
                    </div>

                    <div class="faq-item" style="background-color: #00205c; color: #fff;">
                        <div class="faq-question" style="display: flex; justify-content: space-between;">
                            <h5 style="margin-right: 10px;"><b>5. Craft a regal soundtrack fit for a royal ball in a magnificent castle, featuring elegant melodies, joyful rhythms, and a touch of fairy-tale magic.</b></h5>
                            <button type="button" class="btn" onclick="generateSoundtrack('assets/music/Royal Elegance 90bpm song_5.mp3', 'audioPlayer_5')" style="background-color: #fee665; color: #00205c;">
                                Generate&nbsp;<span class="spinner-border spinner-border-sm" style="color: rgb(0, 32, 92); display: none;" role="status" aria-hidden="true"></span>
                            </button>
                        </div>
                        <div class="chat-gpt-api-ansawer_5" style="display: none;">
                            <audio class="audioPlayer" id="audioPlayer_5" controls=""></audio>
                        </div>
                        <p style="font-size: 16px;" class="api-answer-content"></p>
                    </div>
                </div>
              <div class="faq-section">
                        <!-- <div class="faq-item">
                            
                        <div>
                        
                        </div>
                       </div> -->      
            </div>  
            </div>
                 <hr>
                 <section class="faq-section">
                    <div class="info-section">
                        <h3><b>Activities:</b></h3>
                        <p>Copy the following prompts, click on 'Open Website' and paste them in the input box.</p>
                        <ul class="p-5">
                            <li>
                                <h5 class="mt-10" id="questionText_1"><b>Produce epic and heroic music that accompanies young heroes on thrilling adventures, saving the day and defeating villains with courage and determination.</b></h5>
                                <a href="#" class="waves-effect waves-light btn" id="copyPrompt_1" style="background-color: #00205c; color: #fff;" title="Copy"><i class="fa fa-copy"></i>&nbsp;Copy</a>
                                <a href="https://pro.splashmusic.com/" target="_blank" class="waves-effect waves-light btn" style="background-color: #00205c; color: #fff;">Open Website</a>
                                

                                <div id="imageContainer_1" style="display: none;">
                                  <audio class="video-btn mt-2" controls="" src="http://localhost/valuez-hut-staging/uploads/aimodule/activity_20240304_user.png"></audio>
                                </div> 
                            
                            </li>
                            <li>
                                <h5 class="mt-10" id="questionText_2"><b>Craft playful and lively music that transports children to the heart of the jungle or savanna, where they can imagine going on a safari to spot lions, elephants, and giraffes.</b></h5>
                                <a href="#" class="waves-effect waves-light btn" id="copyPrompt_2" style="background-color: #00205c; color: #fff;" title="Copy"><i class="fa fa-copy"></i>&nbsp;Copy</a>
                                <a href="https://pro.splashmusic.com/" target="_blank" class="waves-effect waves-light btn" style="background-color: #00205c; color: #fff;">Open Website</a>
                               

                                <div id="imageContainer_1" style="display: none;">
                                <audio class="video-btn mt-2" controls="" src="http://localhost/valuez-hut-staging/uploads/aimodule/activity_20240304_user.png"></audio>
                                </div> 
                            
                            </li>
                        </ul>
                    </div>
                    <hr>
                </section>
                
            </div>
            </div>
                </div>
            </div>
        </div>
    </section>

    <script src="{{ asset('assets/src/js/chatgpt_js.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    function generateSoundtrack(audioSrc, audioPlayerId) {
        // Show the hidden audio player
        document.querySelector(`.chat-gpt-api-ansawer_${audioPlayerId.split('_')[1]}`).style.display = 'block';

        // Play the audio
        var audio = document.getElementById(audioPlayerId);
        audio.src = audioSrc;
        audio.play();
    }
</script>





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
                '<div>' +
                '<textarea name="ask_question_chatGpt" class="form-control" rows="4" id="ask_question_chatGpt_' + uniqueId + '" required placeholder="Enter Question to ChatGpt"></textarea>' +
                '</div>' +
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
            var formArray = $("#chatgptcreateform_" + id).serializeArray();
var button_new = document.getElementById('chatgptsearchbutton_'+id);
console.log(button_new,"button_new");
        var spinner_new = document.getElementById('spinner_new_prompt_'+id);
        spinner_new.style.display = 'inline-block';
        button_new.disabled = true;
            $.ajax({
                url: '{{ route("search-own-prompts") }}',
                type: 'post',
                data: formArray,
                xhrFields: {
                    responseType: 'blob' 
                },
                success: function(response) {
                spinner_new.style.display = 'none';
                button_new.disabled = false;

                var audioUrl = URL.createObjectURL(response);
                document.getElementById("chatgptsearchbutton_" + id).disabled = true; 
                var $audioElement = $("#chatgptanswer_"+ id).find('.player_Own_Prompts');
                $audioElement.attr('src', audioUrl);
                $audioElement[0].play();
                $("#chatgptanswer_"+ id).show();
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
document.addEventListener("DOMContentLoaded", function () {
    var copyPromptButton = document.getElementById("copyPrompt_1");
    var questionText = document.getElementById("questionText_1");

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
document.addEventListener("DOMContentLoaded", function () {
    var copyPromptButton = document.getElementById("copyPrompt_2");
    var questionText = document.getElementById("questionText_2");

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
        var formArray = $(this).serializeArray();
        document.getElementById("chatgptsearchbutton").disabled = true;
        var button = document.getElementById('chatgptsearchbutton');
        var spinner = document.getElementById('spinner_new_prompt');
        spinner.style.display = 'inline-block';
        button.disabled = true;
           $.ajax({
             url: '{{ route("search-own-prompts") }}',
             type: 'post',
             data: formArray,
            xhrFields: {
                    responseType: 'blob' 
                },
             success: function(response){
                spinner.style.display = 'none';
                var audioUrl = URL.createObjectURL(response);
                var $audioElement = $("#chatgptanswer").find('.player_Own_Prompts');
                $audioElement.attr('src', audioUrl);
                $audioElement[0].play();
                $("#chatgptanswer").show();
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
                console.log("Some OWn things went wrong");
             }
             
           });
        }); 
        


function ChatGptPrompts(prompts, responseanswer, key){
          var button = document.getElementById('btncustom_'+key);
        var spinner = document.getElementById('spinner_'+key);
        spinner.style.display = 'inline-block';
        button.disabled = true;
           $.ajax({
             url: '{{ route("search-text-to-music") }}',
             type: 'post',
             data: {
                ask_question_chatGpt : prompts,
            },
            xhrFields: {
                    responseType: 'blob' 
                },
             success: function(response){
                var audioUrl = URL.createObjectURL(response);
                var $audioElement = $(".chat-gpt-api-ansawer_"+key).find('.audioPlayer');
                $audioElement.attr('src', audioUrl);
                $audioElement[0].play();
                $(".chat-gpt-api-ansawer_"+key).show();
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
             }
           });
        }; 


</script>

@endsection
