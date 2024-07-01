@extends('layout.index')

@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> List Request of user</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            @include('layout.message')
            <div class="card">
                <div class="card-body table-responsive p-0">
                    @if ($data->isNotEmpty())
                        <table class="table table-hover text-nowrap">
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
                            <td colspan="7">Records Not Found</td>
                        </tr>
                    @endif
                </div>
            </div>
        </div>
    </section>
    {{-- <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">List of requests from the User</h3>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                @if ($data->isNotEmpty())
                    @foreach ($data as $date => $attendances)
                        <h3 class="p-2">{{ $date }}</h3>
                        <table id="basic-datatables" class="table table-head-bg-info text-center">
                            <thead>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </thead>
                            <tbody>
                                @foreach ($attendances as $attendance)
                                    <tr>
                                        <td>{{ $attendance->id }}</td>
                                        <td>{{ $attendance->user->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d-m-Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($attendance->date)->format('H:i') }}</td>
                                        <td>{{ $attendance->type }}</td>
                                        <td>{{ $attendance->status }}</td>
                                        <td>
                                            <a class="btn btn-danger"
                                                href="{{ route('reject', $attendance->id) }}">Reject</a>
                                            <a href="{{ route('approve', $attendance->id) }}"
                                                class="btn btn-primary">Approve</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7">Records Not Found</td>
                    </tr>
                @endif
            </div>
        </div>
    </div> --}}
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
