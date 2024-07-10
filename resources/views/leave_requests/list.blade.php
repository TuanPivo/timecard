@extends('layout.index')

@section('content')
    @include('layout.message')
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h5 class="fw-bold mb-3">Lists Leave Request</h5>
                </div>
                <div class="ms-md-auto py-2 py-md-0">
                    <a href="{{ route('leave_requests.index') }}" class="btn btn-primary">Back</a>
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
                                   
                                    <a href="#" type="button" class="text-danger w-4 h-4 mr-1"
                                        data-bs-toggle="modal" data-bs-target="#modal-notification"
                                        onclick="setDeleteLeaveId({{ $request->id }})">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <div class="modal fade" id="modal-notification" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
                <div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title fw-bold" id="modal-title-notification">
                                Confirm request deletion
                            </h6>
                        </div>
                        <div class="modal-body">
                            <div class="py-1 text-center">
                                <h6 class="text-danger">Are you sure you want to delete this leave request?</h6>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" onclick="confirmDelete()" style="font-size: 12px">Delete</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" style="font-size: 12px">Cancel</button>
                        </div>
                    </div>
                </div>
                <meta name="csrf-token" content="{{ csrf_token() }}">
            </div>
        </div>
    </div>
@endsection

@section('customJs')
    <script>
        $(document).ready(function() {
            $('#basic-datatables').DataTable({});

            $('#multi-filter-select').DataTable( {
                "pageLength": 5,
                initComplete: function () {
                    this.api().columns().every( function () {
                        var column = this;
                        var select = $('<select class="form-select"><option value=""></option></select>')
                        .appendTo( $(column.footer()).empty() )
                        .on( 'change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                                );

                            column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                        } );

                        column.data().unique().sort().each( function ( d, j ) {
                            select.append( '<option value="'+d+'">'+d+'</option>' )
                        } );
                    } );
                }
            });
        });

        let leaveIdToDelete = null;

        // Function to set leaveIdToDelete when delete button is clicked
        function setDeleteLeaveId(id) {
            leaveIdToDelete = id;
        }

        // Function to confirm deletion and send AJAX request
        function confirmDelete() {
            if (leaveIdToDelete) {
                const url = '{{ route('leave_requests.destroy', ':id') }}'.replace(':id', leaveIdToDelete);

                $.ajax({
                    url: url,
                    type: 'DELETE',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status) {
                            window.location.href = '{{ route('leave_requests.list') }}';
                        } else {
                            alert('Failed to delete leave');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX request failed:', status, error);
                        alert('Failed to delete leave');
                    }
                });

                $('#modal-notification').modal('hide');
            } else {
                console.warn('No leaveIdToDelete set.');
            }
        }
    </script>
@endsection
