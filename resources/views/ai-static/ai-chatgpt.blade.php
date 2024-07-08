@extends('layout.main')
@section('content')
    <!-- <div class="containerrr"> -->
    <section class="content">
        <div class="row">
<div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
            <div class="content-wrappertest  mx-auto" style="margin-top: 8px;">
            <h2 ><b>Module 1 | ChatGPT</b></h2>
                <hr >
                <section style="margin-top: 8px;">
                <div >
                        <h4><b>Overview</b></h4><div>
                            <div>
                                <p><h5>In this module, we'll explore ChatGPT, a smart computer program that can chat with you, answer your questions, and even help with homework, just like a friendly robot that loves to learn and teach!</h5></p>
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
                        
                        <iframe src="https://player.vimeo.com/video/817309428?h=24ea3ef3d8" width="640" height="360" 
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

                 <!--  @if (!empty($aimodules->Promptsdata))
                        @foreach ($aimodules->Promptsdata as $key=> $cdata)  
                        <section class="faq-section">
                            <div class="faq-item">
                                <h5 class="faq-question"><b>{{ $key + 1 }}&nbsp;.&nbsp;&nbsp;{{ isset($cdata->question) ? $cdata->question : '' }}</b></h5>
                                <div class="faq-answer">
                                    <div class="typing">
                                        <p style="font-size: 16px;">{{ isset($cdata->answer) ? $cdata->answer : '' }}</p>
                                    </div>
                                    <hr>
                                </div>
                            </div>
                        </section>
                        @endforeach
                    @endif     -->
                    
                    <div class="faq-section">
                    <div class="faq-item">
                            <div class="faq-question">
                                <b>1. Suggest a fun itinerary if you have only 12 hours to explore Jaipur with family.</b>
                                <span class="toggle-icon">+</span>
                            </div>
                            <div class="faq-answer" style="display: none;">
                                <div class="typing">
                                    <p style="font-size: 16px;" class="answer-content">Start your day with a visit to the majestic Amber Fort, located just outside of Jaipur. Take a guided tour of the royal palace and experience the grandeur of the Rajput era. 2. Enjoy a delicious Rajasthani thali lunch at one of the local restaurants. 3. Visit the City Palace, a grand palace complex built by the Maharaja of Jaipur in the 18th century. 4. Take a tour of the Jantar Mantar, an astronomical observatory built by Maharaja Jai Singh II. 5. Visit the Hawa Mahal, or the Palace of Winds, a pink sandstone structure with intricate carvings and latticework. 6. Take a rickshaw ride through the bustling streets of Jaipur and explore the local markets. 7. Enjoy a traditional Rajasthani dinner at one of the local restaurants. 8. End your day with a visit to the Nahargarh Fort, located on a hilltop overlooking the city of Jaipur.</p>
                                </div>
                                <hr>
                            </div>
                        </div>
                        <div class="faq-item">
                            <div class="faq-question">
                                <b>2. Explain the rules of cricket to 8 year old.</b>
                                <span class="toggle-icon">+</span>
                            </div>
                            <div class="faq-answer" style="display: none;">
                                <div class="typing">
                                    <p style="font-size: 16px;" class="answer-content">Cricket is a fun game played between two teams of 11 players each. The game is played on a large field with a flat surface. The two teams take turns batting and fielding. When it''s a team''s turn to bat, they have two batsmen at the crease. The batsmen have to try and score as many runs as they can. The batsman does this by hitting the ball and running between the two wickets (three stumps with two bails on top). The batsman can score runs by running between the wickets or by hitting the ball to the boundary of the field. When it''s a team''s turn to field, they have to try and get the batsmen out. There are several ways to do this. The most common is to catch the ball after it has been hit by the batsman. The fielder can also get the batsman out by hitting the wickets with the ball. The team with the most runs at the end of the game wins. It''s a great game to play and watch.</p>
                                </div>
                                <hr>
                            </div>
                        </div>
                        <div class="faq-item">
                            <div class="faq-question">
                                <b>3. Top 3 amusement parks for children in India and what's special about them?</b>
                                <span class="toggle-icon">+</span>
                            </div>
                            <div class="faq-answer" style="display: none;">
                                <div class="typing">
                                    <p style="font-size: 16px;" class="answer-content">1. Adlabs Imagica: Adlabs Imagica is India's first international theme park located in Khopoli, Maharashtra. This amusement park offers a variety of thrilling rides, water parks, live entertainment, and shows. It also has a variety of dining and shopping options. 2. Essel World: Essel World is located in Mumbai and is one of the oldest and most popular amusement parks in India. It has over 35 rides, a variety of restaurants, and a water park. It also hosts live entertainment and shows. 3. Wonderla: Wonderla is located in Kochi, Kerala and is one of the most popular amusement parks in India. It features over 50 rides, a variety of water slides, and an array of dining and shopping options. It also has a virtual reality zone and a 5D theatre.</p>
                                </div>
                                <hr>
                            </div>
                        </div>
                        <div class="faq-item">
                            <div class="faq-question">
                                <b>4. Write a short poem on 'why school is important'.</b>
                                <span class="toggle-icon">+</span>
                            </div>
                            <div class="faq-answer" style="display: none;">
                                <div class="typing">
                                    <p style="font-size: 16px;" class="answer-content">School is the place we learn and grow, The knowledge we gain will help us go, It teaches us the skills we need, To build a bright and successful future indeed.</p>
                                </div>
                                <hr>
                            </div>
                        </div>
                        <div class="faq-item">
                            <div class="faq-question">
                                <b>5. How does solar eclipse happen? Explain to a 10 year old.</b>
                                <span class="toggle-icon">+</span>
                            </div>
                            <div class="faq-answer" style="display: none;">
                                <div class="typing">
                                    <p style="font-size: 16px;" class="answer-content">A solar eclipse happens when the moon passes between the sun and the Earth, blocking out the sunlight. The moon casts a shadow on the Earth and the sun appears to disappear. This only happens during a new moon, when the moon is directly between the Earth and the sun. For a 10 year old, you could explain it like this: Imagine the sun is a bright light bulb, and the moon is a piece of paper. When the moon moves between the sun and the Earth, it's like the paper is blocking the light from the bulb. That's why the sun looks like it's gone away!</p>
                                </div>
                                <hr>
                            </div>
                        </div>
                        <div class="faq-item">
                            <div class="faq-question">
                                <b>6. Explain gravity in simple terms.</b>
                                <span class="toggle-icon">+</span>
                            </div>
                            <div class="faq-answer" style="display: none;">
                                <div class="typing">
                                    <p style="font-size: 16px;" class="answer-content">Gravity is a force of attraction between objects that is due to their mass. It is what makes objects fall to the ground when dropped and keeps planets in orbit around the sun.</p>
                                </div>
                                <hr>
                            </div>
                        </div>
                        <div class="faq-item">
                            <div class="faq-question">
                                <b>7. Write a fairytale about a young girl Tina, who loves her dolls and gives them names of popular Disney characters, and how they come to life and join Tina for a grand feast and teach her 3 essential table manners.</b>
                                <span class="toggle-icon">+</span>
                            </div>
                            <div class="faq-answer" style="display: none;">
                                <div class="typing">
                                    <p style="font-size: 16px;" class="answer-content">Once upon a time, there was a young girl named Tina who adored her dolls. She would give them names of popular Disney characters, like Snow White, Cinderella, and Ariel. One night, Tina was getting ready for bed and was about to put away her dolls when they suddenly came to life! Tina was so shocked and delighted to see her beloved dolls talking and walking around. The dolls told Tina that they were here to teach her three essential table manners. They told her that the first was to always say “please” and “thank you” when asking for something. The second was to chew with her mouth closed. And the third was to never talk with food in her mouth. Tina was so excited to learn these important table manners that she invited her dolls to a grand feast. She prepared a delicious meal of her favorite foods and set the table with her best dishes. When the dolls arrived, they taught Tina the three essential table manners. She was so pleased with herself that she thanked them for teaching her. At the end of the feast, the dolls thanked Tina for the wonderful meal and said goodbye. As they left, they promised to come back soon to check on her manners. Tina was so happy that her dolls had come to life and taught her such important lessons. From then on, she always remembered to use her manners whenever she ate.</p>
                                </div>
                                <hr>
                            </div>
                        </div>
                        <div class="faq-item">
                            <div class="faq-question">
                                <b>8. How to keep our rivers clean? Explain through a short story that kids would enjoy reading.</b>
                                <span class="toggle-icon">+</span>
                            </div>
                            <div class="faq-answer" style="display: none;">
                                <div class="typing">
                                    <p style="font-size: 16px;" class="answer-content">Once upon a time, there lived a little fish named Finny. Finny lived in a beautiful river that was full of life and color. Every day, Finny would swim around and explore the river, admiring the bright colors of the fish and plants. One day, Finny noticed something strange in the river. There were some strange things floating in the water that he had never seen before. Finny swam closer and saw that the objects were plastic bags, bottles, and other garbage. Finny was very sad to see the river so polluted. He decided that he had to do something to help. So, he asked his friends to join him in a mission to clean up the river. Finny and his friends worked together to collect the garbage from the river and put it in a big pile on the bank. They then asked the people who lived near the river to help them take the garbage away to be recycled. The people were so impressed with Finny and his friends that they started to take care of the river every day. They picked up garbage whenever they saw it, and they made sure to put it in the right places. Finny and his friends were very proud of what they had done. The river was now clean and full of life again. From then on, Finny and his friends made sure to keep the river clean and healthy. Moral of the story: Everyone can help keep our rivers clean by picking up garbage and putting it in the right places. We all need to do our part to protect our rivers and keep them clean and healthy for future generations.</p>
                                </div>
                                <hr>
                            </div>
                        </div>
                        <div class="faq-item">
                            <div class="faq-question">
                                <b>9. Explain Jagdish Chandra Bose's contribution to science in simple words for 10 year old.</b>
                                <span class="toggle-icon">+</span>
                            </div>
                            <div class="faq-answer" style="display: none;">
                                <div class="typing">
                                    <p style="font-size: 16px;" class="answer-content">Jagdish Chandra Bose was an Indian scientist who made important contributions to the fields of physics, biology and botany. He was the first person to prove that plants have feelings and can respond to external stimuli, such as light, sound and heat. He also invented the first wireless radio in 1895, which was the first device that could send and receive messages without using wires. He also developed the crescograph, an instrument that could measure the growth of plants. His work showed that plants are living things that can feel, think and respond to their environment.</p>
                                </div>
                                <hr>
                            </div>
                        </div>
                        <div class="faq-item">
                            <div class="faq-question">
                                <b>10. Give some tips on how ChatGPT can be used by 10 year old students in their day to day studies.</b>
                                <span class="toggle-icon">+</span>
                            </div>
                            <div class="faq-answer" style="display: none;">
                                <div class="typing">
                                    <p style="font-size: 16px;" class="answer-content">1. Encourage 10 year old students to use ChatGPT to practice their writing skills. Ask them to type out a short story, essay, or even a poem and have ChatGPT suggest words and phrases to help them complete their work. 2. Have 10 year old students use ChatGPT to practice their math skills. Ask them to type out a math problem and have ChatGPT suggest words and phrases to help them solve it. 3. Encourage 10 year old students to use ChatGPT to practice their reading comprehension skills. Ask them to type out a passage or story and have ChatGPT suggest words and phrases to help them understand the content better. 4. Have 10 year old students use ChatGPT to practice their problem-solving skills. Ask them to type out a problem and have ChatGPT suggest words and phrases to help them come up with a solution. 5. Encourage 10 year old students to use ChatGPT to practice their researching skills. Ask them to type out a topic and have ChatGPT suggest words and phrases to help them find relevant information.</p>
                                </div>
                                <hr>
                            </div>
                        </div>
                    </div>
                
                <!-- Add an input field and submit button here -->
                
                <form name="chatgptcreate" id="chatgptcreate" method="POST">
                   <div class="row">
                        <div class="form-group col-md-6">
                        <!-- <label class="form-label">Ask question to ChatGpt <span class="text-danger">*</span></label> -->

                            <div>
                            <input type="text" name="ask_question_chatGpt" class="form-control" id="ask_question_chatGpt" placeholder="Enter Question to ChatGpt">
                            </div>
                        </div>
                            <div class="col-md-2 text-center">
                            <button type="submit" class="btn " id="chatgptsearchbutton" style="background-color: #00205c; color: #fff; font-weight: bold;">Submit</button>

                            </div>
                        </div> 
                        <div class="faq-answer">
                        <div   id="chatgptanswer" style="display: none;">
                                <div class="typing">
                                   <!--  <p style="font-size: 16px;" class="answer-content" ></p> -->
                                </div>
                                
                                <hr>
                        </div>    
                        <p class="answer-content"></p> 
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
                                <p id="questionText"><b>I have exams coming up in a month and need to create a study schedule. I attend school in the mornings and have classes in the afternoon. How can I effectively allocate study time for my subjects during the weekdays and weekends?</b></p>
                                <a href="#" target="_blank" class="btn-open-website" id="copyPrompt"><span class="fw-600"><i class="fa fa-copy"></i></span></a>
                                <a href="https://chat.openai.com/" target="_blank" class="btn-open-website">Open Website</a>
                               
                            </li>
                            <li>
                                <p id="questionText"><b>I'm writing an essay on the impact of climate change on polar bear populations. Can you help me create an outline for my essay?</b></p>
                                <a href="#" target="_blank" class="btn-open-website" id="copyPrompt"><span class="fw-600"><i class="fa fa-copy"></i></span></a>
                                <a href="https://chat.openai.com/" target="_blank" class="btn-open-website">Open Website</a>
                               
                            </li>
                            <li>
                                <p id="questionText"><b>Can you generate ten multiple-choice questions about the American Revolution for my history practice test?</b></p>
                                <a href="#" target="_blank" class="btn-open-website" id="copyPrompt"><span class="fw-600"><i class="fa fa-copy"></i></span></a>
                                <a href="https://chat.openai.com/" target="_blank" class="btn-open-website">Open Website</a>
                               
                            </li>
                            <li>
                                <p id="questionText"><b>I'm stuck on my physics homework about projectile motion. Can you explain how to determine the range of a projectile at a given angle?</b></p>
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
                  // var chatgptAnswerElement = document.getElementById('chatgptanswer');
                   // chatgptAnswerElement.innerHTML = response['message'];
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

        $('#chatgptcreatedalle').submit(function(){
            event.preventDefault();
            var formArray = $(this).serializeArray();
            document.getElementById("chatgptsearchbuttondalle").disabled = true;
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
                        var responseImage = document.getElementById('chatgptanswer');
                        responseImage.src = value;
                        responseImage.style.display = 'block';
                    } else {
                        // Hide the image if no image URL is provided in the response
                        var responseImage = document.getElementById('chatgptanswer');
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
