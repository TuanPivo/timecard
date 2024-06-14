<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('calendar.index', [
            'users' => $users,
        ]);
    }

    public function getCalendar()
    {
        $user = Auth::user();

        $attendances = Attendance::where('user_id', $user->id)
            ->whereIn('status', ['success', 'approve', 'pending', 'reject']) // Chỉ lấy các sự kiện có status là success, approve hoặc pending
            ->select('type', 'date', 'status')
            ->orderBy('date', 'desc')
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->date)->format('Y-m-d');
            })
            ->map(function ($dayGroup) {
                return $dayGroup->unique('type'); // Lấy sự kiện gần nhất của mỗi loại trong ngày
            })
            ->flatten()
            ->map(function ($attendance) {
                $title = ucfirst($attendance->type);
                if ($attendance->status === 'pending') {
                    $title .= ' - ' . $attendance->status;
                }
                if ($attendance->status === 'reject') {
                    $title .= '-' . $attendance->status;
                }
                return [
                    'title' => $title,
                    'start' => Carbon::parse($attendance->date)->format('Y-m-d\TH:i:s'),
                    'status' => $attendance->status,
                ];
            });

        return response()->json($attendances);
    }
}
