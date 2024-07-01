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
        @if ($holidays->isEmpty())
            <p>Không có ngày nghỉ nào.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($holidays as $holiday)
                        <tr>
                            <td>{{ $holiday->title }}</td>
                            <td>{{ $holiday->start }}</td>
                            <td>
                                <button class="btn btn-warning me-2" data-bs-toggle="modal" data-bs-target="#editHolidayModal" onclick="editHoliday({{ $holiday->id }})">Edit</button>
                                <a href="{{ route('holiday.delete', $holiday->id) }}" class="btn btn-danger me-2">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
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
                <form action={{ route('holiday.store')}} method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="holidayTitle" class="form-label">Title</label>
                        <input type="text" class="form-control" id="holidayTitle" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="holidayDate" class="form-label">Date</label>
                        <input type="date" class="form-control" id="holidayDate" name="start" required>
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
            <label for="editHolidayDate" class="form-label">Date</label>
            <input type="date" class="form-control" id="editHolidayDate" name="start" required>
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
    $.get('/admin/holiday/edit/' + id, function (holiday) {
      $('#editHolidayTitle').val(holiday.title);
      $('#editHolidayDate').val(holiday.start);
      $('#editHolidayForm').attr('action', '/admin/holiday/update/' + holiday.id);
      $('#editHolidayModal').modal('show');
    });
  }
</script>
@endsection