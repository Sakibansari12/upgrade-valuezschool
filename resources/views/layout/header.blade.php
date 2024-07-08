<header class="main-header">
    <div class="d-flex align-items-center logo-box justify-content-start">
        <!-- Logo -->
        <a href="{{ url('/') }}" class="logo">
            @php
                if (session()->get('usertype') != 'superadmin' && session()->get('usertype') != 'contentadmin') {
                    $user = Auth::user();
                    
                    $schoolid = $user->school_id;
                    $school = App\Models\School::where('id', $schoolid)->first();
                    $student_count = App\Models\Student::where('school_id', $schoolid)->count();

                    $studentid = $user->student_id;
                    $student = App\Models\Student::where('id', $studentid)->first();

                    $grade_id = $user->grade;
                    $grade_data = App\Models\Program::where('id', $grade_id)->first();

                    $logo = 'uploads/school/' . ($school->school_logo ?? '');

                    $header_name = $school->school_name ?? '';
                    $display_name = !empty($school->school_logo) ? 'd-none' : '';
                    $display_logo = empty($school->school_logo) ? 'd-none' : '';
                } else {
                    $logo = 'assets/images/company_logo.png';
                    $header_name = '';
                    $display_name = '';
                    $display_logo = '';
                    
                }
            @endphp


            <!-- logo-->
            <div class="logo-mini">
                <span class="light-logo"><img src="{{ asset('assets/images/company_logo.png') }}" alt="logo"
                        style="max-height:60px;"></span>
            </div>
            {{-- <div class="logo-lg {{ $display_name }}">
                {{ $header_name }}
            </div> --}}

        </a>
    </div>
    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <div class="app-menu">
            <ul class="header-megamenu nav">
                <li class="btn-group nav-item">
                    <a href="#" class="waves-effect waves-light nav-link push-btn  ms-0"
                        style="background-color: #00205c; color: #fff;" data-toggle="push-menu" role="button">
                        <i data-feather="menu"></i>
                    </a>
                </li>
                <li class="header-megamenu nav">
                    @if (auth()->user()->usertype == 'student')
                        <a href="#" class="waves-effect waves-light bg-transparent"
                            style="background-color: #00205c; font-size: 16px; text-align:center; margin-top:10px;"
                            id="show_student_subcrition_text">
                        </a>
                    @endif
                </li>

                <li class="btn-group d-lg-inline-flex d-none">
                    <div class="app-menu">
                        <div class="search-bx mx-5">
                            @if (auth()->user()->usertype == 'teacher')
                                <form action="{{ route('teacher-search.lesson.list') }}" method="GET"
                                    id="search-form">
                                    <div class="input-group-searchll">
                                        <input type="text" class="form-control" id="search-input-instruction"
                                            name="query" style="text-align: center;" placeholder="Search Content"
                                            onkeydown="if (event.key === 'Enter') submitForm();">
                                    </div>
                                </form>
                            @endif
                            @if (auth()->user()->usertype == 'superadmin')
                                <form>
                                    <div class="input-group-search">
                                        <input type="search" class="form-control" id="search-input"
                                            placeholder="Search School/Classroom">
                                        <ol id="search-results-dashbord"
                                            style="display: none; list-style-type: decimal;"></ol>
                                    </div>
                                </form>
                            @endif
                            @if (session('usertype') == 'admin')
                                <div class="input-group-searchtt">
                                    <input type="text" class="form-control" id="search-input-teacher" name="query"
                                        style="text-align: center;" placeholder="Search Classroom">
                                </div>
                            @endif
                        </div>
                    </div>
                </li>


                {{-- <li class="btn-group nav-item"><h4 class="title-bx text-primary">{{ $header_name }}</h4></li>
                <li class="btn-group nav-item">
                    <div class="d-flex pt-1 align-items-center">
                        <img src="{{ asset($logo) }}"
                            class="bg-primary-light h-40" alt="">
                    </div>
                </li> --}}
            </ul>
        </div>

        @php
            $user_type = session('usertype') == 'superadmin' ? 'Super Admin' : session('usertype');
            $fullname = auth()->user()->name;
        @endphp
        <div class="navbar-custom-menu r-side">
            <ul class="nav navbar-nav">
                {{-- <li class="btn-group d-md-inline-flex d-none">
                    <a href="javascript:void(0)" title="skin Change" class="waves-effect skin-toggle waves-light">
                        <label class="switch">
                            <input type="checkbox" data-mainsidebarskin="toggle" id="toggle_left_sidebar_skin">
                            <span class="switch-on"><i data-feather="sun"></i></span>
                            <span class="switch-off"><i data-feather="moon"></i></span>
                        </label>
                    </a>
                </li> --}}
                @if ($user_type == 'admin' || $user_type == 'teacher' || $user_type == 'student')
                    <li class="dropdown notifications-menu btn-group">
                        <a href="#" id="notify_list_icon"
                            class="waves-effect waves-light svg-bt-icon bg-transparent notifyList"
                            style="background-color: #00205c;" data-user-type="{{ $user_type }}"
                            data-bs-toggle="dropdown" title="Notifications">
                            <!-- <i data-feather="bell"></i> -->
                            <i data-feather="bell" style="position: relative;"></i>
                            <span class="d-flex" id="notify_count_top"></span>
                            {{-- <div class="pulse-wave"></div> --}}
                        </a>
                        <ul class="dropdown-menu animated bounceIn">
                            <li class="header">
                                <div class="p-20">
                                    <div class="flexbox">
                                        <div>
                                            <h4 class="mb-0 mt-0">Notifications</h4>
                                        </div>
                                        <div>
                                            <a href="javascript:void(0);" data-user-type="{{ $user_type }}"
                                                class="text-danger text-nowrap clear_all_notify">Clear All</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <ul class="menu sm-scrol" id="notify_list">
                                    {{-- <li><a href="#">noti</a></li> --}}
                                </ul>
                            </li>
                            <li class="footer " style="background-color: #00205c; color: #fff;">
                                <a href="{{ route('notify.schoolview') }}" class="text-white">View all</a>
                            </li>
                        </ul>
                    </li>
                @endif
                <!-- User Account-->
                <li class="dropdown user user-menu">
                    <a href="#" id="showSidebar"
                        class="waves-effect waves-light dropdown-toggle w-auto l-h-12 bg-transparent p-0 no-shadow"
                        data-bs-toggle="modal" data-bs-target="#quick_user_toggle">
                        <div class="d-flex pt-1 align-items-center">
                            <div class="text-end me-10">
                                <p class="pt-5 fs-14 mb-0 fw-700">{{ $fullname }}</p>
                                @if (auth()->user()->usertype != 'teacher')
                                    <small class="fs-10 mb-0 text-uppercase text-mute">{{ ucfirst($user_type) }}</small>
                                @endif
                                @if (auth()->user()->usertype == 'teacher')
                                    @if (ucfirst($user_type) == 'Teacher')
                                        <small class="fs-10 mb-0  text-mute">Classroom</small>
                                    @endif
                                @endif
                            </div>
                            @if (auth()->user()->usertype == 'admin')
                                <img src="{{ asset('assets/images/avatar/school_admin_2.jpg') }}"
                                    class="avatar rounded-circle bg-primary-light h-40 w-40" alt="" />
                            @endif
                            @if (auth()->user()->usertype == 'student')
                                <img src="{{ url('uploads/avatar/') }}/{{ $student->student_image ? $student->student_image : '' }}"
                                    class="avatar rounded-circle bg-primary-light h-40 w-40" />
                            @endif
                            @if (auth()->user()->usertype == 'superadmin')
                                <img src="{{ asset('assets/images/avatar/50980.png') }}"
                                    class="avatar rounded-circle bg-primary-light h-40 w-40" alt="" />
                            @endif

                            @if (auth()->user()->usertype == 'teacher')
                                @if ($grade_data && $grade_data->class_image)
                                    <img src="{{ url('uploads/program') }}/{{ $grade_data->class_image }}"
                                        class="avatar rounded-circle bg-primary-light h-55 w-50">
                                @endif
                            @endif

                        </div>
                    </a>
                </li>
                <!-- Yecode -->
                <li class="dropdown user user-menu" style="list-style: none;">
                    @php
                        $user_data = auth()->user();
                    @endphp
                    <a href="#" class="waves-effect waves-light w-auto l-h-12 bg-transparent p-0 no-shadow"
                        title="User" data-bs-toggle="modal" data-bs-target="#quick_user_toggle">
                        <div class="d-flex pt-1 align-items-center">
                            <div class="btn btn-icon w-auto btn-clean d-flex align-items-center btn-lg px-2">
                                <div class="user-details">
                                    <!-- <span class="user-greeting">Hi,</span>
                    <span class="user-name">{{ isset($user_data->name) ? $user_data->name : '' }}</span> -->
                                </div>
                            </div>
                        </div>
                    </a>
                    <!-- User Information Sidebar -->
                    <div class="sidebar-login-detail" id="userSidebar"
                        style="width: {{ strlen($user_data->email) > 20 && auth()->user()->usertype != 'teacher' ? '320px' : '250px' }};">
                        <span class="close-button-login-detail" id="closeSidebar"
                            style="font-size: 24px;">&times;</span>
                        <div class="user-content">
                            <div class="user-avatar">
                                @if (auth()->user()->usertype == 'admin')
                                    <img src="{{ asset('assets/images/avatar/school_admin_2.jpg') }}" width="100"
                                        height="100" alt="" />
                                @endif
                                @if (auth()->user()->usertype == 'superadmin')
                                    <img src="{{ asset('assets/images/avatar/50980.png') }}" width="100"
                                        height="100" alt="" />
                                @endif

                                @if (auth()->user()->usertype == 'teacher')
                                    @if ($grade_data && $grade_data->class_image)
                                        <img src="{{ url('uploads/program') }}/{{ $grade_data->class_image }}"
                                            width="100" height="100" alt="">
                                    @endif
                                @endif
                                @if (auth()->user()->usertype == 'student')
                                    @if ($grade_data && $grade_data->class_image)
                                        <!-- <img src="{{ url('uploads/program') }}/{{ $grade_data->class_image }}" width="100" height="100" alt=""> -->


                                        <img src="{{ url('uploads/avatar/') }}/{{ $student->student_image ? $student->student_image : '' }}"
                                            width="100" height="100" alt="" />
                                    @endif
                                @endif
                                @if (auth()->user()->usertype == 'admin')
                                    <div class="navi mt-2">
                                        <span class="navi-text text-capitalize">School
                                            {{ isset($user_data->usertype) ? $user_data->usertype : '' }} :
                                            {{ isset($user_data->name) ? $user_data->name : '' }} </span>
                                    </div>
                                @else
                                    @if (auth()->user()->usertype != 'teacher')
                                        <div class="navi mt-2">
                                            <span
                                                class="navi-text text-capitalize"><!-- {{ isset($user_data->usertype) ? $user_data->usertype : '' }} :  -->{{ isset($user_data->name) ? $user_data->name : '' }}</span>
                                        </div>
                                    @endif

                                @endif
                                <div class="navi mt-2">
                                    <span
                                        class="navi-text text-capitalize">{{ isset($header_name) ? $header_name : '' }}</span>
                                </div>
                                @if (auth()->user()->usertype == 'teacher')
                                    <div class="navi mt-2">
                                        <span class="navi-text text-capitalize"><b>Grade :</b>
                                            {{ isset($grade_data->class_name) ? $grade_data->class_name : '' }}
                                        </span>

                                    </div>
                                    <div class="navi mt-2">

                                        <span class="navi-text"><b>Section :</b>
                                            {{ isset($user_data->section) ? $user_data->section : '' }}
                                        </span>
                                    </div>
                                    <div class="navi mt-2">

                                        <span class="navi-text"><b>Username :</b>
                                            {{ isset($user_data->username) ? $user_data->username : '' }}
                                        </span>
                                    </div>
                                    <div class="navi mt-2">
                                        <span class="navi-text"><b>Subscription type :</b>
                                            {{ $school->is_demo == 1 ? 'Demo' : 'Full Access' }}
                                        </span>
                                    </div>
                                @endif
                                @if (auth()->user()->usertype != 'teacher')
                                    @if ($user_data->email)
                                        <div class="navi mt-2">
                                            <span class="navi-link p-0 pb-2">
                                                <span class="navi-icon mr-1">
                                                    <span class="svg-icon svg-icon-lg svg-icon-primary">
                                                        <i class="fas fa-envelope"></i>
                                                    </span>
                                                </span>
                                                <span
                                                    class="navi-text">{{ isset($user_data->email) ? $user_data->email : '' }}</span>
                                            </span>
                                        </div>
                                    @endif
                                @endif
                                @if (auth()->user()->usertype == 'student')
                                    <div class="navi mt-2">
                                        <span class="navi-text"><!-- Grade : -->
                                            {{ isset($grade_data->class_name) ? $grade_data->class_name : '' }}</span>
                                    </div>
                                    <div class="navi mt-2">
                                        <span class="navi-text">Username :
                                            {{ isset($user_data->username) ? $user_data->username : '' }}</span>
                                    </div>
                                @endif


                                @if (auth()->user()->usertype == 'admin')
                                    <div class="navi mt-3">
                                        <button class="btn"
                                            style="background-color: #00205c; color: #fff; height: 38px;"
                                            id="userDetailPopup"> View login credentials</button>
                                    </div>
                                    <div class="modal-status" id="bs-download-file-user">

                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header py-3">
                                                    <h4 class="modal-title" id="modal-label-school">
                                                        User Detail </h4>
                                                    <button type="button" class="btn-close"
                                                        id="closeuserDetailPopup" data-bs-dismiss="modal"
                                                        aria-hidden="true"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div id="viewSchool">
                                                        <div class="card-body p-0">
                                                            <table class="table my-2">
                                                                <tbody>
                                                                    <tr>
                                                                        <th style="width: 120px;">Name</th>
                                                                        <td>
                                                                            <a href="#" class="fw-bold">
                                                                                {{ isset($user_data->name) ? $user_data->name : '' }}
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th style="width: 120px;">Email</th>
                                                                        <td>
                                                                            <a href="#" class="fw-bold">
                                                                                {{ isset($user_data->email) ? $user_data->email : '' }}
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th style="width: 120px;">Password</th>
                                                                        <td>
                                                                            <a href="#" class="fw-bold">
                                                                                {{ isset($user_data->view_pass) ? $user_data->view_pass : '' }}
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- /.modal-content -->
                                        </div>
                                    </div>
                                @endif
                                @if (auth()->user()->usertype != 'teacher')
                                    <div class="navi mt-2 ">
                                        <a href="{{ route('signout') }}">
                                            <button class="btn"
                                                style="background-color: #00205c; color: #fff; height: 37px;">Logout
                                            </button>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </li>
                <!-- end code -->


                <!-- Control Sidebar Toggle Button -->
                <li class="btn-group nav-item d-none">
                    <a href="#" data-provide="fullscreen"
                        class="waves-effect waves-light nav-link btn-primary-light svg-bt-icon" title="Full Screen">
                        <i data-feather="maximize"></i>
                    </a>
                </li>

            </ul>
        </div>
    </nav>
</header>

<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar-->
    <section class="sidebar position-relative">
        <div class="multinav">
            <div class="multinav-scroll" style="height: 97%;">
                <!-- sidebar menu-->
                <ul class="sidebar-menu" data-widget="tree">

                    @if (session('usertype') == 'superadmin')
                        <li class="{{ Request::is('admin-dashboard') ? 'active' : '' }}">
                            <a href="{{ route('admin-dashboard') }}"><i
                                    data-feather="home"></i><span>Dashboard</span></a>
                        </li>
                        <li class="{{ Request::is('grade/*') ? 'active' : '' }}">
                            <a href="{{ route('program.list') }}"><i data-feather="list"></i><span>Grade</span></a>
                        </li>
                        <li class="{{ Request::is('course/*') ? 'active' : '' }}">
                            <a href="{{ route('course.list') }}"><i data-feather="folder"></i><span>Course</span></a>
                        </li>
                        <li class="{{ Request::is('identifier/*') ? 'active' : '' }}">
                            <a href="{{ route('identifier.list') }}"><i data-feather="folder"></i><span>
                            AI Module</span></a>
                        </li>
                        <li class="{{ Request::is('master/aimodules/*') ? 'active' : '' }}">
                            <a href="{{ route('aimodules.list') }}"><i data-feather="folder"></i><span>
                                    Module Prompts </span></a>
                        </li>
                        <li class="{{ Request::is('lesson-plan/*') ? 'active' : '' }}">
                            <a href="{{ route('lesson.plan.list') }}"><i
                                    data-feather="calendar"></i><span>Instructional Module</span></a>
                        </li>
                        <li class="{{ Request::is('healty-mind/*') ? 'active' : '' }}">
                            <a href="{{ route('healty-mind-list') }}">
                                <i data-feather="smile"></i>
                                <span>
                                    Healthy Mind
                                </span></a>
                        </li>
                        <li class="{{ Request::is('ncf-assessment/*') ? 'active' : '' }}">
                            <a href="{{ route('ncf-assessment-list') }}">
                                <i data-feather="bar-chart"></i>
                                <span>
                                    NCF Assessments
                                </span></a>
                        </li>
                        <li class="{{ Request::is('quiz-category/*') ? 'active' : '' }}">
                            <a href="{{ route('quiz-category-list') }}">
                                <i data-feather="help-circle"></i>
                                <span>
                                    Quiz Category
                                </span></a>
                        </li>
                        <li class="{{ Request::is('quiz-title/*') ? 'active' : '' }}">
                            <a href="{{ route('quiz-title-list') }}">
                                <i data-feather="help-circle"></i>
                                <span>
                                    Quiz Title
                                </span></a>
                        </li>
                        <li class="{{ Request::is('quiz/*') ? 'active' : '' }}">
                            <a href="{{ route('quiz-list') }}">
                                <i data-feather="help-circle"></i>
                                <span>
                                    Quiz Questions
                                </span></a>
                        </li>
                        <li class="{{ Request::is('avatar /*') ? 'active' : '' }}">
                            <a href="{{ route('avatar-list') }}">
                                <i data-feather="help-circle"></i>
                                <span>
                                    Avatar
                                </span></a>
                        </li>

                        <li class="{{ Request::is('whats-new/*') ? 'active' : '' }}">
                            <a href="{{ route('notify.list') }}"><i data-feather="bell"></i><span>What's
                                    new</span></a>
                        </li>


                        <!-- <li class="{{ Request::is('whats-new/*') ? 'active' : '' }}">
                            <a href="{{ route('notify.list') }}"><i data-feather="bell"></i><span>What's
                                    new</span></a>
                        </li> -->

                        <li class="{{ Request::is('reminder/*') ? 'active' : '' }}">
                            <a href="{{ route('reminder.list') }}"><i
                                    data-feather="bell"></i><span>Reminders</span></a>
                        </li>
                        <li class="{{ Request::is('terms-privacy/*') ? 'active' : '' }}">
                            <a href="{{ route('terms-privacy.list') }}"><i data-feather="bell"></i><span>Terms &
                                    Privacy</span></a>
                        </li>
                        <li class="header">Account</li>
                        <li class="{{ Request::is('school/*') ? 'active' : '' }}">
                            <a href="{{ route('school.list') }}"><i data-feather="grid"></i>Manage School</a>
                        </li>
                        <li class="{{ Request::is('school/*') ? 'active' : '' }}">
                            <a href="{{ route('all.school.student.list') }}"><i data-feather="grid"></i>All School
                                Student</a>
                        </li>
                        <li class="{{ Request::is('download/*') ? 'active' : '' }}">
                            <a href="{{ route('download-list') }}">
                                <i data-feather="download"></i>
                                School Ads</a>
                        </li>
                        <li class="{{ Request::is('feedback/*') ? 'active' : '' }}">
                            <a href="{{ route('feedback.teacher') }}"><i
                                    data-feather="message-circle"></i><span>FeedBack
                                    <span class="d-flex" id="feedback_reply_noty"></span>
                                </span></a>
                        </li>

                        <li class="{{ Request::is('support/*') ? 'active' : '' }} color-icon">
                            <a href="{{ route('support-list') }}">
                                <i data-feather="life-buoy"></i>
                                Support
                                <span class="d-flex" id="support_count_noty"></span>
                            </a>
                        </li>

                        <li class="{{ Request::is('testing/*') ? 'active' : '' }}">
                            <a href="{{ route('testing') }}"><i data-feather="grid"></i>Testing</a>
                        </li>
                        <li class="{{ Request::is('users/*') ? 'active' : '' }}">
                            <a href="{{ route('users.admin.list') }}"><i data-feather="user"></i><span>Content Admin
                                </span></a>
                        </li>
                        <li class="{{ Request::is('subscription/*') ? 'active' : '' }} color-icon">
                            <a href="{{ route('subscription-request-list') }}"><span class="text-fade"><i
                                        class="fas fa-headset support-class"></i></span>
                                <span class="email-class">Subscription Request
                                    <span class="d-flex" id="subscription_request_count"></span>
                                </span></a>
                        </li>
                        <li class="{{ Request::is('forgpt-password/*') ? 'active' : '' }}">
                            <a href="{{ route('student.forgot.list') }}"><i data-feather="user"></i><span>Forgot
                                    Password
                                    <span class="d-flex" id="forgot_noty_password"></span>
                                </span></a>
                        </li>
                        <li class="{{ Request::is('register-student-list/*') ? 'active' : '' }}">
                            <a href="{{ route('register-list') }}"><i data-feather="user"></i><span>Users (no access
                                    code)
                                </span></a>
                        </li>
                        <li class="{{ Request::is('school-payments-list/*') ? 'active' : '' }}">
                            <a href="{{ route('payment-list') }}"><i data-feather="user"></i><span>Payments
                                </span></a>
                        </li>
                        <li class="{{ Request::is('invoice/*') ? 'active' : '' }}">
                            <a href="{{ route('invoice.list') }}">
                                <i data-feather="help-circle"></i>
                                <span>
                                    Invoice
                                </span></a>
                        </li>
                        <li class="{{ Request::is('package/*') ? 'active' : '' }}">
                            <a href="{{ route('package-list') }}"><i data-feather="user"></i><span>Package
                                </span></a>
                        </li>
                    @elseif(session('usertype') == 'contentadmin')
                        <li class="{{ Request::is('admin-dashboard') ? 'active' : '' }}">
                            <a href="{{ route('admin-dashboard') }}"><i
                                    data-feather="home"></i><span>Dashboard</span></a>
                        </li>
                        <li class="{{ Request::is('lesson-plan/*') ? 'active' : '' }}">
                            <a href="{{ route('lesson.plan.list') }}"><i
                                    data-feather="calendar"></i><span>Instructional Module</span></a>
                        </li>
                    @elseif(session('usertype') == 'admin')
                        <li class="{{ Request::is('dashboard') ? 'active' : '' }}">
                            <a href="{{ route('dashboard') }}"><i data-feather="home"></i><span>Dashboard</span></a>
                        </li>
                        <li class="{{ Request::is('school/*') ? 'active' : '' }}">
                            <a href="{{ route('school.teacher.list') }}"><i data-feather="users"></i><span>Manage
                                    classrooms</span></a>
                        </li>
                        @if ($student_count > 0)
                        <li class="{{ Request::is('student/*') ? 'active' : '' }}">
                            <a href="{{ route('student-list', ['school_id' => $user_data->school_id]) }}"><i
                                    data-feather="users"></i><span>Manage
                                    Students</span></a>
                        </li>
                        @endif
                        <li class="{{ Request::is('whats-new/*') ? 'active' : '' }}">
                            <a href="{{ route('notify.schoolview') }}"><i data-feather="bell"></i><span>What's
                                    new
                                    <span class="d-flex" id="notify_count"></span>
                                </span></a>
                        </li>
                        <li class="{{ Request::is('download/*') ? 'active' : '' }}">
                            <a href="{{ route('download-lists') }}"><i data-feather="download"></i>Ad graphics</a>
                        </li>

                        <!-- <li class="{{ Request::is('student/*') ? 'active' : '' }}">
                            <a href="{{ route('student_payment_list') }}"><i data-feather="user"></i><span>Students
                                    </span></a>
                        </li> -->

                        <li class="{{ Request::is('support/*') ? 'active' : '' }} color-icon">
                            <a href="{{ route('support') }}"><i data-feather="life-buoy"></i>Support</a>
                        </li>
                        <!-- <li class="{{ Request::is('subscription/*') ? 'active' : '' }} color-icon">
                            <a href="{{ route('subscription-list') }}"><span class="text-fade"><i
                                        class="fas fa-plus-circle"></i></span><span class="email-class">Add
                                    subscription </span></a>
                        </li> -->
                    @elseif(session('usertype') == 'teacher')
                        <li class="{{ Request::is('teacher/*') ? 'active' : '' }}">
                            <a href="{{ route('teacher.class.list') }}"><i data-feather="list"></i><span>Grade
                                    list</span></a>
                        </li>
                        <li class="{{ Request::is('teacher.class.history') ? 'active' : '' }}">
                            <a href="{{ route('teacher.class.history') }}"><i
                                    data-feather="calendar"></i><span>Module completion</span></a>
                        </li>
                        <li class="{{ Request::is('healty-mind/*') ? 'active' : '' }}">
                            <a href="{{ route('healty-minds-list') }}"><i data-feather="smile"></i><span>Healthy
                                    mind</span></a>
                        </li>
                        <li class="{{ Request::is('ncf-assessment/*') ? 'active' : '' }}">
                            <a href="{{ route('ncf-assessments-list') }}">
                                <i data-feather="bar-chart"></i>
                                <span>
                                    NCF Assessments
                                </span></a>
                        </li>

                        <li class="{{ Request::is('whats-new/*') ? 'active' : '' }}">
                            <a href="{{ route('notify.teacherview') }}"><i data-feather="bell"></i><span>What's
                                    new
                                    <span class="d-flex" id="notify_count"></span>
                                </span></a>
                        </li>
                        <!-- <li class="{{ Request::is('play-quiz/*') ? 'active' : '' }}">
                            <a href="{{ route('play.quiz') }}"><i data-feather="message-circle"></i><span>Take
                                    Quiz</span></a>
                        </li> -->
                        <li class="{{ Request::is('play-quiz/*') ? 'active' : '' }}">
                            <a href="{{ route('classroom.quiz-category') }}"><i data-feather="help-circle"></i><span>
                                    Quiz</span></a>
                        </li>
                        <li class="{{ Request::is('feedback/*') ? 'active' : '' }}">
                            <a href="{{ route('teacher.feedback') }}"><i
                                    data-feather="message-circle"></i><span>Feedback</span></a>
                        </li>
                    @elseif(session('usertype') == 'student')
                        <li class="{{ Request::is('student/*') ? 'active' : '' }}">
                            <a href="{{ route('student.grade.list') }}"><i data-feather="list"></i><span>Grade
                                    list</span></a>
                        </li>
                        <li class="{{ Request::is('reports/*') ? 'active' : '' }}">
                            <a href="{{ route('student-view-history') }}"><i
                                    data-feather="calendar"></i><span>Module completion</span></a>
                        </li>
                        <li class="{{ Request::is('whats-new/*') ? 'active' : '' }}">
                            <a href="{{ route('notify.studentview') }}"><i data-feather="bell"></i><span>What's
                                    new
                                    <span class="d-flex" id="notify_count"></span>
                                </span></a>
                        </li>
                        <li class="{{ Request::is('reminder/*') ? 'active' : '' }}">
                            <a href="{{ route('reminder.lists') }}"><i
                                    data-feather="bell"></i><span>Reminders
                                    <span class="d-flex" id="notify_reminder_count"></span>
                                    </span></a>
                        </li>
                        <li class="{{ Request::is('my-profile/*') ? 'active' : '' }}">
                            <a href="{{ route('student-details') }}"><i data-feather="user"></i><span>My
                                    Profile</span></a>
                        </li>
                        <li class="{{ Request::is('quiz/*') ? 'active' : '' }}">
                            <a href="{{ route('student.quiz.category') }}"><i data-feather="help-circle"></i><span>Quiz
                                    </span></a>
                        </li>
                        <li class="{{ Request::is('quiz/student-quiz-report') ? 'active' : '' }}">
                            <a href="{{ route('student.quiz.report') }}"><i data-feather="user"></i><span>Quiz
                                    Report</span></a>
                        </li>
                        <!-- <li class="{{ Request::is('billing/*') ? 'active' : '' }}">
                            <a href="{{ route('student-billing') }}"><i data-feather="file-text"></i><span>Billing</span></a>
                        </li> -->

                        <!-- <li class="">
                            <a href="#"><i data-feather="book"></i><span>Olympiad mocks</span></a>
                        </li> -->
                        <!-- <li class="">
                            <a href="#"><i data-feather="edit"></i><span>Take Olympiad</span></a>
                        </li> -->


                        <li>
                            <a href="{{ route('student-signout') }}"><i
                                    data-feather="log-out"></i><span>Logout</span></a>
                        </li>
                    @endif
                    @if (session('usertype') != 'student' && session('usertype') != 'teacher')
                        <li>
                            <a href="{{ route('signout') }}"><i data-feather="log-out"></i><span>Logout</span></a>
                        </li>
                    @endif
                </ul>

                <div class="sidebar-widgets">
                    <div class="mx-25 mb-30 pb-20 side-bx">
                        <div class="text-center">
                            <img src="{{ asset($logo) }}" class="sideimg p-5" alt="">
                            <h4 class="title-bx" style="color: 00205c;" id="get_schoolname"
                                data-text="{{ $header_name }}">{{ $header_name }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</aside>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#userDetailPopup').click(function() {
            $('#bs-download-file-user').show();
        });
        $('#closeuserDetailPopup').click(function() {
            $('#bs-download-file-user').hide();
        });
    });


    jQuery(document).ready(function() {
        $('#search-input').on('input', function() {
            var query = $(this).val();
            $.ajax({
                type: 'GET',
                url: '{{ route('school-teacher') }}',
                data: {
                    query: query
                },
                success: function(response) {
                    if (response['status'] == true) {
                        var data = response['school_teacher_Data'];
                        $('#search-results-dashbord').empty();
                        if (data.length > 0) {
                            var resultList = $('#search-results-dashbord');
                            for (var i = 0; i < data.length; i++) {
                                var item = data[i];
                                resultList.append('<li data-school-id="' + item.school_id +
                                    '">' + item.common_name + '</li>');
                            }
                            resultList.show();
                        } else {
                            $('#search-results-dashbord').hide();
                        }
                    }
                },
                error: function() {
                    $('#search-results-dashbord').html(
                        'An error occurred while fetching data.');
                }
            });
        });
        $('#search-results-dashbord').on('click', 'li', function() {
            var selectedSchoolId = $(this).data('school-id');
            console.log(selectedSchoolId, "selectedSchoolId");
            if (selectedSchoolId) {
                window.location.href = '{{ route('teacher.list') }}?school=' + selectedSchoolId;
            }
        });
    });
</script>
<script>
    const showSidebarButton = document.getElementById("showSidebar");
    const userSidebar = document.getElementById("userSidebar");
    const closeSidebarButton = document.getElementById("closeSidebar");

    showSidebarButton.addEventListener("click", () => {
        userSidebar.style.right = "0";
    });

    closeSidebarButton.addEventListener("click", () => {
        userSidebar.style.right = "-300px";
    });
</script>
<script>
    function submitForm() {
        document.getElementById('search-form').submit();
    }
</script>
