@extends('layout.main')
@section('content')
    <!-- <div class="containerrr"> -->
    <section class="content">
        <div class="row">
<div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
            <div class="content-wrappertest  mx-auto" style="margin-top: 8px;">
            <h2 ><b>Module 2 | Dalle</b></h2>
                <hr >
                <section style="margin-top: 8px;">
                    <div >
                        <h4><b>Overview</b></h4><div>
                            <div>
                                <p><h5>In this module, we'll discover DALL-E, a clever art-making program that turns your words into pictures. It's like a magical paintbrush that can draw anything you imagine, from a purple cat on a skateboard to a castle in the clouds!</h5></p>
                            </div>
                        </div>
                    </div>
                    <hr >
                </section>
                  <!-- Embedded Video Section -->
            <section>
                <h4 style="text-align: center;"><b>Intro Video</b></h4>
                <div>
                    <div style="text-align: center;">
                        <!-- <video width="640" height="360" style="border-radius:10%" controls>
                            <source src="{{isset($aimodules->content[0]->video_url) ? $aimodules->content[0]->video_url : ''}}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video> -->
                        
                        <iframe src="https://player.vimeo.com/video/840401930?h=d8bb9feafa" width="640" height="360" 
                            frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                    

                    <!-- <video preload="none" tabindex="-1" style=""><track kind="captions" src="/texttrack/85611419.vtt?token=6549d89f_0x13e93354a18372b4cade8bf677f1f5dbe1df873c" 
                    id="telecine-track-85611419" srclang="en-x-autogen" label="English (auto-generated)"></video> -->



                        {{-- <iframe width="560" height="315" src="{{ asset('assets/AI/video_chatgpt.mp4')}}" frameborder="0" allowfullscreen></iframe> --}}
                    </div>
                </div>
                <hr>

            </section>
            <div class="white-desk">
            <h3><b>Prompts</b></h3>
                  <p style="font-size: 15px;">Expand the following prompts to see the answer given by AI.</p>

                   
                          <div class="faq-section">
                        
                            <div class="faq-item">
                                <div class="faq-question">
                                    <b>1. A stylish Panda on skateboard wearing goggle with India gate in background</b>
                                        <form name="chatgptcreatedalle-1" id="chatgptcreatedalle-1" method="POST">
                                                <div class="row">
                                                    <div>
                                                    <input type="hidden" name="ask_question_chatGpt" value="A stylish Panda on skateboard wearing goggle with India gate in background" class="form-control" id="ask_question_chatGpt" placeholder="Enter Image Description to Dalle">
                                                    </div>
                                                    <div class="col-md-2 text-center">
                                                    <button type="submit" class="btn " id="chatgptsearchbuttondalle-1" style="background-color: #00205c; color: #fff; font-weight: bold;">Submit</button>
                                                    </div>
                                                </div> 
                                                    <!-- <img src="" alt="Response Image" id="dalleimage-1" style="display: none;"> -->
                                                </div>
                                        </form>
                                        <img src="" alt="Response Image" id="dalleimage-1" style="display: none;">    
                                </div>
                                <div class="faq-answer" style="display: none;">
                                    <div class="typing">
                                        <p style="font-size: 16px;" class="answer-content"></p>
                                        
                                    </div>
                                    <hr>
                                </div>
                            </div>
                            <div>
                       <div class="faq-section">        
                         <div class="faq-item">
                            <div class="faq-question">
                                <b>2. A cute cat looking at herself in mirror near the beach on a sunny day</b>
                                <form name="chatgptcreatedalle-2" id="chatgptcreatedalle-2" method="POST">
                                            <div class="row">
                                                <div>
                                                <input type="hidden" name="ask_question_chatGpt" value="A cute cat looking at herself in mirror near the beach on a sunny day" class="form-control" id="ask_question_chatGpt" placeholder="Enter Image Description to Dalle">
                                                </div>
                                                <div class="col-md-2 text-center">
                                                <button type="submit" class="btn " id="chatgptsearchbuttondalle-2" style="background-color: #00205c; color: #fff; font-weight: bold;">Submit</button>
                                                </div>
                                            </div> 
                                                <!-- <img src="" alt="Response Image" id="dalleimage-1" style="display: none;"> -->
                                            </div>
                                    </form>
                                    <img src="" alt="Response Image" id="dalleimage-2" style="display: none;">

                                
                            </div>
                            <div class="faq-answer" style="display: none;">
                                <div class="typing">
                                    <p style="font-size: 16px;" class="answer-content"></p>
                                </div>
                                <hr>
                            </div>
                        </div>
                        </div>
                    <div class="faq-section">
                       <div class="faq-item">
                            <div class="faq-question">
                                <b>3. A potato wearing a trench coat in a heroic pose, 3d digital art</b>
                                <form name="chatgptcreatedalle-3" id="chatgptcreatedalle-3" method="POST">
                                            <div class="row">
                                                <div>
                                                <input type="hidden" name="ask_question_chatGpt" value="A potato wearing a trench coat in a heroic pose, 3d digital art" class="form-control" id="ask_question_chatGpt" placeholder="Enter Image Description to Dalle">
                                                </div>
                                                <div class="col-md-2 text-center">
                                                <button type="submit" class="btn " id="chatgptsearchbuttondalle-3" style="background-color: #00205c; color: #fff; font-weight: bold;">Submit</button>
                                                </div>
                                            </div> 
                                                <!-- <img src="" alt="Response Image" id="dalleimage-1" style="display: none;"> -->
                                            </div>
                                    </form>
                                    <img src="" alt="Response Image" id="dalleimage-3" style="display: none;">  
                                <!-- <span class="toggle-icon">+</span> -->
                            </div>
                            <div class="faq-answer" style="display: none;">
                                <div class="typing">
                                    <p style="font-size: 16px;" class="answer-content"></p>
                                </div>
                                <hr>
                            </div>
                        </div>
                        </div>
                        <div class="faq-section">

                        <div class="faq-item">
                                <div class="faq-question">
                                    <b>4. A group of 5 cute dogs playing poker</b>
                                    <form name="chatgptcreatedalle-4" id="chatgptcreatedalle-4" method="POST">
                                                <div class="row">
                                                    <div>
                                                    <input type="hidden" name="ask_question_chatGpt" value="A group of 5 cute dogs playing poker" class="form-control" id="ask_question_chatGpt" placeholder="Enter Image Description to Dalle">
                                                    </div>
                                                    <div class="col-md-2 text-center">
                                                    <button type="submit" class="btn " id="chatgptsearchbuttondalle-4" style="background-color: #00205c; color: #fff; font-weight: bold;">Submit</button>
                                                    </div>
                                                </div> 
                                                    <!-- <img src="" alt="Response Image" id="dalleimage-1" style="display: none;"> -->
                                                </div>
                                        </form>
                                        <img src="" alt="Response Image" id="dalleimage-4" style="display: none;">  
                                    <!-- <span class="toggle-icon">+</span> -->
                                </div>
                                <div class="faq-answer" style="display: none;">
                                    <div class="typing">
                                        <p style="font-size: 16px;" class="answer-content"></p>
                                    </div>
                                    <hr>
                                </div>
                        </div>
                        <div class="faq-item">
                                <div class="faq-question">
                                    <b>5. Create a digital painting that captures the journey of a seed turning into a towering tree, demonstrating the stages of germination, growth, and maturity to be used as an educational poster for a biology class.</b>
                                    <form name="chatgptcreatedalle-5" id="chatgptcreatedalle-5" method="POST">
                                                <div class="row">
                                                    <div>
                                                    <input type="hidden" name="ask_question_chatGpt" value="Create a digital painting that captures the journey of a seed turning into a towering tree, demonstrating the stages of germination, growth, and maturity to be used as an educational poster for a biology class." class="form-control" id="ask_question_chatGpt" placeholder="Enter Image Description to Dalle">
                                                    </div>
                                                    <div class="col-md-2 text-center">
                                                    <button type="submit" class="btn " id="chatgptsearchbuttondalle-5" style="background-color: #00205c; color: #fff; font-weight: bold;">Submit</button>
                                                    </div>
                                                </div> 
                                                    <!-- <img src="" alt="Response Image" id="dalleimage-1" style="display: none;"> -->
                                                </div>
                                        </form>
                                        <img src="" alt="Response Image" id="dalleimage-5" style="display: none;">  
                                    <!-- <span class="toggle-icon">+</span> -->
                                </div>
                                <div class="faq-answer" style="display: none;">
                                    <div class="typing">
                                        <p style="font-size: 16px;" class="answer-content"></p>
                                    </div>
                                    <hr>
                                </div>
                        </div>
                        </div>
                        
                        


                        
                        
                    
                    
                
               
                
                
                 
            </div>
            <div>
                <!-- Add an input field and submit button here -->
            <form name="chatgptcreatedalleinput" id="chatgptcreatedalleinput" method="POST">
                   <div class="row">
                        <div class="form-group col-md-6">
                            <div >
                            <input type="text" name="ask_question_chatGpt" class="form-control-dalle" id="ask_question_chatGpt" placeholder="Enter Image Description to Dalle">
                            </div>
                        </div>
                            <div class="col-md-2 text-center">
                            <button type="submit" class="btn " id="chatgptsearchbuttondalleinput" style="background-color: #00205c; color: #fff; font-weight: bold;">Submit</button>

                            </div>
                        </div> 
                        
                        <div   id="chatgptanswerfgfgh" style="display: none;">
                                <div class="typing">
                                   
                                </div>
                                
                                <hr>
                        </div>    
                    
                       <img src="" alt="Response Image" id="dalleyimagesearch" style="display: none;">

                        </div>
                </form>
                </div>
                 <hr>
                <section class="faq-section">
                    <div class="info-section">
                        <h3><b>Activities</b></h3>
                        <p>Copy the following prompts, click on 'Open Website' and paste them in the input box.</p>
                        <ul>
                        
                            <li>
                                <p id="questionText"><b>Create an infographic that explains the water cycle for a middle school science class.</b></p>
                                <a href="#" target="_blank" class="btn-open-website" id="copyPrompt"><span class="fw-600"><i class="fa fa-copy"></i></span></a>
                                
                                <a href="https://chat.openai.com/" target="_blank" class="btn-open-website">Open Website</a>
                               
                            </li>
                            <li>
                                <p id="questionText"><b>Generate an illustration of the balcony scene from Romeo and Juliet to support my literature class assignment.</b></p>
                                <a href="#" target="_blank" class="btn-open-website" id="copyPrompt"><span class="fw-600"><i class="fa fa-copy"></i></span></a>
                                <a href="https://chat.openai.com/" target="_blank" class="btn-open-website">Open Website</a>
                               
                            </li>
                            <li>
                                <p id="questionText"><b>Design a poster showing the different parts of a cell and their functions for my biology notebook.</b></p>
                                <a href="#" target="_blank" class="btn-open-website" id="copyPrompt"><span class="fw-600"><i class="fa fa-copy"></i></span></a>
                                <a href="https://chat.openai.com/" target="_blank" class="btn-open-website">Open Website</a>
                               
                            </li>
                            <li>
                                <p id="questionText"><b>Produce a set of images that depict the Pythagorean theorem in various real-life applications to aid my study for a math test.</b></p>
                                <a href="#" target="_blank" class="btn-open-website" id="copyPrompt"><span class="fw-600"><i class="fa fa-copy"></i></span></a>
                                <a href="https://chat.openai.com/" target="_blank" class="btn-open-website">Open Website</a>
                            </li>
                        
                        </ul>
                    </div>
                    <hr>
                </section>
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

$('#chatgptcreate').submit(function(){
            event.preventDefault();
            var formArray = $(this).serializeArray();
            document.getElementById("chatgptsearchbutton").disabled = true;
           $.ajax({
             url: '{{ route("search-chatgpt") }}',
             type: 'post',
             data: formArray,
             dataType: 'json',
             success: function(response){
              
                if(response['status'] == 'success'){
                   console.log(response['message'],"sakibhello");
                   var value = response['message'];
                    $("#chatgptanswer").siblings('p').addClass('text-black').html(value);
                   
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
        $('#chatgptcreatedalleinput').submit(function(){
           // alert(2433);
            event.preventDefault();
            var formArray = $(this).serializeArray();
            console.log(formArray,"formArray");
            document.getElementById("chatgptsearchbuttondalleinput").disabled = true;
           $.ajax({
             url: '{{ route("search-dalle") }}',
             type: 'post',
             data: formArray,
             dataType: 'json',
             success: function(response){
              
                if(response['status'] == 'success'){
                   console.log(response['message'],"sakibhello");
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



        $('#chatgptcreatedalle-1').submit(function(){
           // alert(2433);
            event.preventDefault();
            var formArray = $(this).serializeArray();
            console.log(formArray,"formArray");
            document.getElementById("chatgptsearchbuttondalle-1").disabled = true;
           $.ajax({
             url: '{{ route("search-dalle") }}',
             type: 'post',
             data: formArray,
             dataType: 'json',
             success: function(response){
              
                if(response['status'] == 'success'){
                   console.log(response['message'],"sakibhello");
                   var value = response['message'];
                   if (value) {
                        // Set the image source and display it
                        var responseImage = document.getElementById('dalleimage-1');
                        responseImage.src = value;
                        responseImage.style.display = 'block';
                    } else {
                        // Hide the image if no image URL is provided in the response
                        var responseImage = document.getElementById('dalleimage-1');
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
        $('#chatgptcreatedalle-2').submit(function(){
           // alert(2433);
            event.preventDefault();
            var formArray = $(this).serializeArray();
            console.log(formArray,"formArray");
            document.getElementById("chatgptsearchbuttondalle-2").disabled = true;
           $.ajax({
             url: '{{ route("search-dalle") }}',
             type: 'post',
             data: formArray,
             dataType: 'json',
             success: function(response){
              
                if(response['status'] == 'success'){
                   console.log(response['message'],"sakibhello");
                   var value = response['message'];
                   if (value) {
                        // Set the image source and display it
                        var responseImage = document.getElementById('dalleimage-2');
                        responseImage.src = value;
                        responseImage.style.display = 'block';
                    } else {
                        // Hide the image if no image URL is provided in the response
                        var responseImage = document.getElementById('dalleimage-2');
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
        $('#chatgptcreatedalle-3').submit(function(){
           // alert(2433);
            event.preventDefault();
            var formArray = $(this).serializeArray();
            console.log(formArray,"formArray");
            document.getElementById("chatgptsearchbuttondalle-3").disabled = true;
           $.ajax({
             url: '{{ route("search-dalle") }}',
             type: 'post',
             data: formArray,
             dataType: 'json',
             success: function(response){
              
                if(response['status'] == 'success'){
                   console.log(response['message'],"sakibhello");
                   var value = response['message'];
                   if (value) {
                        // Set the image source and display it
                        var responseImage = document.getElementById('dalleimage-3');
                        responseImage.src = value;
                        responseImage.style.display = 'block';
                    } else {
                        // Hide the image if no image URL is provided in the response
                        var responseImage = document.getElementById('dalleimage-3');
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
        $('#chatgptcreatedalle-4').submit(function(){
           // alert(2433);
            event.preventDefault();
            var formArray = $(this).serializeArray();
            console.log(formArray,"formArray");
            document.getElementById("chatgptsearchbuttondalle-4").disabled = true;
           $.ajax({
             url: '{{ route("search-dalle") }}',
             type: 'post',
             data: formArray,
             dataType: 'json',
             success: function(response){
              
                if(response['status'] == 'success'){
                   console.log(response['message'],"sakibhello");
                   var value = response['message'];
                   if (value) {
                        // Set the image source and display it
                        var responseImage = document.getElementById('dalleimage-4');
                        responseImage.src = value;
                        responseImage.style.display = 'block';
                    } else {
                        // Hide the image if no image URL is provided in the response
                        var responseImage = document.getElementById('dalleimage-4');
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
        $('#chatgptcreatedalle-5').submit(function(){
           // alert(2433);
            event.preventDefault();
            var formArray = $(this).serializeArray();
            console.log(formArray,"formArray");
            document.getElementById("chatgptsearchbuttondalle-5").disabled = true;
           $.ajax({
             url: '{{ route("search-dalle") }}',
             type: 'post',
             data: formArray,
             dataType: 'json',
             success: function(response){
              
                if(response['status'] == 'success'){
                   console.log(response['message'],"sakibhello");
                   var value = response['message'];
                   if (value) {
                        // Set the image source and display it
                        var responseImage = document.getElementById('dalleimage-5');
                        responseImage.src = value;
                        responseImage.style.display = 'block';
                    } else {
                        // Hide the image if no image URL is provided in the response
                        var responseImage = document.getElementById('dalleimage-5');
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
</script>

@endsection
