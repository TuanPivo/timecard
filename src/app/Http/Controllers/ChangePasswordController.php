<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\ChangePassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ChangePasswordController extends Controller
{
    public function changePassword()
    {
        return view('password.change-password');
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|min:8',
            'new_password' => [
                'required',
                'min:8',
                'regex:/^(?=.*[!@#$%^&*()\-_=+{};:,<.>ยง~`|[\]\\/"\'])/'
            ],
            'password_confirmation' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' =>false,
                'errors' => $validator->errors(),
            ]);
        }

        if (Hash::check($request->old_password, Auth::user()->password) == false) {
            session()->flash('error', 'You entered your old password incorrectly');

            return response()->json([
                'status' => true,
            ]);
        }

        $user = User::find(Auth::user()->id);
        $user->password = Hash::make($request->new_password);
        $user->save();

        $subject = 'Password Changed Successfully';
        $name = $request->user()->name;
        $password = $request->new_password;

        Mail::to(env('MAIL_FROM_ADDRESS'))->send(new ChangePassword($subject, $name, $password));

        session()->flash('success', 'Password Changed Successfully');

        return response()->json([
            'status' => true
        ]);
    }
}
