@extends('layout.index')
@section('content')
<div class="hold-transition login-page">
     <div class="login-box">
        <div class="card card-outline card-primary">
            @include('layout.message')
            <div class="card-header text-center">
                <h3>Change Password</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('password.updatePassword') }}" id="changePasswordForm" name="changePasswordForm" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="mb-2">Old Password <span style="color:#FF0000">*</span></label>
                        <input type="password" name="old_password" id="old_password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter Old Password">
                        <p></p>
                    </div>
                    <div class="mb-3">
                        <label for="" class="mb-2">New Password <span style="color:#FF0000">*</span></label>
                        <input type="password" name="new_password" id="new_password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter New Password">
                        <p></p>
                    </div>
                    <div class="mb-3">
                        <label for="" class="mb-2">Confirm Password <span style="color:#FF0000">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password') is-invalid @enderror" placeholder="Confirm New Password">
                        <p></p>
                    </div>
                    <div class="justify-content-between d-flex">
                        <button type="submit" class="btn btn-primary">Change Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
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
                        $("#old_password").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();
                        $("#new_password").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();
                        $("#password_confirmation").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();
                    } else {
                        var errors = response['errors'];

                        if (errors['old_password']) {
                            $("#old_password").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['old_password']);
                        } else {
                            $("#old_password").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();
                        }
                        if (errors['new_password']) {
                            $("#new_password").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['new_password']);
                        } else {
                            $("#new_password").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();
                        }
                        if (errors['password_confirmation']) {
                            $("#password_confirmation").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['password_confirmation']);
                        } else {
                            $("#password_confirmation").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();
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
