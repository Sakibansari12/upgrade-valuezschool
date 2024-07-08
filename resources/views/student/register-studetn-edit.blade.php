@extends('layout.main')
@section('content')
{{-- <head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head> --}}
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Students</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item alignment-text-new" aria-current="page">Manage Students</li>
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page">Update Student</li>
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
                @if ($studentdata)
                    <!-- Basic Forms -->
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title">Update Student</h4>
                        </div>
                        <!-- /.box-header -->
                        <form action=""
                        name="studentUpdateForm" id="studentUpdateForm"
                        method="post" enctype="multipart/form-data">

                            @csrf
                            <div class="box-body">
                                <div class="form-group">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" value="{{ $studentdata->name}}" class="form-control"
                                        placeholder="Enter Name" >
                                        <p></p>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" value="{{ $studentdata->last_name}}" id="last_name" class="form-control" placeholder="Last Name">
                                    <p></p>
                                     @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                </div>
                                    <div class="form-group">
                                    <label for="grade" class="form-label">Grade:<span class="text-danger">*</span></label>
                                    <select name="grade" id="grade" class="form-control">
                                        <option value="">Select a Category</option>
                                                @if($programs->isNotEmpty())
                                                  @foreach($programs as $program)
                                                <option {{ ($studentdata->grade == $program->id) ? 'selected' : ''  }} value="{{ $program->id }}">{{ $program->class_name }}</option>
                                                @endforeach
                                                @endif
                                    </select>
                                    <p></p>
                                    @error('grade')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                        <div class="form-group">
                            <label for="student_status">Student Status <span class="text-danger">*</span></label>
                            <select name="student_status" id="student_status" class="form-control">
                                <option value="">Select Student Status</option>
                                <option {{ ($studentdata->student_status == 'Demo') ? 'selected' : '' }} value="demo">Demo</option>
                                <option {{ ($studentdata->student_status == 'Paid') ? 'selected' : '' }} value="paid">Paid</option>
                                <option {{ ($studentdata->student_status == 'Pending') ? 'selected' : '' }} value="pending">Pending</option>
                            </select>
                            <p></p>
                            @error('grade')
                               <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                                <div class="form-group">
                                    <label class="form-label">Email <span class="text-danger"></span></label>
                                    <input type="email" name="email" id="email" value="{{ $studentdata->email}}" class="form-control"
                                        placeholder="Enter Email" >
                                        <p></p>
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Phone <span class="text-danger"></span></label>
                                    <input type="text" name="phone_number" id="phone_number" value="{{ $studentdata->phone_number}}" class="form-control"
                                        placeholder="Enter Phone">

                                        <p></p>
                                    @error('phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- <div class="form-group">
                                    <label class="form-label">Password <span class="text-danger"></span></label>
                                    <input type="text" name="password" id="password" value="{{ $studentdata->password}}" class="form-control"
                                        placeholder="Password">

                                        <p></p>
                                    @error('phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Confirm Password <span class="text-danger"></span></label>
                                    <input type="text" name="confirm_password" id="confirm_password" value="{{ $studentdata->confirm_password}}" class="form-control"
                                        placeholder="Confirm Password">

                                        <p></p>
                                    @error('phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div> -->
                                <div class="form-group" id="password_hide">
                                        <label class="form-label">Password <span class="text-danger"></span></label>
                                        <div class="controls">
                                            <div class="input-group" id="passwordsibling">
                                                <input type="password" id="password" name="password" class="form-control" placeholder="Password">
                                                
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="showPasswordToggle"><i class="fa fa-eye"></i></span>
                                                </div>
                                            </div>
                                            <p></p>
                                        </div>
                                        @if ($errors->has('password'))
                                            <div class="form-control-feedback">
                                                <small class="text-danger">{{ $errors->first('password') }}</small>
                                            </div>
                                        @endif
                                    </div>
                                
                                <!-- <div class="form-group" id="confirm_password_hide">
                                        <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                        <div class="controls" id="confirm_password_dontmatch_error">
                                            <div class="input-group" id="confirmpasswordsibling">
                                            <input type="hidden" id="mobile" name="mobile">
                                                <input type="password" id="confirm_password" name="confirm_password" value="{{ $studentdata->confirm_password}}" class="form-control" placeholder="Confirm Password">
                                                
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="showConfirmPasswordToggle"><i class="fa fa-eye" aria-hidden="true"></i>
</span>
                                                </div>
                                            </div>
                                            <p></p>
                                        </div>
                                        <p></p>
                                        @if ($errors->has('password'))
                                            <div class="form-control-feedback">
                                                <small class="text-danger">{{ $errors->first('password') }}</small>
                                            </div>
                                        @endif
                                    </div>
 -->
                            </div>

                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <input type="hidden" name="id" value="{{ $studentdata->id }}">
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.box -->
                @else
                    <h1>Something went wrong.</h1>
                @endif
            </div>

        </div>
    </section>
    <!-- /.content -->
@endsection
<!-- {{ route('student.update' , ['userid' => $studentdata->id] )}} -->
@section('script-section')
    <script>
    $('#studentUpdateForm').submit(function(){
            event.preventDefault();
            var formArray = $(this).serializeArray();
           $.ajax({
             url: '{{ route("register-student.update",['student_id' => $studentdata->id]) }}',
             type: 'put',
             data: formArray,
             dataType: 'json',
             success: function(response){
                if(response['status'] == true){
                    //window.location.href="{{  route('student.list',['school_id' => $studentdata->school_id]) }}";
                    window.location.href="{{  route('register-list') }}";
                }else{
                    var errors = response['errors'];
                    var phone_number_exists = response['phone_number_exists'];
                    var conform_password = response['conform_password'];
                    /* if(conform_password){
                        $('#confirm_password_dontmatch_error').siblings('p').removeClass('text-danger').html('');
                        $('#confirmpasswordsibling').siblings('p').removeClass('text-danger').html('');
                        $('#confirm_password_dontmatch_error').siblings('p').addClass('text-danger').html(conform_password);
                     }else{
                        $('#confirm_password_dontmatch_error').siblings('p').removeClass('text-danger').html('');
                     }
 */
                     if(errors['password']){
                        $('#passwordsibling').siblings('p').addClass('text-danger').html(errors['password']);
                     }else{
                        $('#passwordsibling').siblings('p').removeClass('text-danger').html('');
                     }
                    $('#phone_number').siblings('p').addClass('text-danger').html(phone_number_exists);
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
    var button = $('#getotpButton');
    // Remove non-numeric characters and limit to 10 digits
    phoneNumber = phoneNumber.replace(/[^0-9]/g, '').slice(0, 10);
    // Check if the phone number is exactly 10 digits and does not start with '0'
     if(phoneNumber[0] == '0'){
        $('#phone_number').siblings('p').addClass('text-danger').html("Phone number can't start with 0.");
     }else{
        $('#phone_number').siblings('p').addClass('text-danger').html("");
     }
    if (phoneNumber.length === 10 && phoneNumber[0] !== '0') {
        button.prop('disabled', false);
    } else {
        button.prop('disabled', true);
    }
    $('#phone_number').val(phoneNumber);
}
$('#phone_number').on('input', function() {
    toggleButtonState();
});


        $(document).ready(function() {
    $('#showPasswordToggle').click(function() {
        var passwordField = $('#password');
        var passwordFieldType = passwordField.attr('type');
        
        if (passwordFieldType === 'password') {
            passwordField.attr('type', 'text');
        } else {
            passwordField.attr('type', 'password');
        }
    });
    $('#showConfirmPasswordToggle').click(function() {
        var passwordField = $('#confirm_password');
        var passwordFieldType = passwordField.attr('type');
        
        if (passwordFieldType === 'password') {
            passwordField.attr('type', 'text');
        } else {
            passwordField.attr('type', 'password');
        }
    });
});       
</script>
@endsection