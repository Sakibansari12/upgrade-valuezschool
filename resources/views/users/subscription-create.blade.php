@extends('layout.main')
@section('content')
<head>
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> --}}
</head>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title"><!-- Subscription --></h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                        
                        
                        <li class="breadcrumb-item alignment-text-new" aria-current="page"><a href="{{ route('subscription-list') }}"><i class="mdi mdi-home-outline"></i> - Subscription Request</a></li>
                        <li class="breadcrumb-item active alignment-text-new" aria-current="page">Add Subscription</li>
                        
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
                        <h4 class="box-title">Add New Subscription </h4>
                    </div>
                    <!-- /.box-header -->
                    <form name="studentcreate" id="studentcreate" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body" id="package-container">
                        <!-- Initial row -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">School Name <span class="text-danger"></span></label>
                                    <input type="hidden" name="school_id" value="{{ $school_data->id }}">
                                    <input type="text" readonly id="school_name" value="{{ $school_data->school_name }}" name="school_name"  class="form-control" placeholder="School Name">
                                    <p></p>
                                </div>
                            </div>
                        </div>
                        <div class="package-row">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Teacher Name<span class="text-danger"></span></label>
                                        <input type="text" id="subscriptions_subscription_0_teacher_name" name="teacher_name[]" class="form-control" placeholder="Teacher Name">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label" for="garde">Grade <span class="text-danger">*</span></label>
                                        <select name="garde[]" id="subscriptions_subscription_0_garde" class="form-control">
                                        <option  value="">Select a Grade</option>
                                            <option  value="1">Grade 1</option>
                                            <option  value="2">Grade 2</option>
                                            <option  value="3">Grade 3</option>
                                            <option  value="4">Grade 4</option>
                                            <option  value="5">Grade 5</option>
                                            <option  value="6">Grade 6</option>
                                            <option  value="7">Grade 7</option>
                                            <option  value="8">Grade 8</option>
                                            <option  value="9">Grade 9</option>
                                            <option  value="10">Grade 10</option>
                                            <option  value="11">Pre School</option>
                                        </select>
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" class="btn add-fields" style="background-color: #00205c; color: #fff; margin-top: 21px;">Add classroom</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Section<span class="text-danger">*</span></label>
                                        <input type="text" id="subscriptions_subscription_0_section" name="section[]" class="form-control" placeholder="Section">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Confirm Section <span class="text-danger">*</span></label>
                                        <input type="text" id="subscriptions_subscription_0_confirm_section" name="confirm_section[]"  class="form-control" placeholder="Confirm Section">
                                        <p></p>
                                    </div>
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
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Teacher Name<span class="text-danger"></span></label>
                            <input type="text" id="subscriptions_subscription_${errorpackage}_teacher_name" name="teacher_name[]" class="form-control" placeholder="Teacher Name">
                            <p></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label" for="garde">Grade <span class="text-danger">*</span></label>
                            <select name="garde[]" id="subscriptions_subscription_${errorpackage}_garde" class="form-control">
                                <option  value="">Select a Grade</option>
                                <option  value="1">Grade 1</option>
                                <option  value="2">Grade 2</option>
                                <option  value="3">Grade 3</option>
                                <option  value="4">Grade 4</option>
                                <option  value="5">Grade 5</option>
                                <option  value="6">Grade 6</option>
                                <option  value="7">Grade 7</option>
                                <option  value="8">Grade 8</option>
                                <option  value="9">Grade 9</option>
                                <option  value="10">Grade 10</option>
                                <option  value="11">Pre School</option>
                            </select>
                            <p></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn remove-fields" style="background-color: #ff0000; color: #fff; margin-top: 21px;">Remove</button>
                    </div>
                </div>
                <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Section<span class="text-danger">*</span></label>
                                <input type="text" id="subscriptions_subscription_${errorpackage}_section" name="section[]" class="form-control" placeholder="Section">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Confirm Section <span class="text-danger">*</span></label>
                                <input type="text" id="subscriptions_subscription_${errorpackage}_confirm_section" name="confirm_section[]"  class="form-control" placeholder="Confirm Section">
                                <p></p>
                            </div>
                        </div>
                    </div>
                </div>        
            `;
            
        }
        /* function calculateTotalPrice(packageNumber) {
        $('#packages_package_' + packageNumber + '_this_package_includes').wysihtml5();
        $('#packages_package_' + packageNumber + '_set_pricing, #packages_package_' + packageNumber + '_tax_rate').on('input', function () {
            var setPricing = parseFloat($('#packages_package_' + packageNumber + '_set_pricing').val()) || 0;
            var taxRate = parseFloat($('#packages_package_' + packageNumber + '_tax_rate').val()) || 0;
            var totalPrice = setPricing + (setPricing * (taxRate / 100));
            $('#packages_package_' + packageNumber + '_total_prices').val(totalPrice.toFixed(2));
        });
    } */
        // Add fields
        wrapper.on('click', '.add-fields', function () {
            wrapper.append(createFieldHTML());
           // calculateTotalPrice(errorpackage);
        });

        // Remove fields
        wrapper.on('click', '.remove-fields', function () {
            $(this).closest('.package-row').remove();
        });
    });
</script>



<script src="{{ asset('assets/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.js') }}"></script>
<script>
        $('#packages_package_0_this_package_includes').wysihtml5();
       
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
    var packageRowCount = $('.package-row').length;
   
    var formData = new FormData();
    formData.append('package_row_count', packageRowCount);
   // formData.append('deal_code', $('#deal_code').val());
   // formData.append('deal_code_per', $('#deal_code_per').val());
    var id = 1;
    $('.package-row').each(function () {
        formData.append('subscriptions[subscription][' + (id - 1) + '][id]', id);
        formData.append('subscriptions[subscription][' + (id - 1) + '][teacher_name]', $(this).find('input[name="teacher_name[]"]').val());
        formData.append('subscriptions[subscription][' + (id - 1) + '][garde]', $(this).find('select[name="garde[]"]').val());
        formData.append('subscriptions[subscription][' + (id - 1) + '][section]', $(this).find('input[name="section[]"]').val());
        formData.append('subscriptions[subscription][' + (id - 1) + '][confirm_section]', $(this).find('input[name="confirm_section[]"]').val());
        id++;
    });


    $.ajax({
        url: '{{ route("subscription.store") }}',
        type: 'POST',
        processData: false,
        contentType: false,
        data: formData,
        success: function (response) {
            if (response['status'] == true) {
                window.location.href="{{  route('subscription-list') }}";
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
        $('#packages_package_0_set_pricing, #packages_package_0_tax_rate').on('input', function () {
            // Get the values from Set Pricing and Tax rate fields
            var setPricing = parseFloat($('#packages_package_0_set_pricing').val()) || 0;
            var taxRate = parseFloat($('#packages_package_0_tax_rate').val()) || 0;

            // Calculate Total Price
            var totalPrice = setPricing + (setPricing * (taxRate / 100));

            // Update the Total Price field
            $('#packages_package_0_total_price').val(totalPrice.toFixed(2));
        });
    });
</script>
@endsection