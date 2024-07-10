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
        // $leaveRequests = LeaveRequest::all();
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
                'title' => $leaveRequest->reason,
                'start' => $leaveRequest->start_date,
                'end' => $leaveRequest->end_date,
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
        // return response()->json($leaveRequests);
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

        // LeaveRequest::create($validated);
        $leaveRequest = LeaveRequest::create($validated);

        // Định dạng thời gian bắt đầu và kết thúc
        $formattedStartDate = Carbon::parse($leaveRequest->start_date)->format('H:i d/m/Y');
        $formattedEndDate = Carbon::parse($leaveRequest->end_date)->format('H:i d/m/Y');

         // Gửi thông báo đến Slack cho admin với thời gian đã định dạng
        Notification::route('slack', config('services.slack.webhook_url'))
        ->notify(new LeaveRequestNotification($leaveRequest, $formattedStartDate, $formattedEndDate));

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
        $leaveRequest = LeaveRequest::where('id', $id)->where('status', 'pending')->firstOrFail();
        if ($leaveRequest == null) {
            $message = 'Leave not found.';
            session()->flash('error', $message);

            return response()->json([
                'status' => false,
                'message' => $message,
            ]);
        }

        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        if ($validator->passes()) {
            $leaveRequest->start_date = $request->start_date;
            $leaveRequest->end_date = $request->end_date;
            $leaveRequest->reason = $request->reason;

            $leaveRequest->update();

            $formattedStartDate = Carbon::parse($leaveRequest->start_date)->format('H:i d/m/Y');
            $formattedEndDate = Carbon::parse($leaveRequest->end_date)->format('H:i d/m/Y');
    
             // Gửi thông báo đến Slack cho admin với thời gian đã định dạng
            Notification::route('slack', config('services.slack.webhook_url'))
            ->notify(new LeaveRequestNotification($leaveRequest, $formattedStartDate, $formattedEndDate));

            session()->flash('success', 'Updated leave successfully.');

            return response()->json([
                'status' => true,
                'message' => 'Updated leave successfully.',
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
