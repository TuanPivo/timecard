<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Attendance;
use App\Mail\CreateAccount;
use App\Mail\ResetPassword;
use App\Mail\UpdateAccount;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $users = User::orderBy('created_at', 'DESC');

        if (!empty($request->get('keyword'))) {
            $users = $users->Where('name', 'like', '%' . $request->get('keyword') . '%');
            $users = $users->orWhere('email', 'like', '%' . $request->get('keyword') . '%');
        }

        $users = $users->paginate(15);

        return view('account.index', [
            'users' => $users,
        ]);
    }

    public function create()
    {
        return view('account.create');
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
                'status' => true,
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
                'status' => true,
                'message' => 'User not found',
            ]);
        }
        $user->delete();

        session()->flash('success', 'User deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'User deleted successfully',
        ]);
    }

    public function showAttendance(User $user)
    {
        return view('account.attendance', compact(['user']));
    }

    public function getAttendance(User $user)
    {
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

        return response()->json($attendances);
    }

    public function forgotPassword()
    {
        return view('account.forgot-password');
    }

    public function processForgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);

        if ($validator->fails()) {
            return redirect()->route('account.forgot-password')->withInput()->withErrors($validator);
        }

        $token = Str::random(60);

        \DB::table('password_resets')->where('email', $request->email)->delete();

        \DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        // send email here
        $user = User::where('email', $request->email)->first();
        $mailData = [
            'token' => $token,
            'user' => $user,
            'subject' => 'You have requested to change your password'
        ];

        Mail::to(env('MAIL_FROM_ADDRESS'))->send(new ResetPassword($mailData));

        return redirect()->route('account.forgot-password')->with('success', 'Reset password email has been sent to your inbox.');
    }

    public function resetPassword($tokenString)
    {
        $token = \DB::table('password_resets')->where('token', $tokenString)->first();

        if ($token == null) {
            return redirect()->route('account.forgot-password')->with('error', 'Invalid token.');
        }

        return view('account.reset-password', ['tokenString' => $tokenString]);
    }

    public function processResetPassword(Request $request)
    {
        $token = \DB::table('password_resets')->where('token', $request->token)->first();

        if ($token == null) {
            return redirect()->route('account.forgot-password')->with('error', 'Invalid token.');
        }

        $validator = Validator::make($request->all(), [
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            return redirect()->route('account.resetPassword', $request->token)->withErrors($validator);
        }

        User::where('email', $token->email)->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('login')->with('success', 'You have successfully change your password');
    }
}
