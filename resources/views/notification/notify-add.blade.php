@extends('layout.main')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title"><!-- What's New --></h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <!-- <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li> -->
                            <li class="breadcrumb-item alignment-text-new" aria-current="page"><a href="{{ route('notify.list') }}"><i class="mdi mdi-home-outline"></i> - What's New</a></li>
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page">Add What's New</li>
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
                        <h4 class="box-title">Add New What's New</h4>
                    </div>
                    <!-- /.box-header -->
                    <form action="{{ route('notify.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="form-group">
                                <label for="whatnew_type"> What's New Type <span class="text-danger">*</span></label>
                                <select name="whatnew_type" id="whatnew_type" class="form-control">
                                    <option value="">Select Type</option>
                                    <option value="Classroom">Classroom</option>
                                    <option value="Student">Student</option>
                                    <option value="Both">Both</option>
                                </select>
                                <p></p>
                                @error('grade')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            </div>


                            <div class="form-group">
                                <label class="form-label">Subject <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" placeholder="Enter What's New Subject">
                                @error('title')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Description<span class="text-danger">*</span></label>
                                <textarea name="description_txt" id="description_txt" class="form-control"  placeholder="Enter What's New Description"></textarea>
                                <!-- <input type="text" name="description_txt" class="form-control" placeholder="Enter What's New Description"> -->
                                @error('description_txt')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        
                            
                            <hr>
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <div class="c-inputs-stacked">
                                    <input name="status" type="radio" id="active" value="1" checked>
                                    <label for="active" class="me-30">Active</label>
                                    <input name="status" type="radio" id="inactive" value="0">
                                    <label for="inactive" class="me-30">Inactive</label>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
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
@section('script-section')
    <script src="{{ asset('assets/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.js') }}"></script>
    <script>
        $('#description_txt').wysihtml5();
    </script>

</script>
@endsection    