@extends('layout.main')
@section('content')
<!-- Main content -->
<section class="content mt-20">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                    Instructional Module
                    </h5>
                    <div class="card-actions float-end">
                            <div class="dropdown show">
                                
                            </div>
                        </div>
                </div>
                @if (!empty($lessonPlan))
                                    @foreach ($lessonPlan as $cdata)
                <div class="card-body">
                        <div aria-expanded="true" class="v-expansion-panel v-expansion-panel--active v-item--active">
                            <div class="v-expansion-panel-content" style="">
                                <div class="v-expansion-panel-content__wrap">
                               
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
                                    <div class="row custom-content-container pt-0">
                                    
                                        <div class="col-md-4 col">
                                            <div class="p-10">
                                                <div class="v-image v-responsive custom-image-style">
                                                    <div class="v-image__image v-image__image--preload v-image__image--cover">

                                                    @if ($is_demo_content == '#')
                                                    <div class="position-absolute r-0">
                                                        <button type="button"
                                                            class="waves-effect waves-circle btn btn-circle btn-info btn-md mb-5"
                                                            data-bs-toggle="modal" data-bs-target="#bs-video-modal-demo"><i
                                                            class="mdi mdi-lock fs-2"></i>
                                                        </button>
                                                    </div>
                                                    @endif

                                                         @if(!$cdata->lesson_image) 
                                                        <img src="{{ asset('assets/images/no-profile-image.PNG') }}" width="100" height="100">
                                                        @endif
                                                        @if ($cdata->lesson_image)
                                                           <img class="video-btn"
                                                            src="{{ url('uploads/lessonplan') }}/{{ $cdata->lesson_image ? $cdata->lesson_image : 'no_image.png' }}"
                                                            alt="{{ $cdata->title }}" data-title="{{ $cdata->title }}" data-bs-toggle="modal"
                                                            data-src="{{ $main_video }}" data-msrc="{{ $myra_video }}"
                                                            data-bs-target="#bs-video-modal{{ $main_video == '#' ? '-demo' : '' }}" />
                                                        @endif

                                                        <table width="100%">
                                                            <thead>
                                                            </thead>
                                                            <tbody>
                                                            <tr id="studentImageError">
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                   
                                                    <div class="image-upload-option">
                                                       
                                                       
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="pb-0 pl-4 col col-8 custom-light-gray-background">
                                            <div class="p-10">
                                                <table class="width-100 custom-table-teacher" >
                                                    <tr>
                                                        <td class="p-10 width-200px fw-bold font-size-24 text-capitalize">School Name :</td>
                                                        <td class="p-5 font-size-22 font-weight-600 text-capitalize">{{ isset($check_premium->school_name) ? $check_premium->school_name : '' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="p-10 width-200px fw-bold font-size-24 text-capitalize">Title :</td>
                                                        <td class="p-5 font-size-22 font-weight-600 text-capitalize">{{ isset($cdata->title) ? $cdata->title : '' }}</td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <td class="p-10 width-200px fw-bold font-size-24 text-capitalize">Course :</td>
                                                        <td class="p-5 font-size-22 font-weight-600 text-capitalize">{{ isset($cdata->course->course_name) ? $cdata->course->course_name : '' }}</td>
                                                    </tr>
                                                    

                                                    <!-- <tr>
                                                        <td class="p-10 width-200px fw-bold font-size-24 text-capitalize">Teacher Guidance :</td>
                                                        <td class="p-5 font-size-22 font-weight-600 text-capitalize"
                                                        >
                                                            <button type="button" class="btn btn-info btn-sm mb-5"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#bs-info-modal-{{ $cdata->id }}">Read
                                                        Guidance</button>
                                                        </td>
                                                    </tr> -->
                                                    <tr>
                                                        <td class="p-10 width-200px fw-bold font-size-24  d-flex">Grade & Completion Status:</td>
                                                        <td class="p-5 font-size-22 font-weight-600 ">
                                                        @if($cdata->grade_name)
                                                                <ul class="custom-list-search">
                                                                    @foreach ($cdata->grade_name as $gdata)
                                                                        <li>
                                                                            <br>
                                                                            {{ $gdata->class_name }}<br>
                                                                            @if (in_array($gdata->id, $complete_lesson))
                                
                                                                                <span>Status:</span><span class="status-comp"> Completed </span><i class="fas fa-check-circle text-success"></i><br>
                                                                                <span><a href="{{ route('teacher.lesson.list', ['classid' => $gdata->id, 'course' => $cdata->course_id]) }}" class="complete-link" style="text-decoration: underline;">
                                                                                    Click for classroom view of module
                                                                                </a></span>
                                                                                
                                                                            @else
                                                                           
                                                                            <span>Status:</span><span class="status-incomp"> Incomplete </span><i class="fas fa-times-circle text-danger"></i><br>
                                                                                <span><a href="{{ route('teacher.lesson.list', ['classid' => $gdata->id, 'course' => $cdata->course_id]) }}" class="incomplete-link" style="text-decoration: underline; margin:2px;">
                                                                                    Click for classroom view of module
                                                                                </a></span>
                                                                            @endif
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    
                    
                                </div>
                            </div>
                        </div>
                </div>
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
<!-- Popup -->
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
                    <!-- <h5>This is a teacher preview of the module to access the class room view</h5> -->
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script-section')
<script>
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
</style>
