@extends('layout.main')
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xl-12 col-12">
                <div class="box" style="background-color: #00205c; color: #fff;">
                    <div class="box-body d-flex p-0">
                        <div class="flex-grow-1 p-30 flex-grow-1 bg-img bg-none-md"
                            style="background-position: right bottom; background-size: auto 100%; background-image: url(../../../images/svg-icon/color-svg/custom-30.svg)">
                            <div class="row">
                                <div class="col-12 col-xl-12">
                                    <h1 class="mb-0 fw-600">21st Century Social Emotional Learning + Near-Future Tech Package</h1>
                                    <div class="mt-45 d-md-flex align-items-center">
                                        <div class="me-30 mb-30 mb-md-0">
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="me-15 text-center fs-24 w-50 h-50 l-h-50 bg-danger b-1 border-white rounded-circle">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <div>
                                                    <h5 class="mb-0">Subscription validity</h5>
                                                    <p class="mb-0 text-white-70">
                                                        {{ date('d-m-Y', strtotime($school->package_start)) }} to
                                                        {{ date('d-m-Y', strtotime($school->package_end)) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="me-30 mb-30 mb-md-0">                                           
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="me-15 text-center fs-24 w-50 h-50 l-h-50 bg-success b-1 border-white rounded-circle">
                                                    <i class="fa fa-check-circle"></i>
                                                </div>
                                                <div>
                                                    <h5 class="mb-0">Subscription type</h5>
                                                    <p class="mb-0 text-white-70">{{ $school->is_demo == 1 ? 'Full Access' : 'Paid' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="me-30 mb-30 mb-md-0">                                           
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="me-15 text-center fs-24 w-50 h-50 l-h-50 bg-warning b-1 border-white rounded-circle">
                                                    <i class="fa fa-hourglass-half"></i>
                                                </div>
                                                <div>
                                                    <h5 class="mb-0">Time period left till subscription expiry</h5>
                                                    <p class="mb-0 text-white-70">{{$time_left}} Days</p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="col-12 col-xl-5"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

           <!--  <div class="col-xl-4 col-12">
                <div class="box bg-transparent no-shadow">
                    <div class="box-body p-xl-0 text-center">
                        <h3 class="px-30 mb-20">Have More<br>Knowledge to share?</h3>
                        <a href="{{ route('lesson.plan.add') }}" class="waves-effect waves-light w-p100 btn" style="background-color: #00205c; color: #fff;"><i
                                class="fa fa-plus me-15"></i> Add Teacher Account</a>
                    </div>
                </div>              
            </div> -->


            <div class="col-xl-12 col-12">
                <div class="row">
                    <div class="col-xl-6 col-6">
                        <div class="box">
                            <div class="box-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="me-15 h-58 w-50 height-circal rounded-circle text-center" style="background-color: #00205c; color: #fff;">
                                            <span class=" fs-20 text-center">{{ $school->licence }}</span>
                                        </div>
                                        <div class="d-flex flex-column fw-500">
                                            <a href="#" class="text-dark mb-1 fs-16">Total Classroom Subscriptions</a>
                                            <span class="text-fade"></span>
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6 col-6">
                        <div class="box">
                            <div class="box-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="me-15 h-58 w-50 height-circal rounded-circle text-center" style="background-color: #00205c; color: #fff;">
                                            <span class="fs-20 text-center">{{ $school->teacher->count() }}</span>
                                        </div>
                                        <!-- <div class="me-15 h-58 w-50 height-circal rounded-circle text-center" style="background-color: #00205c; color: #fff;">
                                            <span class=" fs-20 text-center">&nbsp;&nbsp;{{ $school->teacher->count() }}</span>
                                        </div> -->
                                        <div class="d-flex flex-column fw-500">
                                            <a href="#" class="text-dark  mb-1 fs-16">Active Classroom Subscriptions</a>
                                            <span class="text-fade"></span>
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                    


<!-- <div class="col-xl-12 ">
    <div class="box">
        <div class="box-body">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="d-flex flex-column fw-500 align-items-center">
                        <a href="#" class="text-dark mb-1 fs-16">Demo tutorial for school admin</a>
                        <iframe class="embed-responsive-item" frameborder="0" src="https://player.vimeo.com/video/869096592?h=e012a80feb&amp;badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479"
                            id="video" allow="autoplay" width="500" height="400"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->
<div class="col-xl-12">
    <div class="box">
        <div class="box-body">
            <div class="d-flex align-items-center justify-content-center"><!-- Center content horizontally -->
                <div class="text-center"><!-- Center content horizontally -->
                    <div class="d-flex flex-column fw-500 align-items-center">
                        <a href="#" class="text-dark mb-1 fs-16">Demo tutorial for school admin</a>
                        <iframe class="embed-responsive-item" frameborder="0" src="https://player.vimeo.com/video/925836004?h=6497352ba8&amp;badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479"
                            id="video" allow="autoplay" width="500" height="400"
                            style="border: 0.5px solid #ccc; border-radius: 50px;"
                            ></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





                </div>
            </div>


        </div>
    </section>
    <!-- /.content -->
@endsection
