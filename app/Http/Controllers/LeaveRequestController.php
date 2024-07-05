<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $leaveRequests = LeaveRequest::all();
        return view('leave_requests.index', compact('leaveRequests'));
    }

    public function getLeaveRequest()
    {
        $leaveRequests = LeaveRequest::all();
        $leaveRequests = $leaveRequests->map(function ($leaveRequest) {
            return [
                'id' => $leaveRequest->id,
                'title' => $leaveRequest->reason,
                'start' => $leaveRequest->start_date,
                'end' => $leaveRequest->end_date,
                'status' => $leaveRequest->status,
            ];
        });

        return response()->json($leaveRequests);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        $userId = auth()->user()->id;
        $validated['user_id'] = $userId;

        LeaveRequest::create($validated);

        session()->flash('success', 'Created a leave request successfully.');
        return response()->json([
            'status' => true,
            'message' => 'Created a leave request successfully.',
        ]);
    }

    public function list()
    {
        $leaveRequests = LeaveRequest::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->get();

        return view('leave_requests.list', compact('leaveRequests'));
    }

    public function edit($id)
    {
        $leaveRequest = LeaveRequest::where('id', $id)->where('status', 'pending')->firstOrFail();
        return view('leave_requests.edit', compact('leaveRequest'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        $leaveRequest = LeaveRequest::where('id', $id)->where('status', 'pending')->firstOrFail();
        $leaveRequest->update($validated);

        session()->flash('success', 'Updated leave request successfully.');
        return redirect()->route('leave_requests.list');
    }

    public function destroy($id)
    {
        $leaveRequest = LeaveRequest::where('id', $id)->where('status', 'pending')->firstOrFail();
        $leaveRequest->delete();

        return response()->json(['status' => true, 'message' => 'Successfully deleted leave request.']);
    }
}
