<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class AdminLeaveRequestController extends Controller
{
    public function index()
    {
        $leaveRequests = LeaveRequest::with('user')->where('status', 'pending')->get();
        return view('admin_leave_requests.index', compact('leaveRequests'));
    }

    public function updateStatus(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate(['status' => 'required|in:approved,rejected']);

        $leaveRequest->update(['status' => $request->status]);

        return response()->json(['success' => true, 'message' => 'Leave request status updated successfully.']);
    }
}