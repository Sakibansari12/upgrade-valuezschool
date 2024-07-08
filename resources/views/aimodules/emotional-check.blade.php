
@extends('layout.main')
@section('content')

    <div class="spacer">
    </div>
    {{-- <div class="column1">
        <button class="question-button" id="button1">1</button>
        <button class="question-button" id="button2">2</button>
        <button class="question-button" id="button3">3</button>
        <button class="question-button" id="button4">4</button>
        <button class="question-button" id="button5">5</button>

    </div> --}}
    <div class="column1">
        <ul class="no-numbers-list">
            <li><button class="question-button" id="button1">1</button></li>
            <li><button class="question-button" id="button1">2</button></li>
            <li><button class="question-button" id="button1">3</button></li>
            <li><button class="question-button" id="button1">4</button></li>
            <li><button class="question-button" id="button1">5</button></li>
        </ul>
    </div>
    <div class="column">

        <div class="column2main">
        <div class="column2">
            <div class="col2-container">
                <div class="col2-heading">
                    <div class="col2-sub1">
                        <button class="content" id="content"></button>
                    </div>
                    <div class="col2-sub2">
                        <div class="questions">
                            <h3 class="q1">How Are You Feel Today?</h3>
                            <h3 class="q2">How Is Your Energy Today?</h3>
                            <h3 class="q3">How Are You?</h3>
                            <h3 class="q4">question4</h3>
                            <h3 class="q5">question5</h3>
                        </div>
                    </div>


                </div>
            </div>
            <div class="col2body">
                <button class="btn" data-value="A">&#128540;</button>
                <button class="btn" data-value="B">&#128529;</button>
                <button class="btn" data-value="C">&#128528;</button>
                <button class="btn" data-value="D">&#128525;</button>
                <button class="btn" data-value="E">&#128519;</button>
            </div>
        </div>
        <div class="col-footer">
            <div class="firstcol">
                <button class="prev" id="prevButton" disabled>
                    <span class="glyphicon glyphicon-chevron-left"></span>
                </button>
            </div>
            <div class="secondcol">
                <button class="next" id="nextButton">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                </button>
            </div>
        </div>

            <div class="submitcol">
                <button class="submit" id="submitButton" style="display: none;">Submit</button>
            </div>
        </div>
            </div>
            <button id="apiButton" style="display: none;">Call API</button>
            <p id="apiOutput" style="display: none;"></p>

    </div>
    @endsection
@section('script-section')
    <script src="{{ asset('assets/src/js/feedback.js') }}"></script>


    @endsection
