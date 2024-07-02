@extends('layout.index')

@section('content')
    @include('layout.message')
    <div class="card">
        <div class="card-header">
            <h5 class="fw-bold mb-3">My request list</h5>
        </div>
        <div class="card-body">							
            @if ($data->isNotEmpty())
                <table id="basic-datatables" class="table table-head-bg-info text-center">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $attendances)
                            <tr>
                                <td>{{ $attendances->user->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($attendances->date)->format('d-m-Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($attendances->date)->format('H:i') }}</td>
                                <td>{{ $attendances->type }}</td>
                                <td>{{ $attendances->status }}</td>
                                <td>
                                    <form action="{{ route('delete.request', $attendances->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete Request</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <tr>
                    <td colspan="6">Records Not Found</td>
                </tr>
            @endif
        </div>
    </div>
@endsection

@section('customJs')
    <script>
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
