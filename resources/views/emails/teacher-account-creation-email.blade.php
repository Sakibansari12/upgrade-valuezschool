<!DOCTYPE html>
<html>

<head>
    <title>Valuez school</title>
    <style>
        img {
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>

<body>
    <img src="https://learn.valuezschool.com/assets/images/company_logo.png" alt="valuez" style="max-height:100px; width:100px;" >
    <!-- <p>Welcome! Your teacher account to access Valuez School 21st Century LMS has been created</p> -->
    <p>Hello {{ $details['title'] }},</p>
    <p>{{ $details['school_name'] }}</p>
    <p>Your Valuez school 21st century LMS teacher account password has been created.</p>
        User ID : <strong>{{ $details['email'] }}</strong><br>
        Password : <strong>{{ $details['pass'] }}</strong>
    <p></br>
    <p>Login at <strong>www.learn.valuezschool.com/login</strong> with above credentials.</p>
    <p>Please contact your school admin or reach us at <strong>support@valuezschool.com</strong> if facing any issues. Happy learning!</p>
    <p>This message was generated automatically. Please don't respond to this email.</p></br>
    <p>Warm Regards,</p></br>
    <p>Valuez School team</p>
</body>

</html>

