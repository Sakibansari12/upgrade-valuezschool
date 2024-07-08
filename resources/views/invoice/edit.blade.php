@extends('layout.main')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title"><!-- What's New --></h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <!-- <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li> -->
                            <li class="breadcrumb-item alignment-text-new" aria-current="page"><a href="{{ route('invoice.list') }}"><i class="mdi mdi-home-outline"></i> - Invoices</a></li>
                            <li class="breadcrumb-item active alignment-text-new" aria-current="page">Edit Invoice</li>
                        </ol>
                    </nav>
                </div>
            </div>

        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-lg-9 col-12">
                <!-- Basic Forms -->
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Update Invoice</h4>
                    </div>
                    <!-- /.box-header -->
                    <form id="InvoiceUpdate" name="InvoiceUpdate" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Invoice  <span class="text-danger">*</span></label>
                                        <input type="text" name="invoice_number" value="{{ $data->invoice_number }}" id="invoice_number" class="form-control" placeholder="Enter Invoice Number">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Invoice Date <span class="text-danger">*</span></label>
                                        <input type="date" name="invoice_date" id="invoice_date" value="{{ $data->invoice_date }}" class="form-control" placeholder="Invoice Date">
                                        <p></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">HSN CODE <span class="text-danger">*</span></label>
                                        <input type="text" name="hsn_code" id="hsn_code"  value="{{ $data->hsn_code }}" class="form-control" placeholder="Enter HSN CODE">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">CGST <span class="text-danger">*</span></label>
                                        <input type="text" name="cgst" id="cgst" value="{{ $data->cgst }}" class="form-control" placeholder="Enter CGST">
                                        <p></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">SGST <span class="text-danger">*</span></label>
                                        <input type="text" name="sgst" id="sgst" value="{{ $data->sgst }}" class="form-control" placeholder="Enter SGST">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">IGST <span class="text-danger">*</span></label>
                                        <input type="text" name="igst" id="igst" value="{{ $data->igst }}" class="form-control" placeholder="Enter IGST">
                                        <p></p>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="form-label">Address<!-- <span class="text-danger">*</span> --></label>
                                <textarea name="address" id="address" class="form-control"  placeholder="Enter Address">{{ $data->address }}</textarea>
                                <p></p>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Description<!-- <span class="text-danger">*</span> --></label>
                                <textarea name="description" id="description" class="form-control"  placeholder="Enter Description">{{ $data->description }}</textarea>
                                <p></p>
                            </div>
                        </div>
                        <input type="hidden" id="invoice_id" name="invoice_id" value="{{ $data->id }}">
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
    <script src="{{ asset('assets/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.js') }}"></script>
    <script>
        $('#description').wysihtml5();
        $('#address').wysihtml5();
    </script>
    <script>
$('#InvoiceUpdate').submit(function (event) {
    event.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        url: '{{ route("invoice.update") }}',
        type: 'POST',
        processData: false,
        contentType: false,
        data: formData,
        success: function (response) {
            if (response['status'] == true) {
                window.location.href="{{  route('invoice.list') }}";
            } else {
                var errors = response['errors'];
                $.each(errors, function (key, value) {
                    console.log(value[0],"value[0]");
                    var elementId = key.replace(/\./g, '_');
                    console.log(elementId,"key");
                    //$('#' + key).siblings('p').addClass('text-danger').html(value[0]);
                    $('#' + elementId).next('p').addClass('text-danger').html(value[0]);
                });
            }
        },
        error: function () {
            console.log('Something went wrong');
        }
    });
});
</script>
@endsection    