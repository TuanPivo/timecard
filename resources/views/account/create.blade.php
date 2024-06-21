@extends('layout.index')

@section('content')
    <div class="card-header">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Add new user</h3>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <a href="{{ route('account.index') }}" class="btn btn-primary btn-round">Back</a>
            </div>
        </div>
    </div>
    <form action="#" method="post" id="userForm" name="userForm">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="name">Full Name</label>
                            <input type="text" value="{{ old('name') }}" name="name" id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Enter full name of user">
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
                            <label for="slug">Password</label>
                            <input type="text" name="password" id="password" class="form-control" placeholder="Password">
                                <span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="pb-5 pt-3">
            <button type="submit" class="btn btn-primary" onclick="generatePassword()">Create</button>
            <a href="{{ route('account.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
        </div>
    </form>
@endsection

@section('customJs')
    <script>
        $("#userForm").submit(function(e) {
            e.preventDefault();
            var element = $(this);
            $("button[type='submit']").prop('disable', true);
            $.ajax({
                url: '{{ route('account.store') }}',
                type: 'post',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response) {
                    $("button[type='submit']").prop('disable', false);

                    if (response["status"] == true) {
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

        function generateRandomPassword() {
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+~`|}{[]\:;?><,./-=';
            var length = 8;
            var password = '';

            for (var i = 0; i < length; i++) {
                var randomIndex = Math.floor(Math.random() * characters.length);
                password += characters.charAt(randomIndex);
            }
            return password;
        }
    </script>
@endsection
