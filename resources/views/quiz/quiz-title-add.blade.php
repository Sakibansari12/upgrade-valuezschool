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
                                        <input type="text" name="quiz_title" id="quiz_title" value="{{ old('quiz_title') }}" class="form-control" placeholder="Quiz Title">
                                        <p></p>
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
                                                            {{ $quiz_category->id == old('quiz_category_id') ? 'selected' : '' }}>
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
                                <select class="form-control select2" name="class_id[]" style="width: 100%;"
                                    multiple="multiple" id="class_id">
                                    @foreach ($program_list as $prog)
                                        @php $classIds = old('class_id'); @endphp
                                        <option value="{{ $prog->id }}"
                                            {{ (!empty($classIds) && in_array($prog->id, $classIds)) ? 'selected' : '' }}>
                                            {{ $prog->class_name }}</option>
                                    @endforeach
                                </select>
                                
                                @error('class_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="image" class="form-label">Quiz Title Image <span class="text-danger">*</span></label>
                                        <input class="form-control" type="file" name="image" id="image" accept=".jpg, .png .webp">
                                        <p></p>
                                        <span  id="file-size-limit">(Max file size: 250 KB)</span>
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
        url: '{{ route("quiz-title-store") }}',
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
