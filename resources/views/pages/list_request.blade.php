@extends('layout.index')

@section('content')
    @include('layout.message')
    <div class="card">
        <div class="card-header">
            <h5 class="fw-bold mb-3">Lists Request</h5>
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
                                        <a class="btn btn-danger" href="{{ route('reject', $attendance->id) }}">Reject</a>
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
@endsection

@section('customJs')
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
