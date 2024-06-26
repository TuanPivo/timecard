@extends('layout.index')
@section('content')
<div class="hold-transition login-page">
     <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a class="h3">Confirm Password</a>
            </div>
            <div class="card-body">
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                <form action="{{ route('loginPost') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="mb-2">Password <span style="color:#FF0000">*</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter Password">
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="justify-content-between d-flex">
                        <button class="btn btn-primary mt-2" type="submit">Confirm</button>
                        <a href="#" class="mt-3">Forgot Password?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
