@extends('layout.index')

@section('content')
    @include('layout.message')
    {{-- <div class="card-header">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <h5 class="fw-bold mb-3">Create Leave Request</h5>
        </div>
    </div> --}}
    <div class="card-body page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <h5 class="fw-bold mb-3">Create Leave Request</h5>
        </div>
        <form action="{{ route('leave_requests.store') }}" method="POST">
            @csrf
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="start_date">Start Date</label>
                    <input type="date" value="{{ old('start_date') }}" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="end_date">End Date</label>
                    <input type="date" value="{{ old('end_date') }}" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="reason">Reason</label>
                    <textarea class="form-control" name="reason" id="reason"></textarea>
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="#" class="btn btn-danger">Cancel</a>
                </div>
            </div>
        </form>
    </div>

@endsection

@section('customJs')
    <script>

    </script>
@endsection
