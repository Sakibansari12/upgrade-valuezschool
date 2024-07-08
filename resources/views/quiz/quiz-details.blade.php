@extends('layout.main')
@section('content')
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">
            Quiz Details
          </h5>
        </div>
        <div class="modal-body">
                 <p>{{ isset($quiz_datas->title) ? $quiz_datas->title : '' }}</p>
              <div class="row">

              @if($quiz_datas->quiz_json_data)
                    @foreach ($quiz_datas->quiz_json_data as $key =>  $quiz_question)
                    <h3 class="question-text"> <strong>{{ $key + 1 }} : </strong> <strong>{{ $quiz_question->question_text }}</strong> </h3>


                    @foreach ($quiz_question->answer as $quiz_answer)
                      <div class="col-md-6">
                        <div class="card card-default" style="height: 50px; background-color: {{ $quiz_answer->answer_checkbox == 'unchecked' ? '#00205c' : ($quiz_answer->answer_checkbox == 'checked' ? 'green' : '') }}; color: #fff;">
                            <div class="quiz-details-page">
                               <input type="checkbox" {{ isset($quiz_answer->answer_checkbox) ? $quiz_answer->answer_checkbox : ''}}> 
                                <span class="quiz-answer-text">{{ isset($quiz_answer->answer_text) ? $quiz_answer->answer_text : '' }}</span>
                            </div>
                        </div>
                      </div>
                      @endforeach
                      <hr>
                    @endforeach
                @endif 
              </div>
              
        </div>
      </div>
    </div>
  </div>
</section>
<!-- /.content -->
<!-- Popup -->






@endsection
@section('script-section')

<script>

</script>
@endsection