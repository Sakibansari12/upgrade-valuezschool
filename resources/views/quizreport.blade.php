@extends('layout.main')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Quiz Report </h4>
            </div>
        </div>
    </div>
    {{-- @dump($quiz_report); --}}
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
                                        <th>Total Attempt</th>
                                        <th>Right Answer</th>
                                        <th>Wrong Answer</th>
                                        <th>Start Date & Time</th>
                                        <th>End Date & Time</th>
                                    </tr>
                                </thead>
                                <tbody class="text-dark">
                                    @if ($quiz_report->isNotEmpty())
                                        @foreach ($quiz_report as $key => $data)
                                            <tr>
                                                <td>
                                                    {{ $key + 1 }}
                                                </td>
                                                <td>
                                                    {{ $data->total_attempt }}
                                                </td>
                                                <td>
                                                    {{ $data->right_attempt }}
                                                </td>
                                                <td>
                                                    {{ $data->wrng_attempt }}
                                                </td>
                                                <td>
                                                    {{ isset($data->start_time) ? \Carbon\Carbon::parse($data->start_time)->format('d/m/Y H:i:s') : '' }}
                                                </td>
                                                <td>
                                                    {{ isset($data->end_time) ? \Carbon\Carbon::parse($data->end_time)->format('d/m/Y H:i:s') : '' }}

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
    <script type="text/javascript">
        $(function() {

            var table = $('#yajra-table').DataTable({
                order: [
                    [0, 'asc']
                ],
            });

        });
    </script>
@endsection
