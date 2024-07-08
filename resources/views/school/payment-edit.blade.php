@extends('layout.main')
@section('content')
<head>
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> --}}
</head>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">School Name</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">{{ isset($schoodata->school_name) ? $schoodata->school_name : '' }}</li>
                            
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
<!-- Add school payment list data -->



    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-lg-8 col-12">
                <!-- Basic Forms -->
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">School Payment</h4>
                    </div>
                    <!-- /.box-header -->
<!-- <form action="{{ route('school-payment.store') }}" method="POST"> -->
    <form name="studentcreate" id="studentcreate" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="box-body" >
        <div class="form-group">
            <label class="form-label">School Name <span class="text-danger">*</span></label>
            <input type="text" name="school_name" id="school_name" readonly value="{{$schoodata->school_name}}" class="form-control" placeholder="School Name">
            <p></p>
            @error('school_name')
                <span class="text-danger">{{ $school_name }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label class="form-label">School Name Billing<span class="text-danger">*</span></label>
            <input type="text" name="school_name_billing" id="school_name_billing" value="{{$paymentdata->school_name_billing}}"  class="form-control" placeholder="School Name">
            <p></p>
            @error('school_name_billing')
                <span class="text-danger">{{ $school_name_billing }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label class="form-label">Payment Amount <span class="text-danger">*</span></label>
            <input type="number" name="payment_amount" id="payment_amount" value="{{$paymentdata->payment_amount}}" class="form-control" placeholder="Payment Amount">
            <p></p>
            @error('price')
                <span class="text-danger">{{ $price }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label class="form-label">Description <span class="text-danger">*</span></label>
            <textarea name="description" id="description" class="form-control" placeholder="Enter Description">{{$paymentdata->description}}</textarea>
            <p></p>
            @error('description')
                <span class="text-danger">{{ $description }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
            <input type="text" name="phone_number" id="phone_number" readonly value="{{$paymentdata->phone_number}}" class="form-control" placeholder="Phone Number">
            <p></p>
            @error('phone_number')
                <span class="text-danger">{{ $phone_number }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label class="form-label">Email<span class="text-danger">*</span></label>
            <input type="text" name="email" id="email" readonly value="{{$paymentdata->email}}" class="form-control" placeholder="Email">
            <p></p>
            @error('email')
                <span class="text-danger">{{ $email }}</span>
            @enderror
        </div>
    </div>
    
    <button type="submit" class="btn btn-success">Update For Reser Pay Link</button>
</form>

                </div>
                
            </div>

        </div>
    </section>
    <!-- /.content -->
@endsection
@section('script-section')
<script>
   $('#studentcreate').submit(function (event) {
    event.preventDefault();
    var csrfToken = $('meta[name="_token"]').attr('content');
    var emailFields = [];

    // Iterate through email input fields and collect their values
    $('.email-field input[name="email"]').each(function () {
        emailFields.push($(this).val());
    });

    // Create a data object with 'milestone' as a JSON string
    var data = {
        _token: csrfToken,
        payment_amount: $('#payment_amount').val(),
        school_id: $('#school_id').val(),
        description: $('#description').val(),
        phone_number: $('#phone_number').val(),
        school_name_billing: $('#school_name_billing').val(),
        email: $('#email').val(),
     //   email: emailFields,
      //  milestone: JSON.stringify(emailFields), 
    };

    $.ajax({
        url: '{{ route("school-payment-update", ['payment_id' => $paymentdata->id]) }}',
        type: 'post',
        data: data,
        dataType: 'json',
        success: function (response) {
            if (response['status'] == true) {
                console.log(response, "response");
                // Redirect to another page or perform further actions
            } else {
                var errors = response['errors'];
                console.log(errors, "errors");
                $.each(errors, function (key, value) {
                    $(`#${key}`).siblings('p').addClass('text-danger').html(value);
                });
            }
        },
        error: function () {
            console.log("Some things went wrong");
        }
    });
});

</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        function addFieldSet() {
            const container = document.getElementById('additional-fields-container');
            const emailFieldSet = container.querySelector('.form-group.col-md-8'); // Select the email input field
            const clonedEmailFieldSet = emailFieldSet.cloneNode(true); // Clone the email field

            // Clear the value of the cloned email field
            clonedEmailFieldSet.querySelector('input').value = '';

            // Create a remove button within a <div> element
            const removeDiv = document.createElement('div');
            removeDiv.className = 'form-group col-md-4';
            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'btn btn-danger';
            removeButton.textContent = 'Remove';

            // Add an event listener to remove the field set when the remove button is clicked
            removeButton.addEventListener('click', function () {
                container.removeChild(clonedEmailFieldSet);
                container.removeChild(removeDiv);
            });

            // Append the cloned email field and remove button to the container
            container.appendChild(clonedEmailFieldSet);
            removeDiv.appendChild(removeButton);
            container.appendChild(removeDiv);
        }

        document.getElementById('add-fields').addEventListener('click', addFieldSet);
    });
</script>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function SchoolPayment(event, payment_id) {
        event.preventDefault();
        $('#fileInput').click();
        
        $('#fileInput').change(function () {
            var fileInput = document.getElementById('fileInput');
            var file = fileInput.files[0];
            var formData = new FormData();
            formData.append('invoice', file);
            formData.append('payment_id', payment_id);

            $.ajax({
                url: "{{ route('upload-invoice') }}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    //alert(response.message); // Handle the success response
                },
                error: function (xhr, status, error) {
                    alert('File upload failed: ' + error); // Handle the error
                }
            });
        });
    }
</script>





@endsection