@extends('layout.main')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title"><!-- Users --></h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <!-- <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item alignment-text-new" aria-current="page">Manage Users</li> -->
                            <li class="breadcrumb-item alignment-text-new" aria-current="page"><a href="{{ route('users.admin.list') }}"><i class="mdi mdi-home-outline"></i> - Content Admin</a></li>
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page">Edit Content Admin</li>
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
                @if ($adminuser)
                    <!-- Basic Forms -->
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title">Update Content Admin</h4>
                        </div>
                        <!-- /.box-header -->
                        <form action="{{ route('users.admin.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="box-body">
                                <div class="form-group">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{ $adminuser->name }}" class="form-control"
                                        placeholder="Enter Name">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" value="{{ $adminuser->email }}"
                                        class="form-control" placeholder="Enter Email">
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Usertype</label>
                                    <select class="form-control" name="usertype" style="width: 100%;">
                                        <option value="contentadmin"
                                            {{ $adminuser->usertype == 'contentadmin' ? 'selected' : '' }}>Content Admin
                                        </option>
                                        {{-- <option value="superadmin"
                                            {{ $adminuser->usertype == 'superadmin' ? 'selected' : '' }}>Super Admin
                                        </option> --}}
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Password</label>
                                    <input type="text" name="password" value="" class="form-control"
                                        placeholder="Enter Password">
                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <hr>
                                <div class="form-group">
                                    <label class="form-label">Status</label>
                                    <div class="c-inputs-stacked">
                                        <input name="status" type="radio" id="active" value="1"
                                            {{ $adminuser->status == 1 ? 'checked' : '' }}>
                                        <label for="active" class="me-30">Active</label>
                                        <input name="status" type="radio" id="inactive" value="0"
                                            {{ $adminuser->status == 0 ? 'checked' : '' }}>
                                        <label for="inactive" class="me-30">Inactive</label>
                                    </div>
                                </div>

                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <input type="hidden" name="id" value="{{ $adminuser->id }}">                                
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
