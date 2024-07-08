@extends('layout.main')
@section('content')
    @routes()
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xl-12">
                <div id="app">
                    <quiz-page />
                </div>
            </div>
        </div>
    </section>
    <style>
        .logo-mini {
            /* display: none !important; */
        }
    </style>
    <!-- /.content -->
    @if (Auth::check())
        @php
            $userData = collect(auth()->user())->only('id', 'name', 'grade', 'school_id', 'usertype');
        @endphp
        <script>
            window.auth = {!! $userData !!};
        </script>
    @else
        <script>
            window.auth = null;
        </script>
    @endif
    <script>
        var baseUrl = "{{ url('/') }}";
        var start_time = "{{ date('Y-m-d H:i:s') }}";
    </script>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
