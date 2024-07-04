<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    // public function index()
    // {
    //     $leaveRequests = LeaveRequest::where('user_id', auth()->id())->get();
    //     return view('leave_requests.index', compact('leaveRequests'));
    // }

    // public function create()
    // {
    //     return view('leave_requests.create');
    // }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'start_date' => 'required|date',
    //         'end_date' => 'required|date|after_or_equal:start_date',
    //         'reason' => 'nullable|string',
    //     ]);

    //     LeaveRequest::create([
    //         'user_id' => auth()->id(),
    //         'start_date' => $request->start_date,
    //         'end_date' => $request->end_date,
    //         'reason' => $request->reason,
    //         'status' => 'pending',
    //     ]);

    //     return redirect()->route('leave_requests.index')->with('success', 'Leave request submitted successfully.');
    // }
    
    public function index()
    {
        $leaveRequests = LeaveRequest::all();

        // Chuyển đổi dữ liệu thành định dạng mà FullCalendar có thể sử dụng
        $events = $leaveRequests->map(function ($leaveRequest) {
            return [
                'id' => $leaveRequest->id,
                'title' => $leaveRequest->reason,
                'start' => $leaveRequest->leave_date,
                'status' => $leaveRequest->status,
            ];
        });

        return response()->json($events);
    }

    public function store(Request $request)
    {
        // Validate and store the leave request data
        $validated = $request->validate([
            'leave_date' => 'required|date',
            'reason' => 'required|string',
        ]);

        LeaveRequest::create($validated);

        return response()->json(['success' => true]);
    }

    public function adminIndex()
    {
        $leaveRequests = LeaveRequest::with('user')->where('status', 'pending')->get();
        return view('admin_leave_requests.index', compact('leaveRequests'));
    }

public function updateStatus(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $leaveRequest->update([
            'status' => $request->status,
        ]);

        return response()->json(['message' => 'Leave request status updated successfully']);
    }
}
