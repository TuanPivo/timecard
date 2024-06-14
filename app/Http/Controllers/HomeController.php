<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;


class HomeController extends Controller
{
    //
    public function index()
    {
        return view('pages.home');
    }

    public function attendance(Request $request){
        if(!Auth::check()){
            return redirect()->route('home')->with('error', "Bạn chưa đăng nhập");
        }
        $user = Auth::user();
        $type = $request->input('type');
        $attendance = [
            'user_id' => $user->id,
            'date' => now()->toDateTimeString(),
            'type' => $type,
            'status' => 'success',
        ];
        Attendance::create($attendance);
        return redirect()->route('home')->with('success', "thành công");
    }

    public function getDataAttendance()
{
    $user = Auth::user();

    $attendances = Attendance::where('user_id', $user->id)
        ->whereIn('status', ['success', 'approve', 'pending','reject']) // Chỉ lấy các sự kiện có status là success, approve hoặc pending
        ->select('type', 'date', 'status')
        ->get()
        ->map(function ($attendance) {
            $title = ucfirst($attendance->type);
            if ($attendance->status === 'pending') {
                $title .= ' - ' . $attendance->status;
            }
            if($attendance->status === 'reject'){
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

    public function sendRequest(Request $request){
        $user = Auth::user();
        $type = $request->input('type');
        $date = $request->input('date');

        switch ($type) {
            case 'check in':
                $date .= ' 08:00:00'; // Thêm giờ 08:00 nếu là check in
                break;
            case 'check out':
                $date .= ' 17:30:00'; // Thêm giờ 17:30 nếu là check in 17h30
                break;
            default:
                break;
        }

        $attendance = [
            'user_id' => $user->id,
            'date' => $date,
            'type' => $type,
            'status' => 'pending',
        ];
        Attendance::create($attendance);

        return response()->json(['success' => true]);
    }


}
