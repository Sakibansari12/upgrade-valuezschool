@extends('layout.main')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title"></h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <!-- <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li> -->
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page">What's New</li>
                        </ol>
                    </nav>
                </div>
            </div>

        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">What's New</h5>
                        <div class="card-actions float-end">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="yajra-table" class="table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Subject</th>
                                        <th>Description</th>
                                        <th>Date & Time</th>
                                    </tr>
                                </thead>
                                <tbody class="text-dark">
                                    @if($datas->isNotEmpty())
                                        @foreach ($datas as $key => $data)
                                                <tr onclick="window.location='{{ route('notify.detail', ['id' => $data->id, 'teacher_noty' => $data->teacher_noty]) }}';" 
                                                style="cursor: pointer; background-color: {{ $data->teacher_noty == 'teacher' ? '#f2f2f2' : 'transparent' }};">
                                                    <td>
                                                    @if($data->teacher_noty == 'teacher')
                                                            <b>{{ $key + 1 }}</b>
                                                        @else
                                                            {{ $key + 1 }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($data->teacher_noty == 'teacher')
                                                            <b>{{ $data->title }}</b>
                                                        @else
                                                            {{ $data->title }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($data->teacher_noty == 'teacher')
                                                            <b>{{ substr(strip_tags($data->description), 0, 10) }}...</b>
                                                        @else
                                                            {{ substr(strip_tags($data->description), 0, 10) }}...
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($data->teacher_noty == 'teacher')
                                                             <b>
                                                                {{ isset($data->created_at) ? \Carbon\Carbon::parse($data->created_at)->format('d/m/Y') : '' }} <strong>|</strong>
                                                                {{ isset($data->created_at) ? \Carbon\Carbon::parse($data->created_at)->format('H:i') : '' }} 
                                                            </b>
                                                        @else
                                                        {{ isset($data->created_at) ? \Carbon\Carbon::parse($data->created_at)->format('d/m/Y') : '' }} <strong>|</strong>
                                                        {{ isset($data->created_at) ? \Carbon\Carbon::parse($data->created_at)->format('H:i') : '' }}
                                                        @endif
                                                    </td>
                                                </tr>
                                        @endforeach
                                    @endif 
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <!-- /.content -->
@endsection
@section('script-section')
<!--     <script type="text/javascript">
        $(function() {

            var table = $('#yajra-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('notify.schoolview') }}",
                order: [[0, 'desc']],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'id'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    }
                ]
            });

        });
    </script> -->
@endsection
