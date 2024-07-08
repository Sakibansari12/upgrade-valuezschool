@extends('layout.main')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title"><!-- Add classroom --></h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <!-- <li class="breadcrumb-item"><a href="{{ route('teacher.list', ['school' => $schoolid]) }}"><i class="mdi mdi-home-outline"></i></a></li> -->
                            @if($user->usertype == 'superadmin')
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page"><a href="{{ route('school.list') }}"><i class="mdi mdi-home-outline"></i> - Manage School</a></li>
                            @endif
                            @if($user->usertype == 'superadmin')
                            <li class="breadcrumb-item alignment-text-new" aria-current="page"><a href="{{ route('teacher.list', ['school' => $schoolid]) }}"><i class="mdi mdi-home-outline"></i> - Manage Classroom</a></li>
                            @endif
                            @if($user->usertype == 'admin')
                              <li class="breadcrumb-item alignment-text-new" aria-current="page"><a href="{{ route('school.teacher.list', ['school' => $schoolid]) }}"><i class="mdi mdi-home-outline"></i> - Manage Classroom</a></li>
                            @endif
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page"> Add classroom</li>
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
                        <h4 class="box-title">Add New classroom</h4>
                    </div>
                    <!-- /.box-header -->
                    <form action="{{ route('create-teacher') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                               <div class="box-body">
                                <div class="form-group">
                                    <label for="grade">Grade <span class="text-danger">*</span></label>
                                    <select name="grade" id="grade" class="form-control">
                                        <option  value="">Select a Grade</option>
                                        <option  value="1">Grade 1</option>
                                        <option  value="2">Grade 2</option>
                                        <option  value="3">Grade 3</option>
                                        <option  value="4">Grade 4</option>
                                        <option  value="5">Grade 5</option>
                                        <option  value="6">Grade 6</option>
                                        <option  value="7">Grade 7</option>
                                        <option  value="8">Grade 8</option>
                                        <option  value="9">Grade 9</option>
                                        <option  value="10">Grade 10</option>
                                        <option  value="11">Pre School</option>
                                    </select>
                                    @error('grade')
                                            <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                

                                <div class="form-group">
                                    <label for="grade">Confirm Grade <span class="text-danger">*</span></label>
                                    <select name="confirm_grade" id="confirm_grade" class="form-control">
                                        <option  value="">Select a Grade</option>
                                        <option  value="1">Grade 1</option>
                                        <option  value="2">Grade 2</option>
                                        <option  value="3">Grade 3</option>
                                        <option  value="4">Grade 4</option>
                                        <option  value="5">Grade 5</option>
                                        <option  value="6">Grade 6</option>
                                        <option  value="7">Grade 7</option>
                                        <option  value="8">Grade 8</option>
                                        <option  value="9">Grade 9</option>
                                        <option  value="10">Grade 10</option>
                                        <option  value="11">Pre School</option>
                                    </select>
                                    @error('confirm_grade')
                                            <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                               
                                <div class="form-group">
                                    <label class="form-label">Section <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="section" name="section" value="{{ old('section') }}" Placeholder="Section" rows="2" ></textarea>
                                    @error('section')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Confirm Section <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="confirm_section" value="{{ old('confirm_section') }}" name="confirm_section" Placeholder="Confirm Section" rows="2" ></textarea>
                                    @error('confirm_section')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            <div class="form-group">
                                <label class="form-label">Teacher Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" value="{{ old('name') }}" class="form-control" placeholder="Enter Teacher Name">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- <div class="form-group">
                                <label class="form-label">Teacher Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Enter Teacher Email">
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div> -->
                            
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <input type="hidden" name="school" value="{{ $schoolid }}">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>

        </div>
    </section>
    <!-- /.content -->
@endsection
