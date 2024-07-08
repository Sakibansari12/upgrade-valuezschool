@extends('layout.main')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0">Package</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
               
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<div class="container-fluid">
    <div class="row">
        <!-- column -->
        <div class="col-sm-12">
            <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Package Information</h5>
                <div class="card-actions" style="display: flex; justify-content: flex-end;">
                  
                </div>
            </div>

                    
                <div class="card-body">
                    <form name="studentcreate" id="studentcreate" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Package :</label>
                                    {{ isset($packages_single->name_of_package) ? $packages_single->name_of_package : '' }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Duration of package :</label>
                                    {{ isset($packages_single->duration_of_package) ? $packages_single->duration_of_package : '' }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Set Pricing: </label>
                                    {{ isset($packages_single->set_pricing) ? $packages_single->set_pricing : '' }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Total Price : </label>
                                    {{ isset($packages_single->total_price) ? $packages_single->total_price : '' }}
                                </div>
                            </div>
                        </div>
                </div>
                </form>
            </div>
            <section class="content">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Discount Detail</h5>
                                        <div class="card-actions float-end">
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                        <table id="yajra-table" class="table" style="width:100%; text-align: center;">
                                                <thead style="background-color: #00205c; color: #fff;">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Deal Code</th>
                                                        <th>Discount Percentage</th>
                                                        <th>Discount Amount</th>
                                                        
                                                        
                                                    </tr>
                                                </thead>
                                                <tbody class="text-dark">
                                                @if (!empty($packages_single->package_data->package))
                                                      @foreach ($packages_single->package_data->package as $key => $data)
                                                        <tr>
                                                            <td>{{ $key + 1 }}</td>
                                                            <td>{{ $data->deal_code }}</td>
                                                            <td>{{ $data->deal_code_per }}</td>
                                                            <td>{{ $data->discount_amount  }}</td>
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
                </div>
</div>
</div>
</div>


@endsection