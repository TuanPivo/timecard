@extends('layout.index')

@section('content')
    @include('layout.message')

    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h5 class="fw-bold mb-3">Lists User</h5>
                </div>
                <div class="ms-md-auto py-2 py-md-0">
                    <a href="{{ route('account.create') }}" class="btn btn-primary">Add User</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table id="basic-datatables" class="table table-head-bg-info text-center">
                <thead>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role Name</th>
                    <th>Joining Date</th>
                    <th>Monthly Attendance</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @if ($users->isNotEmpty())
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    {{ $user->role == 1 ? 'User' : ($user->role == 0 ? 'Admin' : '') }}</td>
                                <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('account.monthly', $user->id) }}">
                                        View Report
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('account.edit', $user->id) }}">
                                        <i class="fas fa-pen"></i>
                                    </a>

                                    <a href="#" type="button" class="text-danger w-4 h-4 mr-1"
                                        data-bs-toggle="modal" data-bs-target="#modal-notification"
                                        onclick="setDeleteUserId({{ $user->id }})">
                                        <i class="fas fa-trash"></i>
                                    </a>
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
    <div class="card-footer">
        <div class="modal fade" id="modal-notification" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
            <div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title fw-bold" id="modal-title-notification">
                            Confirm user deletion
                        </h6>
                    </div>
                    <div class="modal-body">
                        <div class="py-1 text-center">
                            <h6 class="text-danger">Are you sure you want to delete this account?</h6>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="confirmDelete()" style="font-size: 12px">Delete</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" style="font-size: 12px">
                            Cancle
                        </button>
                    </div>
                </div>
            </div>
            <meta name="csrf-token" content="{{ csrf_token() }}">
        </div>
    </div>
@endsection

@section('customJs')
    <script>
        let userIdToDelete = null;

        // Function to set userIdToDelete when delete button is clicked
        function setDeleteUserId(id) {
            userIdToDelete = id;
        }

        // Function to confirm deletion and send AJAX request
        function confirmDelete() {
            if (userIdToDelete) {
                const url = '{{ route('account.delete', 'ID') }}';
                const newUrl = url.replace('ID', userIdToDelete);

                $.ajax({
                    url: newUrl,
                    type: 'DELETE',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status) {
                            window.location.href = '{{ route('account.index') }}';
                        } else {
                            alert('Failed to delete user');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX request failed:', status, error);
                        alert('Failed to delete user');
                    }
                });

                $('#modal-notification').modal('hide');
            } else {
                console.warn('No userIdToDelete set.');
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
