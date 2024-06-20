{{-- @if (Session::has('error'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
        <h5>Error!</h5>{{ Session::get('error') }}
    </div>
@endif

@if (Session::has('success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
        <h5>Success!</h5>{{ Session::get('success') }}
    </div>
@endif --}}

@if (Session::has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!!</strong> {{ Session::get('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (Session::has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!!</strong> {{ Session::get('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
