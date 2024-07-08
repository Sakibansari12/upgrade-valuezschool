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
                            <li class="breadcrumb-item alignment-text-new" aria-current="page"><a
                                    href="{{ route('quiz-list') }}"><i class="mdi mdi-home-outline"></i> - Quiz Questions</a>
                            </li>
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page">Add Quiz Question</li>
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
                        <h4 class="box-title">Add New Quiz </h4>
                    </div>
                    <!-- /.box-header -->
                    <form name="studentcreate" id="studentcreate" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body" id="package-container">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Quiz Title <span class="text-danger">*</span></label>
                                        <select class="form-control select2" name="quiztitle" id="quiztitle"
                                            style="width: 100%;">
                                            @foreach ($quiz_title_data as $quiz_title)
                                                <option value="{{ $quiz_title->id }}"
                                                    {{ $quiz_title->id == old('quiztitle') ? 'selected' : '' }}>
                                                    {{ $quiz_title->quiz_title }}</option>
                                            @endforeach
                                        </select>
                                        <p></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Question Instruction </label>
                                        <input type="text" name="question_text" id="question_text"
                                            value="{{ old('question_text') }}" class="form-control"
                                            placeholder="Question Instruction">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-6 d-none">
                                    <div class="form-group">
                                        <label class="form-label">Audio file url </label>
                                        <input type="text" name="question_audurl" id="question_audurl"
                                            value="{{ old('question_audurl') }}" class="form-control"
                                            placeholder="Audio file url">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Question Text </label>
                                        <textarea name="question_title" id="question_title" class="form-control" placeholder="Question Text">{{ old('question_title') }}</textarea>
                                        <p></p>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Question Video url </label>
                                        <input type="text" name="question_url" id="question_url"
                                            value="{{ old('question_url') }}" class="form-control"
                                            placeholder="Question Video url">
                                        <p></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Question Image</label>
                                        <input type="file" id="question_image" name="question_image" class="form-control"
                                            placeholder="Question Image">

                                        <p></p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="my-3 with-border">
                                    <h5>Please Select correct option</h5>
                                </div>
                                @for ($opt = 1; $opt <= 4; $opt++)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input name="crct_answer" class="with-gap" type="radio"
                                                id="radio_{{ $opt }}" value="{{ $opt - 1 }}"
                                                {{ $opt == 1 ? 'checked' : '' }} />
                                            <label class="form-label" for="radio_{{ $opt }}">Option
                                                {{ $opt }}</label>
                                            <input id="opt_ans_mcq_{{ $opt }}" name="mcq_input_val[]"
                                                class="form-control" value="">
                                        </div>
                                    </div>
                                @endfor
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Correct Feedback</label>
                                        <textarea name="crct_feedback" id="crct_feedback" class="form-control" placeholder="Enter Correct Feedback"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Incorrect Feedback</label>
                                        <textarea name="incrct_feedback" id="incrct_feedback" class="form-control" placeholder="Enter Incorrect Feedback"></textarea>
                                    </div>
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
    <script>
        // Form submission via AJAX
        $('#studentcreate').submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            //formData.append('image', $('#image')[0].files[0]);
            // Send AJAX request
            $.ajax({
                url: '{{ route('quiz-store') }}',
                type: 'POST',
                processData: false,
                contentType: false,
                data: formData,
                success: function(response) {
                    if (response['status'] == true) {
                        // Handle success
                        window.location.href = "{{ route('quiz-list') }}";
                    } else {
                        // Handle errors
                        var errors = response['errors'];
                        $.each(errors, function(key, value) {
                            //console.log(value[0]);
                            var elementId = key.replace(/\./g, '_');
                            //console.log(elementId);
                            $('#' + elementId).next('p').addClass('text-danger').html(value[0]);
                        });
                    }
                },
                error: function() {
                    console.log('Something went wrong');
                }
            });
        });
    </script>
@endsection
