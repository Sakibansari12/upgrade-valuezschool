@extends('layout.main')
@section('content')
<head>
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> --}}
</head>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title"><!-- Students --></h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <!-- <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item alignment-text-new" aria-current="page">Manage School -
                               <a href="{{ route('school.list') }}">
                                    @if(!empty($schooldata->school_name))
                                        {{ $schooldata->school_name }}
                                    @endif    
                                </a>
                            </li> -->
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page"><a href="{{ route('school.list') }}"><i class="mdi mdi-home-outline"></i> - Manage School</a></li>
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page"><a href="{{ route('student.list', ['school_id' => $schoolid]) }}"><i class="mdi mdi-home-outline"></i> - Manage Student</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add Student</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-lg-8 col-12">
                <!-- Basic Forms -->
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Add New Student </h4>
                    </div>
                    <!-- /.box-header -->
                    <form name="studentcreate" id="studentcreate" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="form-group">
                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="First Name">
                                <p></p>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name">
                                <p></p>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        <div class="form-group">
                            <label for="grade">Grade <span class="text-danger">*</span></label>
                            <select name="grade" id="grade" class="form-control">
                                <!-- <option value="" disabled selected>Select a Course</option>
                                @foreach ($programs as $id => $programName)
                                    <option value="{{ $id }}">{{ $programName }}</option>
                                @endforeach -->

                                <option value="">Select a Grade</option>
                                            @if($programs->isNotEmpty())
                                            @foreach ($programs as $prog)
                                                <option value="{{ $prog->id }}">
                                                    {{ $prog->class_name }}</option>
                                            @endforeach
                                            @endif
                            </select>
                            <p></p>
                            @error('grade')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        </div>

                        <div class="form-group">
                                <label class="form-label">Section</label>
                                <textarea class="form-control" id="section" name="section" Placeholder="Section" rows="2" ></textarea>
                                @error('section')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                        </div>

                        <div class="form-group">
                            <label for="student_status">Student Status <span class="text-danger">*</span></label>
                            <select name="student_status" id="student_status" class="form-control">
                                <option value="">Select Student Status</option>
                                <option value="Demo">Demo</option>
                                <option value="Paid">Paid</option>
                                <option value="Pending">Pending</option>
                            </select>
                            <p></p>
                            @error('grade')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        </div>
                        <div class="controls">
                    <label for="set_password">Set Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" id="set_password" name="set_password" class="form-control" placeholder="Set Password" >
                                
                                <div class="input-group-append">
                                    <span class="input-group-text" id="show_new_password"><i class="fa fa-eye"></i></span>
                                </div>
                                <p></p>
                            </div>
                    </div>
                    <div class="controls">
                    <label for="confirm_password">Confirm Password<span class="text-danger">*</span></label>
                            <div class="input-group" id="password_match"`>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm Password" >
                                
                                <div class="input-group-append">
                                    <span class="input-group-text" id="show_Confirm_Password"><i class="fa fa-eye"></i></span>
                                </div>
                                <p></p>
                            </div>
                    </div>






                            <div class="form-group">
                                <label class="form-label">Email <span class="text-danger"></span></label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email">
                                <p></p>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Phone <span class="text-danger"></span></label>
                                <input type="text"  name="phone_number" id="phone_number" maxlength="10" class="form-control" placeholder="Enter phone">
                                <p></p>
                                @error('phone')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                             <input type="hidden" name="school_id" value="{{ $schoolid }}">
                            <button type="submit"  class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>

        </div>
    </section>
    <!-- /.content -->
@endsection
@section('script-section')
    <script>
    $('#studentcreate').submit(function(){
            event.preventDefault();
            var formArray = $(this).serializeArray();
           $.ajax({
             url: '{{ route("student.store") }}',
             type: 'post',
             data: formArray,
             dataType: 'json',
             success: function(response){
                if(response['status'] == true){
                    window.location.href="{{  route('student.list',['school_id' => $schoolid]) }}";
                }else{
                    var errors = response['errors'];
                    var student_licence = response['student_licence'];
                    if(student_licence == 'error'){
                        window.location.href="{{  route('student.list',['school_id' => $schoolid , 'student_licence_error' => 'error']) }}";
                    }
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

    function toggleButtonState() {
        var phoneNumber = $('#phone_number').val();
        /* var button = $('#SubmitButton');
        
        if (phoneNumber.length == 10) {
            button.prop('disabled', false);
        } else {
            button.prop('disabled', true);
        } */
        phoneNumber = phoneNumber.replace(/[^0-9]/g, '');
    phoneNumber = phoneNumber.slice(0, 10);
    $('#phone_number').val(phoneNumber);
    }
    
    // Attach input event listener to the phone number input
    $('#phone_number').on('input', function() {
        toggleButtonState();
    });

    $('#show_new_password').click(function() {
        var passwordField = $('#set_password');
        var passwordFieldType = passwordField.attr('type');
        
        if (passwordFieldType === 'password') {
            passwordField.attr('type', 'text');
        } else {
            passwordField.attr('type', 'password');
        }
    });
    $('#show_Confirm_Password').click(function() {
        var passwordField = $('#confirm_password');
        var passwordFieldType = passwordField.attr('type');
        
        if (passwordFieldType === 'password') {
            passwordField.attr('type', 'text');
        } else {
            passwordField.attr('type', 'password');
        }
    });


</script>
@endsection