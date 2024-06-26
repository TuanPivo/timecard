<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Attendance {{ now()->format('F Y') }}</title>
</head>
<body>

    <table>
        <thead>
            <tr class="header-row">
                <th class="text-center">Name</th>
                @for ($i = 1; $i <= now()->endOfMonth()->day; $i++)
                    <th class="text-center">{{ $i }}</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">{{ $user->name }}</td>
                @for ($i = 1; $i <= now()->endOfMonth()->day; $i++)
                    @php
                        $date = Carbon\Carbon::createFromDate(now()->year, now()->month, $i);
                        $isWeekend = $date->isWeekend();
                    @endphp
                    <td class="text-center {{ $isWeekend ? 'weekend' : '' }}">
                        @if (isset($monthlyAttendance[$i]))
                            @if (isset($monthlyAttendance[$i]['check_in']))
                                <div>Checkin {{ $monthlyAttendance[$i]['check_in']['date'] }}</div>
                            @endif
                            <br>
                            @if (isset($monthlyAttendance[$i]['check_out']))
                                <div>Checkout {{ $monthlyAttendance[$i]['check_out']['date'] }}</div>
                            @endif
                        @endif
                    </td>
                @endfor
            </tr>
        </tbody>
    </table>

</body>
</html>