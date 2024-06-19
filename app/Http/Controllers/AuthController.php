<?php

namespace App\Http\Controllers;

use App\Helpers\ConstCommon;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ChangePassRequest;

class AuthController extends Controller
{
    //
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('home')->with('Bạn đã đăng nhập rồi');
        }
        return view('pages.login');
    }

    public function login(LoginRequest $request)
    {
        $intendedUrl = session('url.intended');
        $credentials = [
            'email' => $request->input('email'),
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
        if(!Auth::check()){
            return redirect()->back()->with('error', "Bạn chưa đăng nhập");
        }
        Auth::logout();
        return redirect()->route('login');
    }

}

