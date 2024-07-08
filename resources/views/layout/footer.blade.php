<footer class="main-footer">
    &copy; {{ date('Y') }} <a href="#">Valuez School</a>. All Rights Reserved.
</footer>

<!-- Vendor JS -->
<script src="{{ asset('assets/src/js/vendors.min.js') }}"></script>
<script src="{{ asset('assets/icons/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('assets/vendor_components/datatable/datatables.min.js') }}"></script>
<script src="{{ asset('assets/vendor_components/select2/dist/js/select2.full.js') }}"></script>
<script src="{{ asset('assets/vendor_components/sweetalert/sweetalert.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });

        $(".notifyList").click(function() {
            var user_type = $(this).attr('data-user-type');
            $.ajax({
                url: "{{ route('ajax.notify.list') }}",
                type: "GET",
                data: {
                    type: "list",
                    user_type: user_type,
                },
                success: function(res) {
                    $("#notify_list").html(res);
                }
            });
        });

        $(".clear_all_notify").click(function() {
        var user_type = $(this).attr('data-user-type');
            $.ajax({
                url: "{{ route('ajax.notify.list') }}",
                type: "GET",
                data: {
                    type: "clear",
                    user_type: user_type,
                },
                success: function(res) {
                    window.location.reload();
                    console.log(res);
                }
            });
        });

        
        $(document).ready(function() {
        $.ajax({
            url: "{{ route('ajax.notify.icon') }}",
            type: "GET",
            data: {
                type: "list",
            },
            success: function(response) {
                if(response['status'] == true){
                          var noty_count  = response['notifacation'];
                          if(noty_count){
                           $("#notify_count").text(noty_count).show().css({
                                position: 'absolute',
                                top: '0',
                                right: '-30px',
                                width: '25px',
                                height: '22px',
                                fontSize: '12px',
                                color: '#fff',
                                backgroundColor: '#ff3f3f',
                                borderRadius: '50%',
                                display: 'flex',
                                alignItems: 'center',
                                justifyContent: 'center'
                            });
                        $("#notify_count_top").text(noty_count).show().css({
                                position: 'absolute',
                                top: '0',
                                right: '0',
                                width: '18px',
                                height: '16px',
                                fontSize: '12px',
                                color: '#fff',
                                backgroundColor: '#ff3f3f',
                                borderRadius: '50%',
                                display: 'flex',
                                alignItems: 'center',
                                justifyContent: 'center'
                            });
                          }
                }
            }
        });
    });

/* reminders */
$(document).ready(function() {
        $.ajax({
            url: "{{ route('ajax.reminder.icon') }}",
            type: "GET",
            data: {
                type: "list",
            },
            success: function(response) {
                if(response['status'] == true){
                          var reminder_count  = response['ReminderNotifacation'];
                          if(reminder_count){
                           $("#notify_reminder_count").text(reminder_count).show().css({
                                position: 'absolute',
                                top: '0',
                                right: '-30px',
                                width: '25px',
                                height: '22px',
                                fontSize: '12px',
                                color: '#fff',
                                backgroundColor: '#ff3f3f',
                                borderRadius: '50%',
                                display: 'flex',
                                alignItems: 'center',
                                justifyContent: 'center'
                            });
                        /* $("#notify_count_top").text(noty_count).show().css({
                                position: 'absolute',
                                top: '0',
                                right: '0',
                                width: '18px',
                                height: '16px',
                                fontSize: '12px',
                                color: '#fff',
                                backgroundColor: '#ff3f3f',
                                borderRadius: '50%',
                                display: 'flex',
                                alignItems: 'center',
                                justifyContent: 'center'
                            }); */
                          }
                }
            }
        });
    });



    $(document).ready(function() {
        $.ajax({
            url: "{{ route('subscription-request-notify') }}",
            type: "GET",
            data: {
                type: "list",
            },
            success: function(response) {
                if(response['status'] == true){
                          var subscription_request_count  = response['subscription_request_count'];
                          if(subscription_request_count){
                           $("#subscription_request_count").text(subscription_request_count).show().css({
                                position: 'absolute',
                                top: '0',
                                right: '-30px',
                                width: '25px',
                                height: '22px',
                                fontSize: '12px',
                                color: '#fff',
                                backgroundColor: '#ff3f3f',
                                borderRadius: '50%',
                                display: 'flex',
                                alignItems: 'center',
                                justifyContent: 'center'
                            });
                          }
                }
            }
        });
    });
    $(document).ready(function() {
        $.ajax({
            url: "{{ route('feedback_reply_notify') }}",
            type: "GET",
            data: {
                type: "list",
            },
            success: function(response) {
                if(response['status'] == true){
                          var feedback_reply_noty  = response['feedback_reply_noty'];
                          if(feedback_reply_noty){
                           $("#feedback_reply_noty").text(feedback_reply_noty).show().css({
                                position: 'absolute',
                                top: '0',
                                right: '-36px',
                                width: '25px',
                                height: '22px',
                                fontSize: '12px',
                                color: '#fff',
                                backgroundColor: '#ff3f3f',
                                borderRadius: '50%',
                                display: 'flex',
                                alignItems: 'center',
                                justifyContent: 'center'
                            });
                          }
                }
            }
        });
    });
    $(document).ready(function() {
        $.ajax({
            url: "{{ route('school_admin_forgotPass_notify') }}",
            type: "GET",
            data: {
                type: "list",
            },
            success: function(response) {
                if(response['status'] == true){
                          var forgot_noty_password  = response['forgot_noty_password'];
                          if(forgot_noty_password){
                           $("#forgot_noty_password").text(forgot_noty_password).show().css({
                                position: 'absolute',
                                top: '0',
                                right: '-36px',
                                width: '25px',
                                height: '22px',
                                fontSize: '12px',
                                color: '#fff',
                                backgroundColor: '#ff3f3f',
                                borderRadius: '50%',
                                display: 'flex',
                                alignItems: 'center',
                                justifyContent: 'center'
                            });
                          }
                }
            }
        });
    });
    $(document).ready(function() {
        $.ajax({
            url: "{{ route('support-count-noty') }}",
            type: "GET",
            data: {
                type: "list",
            },
            success: function(response) {
                if(response['status'] == true){
                          var support_count_noty  = response['support_count_noty'];
                          if(support_count_noty){
                           $("#support_count_noty").text(support_count_noty).show().css({
                                position: 'absolute',
                                top: '0',
                                right: '-36px',
                                width: '25px',
                                height: '22px',
                                fontSize: '12px',
                                color: '#fff',
                                backgroundColor: '#ff3f3f',
                                borderRadius: '50%',
                                display: 'flex',
                                alignItems: 'center',
                                justifyContent: 'center'
                            });
                          }
                }
            }
        });
    });
    $(document).ready(function() {
        // Your API call code
        $.ajax({
            url: "{{ route('show-student-subcrition') }}",
            type: "GET",
            data: {
                type: "list",
            },
            success: function(response) {
                if(response['status'] == true){
                     var show_student_subcrition  = response['show_student_subcrition'];
                    console.log(show_student_subcrition,'show_student_subcrition')
                    /* if(show_student_subcrition){
                    $("#show_student_subcrition_text").text(show_student_subcrition).show();
                    } */
                    
                    
    if (show_student_subcrition) {
        
        var truncatedText = show_student_subcrition.substring(0, 70);

    
        $("#show_student_subcrition_text").text(truncatedText).show();
    }

                   // var show_student_subcrition = "Line 1\nLine 2\nLine 3"; // Example string with plain text line breaks
   
   /* var show_student_subcrition  = response['show_student_subcrition'];
  
    console.log(show_student_subcrition, 'show_student_subcrition');

    if (show_student_subcrition) {
        
        var formattedText = show_student_subcrition.replace(/\/ /g, '<br>');
        
        $("#show_student_subcrition_text").html(formattedText).show();
    } */
                  
                }
           
            }
        });
    });




    });
</script>

<!-- edulearn App -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="{{ asset('assets/src/js/demo.js') }}"></script>
<script src="{{ asset('assets/src/js/template.js') }}"></script>
<script src="{{ asset('assets/src/js/pages/data-table.js') }}"></script>
@yield('script-section')
