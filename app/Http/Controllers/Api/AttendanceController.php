<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
class AttendanceController extends Controller{
    public function AttendanceController (Request $request) {
        $userName = $request->input('user_name');

        if (!$userName) {
            return response()->json(['error' => 'Missing user_name'], 400);
        }

        $user = User::where('name', $userName)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $today = Carbon::today();
        $lastAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->orderBy('date', 'desc')
            ->first();

        // Xác định type
        $type = 'check in';
        if ($lastAttendance && $lastAttendance->type === 'check in') {
            $type = 'check out';
        }

        // Nếu đã có check_out trong ngày thì bỏ qua
        if ($lastAttendance && $lastAttendance->type === 'check out') {
            return response()->json(['message' => 'Already checked out today']);
        }

        Attendance::create([
            'user_id' => $user->id,
            'date' => now(),
            'type' => $type,
            'status' => 'success',
            'note' => 'Face recognition auto',
        ]);

        return response()->json([
            'message' => "Recorded {$type} for {$userName}",
            'type' => $type
        ]);
    }
}