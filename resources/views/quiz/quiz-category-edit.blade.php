@extends('layout.main')
@section('content')
<head>
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> --}}
</head>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title"><!-- Package --></h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item alignment-text-new" aria-current="page"><a href="{{ route('quiz-category-list') }}"><i class="mdi mdi-home-outline"></i> - Quiz Category</a></li>
                        <li class="breadcrumb-item active alignment-text-new" aria-current="page">Edit Quiz Category</li>
                    </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-lg-12 col-12">
                <!-- Basic Forms -->
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Update Quiz Category</h4>
                    </div>
                    <!-- /.box-header -->
                    <form name="studentcreate" id="studentcreate" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body" id="package-container">
                            <!-- Initial row -->

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Quiz Category <span class="text-danger">*</span></label>
                                        <input type="text" name="title" id="title" value="{{ isset($quiz_category_data->title) ? $quiz_category_data->title : ''}}" class="form-control" placeholder="Quiz Category">
                                        <p></p>
                                        <input type="hidden" name="id" id="quiz_id" value="{{ isset($quiz_category_data->id) ? $quiz_category_data->id : ''}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="image" class="form-label">Quiz Category Image <span class="text-danger">*</span></label>
                                        <input class="form-control" type="file" name="image" id="image" accept=".jpg, .png .webp">
                                        <p></p>
                                        <input type="hidden" id="update_quiz_image" name="update_quiz_image" value="{{ $quiz_category_data->quiz_categories_image ? $quiz_category_data->quiz_categories_image : '' }}">
                                        <img id="imagePreview" src="{{ url('uploads/quiz') }}/{{ $quiz_category_data->quiz_categories_image ? $quiz_category_data->quiz_categories_image : '' }}"
                                            width="100px">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
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
  


// Form submission via AJAX
$('#studentcreate').submit(function (event) {
    event.preventDefault();
    var formData = new FormData(this);
    formData.append('image', $('#image')[0].files[0]);
    $.ajax({
        url: '{{ route("quiz-category-update") }}',
        type: 'POST',
        processData: false,
        contentType: false,
        data: formData,
        success: function (response) {
            if (response['status'] == true) {
                // Handle success
                window.location.href="{{  route('quiz-category-list') }}";
            } else {
                // Handle errors
                var errors = response['errors'];
                $.each(errors, function (key, value) {
                    var elementId = key.replace(/\./g, '_');
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
<!-- Your other scripts go here -->

@endsection
