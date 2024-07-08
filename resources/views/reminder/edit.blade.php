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
                            <li class="breadcrumb-item alignment-text-new" aria-current="page"><a href="{{ route('terms-privacy.list') }}"><i class="mdi mdi-home-outline"></i> - Reminder</a></li>
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page">Update Reminder</li>
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
                        <h4 class="box-title">Update Reminder</h4>
                    </div>
                    <!-- /.box-header -->
                    <form id="ReminderUpdate" name="ReminderUpdate" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="form-group">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" value="{{ $data->title }}" id="title" class="form-control" placeholder="Enter Title">
                                <p></p>
                            </div>
                            <input type="hidden" name="reminder_id" id="reminder_id" value="{{ $data->id }}">

                            <div class="form-group">
                                <label class="form-label">Description<!-- <span class="text-danger">*</span> --></label>
                                <textarea name="description" id="description" class="form-control"  placeholder="Enter Description">{{ $data->description }}</textarea>
                                <p></p>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-success">Update</button>
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
        $('#description').wysihtml5();
    </script>
    <script>
$('#ReminderUpdate').submit(function (event) {
    event.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        url: '{{ route("reminder.update") }}',
        type: 'POST',
        processData: false,
        contentType: false,
        data: formData,
        success: function (response) {
            if (response['status'] == true) {
                window.location.href="{{  route('reminder.list') }}";
            } else {
                var errors = response['errors'];
                $.each(errors, function (key, value) {
                    console.log(value[0],"value[0]");
                    var elementId = key.replace(/\./g, '_');
                    console.log(elementId,"key");
                    //$('#' + key).siblings('p').addClass('text-danger').html(value[0]);
                    $('#' + elementId).next('p').addClass('text-danger').html(value[0]);
                });
            }
        },
        error: function () {
            console.log('Something went wrong');
        }
    });
});
</script>
@endsection    