@extends('layout.index')

@section('content')
    <div class="card-header">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <h5>Edit User</h5>
        </div>
    </div>
    <div class="card-body">
        <form action="#" method="POST" id="updateForm" name="updateForm">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="name">Full Name</label>
                        <input type="text" value="{{ $user->name }}" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Enter Full Name Of User">
                        <span></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="email">Email</label>
                        <input type="email" value="{{ $user->email }}" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter Email">
                        <span></span>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button class="btn btn-outline-dark"><a href="{{ route('account.index') }}">Cancel</a></button>
                </div>
            </div>    
        </form>
    </div>
@endsection

@section('customJs')
    <script>
        $("#updateForm").submit(function(e) {
            e.preventDefault();
            var element = $(this);
            $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: '{{ route('account.update', $user->id) }}',
                type: 'PUT',
                data: element.serialize(),
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false);
                    if (response["status"] === true) {
                        window.location.href = "{{ route('account.index') }}";
                        $("#name").removeClass('is-invalid').siblings('span').empty();
                        $("#email").removeClass('is-invalid').siblings('span').empty();
                    } else {
                        if (response['notFound'] === true) {
                            window.location.href = "{{ route('account.index') }}";
                            return;
                        }
                        var errors = response['errors'];
    
                        if (errors['name']) {
                            $("#name").addClass('is-invalid').siblings('span').addClass('invalid-feedback').html(errors['name']);
                        } else {
                            $("#name").removeClass('is-invalid').siblings('span').empty();
                        }
                        if (errors['email']) {
                            $("#email").addClass('is-invalid').siblings('span').addClass('invalid-feedback').html(errors['email']);
                        } else {
                            $("#email").removeClass('is-invalid').siblings('span').empty();
                        }
                    }
                },
                error: function(jqXHR, exception) {
                    $("button[type=submit]").prop('disabled', false);
                    console.log("Something went wrong.");
                }
            });
        });
    </script>
    
@endsection
