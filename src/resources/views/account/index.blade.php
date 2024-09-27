@extends('layout.index')

@section('content')
    @include('layout.message')

    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h5 class="fw-bold mb-3">Lists User</h5>
                </div>
                <div class="ms-md-auto py-2 py-md-0">
                    <a href="{{ route('account.create') }}" class="btn btn-primary">Create</a>
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
                </thead>
                <tbody>
                    @if ($users->isNotEmpty())
                        @foreach ($users as $user)
                            <tr class="clickable table-link" data-href="{{ route('account.edit', $user->id) }}">
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role == 1 ? 'User' : ($user->role == 0 ? 'Admin' : '') }}</td>
                                <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('account.monthly', $user->id) }}">
                                        View Report
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
</script>
@endsection
