@extends('layout.main')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title"><!-- Classroom --></h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <!-- <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item alignment-text-new" aria-current="page">Manage School</li>
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page">Update Classroom</li> -->

                            @if($user_auth->usertype == 'superadmin')
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page"><a href="{{ route('school.list') }}"><i class="mdi mdi-home-outline"></i> - Manage School</a></li>
                            @endif
                            @if($user_auth->usertype == 'superadmin')
                            <li class="breadcrumb-item alignment-text-new" aria-current="page"><a href="{{ route('teacher.list', ['school' => $user->school_id]) }}"><i class="mdi mdi-home-outline"></i> - Manage Classroom</a></li>
                            @endif
                            @if($user_auth->usertype == 'admin')
                            <li class="breadcrumb-item alignment-text-new" aria-current="page"><a href="{{ route('school.teacher.list', ['school' => $user->school_id]) }}"><i class="mdi mdi-home-outline"></i> - Manage Classroom</a></li>
                            @endif
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page"> Edit classroom</li>


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
                @if ($user)
                    <!-- Basic Forms -->
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title">Update Classroom</h4>
                        </div>
                        <!-- /.box-header -->
                        <form action="{{ route('teacher-update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="box-body">
                            @if($user_auth->usertype != 'admin')
                            <div class="form-group">
                                    <label for="grade">Grade <span class="text-danger">*</span></label>
                                    <select name="grade"  id="grade" class="form-control" disabled>
                                        <option  value="">Select a Grade</option>
                                        <option {{ ($user->grade == 1) ? 'selected' : ''  }} value="1">Grade 1</option>
                                        <option {{ ($user->grade == 2) ? 'selected' : ''  }} value="2">Grade 2</option>
                                        <option {{ ($user->grade == 3) ? 'selected' : ''  }} value="3">Grade 3</option>
                                        <option {{ ($user->grade == 4) ? 'selected' : ''  }} value="4">Grade 4</option>
                                        <option {{ ($user->grade == 5) ? 'selected' : ''  }} value="5">Grade 5</option>
                                        <option {{ ($user->grade == 6) ? 'selected' : ''  }} value="6">Grade 6</option>
                                        <option {{ ($user->grade == 7) ? 'selected' : ''  }} value="7">Grade 7</option>
                                        <option {{ ($user->grade == 8) ? 'selected' : ''  }} value="8">Grade 8</option>
                                        <option {{ ($user->grade == 9) ? 'selected' : ''  }} value="9">Grade 9</option>
                                        <option {{ ($user->grade == 10) ? 'selected' : ''  }} value="10">Grade 10</option>
                                        <option {{ ($user->grade == 11) ? 'selected' : ''  }} value="11">Pre School</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Section <span class="text-danger">*</span></label>
                                    <textarea class="form-control" readonly id="section" name="section" Placeholder="Section" rows="2" >{{ $user->section }}</textarea>
                                    @error('section')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                @endif
                                <div class="form-group">
                                    <label class="form-label">Teacher Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{ $user->name }}" class="form-control"
                                        placeholder="Enter Teacher Name">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" value="" class="form-control"
                                        placeholder="Enter Password">
                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" name="confirm_password" id="confirm_password" value="" class="form-control"
                                        placeholder="Enter Password">
                                    @error('confirm_password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <input type="hidden" name="id" value="{{ $user->id }}">
                                <input type="hidden" name="school" value="{{ $user->school_id }}">
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
