<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\ResetPassword;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    public function forgotPassword()
    {
        return view('password.forgot-password');
    }

    public function processForgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);

        if ($validator->fails()) {
            return redirect()->route('password.forgot-password')->withInput()->withErrors($validator);
        }

        $token = Str::random(60);

        DB::table('password_resets')->where('email', $request->email)->delete();

        DB::table('password_resets')->insert([
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

        return redirect()->route('password.forgot-password')->with('success', 'Reset password email has been sent to your inbox.');
    }

    public function resetPassword($tokenString)
    {
        $token = DB::table('password_resets')->where('token', $tokenString)->first();

        if ($token == null) {
            return redirect()->route('password.forgot-password')->with('error', 'Invalid token.');
        }

        return view('password.reset-password', ['tokenString' => $tokenString]);
    }

    public function processResetPassword(Request $request)
    {
        $token = DB::table('password_resets')->where('token', $request->token)->first();

        if ($token == null) {
            return redirect()->route('password.forgot-password')->with('error', 'Invalid token.');
        }

        $validator = Validator::make($request->all(), [
            'new_password' => [
                'required',
                'min:8',
                'regex:/^(?=.*[!@#$%^&*()\-_=+{};:,<.>ยง~`|[\]\\/"\'])/'
            ],
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            return redirect()->route('password.resetPassword', $request->token)->withErrors($validator);
        }

        User::where('email', $token->email)->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('login')->with('success', 'You have successfully change your password');
    }
}
