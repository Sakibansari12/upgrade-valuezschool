@extends('layout.main')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title"><!-- Instruction Module --></h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><i class="mdi mdi-home-outline"></i></li>
                            <li class="breadcrumb-item" aria-current="page"><a
                                    href="{{ route('student.grade.list') }}">{{ $class_name->class_name }}</a></li>
                            <li class="breadcrumb-item" aria-current="page"><a
                                    href="{{ route('student.course.list', ['class' => $class_id]) }}">Course</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Instructional Module</li>
                        </ol>
                    </nav>
                </div>
            </div>

        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    @if ($lessonPlan->first())
                        @foreach ($lessonPlan as $cdata)
                            @php
                               
                            if ($student_check_premium->student_status == 'Paid') {
                                $is_demo_content = 1;
                                    $main_video = !empty($cdata->video_url) ? App\Http\Controllers\WebPage::getVideoUrl($cdata->video_url) : '';
                                    $info_video = !empty($cdata->video_info_url) ? App\Http\Controllers\WebPage::getVideoUrl($cdata->video_info_url) : '';
                                    $myra_video = !empty($cdata->myra_video_url) ? App\Http\Controllers\WebPage::getVideoUrl($cdata->myra_video_url) : '';
                                }elseif ($student_check_premium->student_status == 'Pending')
                                {
                                    $main_video = $info_video = $is_demo_content = $myra_video = '#';
                                }elseif (($cdata->is_demo == 1 && $check_premium->school_student_status == 'Demo' && $student_check_premium->student_status == 'Demo'))
                                {
                                    $is_demo_content = 1;
                                    $main_video = !empty($cdata->video_url) ? App\Http\Controllers\WebPage::getVideoUrl($cdata->video_url) : '';
                                    $info_video = !empty($cdata->video_info_url) ? App\Http\Controllers\WebPage::getVideoUrl($cdata->video_info_url) : '';
                                    $myra_video = !empty($cdata->myra_video_url) ? App\Http\Controllers\WebPage::getVideoUrl($cdata->myra_video_url) : '';
                                }elseif (($cdata->is_demo == 1 && $check_premium->school_student_status == 'Paid' && $student_check_premium->student_status == 'Demo'))
                                {
                                    $is_demo_content = 1;
                                    $main_video = !empty($cdata->video_url) ? App\Http\Controllers\WebPage::getVideoUrl($cdata->video_url) : '';
                                    $info_video = !empty($cdata->video_info_url) ? App\Http\Controllers\WebPage::getVideoUrl($cdata->video_info_url) : '';
                                    $myra_video = !empty($cdata->myra_video_url) ? App\Http\Controllers\WebPage::getVideoUrl($cdata->myra_video_url) : '';
                                }else {
                                    $main_video = $info_video = $is_demo_content = $myra_video = '#';
                                }
                            @endphp
                            <div class="col-xl-4 col-md-6 col-12 px-4">
                                <div class="card">
                                    @if ($is_demo_content == '#')
                                        
                                        <div class="position-absolute r-0"><button type="button"
                                                class="waves-effect waves-circle btn btn-circle btn-info btn-md mb-5"
                                               ><i
                                                    class="mdi mdi-lock fs-2"></i></button></div>
                                    @endif
                                    <img class="video-btn"
                                        src="{{ url('uploads/lessonplan') }}/{{ $cdata->lesson_image ? $cdata->lesson_image : 'no_image.png' }}"
                                        alt="{{ $cdata->title }}" data-title="{{ $cdata->title }}" data-bs-toggle="modal"
                                        data-src="{{ $main_video }}" data-msrc="{{ $myra_video }}"
                                        data-bs-target="#bs-video-modal{{ $main_video == '#' ? '-demo' : '' }}" id="rzp-button1{{ $main_video == '#' ? '-payment' : 'test' }}" />
                                        <input type="hidden" name="student_id" value="{{$student_check_premium->id}}" class="form-control student_id">
                                        <input type="hidden" name="student_status" value="{{$student_check_premium->student_status}}" class="form-control student_status">
                                        <input type="hidden" name="school_student_status" value="{{$check_premium->school_student_status}}" class="form-control school_student_status">

                                    <div class="card-body">
                                        <h5 class="card-title">{{ $cdata->title }}</h5>
                                        <div class="mb-10">
                                            <ul class="list-unstyled d-flex justify-content-between align-items-center">
                                                <li><a href="#" class="text-mute hover-primary"><i
                                                            class="fa fa-tag"></i>
                                                        {{ $class_name->class_name }}</a></li>
                                                <li><a href="#" class="text-mute hover-primary"><i
                                                            class="fa fa-folder-open-o"></i>
                                                        {{ $cdata->course->course_name }}</a></li>
                                            </ul>
                                        </div>
                                        <div class="justify-content-center align-items-center ">
                                        @if ($is_demo_content != '#')
                                        
                                                    <div class="text-center">
                                                        @if ($cdata->conversation)
                                                            <button type="button" class="btn btn-info btn-sm mb-3 mr-3"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#bs-info-modal-{{ $cdata->id }}">Conversations</button>
                                                        @endif
                                                        @if ($cdata->view_assessment)   
                                                            <button type="button" class="btn btn-info btn-sm mb-3 ml-3"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#bs-download-file-{{ $cdata->id }}" > Assessment</button>
                                                        @endif
                                                    </div>

                                                    <div class="modal fade" id="bs-download-file-{{ $cdata->id }}" tabindex="-1" role="dialog"
                                                        aria-labelledby="modal-label-{{ $cdata->id }}" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" >
                                                            <div class="modal-content">
                                                                <div class="modal-header">  
                                                                    <h4 class="modal-title" id="modal-label-{{ $cdata->id }}">
                                                                    Assessment</h4>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                        aria-hidden="true"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                     <iframe src="{{ url('uploads/lessonplan') }}/{{ $cdata->view_assessment }}" width="100%" height="600px"></iframe>
                                                                </div>
                                                                <div class="modal-body-custom mb-5">  
                                                                    <a href="{{ url('uploads/lessonplan') }}/{{ $cdata->view_assessment }}"
                                                                            class="btn btn-sm mb-5" target="_blank" 
                                                                            style="background-color: #00205c; color: #ffff;"
                                                                    >Full Screen</a>
                                                                    <a href="{{ url('uploads/lessonplan') }}/{{ $cdata->view_assessment }}"
                                                                    class="btn btn-sm mb-5"
                                                                    style="background-color: #00205c; color: #ffff;"
                                                                    download>Download</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-center" style="margin-top: -7px;">
                                                                @if ($cdata->video_info_url)
                                                                    <button id="vid-btn-{{ $cdata->id }}"
                                                                        data-src="{{ $info_video }}" data-title="{{ $cdata->title }}"
                                                                        type="button" class="video-btn btn btn-warning btn-sm mb-5"
                                                                        data-bs-toggle="modal" data-bs-target="#bs-video-modal">Instructions
                                                                        Video</button>
                                                                @endif
                                                        <button id="read-btn-{{ $cdata->id }}" data-grade="{{ $class_id }}"
                                                            data-id="{{ $cdata->id }}" type="button"
                                                            style=" color: #ffff;"
                                                            class="mb-5 mark-as-read btn btn-{{ in_array($cdata->id, $complete_lesson) ? 'success' : 'custom-color-chnages ' }} btn-sm">{{ in_array($cdata->id, $complete_lesson)
                                                                ? 'Completed'
                                                                : 'Mark
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    as
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    complete' }}</button>

                                                    </div>
                                                @else
                                                    <div class="card-footer justify-content-between d-flex px-0 pb-0">

                                                        <ul class="list-inline mb-0">
                                                            <li><button class="btn btn-warning btn-sm mb-5" data-bs-toggle="modal"
                                                                    data-bs-target="#bs-video-modal-demo-subcribe">Subscribe</button></li>
                                                        </ul>
                                                    </div>
                                                @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                            

                            <!-- Info modal -->
                            <div class="modal fade" id="bs-info-modal-{{ $cdata->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="modal-label-{{ $cdata->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="modal-label-{{ $cdata->id }}">
                                            Talk with your child</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-hidden="true"></button>
                                        </div>
                                        <div class="modal-body">
                                            {!! $cdata->conversation !!}
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->
                            
                        @endforeach
                    @else
                        <div class="col-sm-6">
                            <div class="card card-body">
                                <h5 class="card-title fw-600">Lesson Plan not found.</h5>
                                <a href="{{ route('student.grade.list') }}" class="btn btn-primary-light">Go Back</a>
                            </div> <!-- end card-->
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->

<!--start Form -->
<div class="modal-status" id="bs-student-payment-modal-demo" tabindex="-1" role="dialog" aria-labelledby="modal-label-demo"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-payment-model mt-60">
            <div class="modal-content">
                <div class="modal-header" style="margin-bottom: -15px;">
                    <button type="button" class="btn-close" onclick="closePaymentSuccessFunction()" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <h1 class="text-center"><strong>Select Plan</strong> </h1>
                <div class="modal-body">
                     @foreach ($groupedData as $key=> $cdata)
                            @foreach ($cdata['student_packages'] as $key => $package)
                                <div class="card card-default" @if ($key == 0) style="border-color: #00205c;" @endif>
                                    <div class="form-group custom-paymentcheckbox">
                                        @if ($key == 0)
                                            <div class="badge" style="position: absolute; top: -10px; right: 10px; background-color: #00205c; color: #fff; border-radius: 10px;">Recommended</div>
                                        @endif
                                        <input type="checkbox" name="duration_of_package[]" @if ($key == 0) checked @endif
                                        data-this-package-include="{!! $package['this_package_includes'] !!}" 
                                        data-package-id="{{ $package['student_package_id'] }}"
                                        data-package-deal-code="{{ json_encode($package['package_data']) }}" 
                                        data-total-price="{{ $package['total_price'] }}" 
                                        data-set-pricing="{{ $package['set_pricing'] }}" 
                                        data-cgst="{{ $cdata['cgst'] }}" 
                                        data-sgst="{{ $cdata['sgst'] }}" 
                                        data-igst="{{ $cdata['igst'] }}" 
                                        >
                                        <span> <strong style="font-size: 15px;"> &#8377; {{ $package['total_price'] }} for {{ $package['duration_of_package'] }}</strong></span>
                                    </div>
                                </div> 
                            @endforeach
                                 <label class="form-label" for="amount"><b>Package  inclusions </b></label>
                                        <p id="package_includes_data"></p>
                                            <div id="deal_code">

                                            </div>
                                  
                                </div>
                            <div >
                        @endforeach     
                        <input type="hidden" name="student_id" value="{{$student_check_premium->id}}" class="form-control student_id">
                        <input type="hidden" name="school_id" value="{{$student_check_premium->school_id}}" class="form-control school_id">
                        <input type="hidden" readonly id="amount" name="amount" class="form-control amount" placeholder="Amount">            
                            <div class="row justify-content-center">
                                <div class="col-md-6 ">
                                    <div class="form-group mt-5 text-center">
                                        <button id="rzp-button1" style="background-color: #00205c; color: #fff;" class="btn">Pay<span id="selectedAmountDisplay"></span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
   



<!--end Form -->    
    <!-- Info modal -->
    <div class="modal-status" id="bs-student-pending-modal-demo" tabindex="-1" role="dialog" aria-labelledby="modal-label-demo"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-label-"><!-- Alert --></h4>
                    <button type="button" class="btn-close" onclick="closeFunction()" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <p>Hello <b>{{ isset($student_check_premium->name) ? $student_check_premium->name : '' }} {{ isset($student_check_premium->last_name) ? $student_check_premium->last_name : '' }}</b></p>

                    <p class="text-center">We are working actively behind the scenes with your school <br> to enable full access to all content. <br> Thanks for your patience!</p>
                    <p>You may connect with us at <b style="color: #00205c;">support@valuezschool.com</b> for any other query.</p>

                </div>
            </div>
        </div>
    </div>

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


    <!-- Info modal -->
<div class="modal fade" id="bs-video-modal-demo-subcribe" tabindex="-1" role="dialog" aria-labelledby="modal-label-demo"
        aria-hidden="true" style="width:100%">
        <div class="modal-dialog modal-dialog-centered custom-modal-width">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <h4 class="modal-title" id="modal-label-">Alert</h4> -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <p style="color: #00205c;"><b>Hello</b> <b id="school_name"></b>,</p>

                    <p>The academic year teacher subscription of our "Near Future Technologies + NEP Values education package for 21st Century school" gives you access to following:</p><br />
                    <p>- Near Future Tech modules in easy, fun way (50+ modules for Grades 4 to 10)</p>
                    <p>- Future Ready Bulletins (56 bulletins & more every 2 weeks for Grades 4 to 10)</p>
                    <p>- NEP Values modules (60+ modules mapped grade-wise for Grades Nursery to 5)</p>
                    <p>- Artificial Intelligence tools for creativity - hands-on modules (Grade 5 and above)</p>
                    <p>Easy teacher guidance and assessments are available with each module and modules are designed to seamlessly blend into your school curriculum. In addition, strong teacher resources for identifying learning or behavioral disorders and further professional development resources are available with the subscription.</p><br />
                    <p>Contact your school admin or write to <b style="color: #00205c;">support@valuezschool.com</b> for a full subscription.</p>
                    <p>Your partner in 21st century learner journey,</p>
                    <p>Valuez School</p>
                    <p><a target="_blank" href="https://valuezschool.com/" style="text-decoration: underline; color: #00205c;"><b>https://www.valuezschool.com</b></a></p>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->




    <div class="modal-status" id="bs-congratulation-popup" tabindex="-1" role="dialog" aria-labelledby="modal-label-demo"
        aria-hidden="true">
        <div class="modal-dialog" style="margin-top: 200px;">
            <div class="modal-content" style="background-color: #4CAF50;">
                <div class="modal-header" >
                <h4 class="modal-title" id="modal-label-pass">
                        </h4>
                    <button type="button" class="btn-close" onclick="closeCongratulation()" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body success-message-congratulation">
                    <p></p>
                </div>
            </div>
        </div>
    </div> 






@endsection
@section('script-section')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script language="javascript" type="text/javascript">


        $(function() {
            var school_name = $("#get_schoolname").text();
           // console.log(school_name,"school_name");
            $("#school_name").html(school_name);
            $('.mark-as-read').click(function() {
                var videoId = $(this).attr('data-id');
                var classId = $(this).attr('data-grade');
                var button = $(this); // Get a reference to the button
                var buttonText = button.text();
              //  console.log(buttonText,"buttonText");
                swal({
                    title: "Are you sure?",
                  //  text: 'Click "Yes" only if you have seen the entire module!',
                  //  text: "Please don't click 'Mark as Complete' unless actually completed in your class. Continue?",
                  text: buttonText === 'Completed' ? 'This action will remove completion status of the module and mark it as incomplete. Continue? This action will reverse "Mark as Complete" status.' : 'Click "Yes" only if you have seen the entire module!',





                    // type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#fec801",
                    confirmButtonText: "Yes",
                    cancelButtonText: "Cancel",
                    closeOnConfirm: false,
                    closeOnCancel: true
                }, function(isConfirm) {
                    if (isConfirm) {
                       console.log(isConfirm,"isConfirm");
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('student.report.save.plan') }}",
                            data: {
                                planId: videoId,
                                gradeId: classId,
                                buttonText: buttonText,
                            },
                            /* beforeSend: function() {
                                $('#read-btn-' + videoId).html("Please wait..");
                            }, */
                            success: function(data) {
                               // console.log(data);
                                if (data == 'update') {
                                    $('#read-btn-' + videoId).html("Completed")
                                        .addClass("btn-success")
                                        .removeClass("btn-dark");
                                        $('#read-btn-' + videoId).removeAttr('style');
                                    swal("Completed!",
                                        "Your Module has been marked as completed.",
                                        "success");
                                } else {
                                  //  console.log(data);
                                    $('#read-btn-' + videoId).html("Error");
                                }
                                if(data == 'status_changes'){
                                    $('#read-btn-' + videoId).html('Mark as complete')
                                        //.addClass("btn-primary")
                                        .removeClass("btn-success");
                                        $('#read-btn-' + videoId).css({
                                            'background-color': '#00205c',
                                            'color': '#fff'
                                        });
                                        swal.close();
                                }
                            }
                        });

                    }
                });
            });

        });

        $(document).ready(function() {
            // Gets the video src from the data-src on each button
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
            // console.log($videoSrc);
             $('#bs-video-modal').on('shown.bs.modal', function(e) {
                setTimeout(() => {
                 $("#video").attr('src', $videoSrc);
                    $("#video-loader").hide();
                }, 200);
            }) 
            $('#bs-video-modal').on('hide.bs.modal', function(e) {
                $("#video").attr('src', "");
            })
            // document ready
        });

        $('body').on('click','#rzp-button1-payment',function(e){
            var student_status = $('.student_status').val();
            var school_student_status = $('.school_student_status').val();
            if(student_status == 'Pending' || school_student_status == 'Pending'){
                
                $('#bs-student-pending-modal-demo').show();
            }else{
                $('#bs-student-payment-modal-demo').show();
            }
           
        });
        $('body').on('click','#rzp-button1',function(e){
            var student_status = $('.student_status').val();
            var school_student_status = $('.school_student_status').val();
            if(student_status == 'Pending' || school_student_status == 'Pending'){
                //$('#studentpendingmodaldemo').show();
                $('#bs-student-pending-modal-demo').show();
             //  console.log(student_status,"student_status");
            }else{

            e.preventDefault();
           // var amount = $('.amount').val();
            //var amount_main = $('#selectedAmountDisplay').val();

            var amountText = $('#selectedAmountDisplay').text();
           var amount = amountText.replace('₹', '').trim(); // Remove the currency symbol and any spaces
           console.log(amount,"amount123"); // Should output '621'


            var student_id = $('.student_id').val();
            var school_id = $('.school_id').val();


       
        if($('#checkboxCheck').prop('checked') == true){
            var dealCodePercentage = parseFloat($('#checkboxCheck').val());
            var datadiscount = $('#checkboxCheck').attr('data-discount');
           
         }else{
            var dealCodePercentage = '';
            var datadiscount = '';
         }


         //   var duration_of_package = $("#duration_of_package").val();
            var duration_of_package = $('input[name="duration_of_package[]"]:checked').val();
            
            var total_amount = amount * 100;
            var options = {
                "key": "rzp_live_nvVMrBwg0kJRIT", // Enter the Key ID generated from the Dashboard
                "amount": total_amount, // Amount is in currency subunits. Default currency is INR. Hence, 10 refers to 1000 paise
                "currency": "INR",
                "name": "NiceSnippets",
                "description": "Test Transaction",
                "image": "https://www.nicesnippets.com/image/imgpsh_fullsize.png",
                "order_id": "", //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
                "handler": function (response){
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type:'POST',
                        url:"{{ route('student-payment') }}",
                        data:{razorpay_payment_id:response.razorpay_payment_id,
                            amount:amount,
                            student_id:student_id,
                            school_id:school_id,
                            duration_package:duration_of_package,
                            discount_percentage:dealCodePercentage,
                            package_deal_code:datadiscount,
                            
                        },
                        success:function(data){
                            $('.success-message').text(data.success);
                            $('.success-alert').fadeIn('slow', function(){
                               $('.success-alert').delay(1000).fadeOut(); 
                            });
                            amount;
                           $('#bs-student-payment-modal-demo').hide();

                           $('#bs-congratulation-popup').show();
                            $('.success-message-congratulation').text("Congratulation! Welcome to 21st Century Valuez Program. You can view subscription details and download invoice in 'My Profile'");

                             setTimeout(function() {
                                window.location.reload();
                            }, 2000); 


                            /* $(window).on('load', function() {
                                $('#bs-congratulation-popup').show();
                                $('.success-message-congratulation').text("Congratulation! Welcome to 21st Century Valuez Program. You can view subscription details and download invoice in 'My Profile'");
                            }); */
                            
                        }
                    });
                },
                prefill: {
                    name: "Mehul Bagda",
                    email: "support@valuezschool.com",
                    contact: "8826708801"
                },
                notes: {
                    address: "test test"
                },
                theme: {
                    color: "#F37254"
                }
            };
    var rzp1 = new Razorpay(options);
    console.log(rzp1,"rzp1");
    rzp1.on('payment.failed', function (data) {
       var csrfToken = $('meta[name="_token"]').attr('content');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            var razorpayPaymentFailed = "{{ route('student-payment-failed') }}";
            console.log(data,"data");
            $.ajax({
                
                type: 'get',
                url: razorpayPaymentFailed,
                data: {
                    failed_data: data.error,
                    amount: amount,
                    student_id:student_id,
                    school_id:school_id,
                    razorpay_payment_id:data.error.metadata.payment_id,
                    duration_package:duration_of_package
                   // student_payment_id: payment_id,
                },
                success: function (response) {
                   /*  $('.success-message').text(data.success);
                    $('.success-alert').fadeIn('slow', function () {
                        $('.success-alert').delay(5000).fadeOut();
                    }); */
                },
                error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
            });
          });
    rzp1.open();
        }
});

        function closeFunction() {
        // Hide the confirmation modal
        $('#bs-student-pending-modal-demo').hide();
    }
    function closePaymentSuccessFunction() {
        // Hide the confirmation modal
        $('#bs-student-payment-modal-demo').hide();
    }
   // $('#bs-congratulation-popup').show();
    function closeCongratulation() {
        $('#bs-congratulation-popup').hide();
    }
    

    </script>
<!-- JavaScript code -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    updatePayButton(); 
    $('input[name="duration_of_package[]"]').on('change', function () {
        
        $('input[name="duration_of_package[]"]').not(this).prop('checked', false); // Ensure only one checkbox is checked
        
        updatePayButton(); 
        toggleCardBorderStyle($(this));
    });
    $('#checkboxCheck').on('change', function () {
        updatePayButton(); 
    });
    
    function toggleCardBorderStyle(checkedCheckbox) {
            var card = checkedCheckbox.closest('.card');
            if (checkedCheckbox.prop('checked')) {
                $('.card').removeAttr('style');
                card.css('border-color', '#00205c'); // Change to the color you desire
            } else {
                card.css('border-color', ''); // Remove inline border color style
            }
        }

    function updatePayButton() {
      
        var amountField = $('#amount');
        
        var selectedAmount = 0;
        $('input[name="duration_of_package[]"]:checked').each(function () {
            selectedAmount += parseFloat($(this).data('amount'));
        });
        
        var dealCodePercentage = parseFloat($('#checkboxCheck').val());
        
         if($('#checkboxCheck').prop('checked') == true){
            document.getElementById('discountMessage').style.display = 'block';
            console.log(dealCodePercentage,"dealCodePercentage");
            
         }else{
            document.getElementById('discountMessage').style.display = 'none';
         }


        if (!isNaN(dealCodePercentage) && $('#checkboxCheck').prop('checked')) {
            selectedAmount -= selectedAmount * (dealCodePercentage / 100);
            
        }
        
        $('#selectedAmountDisplay').text(' - ' + selectedAmount.toFixed(2));
       // $('#rzp-button1').text('Pay &#8377; ' + selectedAmount.toFixed(2));
       $('#rzp-button1').html('Pay &#8377; ' + selectedAmount.toFixed(2));

        amountField.val(selectedAmount.toFixed(2));
    }
});
</script>
<script>
    /*  $(document).ready(function() {
        $('input[name="duration_of_package[]"]:checked').each(function () {
            console.log("sakib222");
            $('input[name="deal_code_per[]"]').not(this).prop('checked', false);
            var package_include = $(this).attr('data-this-package-include');
            $('#package_includes_data').html(package_include);
            var data_package_deal_code = $(this).attr('data-package-deal-code');
            var set_pricing = $(this).attr('data-set-pricing');

                var Cgst = $(this).attr('data-cgst');
                var Sgst = $(this).attr('data-sgst');
                var Igst = $(this).attr('data-igst');
                var data_total_price = $(this).attr('data-total-price');  
            updateDealCode(data_package_deal_code,set_pricing,Cgst,Sgst,Igst, data_total_price);
            $('#selectedAmountDisplay').html(' &#8377; ' + data_total_price);

        });
        
    function updateDealCode(dealCodeData, set_pricing, Cgst, Sgst, Igst, data_total_price) {
        var main_price = set_pricing;
        var dealCodeJson = JSON.parse(dealCodeData);
        var dealCodeHtml = '';
        if (dealCodeJson.package && dealCodeJson.package.length > 0) {
            dealCodeHtml += '<b>Apply Deal Code</b>';
            dealCodeJson.package.forEach(function(deal) {
                dealCodeHtml += '<div class="deal-code-item mt-2">';
                dealCodeHtml += '<label>';
                dealCodeHtml += '<input type="checkbox" class="deal-checkbox" first-total-price="' + data_total_price + '" data-igst-per="' + Igst + '" data-sgst-per="' + Sgst + '" data-cgst-per="' + Cgst + '"  data-main-price="' + main_price + '" name="deal_code_per[]" deal-code-per="' + deal.deal_code_per + '" ' + (deal.checkbox_offer == 1 ? 'checked' : '') + '>';
                dealCodeHtml += '&nbsp;&nbsp;&nbsp;&nbsp; <b>Apply&nbsp;&nbsp;&nbsp;&nbsp;</b>' + deal.deal_code + '<b>&nbsp;&nbsp;&nbsp;&nbsp;to&nbsp;&nbsp;&nbsp;&nbsp;avail&nbsp;&nbsp;&nbsp;&nbsp;</b>'  + deal.deal_code_per + '% ';
                dealCodeHtml += '</label>';
                dealCodeHtml += '</div>';
            });
        } else {
            dealCodeHtml = '<p>No deal code data available.</p>';
        }
        $('#deal_code').html(dealCodeHtml);
    }
      
    }); 
    $(document).ready(function() {
        $('input[name="deal_code_per[]"]').change(function() {
          var firstTotalPrice = $(this).attr('first-total-price');
          $('#selectedAmountDisplay').html(' &#8377; ' + firstTotalPrice);
          $('input[name="deal_code_per[]"]').not(this).prop('checked', false);
          $('input[name="deal_code_per[]"]:checked').each(function () {
          var dataCgst = $(this).attr('data-cgst-per');
          var dataSgst = $(this).attr('data-sgst-per');
          var dataIgst = $(this).attr('data-igst-per');
          var mainPricing = $(this).attr('data-main-price');
          var dealPercentage = $(this).attr('deal-code-per');
          var discount = (mainPricing * dealPercentage) / 100;
          var  totalCgst = (dataCgst * (mainPricing - discount)) / 100;
          var  totalSgst = (dataSgst * (mainPricing - discount)) / 100;
          var  totalIgst = (dataIgst * (mainPricing - discount)) / 100;
          var total = mainPricing - discount + totalCgst + totalSgst + totalIgst;
          var totalAmount = Math.round(total);
                $('#selectedAmountDisplay').html(' &#8377; ' + totalAmount);
             });
        });
    });  */

    $(document).ready(function() {
    // Initial setup: execute for the pre-checked checkbox
    $('input[name="duration_of_package[]"]:checked').each(function() {
        processPackageCheckbox(this);
    });

    // Handle changes in duration_of_package checkboxes
    $('input[name="duration_of_package[]"]').change(function() {
        if ($(this).prop('checked')) {
            // Uncheck all other checkboxes
            $('input[name="duration_of_package[]"]').not(this).prop('checked', false);
            // Process the currently checked checkbox
            processPackageCheckbox(this);
        }
    });

    // Handle changes in deal_code_per checkboxes
    $(document).on('change', 'input[name="deal_code_per[]"]', function() {
        var firstTotalPrice = $(this).attr('first-total-price');
        $('#selectedAmountDisplay').html(' &#8377; ' + firstTotalPrice);

        $('input[name="deal_code_per[]"]').not(this).prop('checked', false);

        $('input[name="deal_code_per[]"]:checked').each(function() {
            var dataCgst = $(this).attr('data-cgst-per');
            var dataSgst = $(this).attr('data-sgst-per');
            var dataIgst = $(this).attr('data-igst-per');
            var mainPricing = $(this).attr('data-main-price');
            var dealPercentage = $(this).attr('deal-code-per');
            var discount = (mainPricing * dealPercentage) / 100;
            var totalCgst = (dataCgst * (mainPricing - discount)) / 100;
            var totalSgst = (dataSgst * (mainPricing - discount)) / 100;
            var totalIgst = (dataIgst * (mainPricing - discount)) / 100;
            var total = mainPricing - discount + totalCgst + totalSgst + totalIgst;
            var totalAmount = Math.round(total);
            $('#selectedAmountDisplay').html(' &#8377; ' + totalAmount);
        });
    });

    function processPackageCheckbox(checkbox) {
        console.log("Processing package checkbox");

        var package_include = $(checkbox).attr('data-this-package-include');
        $('#package_includes_data').html(package_include);

        var data_package_deal_code = $(checkbox).attr('data-package-deal-code');
        var set_pricing = $(checkbox).attr('data-set-pricing');
        var Cgst = $(checkbox).attr('data-cgst');
        var Sgst = $(checkbox).attr('data-sgst');
        var Igst = $(checkbox).attr('data-igst');
        var data_total_price = $(checkbox).attr('data-total-price');

        updateDealCode(data_package_deal_code, set_pricing, Cgst, Sgst, Igst, data_total_price);
        $('#selectedAmountDisplay').html(' &#8377; ' + data_total_price);

        // Clear border color of all cards
        $('.card').css('border-color', '');

        // Set border color for the selected card
        $(checkbox).closest('.card').css('border-color', '#00205c');

        // Hide all package inclusions and show only the selected one
        $('.package-inclusions').hide();
        $(checkbox).closest('.card').find('.package-inclusions').show();
    }

    function updateDealCode(dealCodeData, set_pricing, Cgst, Sgst, Igst, data_total_price) {
        var main_price = set_pricing;
        var dealCodeJson = JSON.parse(dealCodeData);
        var dealCodeHtml = '';

        if (dealCodeJson.package && dealCodeJson.package.length > 0) {
            dealCodeHtml += '<b>Apply Deal Code</b>';
            dealCodeJson.package.forEach(function(deal) {
                dealCodeHtml += '<div class="deal-code-item mt-2">';
                dealCodeHtml += '<label>';
                dealCodeHtml += '<input type="checkbox" class="deal-checkbox" first-total-price="' + data_total_price + '" data-igst-per="' + Igst + '" data-sgst-per="' + Sgst + '" data-cgst-per="' + Cgst + '" data-main-price="' + main_price + '" name="deal_code_per[]" deal-code-per="' + deal.deal_code_per + '" ' + (deal.checkbox_offer == 1 ? 'checked' : '') + '>';
                dealCodeHtml += '&nbsp;&nbsp;&nbsp;&nbsp; <b>Apply&nbsp;&nbsp;&nbsp;&nbsp;</b>' + deal.deal_code + '<b>&nbsp;&nbsp;&nbsp;&nbsp;to&nbsp;&nbsp;&nbsp;&nbsp;avail&nbsp;&nbsp;&nbsp;&nbsp;</b>' + deal.deal_code_per + '% ';
                dealCodeHtml += '</label>';
                dealCodeHtml += '</div>';
            });
        } else {
            dealCodeHtml = '<p>No deal code data available.</p>';
        }

        $('#deal_code').html(dealCodeHtml);
    }
});


</script>
@endsection
<style>
    #bs-video-modal .modal-dialog {
        max-width: 800px;
        margin: 30px auto;
    }

    #bs-video-modal .modal-body {
        position: relative;
        padding: 5px;
    }

    @media (max-width: 765px) {
        .video-close .ribbon.float-end {
            margin-right: 0px !important;
            padding: 5px;
        }
    }

    .switch-btn {
            position: relative;
            display: inline-block;
            width: 55px;
            height: 28px;
        }

        .switch-btn input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .switch-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .switch-slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 5px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked+.switch-slider {
            background-color: #161c35;
        }

        input:focus+.switch-slider {
            box-shadow: 0 0 1px #161c35;
        }

        input:checked+.switch-slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        .switch-slider.round {
            border-radius: 34px;
        }

        .switch-slider.round:before {
            border-radius: 50%;
        }
    
</style>
