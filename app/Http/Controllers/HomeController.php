<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\AttendanceRequest;
use App\Models\Attendance;
use Carbon\Carbon;


class HomeController extends Controller
{
    //
    public function index()
    {
        $user = Auth::user();
        return view('pages.home',compact(['user']));
    }

    public function attendance(Request $request){
        if(!Auth::check()){
            return redirect()->route('home')->with('error', "You are not logged in");
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
        return redirect()->route('home')->with('success', "Success");
    }

    public function getDataAttendance(){

        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $user = Auth::user();

        $attendances = Attendance::where('user_id', $user->id)
            ->whereIn('status', ['success', 'approve', 'pending','reject']) // Chỉ lấy các sự kiện có status là success, approve hoặc pending
            ->select('type', 'date', 'status')
            ->orderBy('date','desc')
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
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401); // Trả về lỗi 401 nếu người dùng chưa đăng nhập
        }
        $type = $request->input('type');
        $date = $request->input('date');

        $attendance = [
            'user_id' => $user->id,
            'date' => $date,
            'type' => $type,
            'status' => 'pending',
        ];
        Attendance::create($attendance);

        return response()->json(['success' => true]);
    }

    public function showRequest(){
        if (!Auth::check()) {
            return redirect()->route('home')->with('error', "Bạn chưa đăng nhập");
        }
        $user = Auth::user();
        $data = Attendance::with('user')
        ->where('status', 'pending')
        ->orderBy('date','desc')
        ->get()
        ->groupBy(function($date) {
            return \Carbon\Carbon::parse($date->date)->format('d-m-Y'); // grouping by dates
        });
        return view('pages.list_request', compact(['data','user']));
    }

    public function reject($id)
    {
        $attendance = Attendance::find($id);
        if ($attendance) {
            $attendance->status = 'reject';
            $attendance->save();
            return redirect()->back()->with('success', 'Request has been rejected.');
        }
        return redirect()->back()->with('error', 'Request not found.');
    }
    public function approve($id)
    {
        $attendance = Attendance::find($id);
        if ($attendance) {
            $attendance->status = 'success';
            $attendance->save();
            return redirect()->back()->with('success', 'Request has been approve.');
        }
        return redirect()->back()->with('error', 'Request not found.');
    }


}
