<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="_token" content="{{ csrf_token() }}" />
    <title>RozerPay Example</title>
    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha512-M5KW3ztuIICmVIhjSqXe01oV2bpe248gOxqmlcYrEzAvws7Pw3z6BK0iGbrwvdrUQUhi3eXgtxp5I8PDo9YfjQ==" crossorigin="anonymous"></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha512-MoRNloxbStBcD8z3M/2BmnT+rg4IsMxPkXaGh2zD6LGNNFE80W3onsAhRcMAMrSoyWL9xD7Ert0men7vR8LUZg==" crossorigin="anonymous" />
</head>
<body>
    <div id="app">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 offset-3 col-md-offset-6">
                        @if($message = Session::get('error'))
                            <div class="alert alert-danger alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <strong>Error!</strong> {{ $message }}
                            </div>
                        @endif
                            <div class="alert alert-success success-alert alert-dismissible fade show" role="alert" style="display: none;">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <strong>Success!</strong> <span class="success-message"></span>
                            </div>
                        {{ Session::forget('success') }}
                        
                         
                        <input type="hidden" name="amount" id="amount" value="{{ $school_payment_data->payment_amount }}" >
                        <input type="hidden" name="payment_id " id="payment_id" value="{{ $school_payment_data->id }}" >
                       
                    </div>
                </div>
            </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>


<script language="javascript" type="text/javascript">
$(document).ready(function () {
    // Retrieve the SCHOOL_PAYMENT_ID from the button value attribute
    var schoolPaymentId = $('#rzp-button1-school').val();
    var payment_id = $('#payment_id').val();
    var razorpayPaymentStoreRoute = "{{ route('sr-razorpay-payment') }}";
    var amount = $('#amount').val();
    
    var csrfToken = $('meta[name="_token"]').attr('content');
    console.log(csrfToken, 'csrfToken');
    var total_amount = amount * 100;
    var options = {
        key: "rzp_live_nvVMrBwg0kJRIT",
        amount: total_amount, // Sample amount in paisa (500 INR)
        currency: "INR",
        name: "NiceSnippets",
        description: "Test Transaction",
        image: "https://www.nicesnippets.com/image/imgpsh_fullsize.png",
        order_id: "", 
        handler: function (response) {
            console.log(response,"response");
        console.log(response, 'csrfToken');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            $.ajax({
                type: 'get',
                url: razorpayPaymentStoreRoute,
                data: {
                   // _token: csrfToken,
                    razorpay_payment_id: response.razorpay_payment_id,
                    amount: amount,
                    school_payment_id: payment_id,
                },
                success: function (data) {
                    // Handle success response
                    $('.success-message').text(data.success);
                    $('.success-alert').fadeIn('slow', function () {
                        $('.success-alert').delay(5000).fadeOut();
                    });
                },
                error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
            });
        },
        prefill: {
            name: "Mehul Bagda",
            email: "support@valuezschool.com",
            contact: "8826708801"
        },
        notes: {
            address: "test test"
        },
        theme: {
            color: "#F37254"
        }
    };
    var rzp1 = new Razorpay(options);
    //console.log(rzp1,"rzp1");
   // rzp1.open();
   rzp1.on('payment.failed', function (data) {
   var razorpayPaymentFailed = "{{ route('sr-razorpay-payment-failed') }}";
   $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            $.ajax({
                type: 'get',
                url: razorpayPaymentFailed,
                data: {
                    failed_data: data.error,
                    amount: amount,
                    school_payment_id: payment_id,
                },
                success: function (response) {
                    
                   /*  $('.success-message').text(data.success);
                    $('.success-alert').fadeIn('slow', function () {
                        $('.success-alert').delay(5000).fadeOut();
                    }); */
                },
                error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
            });

    
});
    rzp1.open();
});
</script>
</body>
</html>