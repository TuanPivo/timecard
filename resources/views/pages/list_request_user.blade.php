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
                                    <button type="button" class="btn btn-primary edit-request-btn" data-id="{{ $attendances->id }}"
                                        data-type="{{ $attendances->type }}"
                                        data-date="{{ $attendances->date }}">Edit</button>
                                    <form action="{{ route('delete.request', $attendances->id) }}" method="POST"
                                        style="display: inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
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

    {{-- modal edit request --}}
    <div class="modal fade" id="editAttendanceModal" tabindex="-1" role="dialog"
        aria-labelledby="editAttendanceModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAttendanceModalLabel">Edit Request</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editAttendanceForm">
                        @csrf
                        <input type="hidden" id="editRequestId" name="id">
                        <div class="form-group">
                            <label for="editAttendanceType">Type:</label>
                            <select class="form-control" id="editAttendanceType" name="type">
                                <option value="check in">Check In</option>
                                <option value="check out">Check Out</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editAttendanceDate">Date:</label>
                            <div class="input-group date">
                                <input type="datetime-local" class="form-control" id="editAttendanceDate" name="date">
                            </div>
                            <div class="text-danger" id="editErrorDate"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="updateAttendanceBtn" class="btn btn-primary">Update</button>
                </div>
            </div>
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
<script>
    // Xử lý khi click vào nút "Edit"
    $('.edit-request-btn').click(function() {
        var id = $(this).data('id');
        var type = $(this).data('type');
        var date = $(this).data('date');

        // Đổ dữ liệu vào modal chỉnh sửa
        $('#editRequestId').val(id);
        $('#editAttendanceType').val(type);
        $('#editAttendanceDate').val(date);

        $('#editAttendanceModal').modal('show');
    });

    // Xử lý khi click vào nút "Update"
    $('#updateAttendanceBtn').click(function() {
        var id = $('#editRequestId').val();
        var type = $('#editAttendanceType').val();
        var date = $('#editAttendanceDate').val();

        // Gửi request AJAX để cập nhật request
        $.ajax({
            url: '/edit/' + id,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                id: id,
                type: type,
                date: date
            },
            success: function(response) {
                $('#editAttendanceModal').modal('hide');
                alert('Request updated successfully');
                window.location.reload(true);
            },
            error: function(xhr) {
                if (xhr.status == 422) {
                    var errors = xhr.responseJSON.errors;
                    // Hiển thị lỗi vào div có id là editErrorDate
                    $('#editErrorDate').empty();
                    $.each(errors, function(key, value) {
                        $('#editErrorDate').append('<p>' + value[0] + '</p>');
                    });
                } else {
                    alert('Failed to update request.');
                }
            }
        });
    });
</script>


@endsection
