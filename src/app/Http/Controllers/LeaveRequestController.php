<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Holiday;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LeaveRequestNotification;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $leaveRequests = LeaveRequest::orderBy('updated_at', 'desc')->get();
        return view('leave_requests.index', compact('leaveRequests'));
    }

    public function getLeaveRequest()
    {
        $user = Auth::user();
        $leaveRequests = LeaveRequest::where('user_id', $user->id)->get();
        $leaveRequests = $leaveRequests->map(function ($leaveRequest) {
            return [
                'id' => $leaveRequest->id,
                'title' => $leaveRequest->title,
                'start' => $leaveRequest->start_date,
                'end' => $leaveRequest->end_date,
                'reason' => $leaveRequest->reason,
                'status' => $leaveRequest->status,
            ];
        });

        // Always fetch holidays
        $holidays = Holiday::select('title', 'start', 'end', 'color')->get()->map(function ($holiday) {
            return [
                'title' => $holiday->title,
                'start' => $holiday->start,
                'end' => $holiday->end ? Carbon::parse($holiday->end)->addDay()->format('Y-m-d\TH:i:s') : null,
            ];
        });
        $combinedEvents = collect($leaveRequests)->merge($holidays);

        return response()->json($combinedEvents);
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => false, 'message' => 'You must log in to continue.'], 401);
        }

        $validated = $request->validate([
            'title' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        $userId = auth()->user()->id;
        $validated['user_id'] = $userId;

        $leaveRequest = LeaveRequest::create($validated);

        // Gửi thông báo đến Slack cho admin
        Notification::route('slack', config('services.slack.webhook_url'))
            ->route('mail', 'admin@example.com')
            ->notify(new LeaveRequestNotification($leaveRequest));

        return response()->json([
            'status' => true,
            'message' => 'Created a leave request successfully.',
        ]);
    }

    public function list()
    {
        $leaveRequests = LeaveRequest::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->orderBy('updated_at', 'DESC')
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
        if (!Auth::check()) {
            return response()->json(['status' => false, 'message' => 'You must log in to continue.'], 401);
        }

        // Kiểm tra quyền sở hữu yêu cầu nghỉ
        $leaveRequest = LeaveRequest::where('id', $id)
            ->where('user_id', auth()->user()->id)
            ->where('status', 'pending')
            ->firstOrFail();

        if ($leaveRequest == null) {
            return response()->json([
                'status' => false,
                'message' => 'Leave not found or already processed.',
            ]);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        if ($validator->passes()) {
            // Sử dụng update để cập nhật tất cả các trường cùng lúc
            $leaveRequest->update($validator->validated());

            // Gửi thông báo đến Slack cho admin
            Notification::route('slack', config('services.slack.webhook_url'))
                ->route('mail', 'admin@example.com')
                ->notify(new LeaveRequestNotification($leaveRequest));

            return response()->json([
                'status' => true,
                'message' => 'Updated leave request successfully.',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function destroy($leaveId)
    {
        $leaveRequest = LeaveRequest::where('id', $leaveId)->where('user_id', auth()->id())->first();

        if (!$leaveRequest) {
            session()->flash('error', 'Leave not found');

            return response()->json([
                'status' => false,
                'message' => 'Leave not found',
            ]);
        }

        $leaveRequest->delete();

        session()->flash('success', 'Successfully deleted leave request.');

        return response()->json([
            'status' => true,
            'message' => 'Successfully deleted leave request.',
        ]);
    }
}
