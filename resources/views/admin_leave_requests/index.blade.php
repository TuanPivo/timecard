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
                    <th>User</th>
                    <th>Reason</th>
                    <th>Leave date</th>
                    <th>Status</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach($leaveRequests as $request)
                        <tr>
                            <td>{{ $request->user->name }}</td>
                            <td>{{ $request->reason }}</td>
                            <td>{{ $request->leave_date }}</td>
                            <td>{{ $request->status }}</td>
                            <td>
                                <form action="{{ route('admin_leave_requests.updateStatus', $request) }}" method="POST" id="status-form-{{ $request->id }}">
                                    @csrf
                                    <input type="hidden" name="status" id="status-input-{{ $request->id }}" value="{{ $request->status }}">
                                    <button type="button" class="btn btn-primary" id="approve-button-{{ $request->id }}" onclick="submitForm('approved', {{ $request->id }})">Approve</button>
                                    <button type="button" class="btn btn-danger" id="reject-button-{{ $request->id }}" onclick="submitForm('rejected', {{ $request->id }})">Reject</button>
                                </form>
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

        function submitForm(status, requestId) {
            // Set the status input value
            document.getElementById('status-input-' + requestId).value = status;
            // Submit the form
            document.getElementById('status-form-' + requestId).submit();
        }
    </script>
@endsection
{{-- @extends('layout.index')

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
                    <th>User</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Action</th>
                </thead>
                <tbody id="leave-requests-tbody">
                    @foreach($leaveRequests as $request)
                        <tr id="leave-request-{{ $request->id }}">
                            <td>{{ $request->user->name }}</td>
                            <td>{{ $request->reason }}</td>
                            <td>{{ $request->status }}</td>
                            <td>
                                <form action="{{ route('admin_leave_requests.updateStatus', $request) }}" method="POST" id="status-form-{{ $request->id }}">
                                    @csrf
                                    <input type="hidden" name="status" id="status-input-{{ $request->id }}" value="{{ $request->status }}">
                                    <button type="button" class="btn btn-primary" id="approve-button-{{ $request->id }}" onclick="submitForm('approved', {{ $request->id }})">Approve</button>
                                    <button type="button" class="btn btn-danger" id="reject-button-{{ $request->id }}" onclick="submitForm('rejected', {{ $request->id }})">Reject</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('customJs')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#basic-datatables').DataTable({});

            $('#multi-filter-select').DataTable({
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

        function submitForm(status, requestId) {
            // Set the status input value
            document.getElementById('status-input-' + requestId).value = status;

            // Create form data
            var formData = new FormData(document.getElementById('status-form-' + requestId));

            // Send AJAX request
            $.ajax({
                url: '{{ route('admin_leave_requests.updateStatus', '') }}/' + requestId,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    // Remove the request row from the table
                    $('#leave-request-' + requestId).remove();
                    alert(response.message);
                },
                error: function(xhr) {
                    console.error('Error: ' + xhr.responseText);
                    alert('Error: ' + xhr.responseText);
                }
            });
        }
    </script>
@endsection --}}

