@extends('layout.main')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title"><!-- Instructional Module --></h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><i class="mdi mdi-home-outline"></i></li>
                            <li class="breadcrumb-item" aria-current="page"><a
                                    href="{{ route('teacher.class.list') }}">{{ $class_name->class_name }}</a></li>
                            <li class="breadcrumb-item" aria-current="page"><a
                                    href="{{ route('teacher.course.list', ['class' => $class_id]) }}">Course</a></li>
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
                                if (($cdata->is_demo == 1 && $check_premium->is_demo == 1) || $check_premium->is_demo == 0) {
                                    $is_demo_content = 1;
                                    $main_video = !empty($cdata->video_url) ? App\Http\Controllers\WebPage::getVideoUrl($cdata->video_url) : '';
                                    $info_video = !empty($cdata->video_info_url) ? App\Http\Controllers\WebPage::getVideoUrl($cdata->video_info_url) : '';
                                    $myra_video = !empty($cdata->myra_video_url) ? App\Http\Controllers\WebPage::getVideoUrl($cdata->myra_video_url) : '';
                                } else {
                                    $main_video = $info_video = $is_demo_content = $myra_video = '#';
                                }
                            @endphp
                            <div class="col-xl-4 col-md-6 col-12 px-4">
                                <div class="card">
                                    @if ($is_demo_content == '#')
                                        <div class="position-absolute r-0"><button type="button"
                                                class="waves-effect waves-circle btn btn-circle btn-info btn-md mb-5"
                                                data-bs-toggle="modal" data-bs-target="#bs-video-modal-demo"><i
                                                    class="mdi mdi-lock fs-2"></i></button></div>
                                    @endif
                                    <img class="video-btn video-play-report"
                                        data-id-play="{{ $cdata->id }}"
                                        data-grade-play="{{ $class_id }}"
                                        src="{{ url('uploads/lessonplan') }}/{{ $cdata->lesson_image ? $cdata->lesson_image : 'no_image.png' }}"
                                        alt="{{ $cdata->title }}" data-title="{{ $cdata->title }}" data-bs-toggle="modal"
                                        data-src="{{ $main_video }}" data-msrc="{{ $myra_video }}"
                                        data-bs-target="#bs-video-modal{{ $main_video == '#' ? '-demo' : '' }}" />

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
                                        @if (!empty($cdata->myra_video_url))
                                           <div class="d-flex justify-content-around align-items-center my-2">
                                              
                                                <span class="fw-bolder">Live Anchor</span>
                                                <label class="switch-btn">
                                                    <input name="vidType" type="checkbox" value="mayra"
                                                        id="myra_radio_{{ $cdata->id }}"class="vidType with-gap">
                                                    <span class="switch-slider round"></span>
                                                </label>
                                                <span class="fw-bolder">Animated host</span>
                                               
                                            </div>
                                        @endif
                                        @if (($cdata->is_demo == 1 && $check_premium->is_demo == 1) || $check_premium->is_demo == 0)
                                            <div class="justify-content-center align-items-center ">
                                                
                                                    <!-- <button type="button" class="btn btn-info btn-sm mb-5"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#bs-info-modal-{{ $cdata->id }}"> Read Guidance</button>
                                                
                                                <button type="button" class="btn btn-info btn-sm mb-5" data-bs-toggle="modal" data-bs-target="#bs-download-file-{{ $cdata->id }}" > Assessment</button>
                                                 -->
                                                <div class="text-center">
                                                @if ($cdata->lesson_desc)
                                                    <button type="button" class="btn btn-info btn-sm mb-3 mr-3"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#bs-info-modal-{{ $cdata->id }}"> Read Guidance</button>
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
                                                        data-bs-toggle="modal" data-bs-target="#bs-video-modal">Instructional
                                                        Video</button>
                                                    {{-- <a class="popup-youtube btn btn-primary" href="{{ $video_url }}">Open
                                                    YouTube video</a> --}}
                                                @endif

                                                 <button id="read-btn-{{ $cdata->id }}" data-grade="{{ $class_id }}"
                                                    data-id="{{ $cdata->id }}" type="button"
                                                    
                                                    class="mb-5 mark-as-read btn btn-{{ in_array($cdata->id, $complete_lesson) ? 'success' : 'custom-color-chnages' }} btn-sm" >{{ in_array($cdata->id, $complete_lesson)
                                                        ? 'Completed'
                                                        : 'Mark
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            as
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            complete' }}</button>
                                                    </div>

                                            </div>
                                        @else
                                            <div class="card-footer justify-content-between d-flex px-0 pb-0">

                                                <ul class="list-inline mb-0">
                                                    <li><button class="btn btn-sm mb-5" data-bs-toggle="modal"
                                                            data-bs-target="#bs-video-modal-demo" style="background-color: #00205c; color: #ffff;">Subscribe</button></li>
                                                </ul>
                                            </div>
                                        @endif

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
                                                {{ $cdata->title }}</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-hidden="true"></button>
                                        </div>
                                        <div class="modal-body">
                                            {!! $cdata->lesson_desc !!}
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->
                        @endforeach
                    @else
                        <div class="col-sm-6">
                            <div class="card card-body">
                                <h5 class="card-title fw-600">Lesson Plan not found.</h5>
                                <a href="{{ route('teacher.class.list') }}" class="btn " style="background-color: #00205c; color: #fff;">Go Back</a>
                            </div> <!-- end card-->
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->


    <!-- Info modal -->
    <div class="modal fade" id="bs-video-modal-demo" tabindex="-1" role="dialog" aria-labelledby="modal-label-demo"
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
@endsection
@section('script-section')
    <script language="javascript" type="text/javascript">
        $(function() {
            var school_name = $("#get_schoolname").text();
            $("#school_name").html(school_name);
            $('.mark-as-read').click(function() {
                var videoId = $(this).attr('data-id');
                var classId = $(this).attr('data-grade');
                var button = $(this); // Get a reference to the button
                var buttonText = button.text();
                console.log(buttonText,"buttonText");
                swal({
                    //title: "Have you completed this module in class?",
                    title: buttonText === "Completed" ? "This action will remove completion status of the module and mark it as incomplete. Continue?" : "Have you completed this module in class?",
                  //  text: "Please don't click 'Mark as Complete' unless actually completed in your class. Continue?",
                  text: buttonText === "Completed" ? 'This action will remove completion status of the module and mark it as incomplete. Continue? This action will reverse "Mark as Complete" status.' : "Please don't click 'Mark as Complete' unless actually completed in your class. Continue?",
                  showCancelButton: true,

                    // type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#fec801",
                   // confirmButtonText: "Yes, Mark as Complete",
                   confirmButtonText: buttonText === "Completed" ? "Yes, mark as 'Not completed'": "Yes, Mark as Complete",
                    cancelButtonText: "Return to modules",
                    closeOnConfirm: false,
                    closeOnCancel: true
                }, function(isConfirm) {
                    if (isConfirm) {

                        $.ajax({
                            type: 'POST',
                            url: "{{ route('report.save.plan') }}",
                            data: {
                                planId: videoId,
                                gradeId: classId,
                                buttonText: buttonText,
                            },
                            /* beforeSend: function() {
                                $('#read-btn-' + videoId).html("Please wait..");
                            }, */
                            success: function(data) {
                                console.log(data,"data");
                                console.log(videoId,"videoId");
                                if (data == 'update') {
                                    $('#read-btn-' + videoId).html("Completed")
                                        .addClass("btn-success")
                                        .removeClass("btn-dark");
                                        $('#read-btn-' + videoId).removeAttr('style');
                                    swal("Completed!",
                                        "Your Module has been marked as completed.",
                                        "success");
                                } else {
                                    console.log(data);
                                    $('#read-btn-' + videoId).html("Error");
                                }
                                if(data == 'status_changes'){
                                    $('#read-btn-' + videoId).html('Mark as complete')
                                        //.addClass("btn-custom-color-chnages")
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

            $('.video-play-report').click(function() {
               // alert(123);
                var plan_Id = $(this).attr('data-id-play');
                var grade_Id = $(this).attr('data-grade-play');
                console.log(plan_Id, "planId");
                console.log(grade_Id, "sakib");
                var button = $(this); 
                $.ajax({
                            type: 'POST',
                            url: "{{ route('video-play-report-store') }}",
                            data: {
                                plan_Id: plan_Id,
                                grade_Id: grade_Id,
                            },
                            success: function(response) {
                                if (response == 'update') {
                                    
                                } else {
                                    
                                }
                               
                            }
                        });
            });

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
