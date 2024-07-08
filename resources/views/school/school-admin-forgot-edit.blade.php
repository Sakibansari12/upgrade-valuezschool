@extends('layout.main')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title"><!-- Manage School --></h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page">Edit School Admin</li>
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
                @if ($forgot_password_data)
                    <!-- Basic Forms -->
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title">Update School Admin</h4>
                        </div>
                        <!-- /.box-header -->
                        <form action="{{ route('school-forgot-password-update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="box-body">
                                <div class="form-group">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" readonly value="{{ $forgot_password_data->name }}" class="form-control"
                                        placeholder="Enter Name">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" readonly value="{{ $user_data->email }}" class="form-control"
                                        placeholder="Enter Email">
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" value="{{ old('password') }}" class="form-control"
                                        placeholder="Enter Password">
                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" name="confirm_password" id="confirm_password" value="{{ old('confirm_password') }}" class="form-control"
                                        placeholder="Enter Confirm Password">
                                    @error('confirm_password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <input type="hidden" name="user_id" value="{{ $user_data->id }}">
                                <input type="hidden" name="school" value="{{ $user_data->school_id }}">
                                <input type="hidden" name="pagetype" value="schooladmin">
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
