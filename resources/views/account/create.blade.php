@extends('layout.index')

@section('content')
    @include('layout.message')
    <div class="card-header">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <h5 class="fw-bold mb-3">Create User</h5>
        </div>
    </div>
    <div class="card-body page-inner">
        <form action="#" method="post" id="userForm" name="userForm">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="name">Full Name</label>
                        <input type="text" value="{{ old('name') }}" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Enter full name of user">
                        <span></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="email">Email</label>
                        <input type="email" value="{{ old('email') }}" name="email" id="email" class="form-control  @error('email') is-invalid @enderror" placeholder="Email">
                        <span></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                            <span></span>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary" onclick="generatePassword()">Create</button>
                    <a href="{{ route('account.index') }}" class="btn btn-danger">Cancel</a>
                </div>
            </div>
        </form>
    </div>

@endsection

@section('customJs')
    <script>
        $("#userForm").submit(function(e) {
            e.preventDefault();
            var element = $(this);
            $("button[type='submit']").prop('disabled', true);
            $.ajax({
                url: '{{ route('account.store') }}',
                type: 'POST',
                // data: element.serializeArray(),
                data: element.serialize(),
                dataType: 'json',
                success: function(response) {
                    $("button[type='submit']").prop('disabled', false);

                    if (response["status"] === true) {
                        window.location.href = "{{ route('account.index') }}";
                        $("#name").removeClass('is-invalid').siblings('span').empty();
                        $("#email").removeClass('is-invalid').siblings('span').empty();
                        $("#password").removeClass('is-invalid').siblings('span').empty();
                    } else {
                        var errors = response['errors'];

                        if (errors['name']) {
                            $("#name").addClass('is-invalid').siblings('span').addClass('invalid-feedback')
                                .html(errors['name']);
                        } else {
                            $("#name").removeClass('is-invalid').siblings('span').empty();
                        }
                        if (errors['email']) {
                            $("#email").addClass('is-invalid').siblings('span').addClass(
                                'invalid-feedback').html(errors['email']);
                        } else {
                            $("#email").removeClass('is-invalid').siblings('span').empty();
                        }
                        if (errors['password']) {
                            $("#password").addClass('is-invalid').siblings('span').addClass(
                                'invalid-feedback').html(errors['password']);
                        } else {
                            $("#password").removeClass('is-invalid').siblings('span').empty();
                        }
                    }
                },
                error: function(jqXHR, exception) {
                    console.log("Something went wrong.");
                }
            })
        });

        function generatePassword() {
            var password = generateRandomPassword();
            document.getElementById('password').value = password;
        }

        // automatically generate password
        function generateRandomPassword() {
            var length = 8;
            var upperCase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            var lowerCase = 'abcdefghijklmnopqrstuvwxyz';
            var specialCharacters = '!@#$%^&*()_+~`|}{[]:;?><,./-=';
            
            var allCharacters = upperCase + lowerCase + specialCharacters;
            var passwordArray = [];

            // Ensure at least one character from each set is included
            passwordArray.push(upperCase.charAt(Math.floor(Math.random() * upperCase.length)));
            passwordArray.push(lowerCase.charAt(Math.floor(Math.random() * lowerCase.length)));
            passwordArray.push(specialCharacters.charAt(Math.floor(Math.random() * specialCharacters.length)));

            // Fill the rest of the password length with random characters
            for (var i = 3; i < length; i++) {
                var randomIndex = Math.floor(Math.random() * allCharacters.length);
                passwordArray.push(allCharacters.charAt(randomIndex));
            }
            // Shuffle the array to make the password more random
            passwordArray = passwordArray.sort(() => Math.random() - 0.5);
            // Convert array to string
            var password = passwordArray.join('');

            return password;
        }
    </script>
@endsection
