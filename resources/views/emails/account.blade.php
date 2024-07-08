<!DOCTYPE html>
<html>

<head>
    <title>Valuez school</title>
    <style>
        img {
            display: block;
            /* margin-left: auto; */
            margin-right: auto;
        }
    </style>
</head>

<body>
    <img src="https://stagelearn.valuezschool.com/assets/images/company_logo.png" alt="valuez" style="max-height:100px; width:100px;">

    <h3>Hi {{ $details['title'] }},</h3><br />
    <p> A warm hello to {{ $details['school_name'] }}.<br />
        Welcome to ValueZ 21st Century school NEP Values + Near Future Tech LMS package - demo version.
    <p>Find your account details herewith.</p>
    <p>User Id : <strong>{{ $details['username'] }}</strong><br />
        Password : <strong>{{ $details['pass'] }}</strong>
    <p><br />
    <p>Please enter above credentials at the link <a
            href="https://learn.valuezschool.com/login">https://learn.valuezschool.com</a> to access sample content
        across
        grades. These credentials are valid for a 2 week period, ending "auto date".</p>
    <p>This is a computer generated email. Please do not reply to this email.<br />
        For any queries you can reach out to support@valuezhut.com
    </p>
    <p> Regards<br />
        ValueZ Team</p>
</body>

</html>
