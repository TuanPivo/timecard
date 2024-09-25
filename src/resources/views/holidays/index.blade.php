@extends('layout.index')

@section('content')
    @include('layout.message')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">List holiday</h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addHolidayModal">
                Add holiday
            </button>
        </div>

        <div class="card-body">
            <table id="basic-datatables" class="table table-head-bg-info text-center">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Date start</th>
                        <th>Date end</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($holidays->isNotEmpty())
                        @foreach ($holidays as $holiday)
                            <tr>
                                <td>{{ $holiday->title }}</td>
                                <td>{{ $holiday->start }}</td>
                                <td>{{ $holiday->end }}</td>
                                <td>
                                    <button class="btn btn-primary me-2" data-bs-toggle="modal"
                                        data-bs-target="#editHolidayModal"
                                        onclick="editHoliday({{ $holiday->id }})">Edit</button>
                                    <a href="{{ route('holiday.delete', $holiday->id) }}"
                                        class="btn btn-danger me-2">Delete</a>
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

    <!-- Add Modal -->
    <div class="modal fade" id="addHolidayModal" tabindex="-1" aria-labelledby="addHolidayModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addHolidayModalLabel">Add Holiday</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action={{ route('holiday.store') }} method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="holidayTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="holidayTitle" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="holidayDate" class="form-label">Date start</label>
                            <input type="date" class="form-control" id="holidayDate" name="start" required>
                        </div>
                        <div class="mb-3">
                            <label for="holidayDate" class="form-label">Date end</label>
                            <input type="date" class="form-control" id="holidayDate" name="end" >
                        </div>
                        <div class="mb-3">
                            <label for="holidayColor" class="form-label">Color</label>
                            <input type="color" class="form-control" id="holidayColor" name="color" >
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Holiday Modal -->
    <div class="modal fade" id="editHolidayModal" tabindex="-1" aria-labelledby="editHolidayModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editHolidayModalLabel">Edit Holiday</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editHolidayForm" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="editHolidayTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="editHolidayTitle" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="editHolidayDate" class="form-label">Date start</label>
                            <input type="date" class="form-control" id="editHolidayDate" name="start" required>
                        </div>
                        <div class="mb-3">
                            <label for="editHolidayDateEnd" class="form-label">Date end</label>
                            <input type="date" class="form-control" id="editHolidayDateEnd" name="end">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function editHoliday(id) {
            $.get('/admin/holiday/edit/' + id, function(holiday) {
                $('#editHolidayTitle').val(holiday.title);
                $('#editHolidayDate').val(holiday.start);
                $('#editHolidayDateEnd').val(holiday.end);
                $('#editHolidayForm').attr('action', '/admin/holiday/update/' + holiday.id);
                $('#editHolidayModal').modal('show');
            });
        }
    </script>
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
