@extends('layout.index')
@section('content')
    @include('layout.message')
    <div class="card-header">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Change Password</h3>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <a href="{{ route('home') }}" class="btn btn btn-black btn-round">Back</a>
            </div>
        </div>
    </div>
    <form action="{{ route('password.updatePassword') }}" id="changePasswordForm" name="changePasswordForm" method="post">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="name">Old Password</label>
                        <input type="password" name="old_password" id="old_password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter Old Password">
                        <span></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="email">New Pasword</label>
                        <input type="password" name="new_password" id="new_password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter New Password">
                        <span></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="slug">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password') is-invalid @enderror" placeholder="Confirm New Password">
                        <span></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Change Password</button>
                <a href="{{ route('home') }}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </div>
    </form>
@endsection

@section('customJs')
    <script>
        $("#changePasswordForm").submit(function(e) {
            e.preventDefault();
            var element = $(this);
            $("button[type='submit']").prop('disable', true);
            $.ajax({
                url: '{{ route('password.updatePassword') }}',
                type: 'post',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response) {
                    $("button[type='submit']").prop('disable', false);

                    if (response["status"] == true) {
                        window.location.href = "{{ route('password.change-password') }}";
                        $("#new_password").removeClass('is-invalid').siblings('span').empty();
                        $("#email").removeClass('is-invalid').siblings('span').empty();
                        $("#password_confirmation").removeClass('is-invalid').siblings('span').empty();
                    } else {
                        var errors = response['errors'];

                        if (errors['old_password']) {
                            $("#old_password").addClass('is-invalid').siblings('span').addClass('invalid-feedback')
                                .html(errors['old_password']);
                        } else {
                            $("#old_password").removeClass('is-invalid').siblings('span').empty();
                        }

                        if (errors['new_password']) {
                            $("#new_password").addClass('is-invalid').siblings('span').addClass('invalid-feedback')
                                .html(errors['new_password']);
                        } else {
                            $("#new_password").removeClass('is-invalid').siblings('span').empty();
                        }

                        if (errors['password_confirmation']) {
                            $("#password_confirmation").addClass('is-invalid').siblings('span').addClass('invalid-feedback')
                                .html(errors['password_confirmation']);
                        } else {
                            $("#password_confirmation").removeClass('is-invalid').siblings('span').empty();
                        }
                    }
                },
                error: function(jqXHR, exception) {
                    console.log("Something went wrong.");
                }
            })
        });
    </script>
@endsection
