@extends('layout.index')

@section('content')
    @include('layout.message')
    <div class="card-header">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <h5>Edit Request</h5>
        </div>
    </div>
    <div class="card-body page-inner">
        <form action="#" method="POST" id="updateForm" name="updateForm">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="datetime-local" class="form-control" id="start_date" name="start_date" value="{{ old('start_date', $leaveRequest->start_date ? \Carbon\Carbon::parse($leaveRequest->start_date)->format('Y-m-d\TH:i') : '') }}">
                        <span></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="datetime-local" class="form-control" id="end_date" name="end_date" value="{{ old('end_date', $leaveRequest->end_date ? \Carbon\Carbon::parse($leaveRequest->end_date)->format('Y-m-d\TH:i') : '') }}">
                        <span></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason</label>
                        <textarea class="form-control" id="reason" name="reason" rows="3">{{ old('reason', $leaveRequest->reason) }}</textarea>
                        <span></span>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('leave_requests.list') }}" class="btn btn-danger">Cancel</a>
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
                url: '{{ route('leave_requests.update', $leaveRequest->id) }}',
                type: 'PUT',
                data: element.serialize(),
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false);
                    if (response["status"] === true) {
                        window.location.href = "{{ route('leave_requests.list') }}";
                        $("#start_date").removeClass('is-invalid').siblings('span').empty();
                        $("#end_date").removeClass('is-invalid').siblings('span').empty();
                        $("#reason").removeClass('is-invalid').siblings('span').empty();
                    } else {
                        if (response['notFound'] === true) {
                            window.location.href = "{{ route('leave_requests.list') }}";
                            return;
                        }
                        var errors = response['errors'];
    
                        if (errors['start_date']) {
                            $("#start_date").addClass('is-invalid').siblings('span').addClass('invalid-feedback').html(errors['start_date']);
                        } else {
                            $("#start_date").removeClass('is-invalid').siblings('span').empty();
                        }
                        if (errors['end_date']) {
                            $("#end_date").addClass('is-invalid').siblings('span').addClass('invalid-feedback').html(errors['end_date']);
                        } else {
                            $("#end_date").removeClass('is-invalid').siblings('span').empty();
                        }
                        if (errors['reason']) {
                            $("#reason").addClass('is-invalid').siblings('span').addClass('invalid-feedback').html(errors['reason']);
                        } else {
                            $("#reason").removeClass('is-invalid').siblings('span').empty();
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

