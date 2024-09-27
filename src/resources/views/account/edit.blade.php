@extends('layout.index')

@section('content')
    <div class="card-header">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
            <div>
                <h5 class="fw-bold mb-3">Edit Information</h5>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <a href="{{ route('account.index') }}" class="btn btn-black">Back</a>
            </div>
        </div>
    </div>
    <div class="card-body page-inner">
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
                    <a href="#" type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal-notification" onclick="setDeleteUserId({{ $user->id }})">
                        Delete Account
                    </a>
                </div>
            </div>    
        </form>
    </div>
    <div class="card-footer">
        <div class="modal fade" id="modal-notification" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
            <div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title fw-bold" id="modal-title-notification">
                            Confirm user deletion
                        </h6>
                    </div>
                    <div class="modal-body">
                        <div class="py-1 text-center">
                            <h6 class="text-danger">Are you sure you want to delete this account?</h6>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="confirmDelete()" style="font-size: 12px">Delete</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" style="font-size: 12px">
                            Cancle
                        </button>
                    </div>
                </div>
            </div>
            <meta name="csrf-token" content="{{ csrf_token() }}">
        </div>
    </div>
@endsection

@section('customJs')
<script>
    let userIdToDelete = null;

    function setDeleteUserId(id) {
        userIdToDelete = id;
    }

    function confirmDelete() {
        if (userIdToDelete) {
            const url = '{{ route('account.delete', 'ID') }}';
            const newUrl = url.replace('ID', userIdToDelete);

            $.ajax({
                url: newUrl,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status) {
                        window.location.href = '{{ route('account.index') }}';
                    } else {
                        alert('Failed to delete user');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX request failed:', status, error);
                    alert('Failed to delete user');
                }
            });

            $('#modal-notification').modal('hide');
        } else {
            console.warn('No userIdToDelete set.');
        }
    }

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
