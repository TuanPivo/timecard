<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use App\Http\Requests\ChangePassRequest;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ConstCommon;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('home')->with('Bạn đã đăng nhập rồi');
        }
        return view('user.login');
    }

    public function login(LoginRequest $request)
    {
        $intendedUrl = session('url.intended');
        $credentials = [
            'name' => $request->input('name'),
            'password' => $request->input('password'),
        ];
        $redirectTo = route('home');
        $message = "Đăng nhập thành công";
        if (!Auth::attempt($credentials)) {
            return redirect()->back()->with('error', "Sai thông tin đăng nhập, vui lòng nhập lại");
        }
        $request->session()->regenerate();

        if (Auth::user()->type == ConstCommon::TypeUser && $intendedUrl && $intendedUrl != route('login')) {
            $redirectTo = $intendedUrl;
        }

        return redirect()->to($redirectTo)->with('message', $message);
    }

    public function logOut()
    {
        Auth::logout();
        return redirect()->route('login');
    }


    public function showFormChangePass(Request $request, $id_user = null)
    {
        if (!Auth::check()) {
            return redirect()->route('home');
        }
        return view('user.changepass')->with(
            ['id_user' => $id_user]
        );
    }

    public function changePass(ChangePassRequest $request)
    {
        $user = Auth::user();

        if (Hash::check($request->password, $user->password)) {

            $user->password = Hash::make($request->passwordNew);
            $user->save();

            return redirect()->back()->with('success', 'Password is change success.');
        } else {
            return redirect()->back()->with('error', 'Password is error.');
        }

    }

}

