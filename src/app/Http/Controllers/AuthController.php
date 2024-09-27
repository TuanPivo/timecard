<?php

namespace App\Http\Controllers;

use App\Helpers\ConstCommon;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // public function showLoginForm()
    // {
    //     if (Auth::check()) {
    //         return redirect()->route('home')->with('You are logged in');
    //     }
    //     return view('pages.login');
    // }

    // public function login(LoginRequest $request)
    // {
    //     $intendedUrl = session('url.intended');
    //     $credentials = [
    //         'email' => $request->input('email'),
    //         'password' => $request->input('password'),
    //     ];
    //     $redirectTo = route('home');
    //     $message = "Login successful";
    //     if (!Auth::attempt($credentials)) {
    //         return redirect()->back()->with('error', "Incorrect login information");
    //     }
    //     $request->session()->regenerate();

    //     if (Auth::user()->type == ConstCommon::TypeUser && $intendedUrl && $intendedUrl != route('login')) {
    //         $redirectTo = $intendedUrl;
    //     }

    //     return redirect()->to($redirectTo)->with('message', $message);
    // }

    // public function logOut()
    // {
    //     if(!Auth::check()){
    //         return redirect()->back()->with('error', "You are not logged in.");
    //     }
    //     Auth::logout();
    //     return redirect()->route('home');
    // }

    public function loginForm()
    {
        if (Auth::check()) {
            return redirect()->route('home')->with('message', 'You are already logged in.');
        }
        return view('pages.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('home')->with('message', 'Login successful!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // return redirect()->route('login')->with('success', 'You have been logged out.');
        return redirect()->route('home')->with('success', 'You have been logged out.');
    }
}

