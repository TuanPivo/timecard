<!-- template_email.html -->
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f7f7f7;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            background-color: #4caf50;
            padding: 20px;
            color: #ffffff;
        }
        .content {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #dddddd;
        }
        .footer p {
            margin: 0;
        }
        .button {
            text-align: center;
            margin-top: 30px;
        }
        .button a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4caf50;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h3>Forgot password email.</h3>
        </div>
        <div class="content">
            <p>Dear, <strong>{{ $mailData['user']->name }}</strong></p>
            <p>Click below to change your password.</p>
            <div class="button">
                <a href="{{ route('password.resetPassword',$mailData['token']) }}">Click here to reset password</a>
            </div>
        </div>
        <div class="footer">
            <p>Best regards!</p>
        </div>
    </div>
</body>
</html>