<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Holiday;
use App\Models\Attendance;
use App\Mail\CreateAccount;
use App\Mail\UpdateAccount;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MonthlyAttendanceExport;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('home')->with('error', "You are not logged in");
        }

        $users = User::orderBy('created_at', 'ASC')->get();

        return view('account.index', [
            'users' => $users,
        ]);
    }

    public function create()
    {
        $user = Auth::user();
        return view('account.create', compact(['user']));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        if ($validator->passes()) {
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            $subject = 'Account created successfully';
            $name = $request->input('name');
            $email = $request->input('email');
            $password = $request->input('password');
            $loginUrl = route('login');

            Mail::to(env('MAIL_FROM_ADDRESS'))->send(new CreateAccount($subject, $name, $email, $password, $loginUrl));

            session()->flash('success', 'Account created successfully');

            return response()->json([
                'status' => true,
                'message' => 'Account created successfully',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function edit($userId)
    {
        $user = User::find($userId);
        if ($user == null) {
            $message = 'User not found.';
            session()->flash('error', $message);
            return redirect()->route('account.index');
        }
        return view('account.edit', [
            'user' => $user,
        ]);
    }

    public function update(Request $request, $userId)
    {
        $user = User::find($userId);
        if ($user == null) {
            $message = 'User not found.';
            session()->flash('error', $message);

            return response()->json([
                'status' => false,
                'message' => $message,
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $userId . ',id',
        ]);

        if ($validator->passes()) {
            $user->name = $request->name;
            $user->email = $request->email;

            $subject = 'Account information has been successfully edited.';
            $name = $request->input('name');
            $email = $request->input('email');

            Mail::to(env('MAIL_FROM_ADDRESS'))->send(new UpdateAccount($subject, $name, $email));

            $user->update();

            session()->flash('success', 'Updated user successfully.');

            return response()->json([
                'status' => true,
                'message' => 'Updated user successfully.',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function destroy($userId)
    {
        $user = User::find($userId);

        if (empty($user)) {
            session()->flash('error', 'User not found');

            return response()->json([
                'status' => false,
                'message' => 'User not found',
            ]);
        }
        $user->delete();

        session()->flash('success', 'User Deleted Successfully');

        return response()->json([
            'status' => true,
            'message' => 'User Deleted Successfully',
        ]);
    }

    public function getMonthlyAttendance($userId, $month, $year)
    {
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $attendances = Attendance::where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->get();

        $monthlyAttendance = [];

        foreach ($attendances as $attendance) {
            $date = Carbon::parse($attendance->date)->format('j');

            if (!isset($monthlyAttendance[$date])) {
                $monthlyAttendance[$date] = [];
            }

            if ($attendance->type == 'check in') {
                $monthlyAttendance[$date]['check_in'] = [
                    'status' => $attendance->status,
                    'date' => Carbon::parse($attendance->date)->format('H:i'),
                ];
            } elseif ($attendance->type == 'check out') {
                $monthlyAttendance[$date]['check_out'] = [
                    'status' => $attendance->status,
                    'date' => Carbon::parse($attendance->date)->format('H:i'),
                ];
            }
        }

        return $monthlyAttendance;
    }

    public function showMonthlyAttendance(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $monthlyAttendance = $this->getMonthlyAttendance($userId, $month, $year);

        // Fetch holidays for the specified month and year
        $holidays = Holiday::whereYear('start', $year)
                            ->whereMonth('start', $month)
                            ->get()
                            ->pluck('start')
                            ->map(function ($date) {
                                return Carbon::parse($date)->day;
                            });

        return view('account.monthly', [
            'user' => $user,
            'monthlyAttendance' => $monthlyAttendance,
            'selectedMonth' => $month,
            'selectedYear' => $year,
            'holidays' => $holidays,
        ]);
    }

    public function getMonthlyAttendanceExcel($userId, $month, $year)
{
    $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
    $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

    $attendances = Attendance::where('user_id', $userId)
        ->where('status', 'success')
        ->whereIn('type', ['check in', 'check out'])
        ->whereBetween('date', [$startDate, $endDate])
        ->orderBy('date')
        ->get();

    $monthlyAttendanceExcel = [];

    foreach ($attendances as $attendance) {
        $date = Carbon::parse($attendance->date)->format('j');

        if (!isset($monthlyAttendanceExcel[$date])) {
            $monthlyAttendanceExcel[$date] = [];
        }

        if ($attendance->type == 'check in') {
            $monthlyAttendanceExcel[$date]['check_in'] = [
                'status' => $attendance->status,
                'date' => Carbon::parse($attendance->date)->format('H:i'),
            ];
        } elseif ($attendance->type == 'check out') {
            $monthlyAttendanceExcel[$date]['check_out'] = [
                'status' => $attendance->status,
                'date' => Carbon::parse($attendance->date)->format('H:i'),
            ];
        }
    }

    return $monthlyAttendanceExcel;
}

    public function exportMonthlyAttendance(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $monthlyAttendanceExcel = $this->getMonthlyAttendanceExcel($userId, $month, $year);

        // Format month with two digits
        $formattedMonth = str_pad($month, 2, '0', STR_PAD_LEFT);
        // File name format
        $fileName = Str::slug($user->name, '_') . '_' . $formattedMonth . '_' . $year . '.xlsx';
        $fileName = str_replace('-', '_', $fileName);

        return Excel::download(
            new MonthlyAttendanceExport($user, $monthlyAttendanceExcel, $month, $year),
            $fileName
        );
    }
}
