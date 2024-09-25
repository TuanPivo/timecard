@extends('layout.index')

@section('content')
    @include('layout.message')
    <div class="card">
        <div class="card-header">
            <h5 class="fw-bold mb-3">Approve or Reject Request (check-in/check-out)</h5>
        </div>
        <div class="card-body">							
            <table id="basic-datatables" class="table table-head-bg-info text-center">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($data->isNotEmpty())
                        @foreach ($data as $attendance)
                            <tr>
                                <td>{{ $attendance->user->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d-m-Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($attendance->date)->format('H:i') }}</td>
                                <td>{{ $attendance->type }}</td>
                                <td>{{ $attendance->status }}</td>
                                <td>
                                    @if (auth()->user()->role == 1 || $attendance->user_id != auth()->id())
                                       <a class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal" data-id="{{ $attendance->id }}">Reject</a>
                                        <a href="{{ route('approve', $attendance->id) }}" class="btn btn-primary">Approve</a>
                                    @else
                                        <!-- Disable buttons for user's own requests -->
                                        <button class="btn btn-danger" onclick="return handleAction('reject', {{ $attendance->id }});">Reject</button>
                                        <button class="btn btn-primary" onclick="return handleAction('approve', {{ $attendance->id }});">Approve</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6">Records Not Found</td>
                        </tr>
                    @endif
                </tbody>
            </table>										
        </div>
    </div>

    <!-- Reject Modal -->
<!-- Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Reject Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rejectReason">Reason for rejection:</label>
                        <textarea class="form-control" id="rejectReason" name="note" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>




@endsection

@section('customJs')
   <script>
        document.addEventListener('DOMContentLoaded', function () {
            var rejectModal = document.getElementById('rejectModal');
            rejectModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var attendanceId = button.getAttribute('data-id');
                var form = document.getElementById('rejectForm');
                form.setAttribute('action', '{{ url("/reject") }}/' + attendanceId);
            });
        });
    </script>


    <script>
         function handleAction(action, id) {
            if (action === 'reject' || action === 'approve') {
                alert('You cannot confirm your request yourself.');
                return false;
            }
        }

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
    </script>
@endsection
