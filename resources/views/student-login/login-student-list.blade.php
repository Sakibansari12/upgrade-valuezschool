@extends('layout.main')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title"><!-- School Student --></h4>
                <div class="d-inline-block align-items-center">
                <nav>
                <ol class="breadcrumb">
                             <li class="breadcrumb-item active alignment-text-new" aria-current="page"><a href="{{ route('all.school.student.list') }}"><i class="mdi mdi-home-outline"></i> - All Student List</a></li>
                          
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page">Student Logs</li>
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
                
                </div>
                    <div class="card-body">
                        <div class="table-responsive tabcontent" id="user_activity_table">
                            <table id="yajra-table" class="table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>IP Address</th>
                                        <th>Device Info</th>
                                        <th>Action</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody class="text-dark">
                                
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
<script>



</script>
    <script type="text/javascript">
        $(function() {

            var table = $('#yajra-table').DataTable({
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: ['excel', 'pdf'],
                order: [[5, 'desc']],
                ajax: "{{ route('student.logs.list', ['userid' => $userId]) }}",
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'ip_address',
                        name: 'ip_address'
                    },
                    // {
                    //     data: 'session_id',
                    //     name: 'session_id'
                    // },
                    {
                        data: 'logs_info',
                        name: 'logs_info'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                    {
                        data: 'created_at',
                        name: 'logs.created_at',
                        visible: true,
                        searchable: true,
                    },
                    {
                        data: 'id',
                        name: 'logs.id',
                        visible: false,
                        searchable: true,
                    },

                ],
                initComplete: function() {
                    this.api().column(3).every(function() {
                        var column = this;
                        var select = $(
                                '<select class="form-select"><option value="">All</option></select>'
                            )
                            .appendTo($('#login-filter').empty())
                            .on('change', function() {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
                                column.search(this.value).draw();
                            });
                        select.append('<option value="login">Login</option>');
                        select.append('<option value="logout">Logout</option>');

                    });
                }
            });

        });
    </script>
@endsection
