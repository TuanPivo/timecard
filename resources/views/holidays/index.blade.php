@extends('layout.index')

@section('content')
    @include('layout.message')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Danh sách ngày nghỉ</h5>
            <a href="" class="btn btn-primary">Thêm ngày lễ</a>
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
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($holidays as $holiday)
                            <tr>
                                <td>{{ $holiday->title }}</td>
                                <td>{{ $holiday->start }}</td>
                                <td>
                                    <a href="" class="btn btn-danger me-2">Sửa</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
    
@endsection
