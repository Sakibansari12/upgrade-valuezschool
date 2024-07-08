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
    <p><b>Teacher Name</b> : {{ $details['title'] }},</p>
    <p><b>School Name</b> : {{ $details['school_name'] }}</p>
    <p><b>Garde</b> : {{ $details['grade'] }}</p>
    <p><b>Section</b> : {{ $details['section'] }}</p>
    <!-- <p>{{ $details['email'] }}</p> -->
    <p><b>Description</b> : {{ $details['Description'] }}</p>
    <p>Warm Regards,</p></br>
    <p>Valuez School team</p>
</body>

</html>

