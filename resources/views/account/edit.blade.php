@extends('layout.index')

@section('content')
    <div class="card-header">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Edit User</h3>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <a href="{{ route('account.index') }}" class="btn btn-black btn-round">Back</a>
            </div>
        </div>
    </div>

    <form action="#" method="POST" id="updateBrandForm" name="updateBrandForm">
        @csrf
        <div class="card">
            <div class="card-body">
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
            </div>
        </div>
        <div class="pb-5 pt-3">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('account.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
        </div>
    </form>
@endsection

@section('customJs')
    {{-- <script>
        $("#updateBrandForm").submit(function(e) {
            e.preventDefault();
            var element = $(this);
            $("button[type=submit]").prop('disable', true);
            $.ajax({
                url: '{{ route('account.update', $user->id) }}',
                type: 'put',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disable', false);
                    if (response["status"] == true) {
                        window.location.href = "{{ route('account.index') }}";
                        $("#name").removeClass('is-invalid').siblings('span').empty();
                        $("#email").removeClass('is-invalid').siblings('span').empty();
                    } else {
                        if (response['notFound'] == true) {
                            window.location.href = "{{ route('account.index') }}";
                            return false;
                        }
                        var errors = response['errors'];

                        if (errors['name']) {
                            $("#name").addClass('is-invalid').siblings('span').addClass(
                                    'invalid-feedback')
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
                    }
                },
                error: function(jqXHR, exception) {
                    console.log("Something went wrong.");
                }
            })
        });
    </script> --}}
    <script>
        $("#updateBrandForm").submit(function(e) {
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
