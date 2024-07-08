@extends('layout.main')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title"><!-- School User --></h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <!-- <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li> -->
                            @if ($user->usertype == 'superadmin')
                                <li class="breadcrumb-item active alignment-text-new" aria-current="page"><a
                                        href="{{ route('school.list') }}"><i class="mdi mdi-home-outline"></i> - Manage
                                        School</a></li>
                            @endif
                            <li class="breadcrumb-item alignment-text-new" aria-current="page"><a
                                    href="{{ route('teacher.list', ['school' => $schoolId]) }}"><i
                                        class="mdi mdi-home-outline"></i> - Manage Classroom</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Classroom Logs</li>
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
                        <div class="row align-items-center ">
                            <div class="col-md-12 ">
                                <h5 class="card-title mb-0">Classroom Logs</h5>
                            </div>
                            <div class="col-md-12 mt-4">
                                <button class="btn btn-block btn-outline mb-1"
                                    onclick="openCity(event, 'user_activity_table')" id="userActivityButton">
                                    User Activity
                                </button>

                                <button class="btn btn-block btn-outline mb-1"
                                    onclick="openCity(event, 'module_compeletion_table')"
                                    id="moduleCompeletionButton">Module Completion
                                    Report</button>
                                    <button class="btn btn-block  btn-danger mb-1" id="logoutOtherDevice">Logout the
                                        classroom</button>

                            </div>
                        </div>

                        <div class="card-actions float-end mt-4">
                            <div id="login-filter" class="px-3"></div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive tabcontent" id="user_activity_table">
                            <table id="yajra-table" class="table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>IP Address</th>
                                        {{-- <th>Session</th> --}}
                                        <th>Device Info</th>
                                        <th>Action</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody class="text-dark">

                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive tabcontent" id="module_compeletion_table">
                            <table id="yajra-table" class="table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Grade</th>
                                        <th>Instructional Module</th>
                                        <th>Date & Time of completion</th>
                                        {{-- <th>Datetime</th> --}}
                                    </tr>
                                </thead>
                                <tbody class="text-dark">
                                    @if (!empty($userLogs['teacherReportArr']))
                                        @foreach ($userLogs['teacherReportArr'] as $key => $rdata)
                                            <tr role="row" class="odd">
                                                <td>{{ $key + 1 }}</td>
                                                <td>
                                                    <strong>
                                                        {{ isset($rdata->reports_created_date) ? $rdata->reports_created_date : '' }}
                                                    </strong>
                                                </td>
                                                <td>
                                                    {{ isset($rdata->grade_class_name) ? $rdata->grade_class_name : '' }}
                                                </td>
                                                <td>
                                                    {{ isset($rdata->lesson_plan_title) ? $rdata->lesson_plan_title : '' }}
                                                </td>
                                                <td>
                                                    <!-- <span class="badge badge-pill badge-primary">
                                                                    {{ $rdata->duration_time->h }} hours
                                                                </span>
                                                                <span class="badge badge-pill badge-primary">
                                                                    {{ $rdata->duration_time->i }} hours
                                                                </span>
                                                                <span class="badge badge-pill badge-primary">
                                                                    {{ $rdata->duration_time->s }} hours
                                                                </span> -->
                                                    {{ isset($rdata->created_at) ? \Carbon\Carbon::parse($rdata->created_at)->format('d/m/Y') : '' }}
                                                    &nbsp;&nbsp;<strong>|</strong>&nbsp;&nbsp;
                                                    {{ isset($rdata->created_at) ? \Carbon\Carbon::parse($rdata->created_at)->format('H:i') : '' }}

                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr class="odd">
                                            <td valign="top" colspan="8" class="dataTables_empty text-center">No data
                                                available in table</td>
                                        </tr>
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
    <script>
        /* tabs js */
        var user_activity_table = $("#module_compeletion_table");
        user_activity_table.toggle();
        // $('#userActivityButton').removeAttr('style');
        //  $('#userActivityButton').removeClass('active');
        $('#userActivityButton').css({
            'background-color': '#00205c',
            'color': '#fff'
        });

        function openCity(evt, cityName) {
            // console.log(evt,"evt");
            console.log(cityName, "cityName");
            if (cityName == 'user_activity_table') {
                var module_compeletion_table = $("#module_compeletion_table");
                module_compeletion_table.toggle();
                $('#userActivityButton').css({
                    'background-color': '#00205c',
                    'color': '#fff'
                });
                $('#moduleCompeletionButton').removeAttr('style');
                $('#moduleCompeletionButton').removeClass('active');
            }
            if (cityName == 'module_compeletion_table') {
                var user_activity_table = $("#user_activity_table");
                user_activity_table.toggle();
                $('#userActivityButton').removeAttr('style');
                $('#moduleCompeletionButton').css({
                    'background-color': '#00205c',
                    'color': '#fff'
                });
                $('#userActivityButton').removeClass('active');

            }
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
        }

        $(document).on('click', '#logoutOtherDevice', function() {
            var remId = $("#remSchool").val();
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#fec801",
                confirmButtonText: "Yes",
                cancelButtonText: "Cancel",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function(isConfirm) {
                if (isConfirm) {
                    console.log(isConfirm, "isConfirm");
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('logout.user.device', ['userid' => $userId]) }}",
                        data: {
                            userid: $("#logoutUser").val(),
                        },
                        success: function(response) {
                            if (response == 'removed') {
                                swal("Completed!",
                                    "All session destroy successfully.",
                                    "success");
                                setTimeout(() => {
                                    location.reload();
                                }, 600);
                            } else {
                                swal("Alert!", response, "info");
                            }
                        }
                    });

                } else {
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                    //  console.log(isConfirm,"isConfirm");
                }
            });
        });
    </script>
    <script type="text/javascript">
        $(function() {

            var table = $('#yajra-table').DataTable({
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: ['excel', 'pdf'],
                order: [[5, 'desc']],
                ajax: "{{ route('user.logs.list', ['userid' => $userId]) }}",
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
