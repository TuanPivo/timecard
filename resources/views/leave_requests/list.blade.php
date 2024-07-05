@extends('layout.index')

@section('content')
    @include('layout.message')

    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h5 class="fw-bold mb-3">Lists Leave Request</h5>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table id="basic-datatables" class="table table-head-bg-info text-center">
                <thead>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach($leaveRequests as $request)
                        <tr>
                            <td>{{ $request->start_date }}</td>
                            <td>{{ $request->end_date }}</td>
                            <td>{{ $request->reason }}</td>
                            <td>{{ $request->status }}</td>
                            <td>
                                @if ($request->status === 'pending')
                                    <a href="{{ route('leave_requests.edit', $request->id) }}">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <a href="#" onclick="deleteLeaveRequest({{ $request->id }})" class="text-danger w-4 h-4 mr-1">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('customJs')
    <script>
        $(document).ready(function() {
            $('#basic-datatables').DataTable({
                "pageLength": 5,
                initComplete: function () {
                    this.api().columns().every(function () {
                        var column = this;
                        var select = $('<select class="form-select"><option value=""></option></select>')
                            .appendTo($(column.footer()).empty())
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                            });

                        column.data().unique().sort().each(function (d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>')
                        });
                    });
                }
            });
        });

        function deleteLeaveRequest(id) {
            if (confirm('Are you sure you want to delete this leave request?')) {
                fetch('{{ url('leave-requests') }}/' + id, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        alert('Leave request deleted successfully.');
                        // Reload the page or update the table
                        location.reload(); // You can also update the table without reloading
                    } else {
                        throw new Error('Failed to delete leave request.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to delete leave request.');
                });
            }
        }
    </script>
@endsection

