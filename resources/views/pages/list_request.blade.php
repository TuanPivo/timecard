@extends('layout.index')

@section('content')
    <section class="content-header">					
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> List Request</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            @include('layout.message')
            <div class="card">
                <form action="" method="get">
                    <div class="card-header">
                        <div class="card-tools">
                            <div class="input-group input-group" style="width: 100%;">
                                <input type="text" value="{{ Request::get('keyword') }}" name="keyword"
                                    class="form-control float-right" placeholder="Search">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                    <button type="button" onclick="window.location.href='{{ url()->current() }}'"
                                        class="btn btn-default"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="card-body table-responsive p-0">
                    @if ($data->isNotEmpty())
                        @foreach ($data as $date => $attendances)
                            <h3 class="p-2">{{ $date }}</h3>
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
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

                <div class="card-footer clearfix">
                </div>
            </div>

        </div>
    </section>
@endsection
