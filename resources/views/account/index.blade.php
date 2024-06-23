@extends('layout.index')

@section('content')
    @include('layout.message')

    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">List of all users</h3>
                </div>
                <div class="ms-md-auto py-2 py-md-0">
                    <a href="{{ route('account.create') }}" class="btn btn-primary btn-round">Add User</a>
                </div>
            </div>
        </div>
        {{-- <form action="" method="get">
        <div class="card-header">
            <div class="card-tools">
                <div class="input-group input-group" style="width: 100%;">
                    <input type="text" value="{{ Request::get('keyword') }}" name="keyword" class="form-control float-right" placeholder="Search">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                        <button type="button" onclick="window.location.href='{{ route('account.index') }}'" class="btn btn-default"> <i class="fas fa-trash"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="card-body">
        <table class="table table-head-bg-primary mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role Name</th>
                    <th>Joining Date</th>
                    <th>Show Attendance</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($users->isNotEmpty())
                    @foreach ($users as $user)
                        <tr>
                            <td class="text-center">{{ $user->id }}</td>
                            <td class="text-center">{{ $user->name }}</td>
                            <td class="text-center">{{ $user->email }}</td>
                            <td class="text-center">
                                {{ $user->role == 1 ? 'User' : ($user->role == 0 ? 'Admin' : '') }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($user->date)->format('d/m/Y') }}</td>
                            <td class="text-center">
                                <a href="{{ route('account.attendance', $user->id) }}">
                                    View Attendance
                                </a>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('account.edit', $user->id) }}">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <a href="#" onclick="deleteUser({{ $user->id }})"
                                    class="text-danger w-4 h-4 mr-1">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5">Records Not Found</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="bd-example">
        {{ $users->links() }}
    </div> --}}

        <div class="card-body">
            <div class="table-responsive">
                <table id="basic-datatables" class="display table table-striped table-hover">
                    <thead>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role Name</th>
                        <th>Joining Date</th>
                        <th>Show Attendance</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @if ($users->isNotEmpty())
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        {{ $user->role == 1 ? 'User' : ($user->role == 0 ? 'Admin' : '') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('account.attendance', $user->id) }}">
                                            View Attendance
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
                                <td colspan="5">Records Not Found</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Modal notification -->
        <div class="modal fade" id="modal-notification" tabindex="-1" role="dialog" aria-labelledby="modal-notification"
            aria-hidden="true">
            <div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title fw-bold text-center" id="modal-title-notification">
                            Confirm User Deletion
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="py-3 text-center">
                            <i class="fas fa-bell" style="font-size: 24px"></i>
                            <h4 class="text-danger mt-4">Are you sure you want to delete this account?</h4>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="confirmDelete()">Yes!</button>

                        <button type="button" class="btn btn-link text-primary text-decoration-none" data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </div>

@endsection

@section('customJs')
    <script>
        let userIdToDelete = null;
        function setDeleteUserId(id) {
            userIdToDelete = id;
        }
        function confirmDelete() {
            if (userIdToDelete) {
                var url = '{{ route('account.delete', 'ID') }}';
                var newUrl = url.replace('ID', userIdToDelete);

                $.ajax({
                    url: newUrl,
                    type: "delete",
                    data: {},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response["status"]) {
                            window.location.href = '{{ route('account.index') }}';
                        } else {
                            alert('Failed to delete user');
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });

                $('#modal-notification').modal('hide');
            }
        }

        $(document).ready(function() {
            $('#basic-datatables').DataTable({});

            $('#multi-filter-select').DataTable({
                "pageLength": 5,
                initComplete: function() {
                    this.api().columns().every(function() {
                        var column = this;
                        var select = $(
                                '<select class="form-select"><option value=""></option></select>'
                                )
                            .appendTo($(column.footer()).empty())
                            .on('change', function() {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                            });

                        column.data().unique().sort().each(function(d, j) {
                            select.append('<option value="' + d + '">' + d +
                                '</option>')
                        });
                    });
                }
            });
        });
    </script>
@endsection
