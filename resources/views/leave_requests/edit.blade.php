@extends('layout.index')

@section('content')
    @include('layout.message')

    <!-- Modal for Leave Request Form -->
    <div class="modal fade" id="leaveRequestModal" tabindex="-1" aria-labelledby="leaveRequestModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="leaveRequestModalLabel">Leave Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="leaveRequestForm" action="{{ route('leave_requests.update', $leaveRequest->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="datetime-local" class="form-control" id="start_date" name="start_date" value="{{ old('start_date', $leaveRequest->start_date ? \Carbon\Carbon::parse($leaveRequest->start_date)->format('Y-m-d\TH:i') : '') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="datetime-local" class="form-control" id="end_date" name="end_date" value="{{ old('end_date', $leaveRequest->end_date ? \Carbon\Carbon::parse($leaveRequest->end_date)->format('Y-m-d\TH:i') : '') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason</label>
                            <textarea class="form-control" id="reason" name="reason" rows="3" required>{{ old('reason', $leaveRequest->reason) }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('customJs')
    <script>
        $(document).ready(function() {
            var leaveRequestModal = new bootstrap.Modal(document.getElementById('leaveRequestModal'));
            leaveRequestModal.show();
        });
    </script>
@endsection

