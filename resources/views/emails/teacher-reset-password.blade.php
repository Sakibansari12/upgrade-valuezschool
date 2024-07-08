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
    <img src="https://learn.valuezschool.com/assets/images/company_logo.png" alt="valuez" style="max-height:100px; width:100px;">
    <!-- <p>Your teacher account password of Valuez school 21st Century LMS has been reset</p> -->
    <p>Hello {{ $details['title'] }},</p>
    <p>{{ $details['school_name'] }}</p>
    <p>Your Valuez school 21st century LMS teacher account password has been rest. Your user ID continues to be this email ID.</p>
        Password : <strong>{{ $details['pass'] }}</strong>
    <p></br>
    <p>Please contact your school admin or reach us at <strong>support@valuezschool.com</strong> if facing any issues. Happy learning!</p><br />
    <p>This message was generated automatically. Please don't respond to this email.</p></br>
    <p>Warm Regards,</p></br>
    <p>Valuez School team</p>
</body>

</html>

