@extends('layout.index')

@section('content')
@include('layout.message')
<div class="card">
    <div class="card-header">
      <h5 class="mb-0">Sửa Ngày Nghỉ</h5>
    </div>
    <div class="card-body">
      <form action="{{ route('holiday.update', $holiday->id) }}" method="POST">
        @csrf
        <div class="mb-3">
          <label for="holidayTitle" class="form-label">Title</label>
          <input type="text" class="form-control" id="holidayTitle" name="title" value="{{ $holiday->title }}" required>
        </div>
        <div class="mb-3">
          <label for="holidayDate" class="form-label">Date</label>
          <input type="date" class="form-control" id="holidayDate" name="start" value="{{ $holiday->start }}" required>
        </div>
        <div class="modal-footer">
        
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>

@endsection