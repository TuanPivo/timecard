{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Uruca K.K</title>
    <link rel="stylesheet" href="{{ asset('assets/css/adminlte.min.css') }}">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-primary">

            @include('layout.message')

            <div class="card-header text-center">
                <h3>Reset Password</h3>
            </div>
            <div class="card-body">                
                <form action="{{ route('password.processResetPassword', ['token' => $tokenString]) }}" method="post">
                    @csrf
                    <input type="hidden" name="token" value="{{ $tokenString }}">
                    <div class="mb-3">
                        <label for="" class="mb-2">New Password <span style="color:#FF0000">*</span></label>
                        <input type="password" value="" name="new_password" id="new_password" class="form-control @error('new_password') is-invalid @enderror"
                            placeholder="Enter new password">
                        @error('new_password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="" class="mb-2">Confirm Password <span style="color:#FF0000">*</span></label>
                        <input type="password" value="" name="confirm_password" id="confirm_password" class="form-control @error('confirm_password') is-invalid @enderror"
                            placeholder="Enter confirm password">
                        @error('confirm_password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="justify-content-between d-flex">
                        <button class="btn btn-primary mt-2" type="submit">Submit</button>
                    </div>
                    <p>Return to the login page? <a href="{{ route('login') }}">Login</a></p>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
</body>
</html> --}}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js')}}"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 500px;
            height: 300;
        }
        h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 3px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Reset Password</h2>
        @include('layout.message')
        <form action="{{ route('password.processResetPassword', ['token' => $tokenString]) }}" method="post">
            @csrf
            <input type="hidden" name="token" value="{{ $tokenString }}">
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" value="" name="new_password" id="new_password" class="form-control @error('new_password') is-invalid @enderror" placeholder="Enter new password">
                @error('new_password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" value="" name="confirm_password" id="confirm_password" class="form-control @error('confirm_password') is-invalid @enderror" placeholder="Enter confirm password">
                @error('confirm_password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit">Submit</button>
        </form>
    </div>

</body>
</html>
