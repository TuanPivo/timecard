<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Uruca K.K</title>
    <!-- Theme style -->
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
                <form action="{{ route('account.processResetPassword', ['token' => $tokenString]) }}" method="post">
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

    <!-- Bootstrap-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- jQuery -->
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
</body>
</html>
