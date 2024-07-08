@extends('layout.main')
@section('content')
<head>
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> --}}
</head>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title"><!-- Package --></h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item alignment-text-new" aria-current="page"><a href="{{ route('package-list') }}"><i class="mdi mdi-home-outline"></i> - Package</a></li>
                        <li class="breadcrumb-item active alignment-text-new" aria-current="page">Add Package</li>
                    </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-lg-12 col-12">
                <!-- Basic Forms -->
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Add New Package </h4>
                    </div>
                    <!-- /.box-header -->
                    <form name="studentcreate" id="studentcreate" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                        <!-- Initial row -->
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label" for="invoice">Invoice <span class="text-danger">*</span></label>
                                    <select name="invoice" id="invoice" class="form-control">
                                        <option value="">Select Invoice</option>
                                        
                                        <option value="{{ $invoice_data->id }}"
                                            {{ $invoice_data->id == old('invoice_id') ? 'selected' : '' }}>
                                            {{ $invoice_data->invoice_number }}</option>
                                    </select>
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">Name of package<span class="text-danger">*</span></label>
                                    <input type="text" id="name_of_package" name="name_of_package" class="form-control" placeholder="Name of package">
                                    <p></p>
                                </div>
                            </div>
                        </div>    


                        <div class="row">
                            
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label" for="duration_of_package">Duration of package <span class="text-danger">*</span></label>
                                    <select name="duration_of_package" id="duration_of_package" class="form-control">
                                        <option value="">Select Duration of package</option>
                                        <option value="6 months">6 months</option>
                                        <option value="12 months">12 months</option>
                                        <option value="3 years">3 years</option>
                                    </select>
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">Set Pricing <span class="text-danger"></span></label>
                                    <input type="text" id="set_pricing" name="set_pricing" class="form-control" placeholder="Set Pricing">
                                    <p></p>
                                </div>
                            </div>
                        </div>
                       <!--  <div class="row"> -->
                           <!--  <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">Set Pricing <span class="text-danger"></span></label>
                                    <input type="text" id="set_pricing" name="set_pricing" class="form-control" placeholder="Set Pricing">
                                    <p></p>
                                </div>
                            </div> -->
                            <!-- <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">Total Price <span class="text-danger"></span></label>
                                    <input type="text"  id="total_price" name="total_price[]"  class="form-control" placeholder="Total Price">
                                    <p></p>
                                </div>
                            </div> -->
                        <!-- </div> -->




                        <div class="box-body" id="package-container">
                            <div class="package-row">
                                <div class="row">
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <input type="checkbox"  name="checkbox_offer[]" id="checkbox_offer" style="transform: scale(1.5); background-color: blue; margin-top: 36px;">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-3" >
                                        <div class="form-group">
                                            <label class="form-label">Deal code <span class="text-danger"></span></label>
                                            <input type="text"  name="deal_code[]" id="packages_package_0_deal_code"  class="form-control" placeholder="Deal code">
                                            <p></p>
                                            @error('deal_code')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3" >
                                        <div class="form-group">
                                            <label class="form-label">Discount Percentage <span class="text-danger"></span></label>
                                            <input type="text"  name="deal_code_per[]" id="packages_package_0_deal_code_per"  class="form-control" placeholder="Discount Percentage">
                                            <p></p>
                                            @error('deal_code_per')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3" >
                                        <div class="form-group">
                                            <label class="form-label">Discount Amount <span class="text-danger"></span></label>
                                            <input type="text" readonly  name="discount_amount[]" id="packages_package_0_discount_amount"  class="form-control" placeholder="Discount Amount">
                                            <p></p>
                                            @error('discount_amount')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn add-fields" style="background-color: #00205c; color: #fff; margin-top: 21px;">Add Deal code</button>
                                    </div>
                                </div>
                            </div>
                        </div>




                            <div class="row">
                                <div class="col-md-5" >
                                    <div class="form-group">
                                        <label class="form-label">Message <span class="text-danger"></span></label>
                                        <input type="text"  name="msg_discount"   id="msg_discount"  class="form-control" placeholder="Message">
                                        <p></p>
                                    </div>
                                </div>
                            </div>
                         


                        <div class="row">
                            <div class="col-md-9">
                                    <div class="form-group">
                                        <label class="form-label">This package includes</label>
                                        <textarea id="this_package_includes" rows="4" name="this_package_includes" class="form-control"
                                            placeholder="This package includes"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit"  class="btn btn-success">Submit</button>
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
<script>
    $(document).ready(function () {
        var wrapper = $('#package-container');
        errorpackage = 0;
        // Function to create a new set of fields
        function createFieldHTML() {
            errorpackage++;
            return `
                    <div class="package-row">
                        <div class="row">
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <input type="checkbox"  name="checkbox_offer[]" id="checkbox_offer" style="transform: scale(1.5); background-color: blue; margin-top: 36px;">
                                            <p></p>
                                        </div>
                                    </div>
                            <div class="col-md-3" >
                                <div class="form-group">
                                    <label class="form-label"></label>
                                    <input type="text"  name="deal_code[]" id="packages_package_${errorpackage}_deal_code"  class="form-control" placeholder="Deal code">
                                    <p></p>
                                    @error('deal_code')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3" >
                                <div class="form-group">
                                    <label class="form-label"></label>
                                    <input type="text"  name="deal_code_per[]" id="packages_package_${errorpackage}_deal_code_per"  class="form-control" placeholder="Discount Percentage">
                                    <p></p>
                                    @error('deal_code_per')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label"><span class="text-danger"></span></label>
                                    <input type="text" readonly  name="discount_amount[]" id="packages_package_${errorpackage}_discount_amount"  class="form-control" placeholder="Discount Amount">
                                    <p></p>
                                    @error('discount_amount')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn remove-fields" style="background-color: #ff0000; color: #fff; margin-top: 21px;">Remove</button>
                            </div>
                            
                        </div>
                    </div>       
            `;
            
        }
         function calculateTotalPrice(packageNumber) {
        $('#packages_package_' + packageNumber + '_this_package_includes').wysihtml5();

        $('#set_pricing, #packages_package_' + packageNumber + '_deal_code_per').on('input', function () {
            var setPricing = parseFloat($('#set_pricing').val()) || 0;
            var taxRate = parseFloat($('#packages_package_' + packageNumber + '_deal_code_per').val()) || 0;
            var totalPrice =  (setPricing * (taxRate / 100));
            $('#packages_package_' + packageNumber + '_discount_amount').val(totalPrice.toFixed(2));
        });

       



    } 
        // Add fields
        wrapper.on('click', '.add-fields', function () {
            wrapper.append(createFieldHTML());
            calculateTotalPrice(errorpackage);
        });

        // Remove fields
        wrapper.on('click', '.remove-fields', function () {
            $(this).closest('.package-row').remove();
        });
    });
</script>



<script src="{{ asset('assets/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.js') }}"></script>
<script>
        $('#this_package_includes').wysihtml5();
       
    </script>
    <script>
    /* $('#studentcreate').submit(function(){
            event.preventDefault();
            var formArray = $(this).serializeArray();
           $.ajax({
             url: '{{ route("student.store") }}',
             type: 'post',
             data: formArray,
             dataType: 'json',
             success: function(response){
                if(response['status'] == true){
                    
                }else{
                    var errors = response['errors'];
                    var student_licence = response['student_licence'];
                    if(student_licence == 'error'){
                       
                    }
                     $.each(errors, function(key,value){
                        $(`#${key}`).siblings('p').addClass('text-danger').html(value);
                    })
                }
             },
             error: function(){
                console.log("Some things went wrong");
             }
           });
        }); */
        $('#studentcreate').submit(function (event) {
    event.preventDefault();

    var formData = new FormData();
    formData.append('msg_discount', $('#msg_discount').val());

    formData.append('name_of_package', $('#name_of_package').val());
    formData.append('duration_of_package', $('#duration_of_package').val());
    
    formData.append('this_package_includes', $('#this_package_includes').val());

    formData.append('invoice', $('#invoice').val());
    formData.append('set_pricing', $('#set_pricing').val());
   // formData.append('total_price', $('#total_price').val());

    var id = 1;
    $('.package-row').each(function () {
        formData.append('packages[package][' + (id - 1) + '][id]', id);
        
        var checkboxOfferValue = $(this).find('input[name="checkbox_offer[]"]').is(':checked') ? 1 : 0;
        formData.append('packages[package][' + (id - 1) + '][checkbox_offer]', checkboxOfferValue);

       
        formData.append('packages[package][' + (id - 1) + '][deal_code]', $(this).find('input[name="deal_code[]"]').val());
        formData.append('packages[package][' + (id - 1) + '][deal_code_per]', $(this).find('input[name="deal_code_per[]"]').val());
        formData.append('packages[package][' + (id - 1) + '][discount_amount]', $(this).find('input[name="discount_amount[]"]').val());
        id++;
    });


    $.ajax({
        url: '{{ route("package.store") }}',
        type: 'POST',
        processData: false,
        contentType: false,
        data: formData,
        success: function (response) {
            if (response['status'] == true) {
                window.location.href="{{  route('package-list') }}";
            } else {
                var errors = response['errors'];
                console.log(errors,"errors");
                $.each(errors, function (key, value) {
                    console.log(value[0],"value[0]");
                    var elementId = key.replace(/\./g, '_');
                    console.log(elementId,"key");
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
<script>
    // Wait for the document to be ready
    $(document).ready(function () {
        // Attach an event listener to the Set Pricing and Tax rate fields
        $('#set_pricing, #packages_package_0_deal_code_per').on('input', function () {
            // Get the values from Set Pricing and Tax rate fields
            var setPricing = parseFloat($('#set_pricing').val()) || 0;
            var deal_code_per = parseFloat($('#packages_package_0_deal_code_per').val()) || 0;
            // Calculate Total Price
            var totalPrice =  (setPricing * (deal_code_per / 100));
              console.log(totalPrice,"totalPrice");
            // Update the Total Price field
           $('#packages_package_0_discount_amount').val(totalPrice.toFixed(2));
        });
    });
</script>
@endsection