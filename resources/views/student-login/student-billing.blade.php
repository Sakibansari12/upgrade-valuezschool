@extends('layout.main')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Billing</h4>
                <div class="d-inline-block align-items-center">
                   <!--  <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Manage Billing</li>
                        </ol>
                    </nav> -->
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
                        <h5 class="card-title mb-0">Billing</h5>
                        <div class="card-actions float-end">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="yajra-table" class="table" style="width:100%">
                                <thead  style="background-color: #00205c; color: #fff;">
                                    <tr>
                                    <th>#</th>
                                        <th>Invoice Number</th>
                                        <th>Payment status</th>
                                        <th>Date & Time Of Payment</th>
                                        <th>Download Invoice</th>
                                    </tr>
                                </thead>
                                <tbody class="text-dark">
                                    @if(!empty($datas))
                                        @foreach ($datas as $key => $data)
                                                <tr>
                                                    <td>
                                                        {{ $key + 1 }}
                                                    </td>
                                                    <td>
                                                        {{ $data->orderid }}
                                                    </td>
                                                    <!-- <td>
                                                      {{ $data->payment_status }}
                                                    </td> -->
                                                    <td><a href="javascript:void(0);"
                                                            class="text-white badge bg-{{ $data->payment_status == 1 ? 'success' : 'danger' }}"
                                                            data-status="{{ $data->payment_status }}">{{ $data->payment_status == 1 ? 'Success' : 'Pending' }}</a>
                                                    </td>
                                                    <td>
                                                        {{ isset($data->created_at) ? \Carbon\Carbon::parse($data->created_at)->format('d/m/Y') : '' }} <strong>|</strong>
                                                        {{ isset($data->created_at) ? \Carbon\Carbon::parse($data->created_at)->format('H:i') : '' }}
                                                       
                                                    </td>
                                                    <td>
                                                    <a href="{{ route('student-pdf-download', ['student_payment_id' => $data->id]) }}" class=" waves-effect waves-light btn btn-sm btn-outline btn-info mb-5"
                                                     ><i class="fas fa-download"></i></a>
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

@endsection
