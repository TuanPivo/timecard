{{-- <!-- template_email.html -->
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
</html> --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #007BFF;
            color: #ffffff;
            padding: 10px;
            text-align: center;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .header h1 {
            margin: 0;
        }
        .content {
            padding: 20px;
            text-align: center;
        }
        .content h2 {
            color: #333333;
        }
        .content p {
            color: #666666;
            line-height: 1.6;
        }
        .content a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            padding: 10px;
            font-size: 12px;
            color: #999999;
            margin-top: 20px;
            border-top: 1px solid #dddddd;
        }
    </style>
    <title>Password Reset Request</title>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Password Reset Request</h1>
        </div>
        <div class="content">
            <h2>Hello {{ $mailData['user']->name }},</h2>
            <p>We received a request to reset your password. Click the button below to reset it.</p>
            <a href="{{ route('password.resetPassword',$mailData['token']) }}" class="button">Reset Password</a>
            <p>If you did not request a password reset, please ignore this email or contact support if you have questions.</p>
            <p>Thanks,</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Uruca K.K. All rights reserved.</p>
        </div>
    </div>
</body>
</html>