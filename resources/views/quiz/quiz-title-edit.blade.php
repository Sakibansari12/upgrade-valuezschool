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
                        <li class="breadcrumb-item alignment-text-new" aria-current="page"><a href="{{ route('quiz-title-list') }}"><i class="mdi mdi-home-outline"></i> - Quiz Title</a></li>
                        <li class="breadcrumb-item active alignment-text-new" aria-current="page">Add Quiz Title</li>
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
                        <h4 class="box-title">Add New Quiz Title </h4>
                    </div>
                    <!-- /.box-header -->
                    <form name="quiztitlecreate" id="quiztitlecreate" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body" id="package-container">
                            <!-- Initial row -->

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Quiz Title <span class="text-danger">*</span></label>
                                        <input type="text" name="quiz_title" id="quiz_title" value="{{ isset($quiz_title_data->quiz_title) ? $quiz_title_data->quiz_title : ''}}" class="form-control" placeholder="Quiz Title">
                                        <p></p>
                                        <input type="hidden" name="quiz_title_id" id="quiz_title_id" value="{{ isset($quiz_title_data->id) ? $quiz_title_data->id : ''}}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Quiz Category <span class="text-danger">*</span></label>
                                                <select class="form-control select2" name="quiz_category" id="quiz_category" style="width: 100%;">
                                                    @foreach ($quiz_category_datas as $quiz_category)
                                                        
                                                            <option value="{{ $quiz_category->id }}"
                                                            {{ $quiz_category->id == $quiz_title_data->quiz_category_id ? 'selected' : '' }}>
                                                            {{ $quiz_category->title }}</option>

                                                    @endforeach
                                                </select>
                                            <p></p>
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="row">
                            <div class="col-md-6">
                        <div class="form-group">
                                <label class="form-label">Grade <span class="text-danger">*</span></label>
                                <select class="form-control select21" name="class_id[]" style="width: 100%;"
                                    multiple="multiple" id="class_id">
                                    @foreach ($program_list as $prog)
                                        @php $classIds = explode(",",$quiz_title_data->quiz_title_grade); @endphp
                                        <option value="{{ $prog->id }}"
                                            {{ in_array($prog->id, $classIds) ? 'selected' : '' }}>
                                            {{ $prog->class_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="image" class="form-label">Quiz Title Image <span class="text-danger">*</span></label>
                                        <input class="form-control" type="file" name="image" id="image" accept=".jpg, .png .webp">
                                        <p></p>
                                        <input type="hidden" id="update_quiz_title_image" name="update_quiz_title_image" value="{{ $quiz_title_data->quiz_title_image ? $quiz_title_data->quiz_title_image : '' }}">
                                        <img id="imagePreview" src="{{ url('uploads/quiz') }}/{{ $quiz_title_data->quiz_title_image ? $quiz_title_data->quiz_title_image : '' }}"
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
  
  $('#class_id').select2({
            tags: true,
            placeholder: "Select a Grade",
        });

// Form submission via AJAX
$('#quiztitlecreate').submit(function (event) {
    event.preventDefault();
    var formData = new FormData(this);
    formData.append('image', $('#image')[0].files[0]);
    $.ajax({
        url: '{{ route("quiz-title-update") }}',
        type: 'POST',
        processData: false,
        contentType: false,
        data: formData,
        success: function (response) {
            if (response['status'] == true) {
                // Handle success
                window.location.href="{{  route('quiz-title-list') }}";
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
