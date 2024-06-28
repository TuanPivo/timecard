@extends('layout.index')
@section('content')
    <div class="card-header">
        <h3>Search By Month/Year</h3>
        <form action="{{ route('account.monthly', $user->id) }}" method="GET" class="form-inline">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="month">Month:</label>
                                <select name="month" id="month" class="form-control mr-2">
                                    @foreach (range(1, 12) as $m)
                                        <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                                            {{ Carbon\Carbon::create(null, $m, 1)->format('F') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="year">Year:</label>
                                <input type="number" id="year" name="year" value="{{ $selectedYear }}" class="form-control mr-2">
                            </div>
                        </div>
                        <div class="pb-5">
                            <button type="submit" class="btn btn-primary">Search</button>
                            <a href="{{ route('account.monthly', $user->id) }}" class="btn btn-dark ml-3">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Monthly Attendance Report of: {{ $user->name }}</h3>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <a href="{{ route('account.index') }}" class="btn btn-black btn-round">Back</a>
                <a href="{{ route('account.exportMonthly', [$user->id, 'month' => $selectedMonth, 'year' => $selectedYear]) }}" class="btn btn-success btn-round">Export Excel</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center bg-info">Name</th>
                        @for ($i = 1; $i <= Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1)->endOfMonth()->day; $i++)
                            <th class="text-center bg-info">{{ $i }}</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">{{ $user->name }}</td>
                        @for ($i = 1; $i <= Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1)->endOfMonth()->day; $i++)
                            @php
                                $date = Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, $i);
                                $isWeekend = $date->isWeekend();
                            @endphp
                            <td class="text-center {{ $isWeekend ? 'bg-danger-gradient' : '' }}">
                                @if (isset($monthlyAttendance[$i]))
                                    @if (isset($monthlyAttendance[$i]['check_in']))
                                        <div class="
                                            {{ $monthlyAttendance[$i]['check_in']['status'] == 'pending' ? 'bg-warning' : '' }}
                                            {{ $monthlyAttendance[$i]['check_in']['status'] == 'reject' ? 'bg-danger' : '' }}
                                            {{ $monthlyAttendance[$i]['check_in']['status'] == 'success' ? 'bg-success' : '' }}
                                        ">
                                            Checkin {{ $monthlyAttendance[$i]['check_in']['date'] }}
                                        </div>
                                    @endif
                                    <br>
                                    @if (isset($monthlyAttendance[$i]['check_out']))
                                        <div class="
                                            {{ $monthlyAttendance[$i]['check_out']['status'] == 'pending' ? 'bg-warning' : '' }}
                                            {{ $monthlyAttendance[$i]['check_out']['status'] == 'reject' ? 'bg-danger' : '' }}
                                            {{ $monthlyAttendance[$i]['check_out']['status'] == 'success' ? 'bg-success' : '' }}
                                        ">
                                            Checkout {{ $monthlyAttendance[$i]['check_out']['date'] }}
                                        </div>
                                    @endif
                                @endif
                            </td>
                        @endfor
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <hr>
    <div class="card-footer">
        <strong>Corresponding color and status</strong>
        <table class="table-bordered">
            <thead class="text-center">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Color</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <tr>
                    <td>1</td>
                    <td class="bg-danger-gradient"></td>
                    <td>Holidays, Saturday, Sunday</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td class="bg-warning"></td>
                    <td>Pending</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td class="bg-danger"></td>
                    <td>Reject</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td class="bg-success"></td>
                    <td>Success</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
