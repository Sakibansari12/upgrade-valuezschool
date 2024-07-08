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
    <h3>Hi {{ $details['title'] }},</h3>
    <p>Your Valuez School Account Password was Reset :</p>
        Email : <strong>{{ $details['email'] }}</strong>
    <p>
    <p>This message was generated automatically. Don't reply to this message.<br />
        Valuez School team</p>
</body>

</html>

