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
    <h4><b>Fun with AI Tools: Let's identify some Flora & Fauna</b></h4>
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
            <h3><b>Let's watch image detection through AI {{ isset($aimodules->type) ? $aimodules->type : '' }} in action</b></h3>
                    <div class="faq-question">
                        <span>{!! isset($aimodules->description) ? $aimodules->description : '' !!}</span>
                    </div>
                    @if (!empty($aimodules->promptsdata))
                    <div class="faq-section">
                        @foreach ($aimodules->promptsdata as $key => $cdata)
                        <div class="faq-item" style="background-color: #00205c; color: #fff;">
                            <div class="row">
                                <div class="col-md-4">
                                     <img 
                                        src="{{ url('uploads/aimodule') }}/{{ $cdata->file ? $cdata->file : '' }}"
                                        width="220" 
                                        height="220"
                                        style="border-radius: 20px;"
                                         />
                                </div>
                                <div class="col-md-2" style="margin-top: 75px;">
                                    <button type="button" class="btn" id="btncustom_{{$key+1}}" onclick="ChatGptPrompts({{ json_encode(isset($cdata->response) ? $cdata->response : '') }}, {{ json_encode(isset($cdata->prompts) ? $cdata->prompts : '') }}, {{$key + 1}})" style="background-color: #fee665; color: #00205c;">
                                    Identify&nbsp;<span id="spinner_{{$key+1}}" class="spinner-border spinner-border-sm" style="color: #00205c; display: none;" role="status" aria-hidden="true"></span>
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <div class="api-false-time-answer_{{ $key + 1}} typing" >
                                    </div>
                                    <p style="font-size: 16px; margin-top: 50px;"  class="api-answer-content"></p>
                                </div>
                            </div>
                            <!-- <div class="faq-question" style="display: flex; justify-content: space-between;">
                                
                                    <img 
                                        src="{{ url('uploads/aimodule') }}/{{ $cdata->file ? $cdata->file : '' }}"
                                        width="200" 
                                        height="200"
                                        style="border-radius: 20px;"
                                         />
                            </div> -->
                            <!-- <div style="text-align: center;">
                                <button type="button" class="btn" id="btncustom_{{$key+1}}" onclick="ChatGptPrompts({{ json_encode(isset($cdata->response) ? $cdata->response : '') }}, {{ json_encode(isset($cdata->prompts) ? $cdata->prompts : '') }}, {{$key + 1}})" style="background-color: #fee665; color: #00205c;">
                                    Generate&nbsp;<span id="spinner_{{$key+1}}" class="spinner-border spinner-border-sm" style="color: #00205c; display: none;" role="status" aria-hidden="true"></span>
                                </button>
                            </div> -->



                            <div>
                                <!-- <div class="api-false-time-answer_{{ $key + 1}} typing" >
                                </div>
                                <p style="font-size: 16px;" class="api-answer-content"></p> -->
                            </div>
                            <!-- <div class="chat-gpt-api-ansawer_{{ $key + 1}}" style="display: none;">
                                <video class="mediaPlayer" controls autoplay loop></video>
                            </div>
                            <p style="font-size: 16px;" class="api-answer-content"></p> -->
                        </div>
                        @endforeach
                    </div>
                    @endif
                <!-- Add an input field and submit button here -->
              <div class="faq-section">


                        <!-- <div class="faq-item">
                            <div class="faq-question">
                                <h3><b>&nbsp;&nbsp;&nbsp;Enter Own Prompts</b></h3>
                            </div>
                        <div class="faq-question">
                            <span>&nbsp;&nbsp;&nbsp;&nbsp;Now that you have an idea of how prompts work let's have fun with own prompts.</span>
                        </div>
                        </div> -->

                        <div class="faq-item">
                            
                            
                            <div class="faq-question">
                               <h3><b>Upload a picture of plant</b></h3>
                            </div>
                            <div class="faq-question">
                            <span>{!! isset($aimodules->own_description) ? $aimodules->own_description : '' !!}</span>
                        </div>
                        <!-- <div class="faq-question">
                        <span>&nbsp;&nbsp;&nbsp;&nbsp; Now that you have an idea of how prompts work let's have fun with own prompts. </span>
                        </div> -->
                        <div id="Createform">
                        <form name="chatgptcreateform" id="chatgptcreateform" method="POST">
                                <div class="row" >
                                    <div class=" col-md-6">
                                        <div class="faq-item" style="background-color:  #00205c;  color: #fff;">
                                             <input type="file" name="ask_photo_video" id="ask_photo_video" required style="font-size: 16px; " onchange="plantImageOne(event)">
                                             <img id="plantOneImage" class="mt-2 " src="#" style="max-width: 150px; max-height: 150px; display: none;">
                                             <br>
                                             
                                              <div class="prompt_one_photo_video typing" style="display: none;" >
                                                 
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
                                       Identify</button>
                                    </div>
                                    <div class="col-md-4" style="width: 15.333333%">
                                        <button type="button" class="btn " id="add-field-input" style="background-color: #00205c; color: #fff;">New Image</button>
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
<!-- Birds -->
<div class="faq-section">
                        <div class="faq-item">
                            <div class="faq-question">
                            <h3><b>Upload a picture of bird</b></h3>
                            </div>
                         <div class="faq-question">
                          <span>{{ isset($aimodules->own_placeholder) ? $aimodules->own_placeholder : '' }}</span>
                        </div>
                        <div id="BirdsCreateform">
                        <form name="birdOnecreateform" id="birdOnecreateform" method="POST">
                                <div class="row">
                                    <div class=" col-md-6 ">
                                        <div class="faq-item" style="background-color:  #00205c;  color: #fff;">
                                             <input type="file" name="ask_photo_bird" id="ask_photo_bird" required style="font-size: 16px; " onchange="BirdsImageOne(event)">
                                             <img id="BirdsOneImage" class="mt-2" src="#" style="max-width: 150px; max-height: 150px;  display: none;">
                                             <br>
                                              <div class="answer_one_photo_bird typing" style="display: none;" >
                                                 
                                              </div>
                                              <p style="font-size: 16px;" class="api-answer-content mt-2"></p>
                                            
                                              <!-- <div style="display: none;">
                                                 <p style="font-size: 16px;" id="Own-Prompts-text" class="api-answer-content mt-2"></p>
                                             </div> -->
                                             
                                        </div>
                                    </div>
                                    <div class="col-md-2" style="width: 12.333333%">
                                       <button type="submit" class="btn chatgpt-search-button" id="birdOnesearchbutton" style="background-color: #00205c; color: #fff; font-weight: bold;">
                                       <span id="spinner_bird_one" class="spinner-border spinner-border-sm " style="color: #fff; display: none;" role="status"  aria-hidden="true" ></span>
                                       Identify</button>
                                    </div>
                                    <div class="col-md-4" style="width: 15.333333%">
                                        <button type="button" class="btn " id="add-birds-field-input" style="background-color: #00205c; color: #fff;">New Image</button>
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

<!--end Birds -->
            </div>
<div class="faq-section">
    <div class="faq-item">
        <div class="faq-question">
            <h3><b>Click to select any image from the collage below and press "Identify"</b></h3>
        </div>
       <!--  <div class="faq-question">
            <span>&nbsp;&nbsp;&nbsp;&nbsp; Select any image click on Identify button about the fauna and flora</span>
        </div> -->
            <div class="row">
    @if (!empty($aimodules->activitiesdata))
        @php
            $total_activities = count($aimodules->activitiesdata);
        @endphp
        @foreach ($aimodules->activitiesdata as $key => $cdata)
            <div class="col-md-4">
                <img 
                    data-description="{{ $cdata->activities_description }}" 
                    class="imgcollage collage_popup" 
                    src="{{ url('uploads/aimodule') }}/{{ $cdata->prompt_screenshot ? $cdata->prompt_screenshot : '' }}"
                    @if ($total_activities - $key <= 3) style="height: 100%; width: 100%; border-radius: 20px;" @endif
                >
            </div>
            @if (($key + 1) % 3 == 0 && $key + 1 != $total_activities)
                @php
                    $margin = ($key + 1 == 3) ? '-83px' : (($key + 1 == 6) ? '-83px' : '0');
                @endphp
                </div><div class="row" style="margin-top: {{ $margin }};">
            @endif
        @endforeach
    @endif
</div>
        
    </div>
</div>
<!--start Hybrid -->
        <div class="faq-question">
            <h3><b>&nbsp;&nbsp;&nbsp;Mixy Matchy Animals</b></h3>
        </div>
        <div class="faq-question">
            <span>&nbsp;&nbsp; Click on "Generate" button to check out some amazing fusion of different animals and birds.</span>
        </div> 
@if (!empty($aimodules->hybridsdata))
                    <div class="faq-section mt-2">
                   
          @foreach ($aimodules->hybridsdata as $key => $cdata)
                        <div class="faq-item" style="background-color: #00205c; color: #fff;">
                            
                                <div class="row" style="margin-top: 35px;">
                                    <div class="col-md-3 ">
                                        <img src="{{ url('uploads/hybrid') }}/{{ $cdata->animal_one_new ? $cdata->animal_one_new : 'no_image.png' }}" width="220" height="220" style="border-radius: 20px;">
                                        <h5 class="mt-2 text-center" >{{ $cdata->animal_one_name ? $cdata->animal_one_name : '' }}</h5>
                                    </div>
                                    <div class="col-md-1" style="margin-top: 50px">
                                       <strong  style="font-size: 85px; ">+</strong>
                                    </div>
                                    <div class="col-md-3">
                                            <img src="{{ url('uploads/hybrid') }}/{{ $cdata->animal_second_new ? $cdata->animal_second_new : 'no_image.png' }}" width="220" height="220" style="border-radius: 20px;">
                                            <h5 class="mt-2 text-center"  >{{ $cdata->animal_second_name ? $cdata->animal_second_name : '' }}</h5>
                                        
                                    </div>
                                    <div class="col-md-2">
                                            <button type="button" class="btn"  id="btncustom_hybrid_{{$key + 1}}"  onclick="HybridtoggleImage({{$key + 1}})" style="background-color: #fee665; color: #00205c; margin-top: 95px;">
                                            Generate&nbsp;<span id="spinner_hybrid_{{$key+1}}" class="spinner-border spinner-border-sm" style="color: #00205c; display: none;" role="status" aria-hidden="true"></span>
                                            </button>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="api-false-time-answer_{{ $key + 1}}">
                                            <img id="image_{{$key + 1}}" src="{{ url('uploads/hybrid') }}/{{ $cdata->result_new ? $cdata->result_new : 'no_image.png' }}" width="220" height="220" style="border-radius: 20px; display: none;">
                                            <!-- <h5 id="result_text_{{$key + 1}}" style="display: none;">{{ $cdata->result_name ? $cdata->result_name : '' }}</h5> -->
                                        </div> 
                                    </div>
                                </div>
                               
                            
                            <div>
                                
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
<!--end Hybrid -->

<!--
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
                -->
            </div>
            </div>
                </div>
            </div>
        </div>
    </section>
    <!-- </div> -->


    <div class="modal fade" id="bs-password-modal" tabindex="-1" role="dialog" aria-labelledby="modal-label"
        aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" style="max-width: 500px; ">
            <div class="modal-content" >
                <!-- <div class="modal-header" style="padding: 0.5rem 1.75rem;">
                     <h2 class="modal-title" id="modal-label-pass">
                        Collage Name </h2> 
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div> -->
                <div class="modal-body" style="background-color: #00205c; color: #fff; border-radius: 5px;">

                    <div class="row">
                        <div class="col-11 text-left">
                            <button type="button" class="btn" id="generate_button" style="background-color: #fee665; color: #00205c;">
                            Identify 
                            </button>
                        </div>
                        <div class="col-1 text-right">
                           <button type="button" style="margin-bottom: 20px" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                        </div>
                    </div>
                    <input type="hidden" id="collage_name" value="">
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="mb-3">
                                <div class="input-group collage_description_out_put">
                                </div>
                                <p style="font-size: 16px;"></p>
                            </div>
                        </div>
                        <!-- <div class="col-3 text-right">
                            <button type="button" class="btn" id="generate_button" style="background-color: #fee665; color: #00205c;">
                            Identify 
                            </button>
                        </div> -->
                    </div>
                </div>

            </div>
        </div>
    </div>




    <script src="{{ asset('assets/src/js/chatgpt_js.js') }}"></script>

    <!-- Your ChatGPT page content goes here -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

$(document).ready(function() {
    
    $('.collage_popup').on('click', function() {
        $(".collage_description_out_put").siblings('p').addClass('text-white').html('');
        $('.collage_popup').removeClass('selected');
       
        $(this).addClass('selected');
        var viewCollageName = $(this).data('description');
        //$("#collage_name").text(viewCollageName);
        $("#collage_name").val(viewCollageName);
        $('#bs-password-modal').modal('show');
    });
});


$('#generate_button').on('click', function() {
    $(".collage_description_out_put").siblings('p').addClass('text-white').html('');
        CollageGenerate(); // Call the function
    });

    function CollageGenerate() {
    var collageDescription = $("#collage_name").val();
    console.log("Collage description:", collageDescription);

    var value = collageDescription;
    var words = value.split(' '); 
    var index = 0;
    var interval = 100; 
    var displayNextWord = function() {
        if (index < words.length) {
            $(".collage_description_out_put").siblings('p').addClass('text-white').html(words.slice(0, index + 1).join(' '));
            index++;
        } else {

            clearInterval(wordDisplayInterval);
        }
    };
    var wordDisplayInterval = setInterval(displayNextWord, interval);

};



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
                '<div class="col-md-6 ">' +
                

                '<div class="faq-item" style="background-color:  #00205c;  color: #fff;">' +
                        '<input type="file" name="ask_photo_video" id="ask_photo_video_' + uniqueId + '" style="font-size: 16px;" onchange="SecondplantImage(event,' + uniqueId + ')" required>'+
                          '<img id="plantSecondImage_' + uniqueId + '" class="mt-2" src="#" style="max-width: 150px; max-height: 150px; display: none;"">' +  
                        '<br>'+
                        '<div class="prompt_one_photo_video_' + uniqueId + '" style="display: none;">'+
                        
                        '</div>'+
                        
                        '<div class="Own-Prompts-text_' + uniqueId + '" style="display: none;">'+
                        '</div>'+
                        '<p style="font-size: 16px;" class="api-answer-content mt-2"></p>'+
                '</div>'+




                


                '</div>' +
                '<div class="col-md-2 mt-2" style="width: 12.333333%">' +
                '<button type="button" class="btn chatgpt-search-button" id="chatgptsearchbutton_' + uniqueId + '" data-id="' + uniqueId + '" style="background-color: #00205c; color: #fff; font-weight: bold;"><span id="spinner_new_prompt_' + uniqueId + '" class="spinner-border spinner-border-sm " style="color: #fff; display: none;" role="status"  aria-hidden="true" ></span> Identify</button>' +
                '</div>' +
                '<div class="col-md-4 mt-2 text-center" style="width: 12.333333%">' +
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
//alert(111);
//console.log(button_new,"button_new");
        var spinner_new = document.getElementById('spinner_new_prompt_'+id);
         spinner_new.style.display = 'inline-block';
         button_new.disabled = true;


            $.ajax({
                url: '{{ route("plant-recognizer") }}',
                type: 'post',
                data: formData,
                //dataType: 'json',
                processData: false, 
                contentType: false, 
                dataType: 'json',
                success: function(response) {
                    //console.log(response['Predicted Species'],"species123");
                   // console.log(id,"id");
                  //  spinner_new.style.display = 'none';
                  //  button_new.disabled = false;
                    if (response && response['Predicted Species'] && response['Probability']) {
                        var species = response['Predicted Species'];
                        console.log(species,"species");

                       // var probability = response['Probability'];
                       // var content = "<p>Predicted Species: " + species + "</p>";
                       // content += "<p>Probability: " + probability + "</p>";
                      //  $(".prompt_one_photo_video_" + id).addClass('text-white').html(species).show();



                      /* start */
        $.ajax({
             url: '{{ route("search-chatgpt") }}',
             type: 'post',
             data: {
                ask_question_chatGpt : species,
            },
             dataType: 'json',
             success: function(response){
                console.log(response,"responsesakib");
            if(response['status'] == true){
           
                var value = response['message'];
               // $(".prompt_one_photo_video").addClass('text-white').html(value).show();
                $(".prompt_one_photo_video_" + id).addClass('text-white').html(value).show();
                console.log(value,"value");
                var words = value.split(' '); 
                var index = 0;
                var interval = 100; 
                var displayNextWord = function() {
                    if (index < words.length) {
                        //$(".prompt_one_photo_video").addClass('text-white').html(words.slice(0, index + 1).join(' '));
                        $(".prompt_one_photo_video_" + id).addClass('text-white').html(words.slice(0, index + 1).join(' '));
                        index++;
                    } else {

                        clearInterval(wordDisplayInterval);
                    }
                };
                var wordDisplayInterval = setInterval(displayNextWord, interval);
                spinner_new.style.display = 'none';
                button_new.disabled = false;
                }else{
                    spinner_new.style.display = 'none';
                    button_new.disabled = false;
                    var errors = response['errors'];
                    console.log(errors,"errors");
                    var value = responseanswer;
                    console.log(value,"value");
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
                    $.each(errors, function(key,value){
                    $(`#${key}`).siblings('p').addClass('text-danger').html(value);
                })
                }
             },
             error: function(){
                spinner_new.style.display = 'none';
                button_new.disabled = false;
                console.log("Some things went wrong");
             }
           });

/* end */
                    }
                    spinner.style.display = 'none';
        button.disabled = false;

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
    event.preventDefault();
    var formData = new FormData();
    formData.append('ask_photo_video', $('#ask_photo_video')[0].files[0]);
    var spinner = document.getElementById('spinner_new_prompt');
    var button = document.getElementById('chatgptsearchbutton');
    spinner.style.display = 'inline-block';
    button.disabled = true;
    $.ajax({
        url: '{{ route("plant-recognizer") }}',
        type: 'post',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            console.log(response,"response123");
           // spinner.style.display = 'none';
          // button.disabled = false;
    if (response && response['Predicted Species'] && response['Probability']) {
        var species = response['Predicted Species'];
        var probability = response['Probability'];
        var content = "<p style='font-size: 16px;'>" + species + "</p>";
        //$(".prompt_one_photo_video").addClass('text-white').html(content).show();

/* start */
        $.ajax({
             url: '{{ route("search-chatgpt") }}',
             type: 'post',
             data: {
                ask_question_chatGpt : species,
            },
             dataType: 'json',
             success: function(response){
                console.log(response,"responsesakib");
            if(response['status'] == true){
           
                var value = response['message'];
                $(".prompt_one_photo_video").addClass('text-white').html(value).show();
                console.log(value,"value");
                var words = value.split(' '); 
                var index = 0;
                var interval = 100; 
                var displayNextWord = function() {
                    if (index < words.length) {
                        $(".prompt_one_photo_video").addClass('text-white').html(words.slice(0, index + 1).join(' '));
                        index++;
                    } else {

                        clearInterval(wordDisplayInterval);
                    }
                };
                var wordDisplayInterval = setInterval(displayNextWord, interval);
                spinner.style.display = 'none';
                button.disabled = false;
                }else{
                    spinner.style.display = 'none';
                    button.disabled = false;
                    var errors = response['errors'];
                    console.log(errors,"errors");
                    var value = responseanswer;
                    console.log(value,"value");
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
                    $.each(errors, function(key,value){
                    $(`#${key}`).siblings('p').addClass('text-danger').html(value);
                })
                }
             },
             error: function(){
                spinner.style.display = 'none';
                button.disabled = false;
                console.log("Some things went wrong");
             }
           });

/* end */
    }
        spinner.style.display = 'none';
        button.disabled = false;
        },
        error: function(jqXHR, textStatus, errorThrown) {
     var error_msg = jqXHR.responseJSON.error;
     $(".Own-Prompts-text").siblings('p').addClass('text-danger').html(error_msg);
    spinner.style.display = 'none';
    button.disabled = false;
    console.log("Something went wrong");
}
    });
});


function ChatGptPrompts(file, responseanswer, key){
    var button = document.getElementById('btncustom_' + key);
    var spinner = document.getElementById('spinner_' + key);
    spinner.style.display = 'inline-block';
    button.disabled = true;
    setTimeout(function() {
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
    spinner.style.display = 'none';
    button.disabled = false;
}, 5000); // 5000 milliseconds = 5 seconds
    }; 




/* Birds */
$(document).ready(function() {
    var birdfieldinput = 1;
$("#add-birds-field-input").click(function() {
        var unique_Id = birdfieldinput;
        var newRow =
            $(
                '<form name="birdsecondcreateform_' + unique_Id + '" id="birdsecondcreateform_' + unique_Id + '" method="POST">' +
                '<div class="row" >' +
                '<div class="col-md-6">' +
                '<div class="faq-item" style="background-color:  #00205c;  color: #fff;">' +
                        '<input type="file" name="answer_second_photo_bird" id="answer_second_photo_bird' + unique_Id + '" style="font-size: 16px; " onchange="SecondBirdsImage(event,' + unique_Id + ')" required>'+
                        '<img id="birdsSecondImage_' + unique_Id + '" class="mt-2" src="#" style="max-width: 150px; max-height: 150px;  display: none;">' +  
                        '<br>'+
                        '<div class="prompt_second_photo_birds_' + unique_Id + '" style="display: none;">'+
                        
                        '</div>'+
                        
                        '<div class="Own-Prompts-text_' + unique_Id + '" style="display: none;">'+
                        '</div>'+
                        '<p style="font-size: 16px;" class="api-answer-content mt-2"></p>'+
                '</div>'+
                '</div>' +
                '<div class="col-md-2 mt-2" style="width: 12.333333%">' +
                '<button type="button" class="btn chatgpt-search-button" id="Birdssearchbutton_' + unique_Id + '" data-id="' + unique_Id + '" style="background-color: #00205c; color: #fff; font-weight: bold;"><span id="spinner_second_birds_' + unique_Id + '" class="spinner-border spinner-border-sm " style="color: #fff; display: none;" role="status"  aria-hidden="true" ></span> Identify</button>' +
                '</div>' +
                '<div class="col-md-4 mt-2 text-center" style="width: 12.333333%">' +
                '<button type="button" class="btn btn-danger remove-field" data-id="' + unique_Id + '">Remove</button>' +
                '</div>' +
                '<div class="faq-answer mt-2">' +
                    '<div   id="chatgptanswer_' + unique_Id + '" style="display: none;">' +
                       '<audio class="player_Own_Prompts" controls></audio>' +
                    '</div>' +
                    '<p class="answer-content"></p>' +
                '</div> ' +
                '</div>' +
                '</form>'
            );

        $("#BirdsCreateform").append(newRow);

        newRow.find(".chatgpt-search-button").click(function() {
            var id = $(this).data("id");
          //  var formArray = $("#birdsecondcreateform_" + id).serializeArray();

            var formData = new FormData($("#birdsecondcreateform_" + id)[0]); 

            /* Add click time */
//var create_one = 1;
var button_new = document.getElementById('Birdssearchbutton_'+id);
//alert(111);
//console.log(button_new,"button_new");
        var spinner_new = document.getElementById('spinner_second_birds_'+id);
         spinner_new.style.display = 'inline-block';
         button_new.disabled = true;


            $.ajax({
                url: '{{ route("bird-classifier-second") }}',
                type: 'post',
                data: formData,
                //dataType: 'json',
                processData: false, 
                contentType: false, 
                dataType: 'json',
                success: function(response) {
                    //console.log(response['Predicted Species'],"species123");
                   // console.log(id,"id");
                  //  spinner_new.style.display = 'none';
                  //  button_new.disabled = false;
                  if (response && response.length > 0 && response[1]['scientificName']) {
                          var bird_scientificsecond =  response[1]['scientificName'];

                      /* start */
        $.ajax({
             url: '{{ route("search-chatgpt") }}',
             type: 'post',
             data: {
                ask_question_chatGpt : bird_scientificsecond,
            },
             dataType: 'json',
             success: function(response){
                console.log(response,"responsesakib");
            if(response['status'] == true){
           
                var value = response['message'];
               // $(".prompt_one_photo_video").addClass('text-white').html(value).show();
                $(".prompt_second_photo_birds_" + id).addClass('text-white').html(value).show();
                console.log(value,"value");
                var words = value.split(' '); 
                var index = 0;
                var interval = 100; 
                var displayNextWord = function() {
                    if (index < words.length) {
                        //$(".prompt_one_photo_video").addClass('text-white').html(words.slice(0, index + 1).join(' '));
                        $(".prompt_second_photo_birds_" + id).addClass('text-white').html(words.slice(0, index + 1).join(' '));
                        index++;
                    } else {

                        clearInterval(wordDisplayInterval);
                    }
                };
                var wordDisplayInterval = setInterval(displayNextWord, interval);
                spinner_new.style.display = 'none';
                button_new.disabled = false;
                }else{
                    spinner_new.style.display = 'none';
                    button_new.disabled = false;
                    var errors = response['errors'];
                    console.log(errors,"errors");
                    var value = responseanswer;
                    console.log(value,"value");
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
                    $.each(errors, function(key,value){
                    $(`#${key}`).siblings('p').addClass('text-danger').html(value);
                })
                }
             },
             error: function(){
                spinner_new.style.display = 'none';
                button_new.disabled = false;
                console.log("Some things went wrong");
             }
           });

/* end */
                    }
                    spinner.style.display = 'none';
                    button.disabled = false;
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
            $("#birdsecondcreateform_" + idToRemove).remove();
        });

        birdfieldinput++;
    });
    });


    $('#birdOnecreateform').submit(function(event) {
      //  alert(222);
    event.preventDefault();
    var formData = new FormData();
    formData.append('ask_photo_bird', $('#ask_photo_bird')[0].files[0]);
    var spinner = document.getElementById('spinner_bird_one');
    var button = document.getElementById('birdOnesearchbutton');
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
            //console.log(response.info,"response_info");
           // console.log(response.messages,"response_messages");
           // spinner.style.display = 'none';
          // button.disabled = false;

    
        if (response && response.length > 0 && response[1]['scientificName']) {
             var bird_scientificName =  response[1]['scientificName'];
           //  console.log(bird_scientificName,"bird_scientificName");
/* start */
        $.ajax({
             url: '{{ route("search-chatgpt") }}',
             type: 'post',
             data: {
                ask_question_chatGpt : bird_scientificName,
            },
             dataType: 'json',
             success: function(response){
                console.log(response,"responsesakib");
            if(response['status'] == true){
           
                var value = response['message'];
                $(".answer_one_photo_bird").addClass('text-white').html(value).show();
                console.log(value,"value");
                var words = value.split(' '); 
                var index = 0;
                var interval = 100; 
                var displayNextWord = function() {
                    if (index < words.length) {
                        $(".answer_one_photo_bird").addClass('text-white').html(words.slice(0, index + 1).join(' '));
                        index++;
                    } else {

                        clearInterval(wordDisplayInterval);
                    }
                };
                var wordDisplayInterval = setInterval(displayNextWord, interval);
                spinner.style.display = 'none';
                button.disabled = false;
                }else{
                    spinner.style.display = 'none';
                    button.disabled = false;
                    var errors = response['errors'];
                    console.log(errors,"errors");
                    var value = responseanswer;
                    console.log(value,"value");
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
                    $.each(errors, function(key,value){
                    $(`#${key}`).siblings('p').addClass('text-danger').html(value);
                })
                }
             },
             error: function(){
                spinner.style.display = 'none';
                button.disabled = false;
                console.log("Some things went wrong");
             }
           });
           spinner.style.display = 'none';
           button.disabled = false;
/* end */
    }
          
        },
        error: function(jqXHR, textStatus, errorThrown) {
     var error_msg = jqXHR.responseJSON.error;
     $(".Own-Prompts-text").siblings('p').addClass('text-danger').html(error_msg);
    spinner.style.display = 'none';
    button.disabled = false;
    console.log("Something went wrong");
}
    });
});



</script>
<script>
    /* Palnt */
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
            imagePreview.style.display = 'none'; // Hide the image if no file is selected
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

/* Birds */
function BirdsImageOne(event) {
      const input = event.target;
      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          const imagePreview = document.getElementById('BirdsOneImage');
        //  imagePreview.style.display = 'block';

          imagePreview.src = e.target.result;
          imagePreview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
      }else {
            imagePreview.style.display = 'none'; // Hide the image if no file is selected
        }
    }

    function SecondBirdsImage(event, unique_Id) {
    const input = event.target;
    const imagePreview = document.getElementById('birdsSecondImage_' + unique_Id);

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            imagePreview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }else {
            imagePreview.style.display = 'none'; 
        }
}
  </script>
  <script>
    function HybridtoggleImage(key) {
        var image = document.getElementById('image_' + key);
        var result_text = document.getElementById('result_text_' + key);
        var btncustom_hybrid = document.getElementById('btncustom_hybrid_' + key);
        var spinner = document.getElementById('spinner_hybrid_' + key);
          spinner.style.display = 'inline-block';
          btncustom_hybrid.disabled = true;
        setTimeout(function() {
            spinner.style.display = 'none'; 
            btncustom_hybrid.disabled = false;
            if (image.style.display === 'none') {
                image.style.display = 'inline-block';
                result_text.style.display = 'inline-block';
            } else {
                image.style.display = 'none';
                result_text.style.display = 'none';
            }
            
        }, 1000);
        
    }
</script>
@endsection
