<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\AttendanceRequest;
use App\Models\Attendance;
use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;


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

    // lấy danh dách chấm công
    public function getDataAttendance(){
        
        $attendances = [];

        // Fetch attendance data if user is authenticated
        if (Auth::check()) {
            $user = Auth::user();

            $attendances = Attendance::where('user_id', $user->id)
                ->whereIn('status', ['success', 'approve', 'pending', 'reject'])
                ->select('type', 'date', 'status')
                ->orderBy('date', 'desc')
                ->get()
                ->groupBy(function ($date) {
                    return Carbon::parse($date->date)->format('Y-m-d');
                })
                ->map(function ($dayGroup) {
                    return $dayGroup->unique('type');
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
        }

        // Always fetch holidays
        $holidays = Holiday::select('title', 'start','color')->get()->toArray();
        $combinedEvents = collect($attendances)->merge($holidays);

        return response()->json($combinedEvents);
    }


    public function sendRequest(Request $request){
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401); // Trả về lỗi 401 nếu người dùng chưa đăng nhập
        }

        $validator = Validator::make($request->all(), [

            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
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
            return redirect()->route('home')->with('error', "You are not login");
        }
        if (Auth::user()->role !== 0) {
            return redirect()->route('home')->with('warning', 'You cannot access this website');
        }
        $data = Attendance::with('user')
        ->where('status', 'pending')
        ->orderBy('date','desc')
        ->get();
        return view('pages.list_request', compact(['data']));
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

    public function showRequestUser()
    {
        if (!Auth::check()) {
            return redirect()->route('home')->with('error', "You are not login");
        }

        $user = Auth::user();
        $data = Attendance::with('user')
            ->where('user_id', $user->id) // Only get requests for the logged-in user
            ->where('status', 'pending')
            ->orderBy('date', 'desc')
            ->get();
    

        return view('pages.list_request_user', compact(['data', 'user']));
    }

    public function editRequestUser(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|string',
            'date' => 'required|date',
        ]);

        $attendance = Attendance::find($id);
        if ($attendance) {
            $attendance->type = $request->input('type');
            $attendance->date = $request->input('date');
            $attendance->save();

            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Request not found.']);
        }
    }

    public function deleteRequestUser($id)
    {
        if (!Auth::check()) {
            return redirect()->route('home')->with('error', "You are not logged in");
        }

        $user = Auth::user();
        $attendance = Attendance::find($id);

        if ($attendance && $attendance->user_id == $user->id) {
            $attendance->delete();
            return redirect()->back()->with('success', "Request deleted successfully");
        }

        return redirect()->route('your-route-name')->with('error', "You cannot delete this request");
    }




}
