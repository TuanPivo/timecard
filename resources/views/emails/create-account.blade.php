<!-- template_email.html -->
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
    <title>Account Created Successfully</title>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Account Created Successfully</h1>
        </div>
        <div class="content">
            <h2>Hello {{ $name }},</h2>
            <p>Your password has been changed successfully. If you did not perform this action, please contact our support team immediately.</p>
            <p>For your security, make sure to use a strong, unique password and never share it with anyone.</p>
            <p>Password: <strong>{{ $password }}</strong></p>
            <p>If you have any questions or need assistance, feel free to contact our support team.</p>
            <a href="{{ $loginUrl }}" target="_blank">Log In to Your Account</a>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Uruca K.K. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
