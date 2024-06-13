<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Attendance;


class HomeController extends Controller
{
    //
    public function index()
    {
        return view('pages.home');
    }

    public function attendance(Request $request){
        $user = Auth::user();
        $type = $request->input('type');
        $attendance = [
            'user_id' => $user->id,
            'date' => now()->toDateString(),
            'type' => $type,
            'status' => 'success',
        ];
        Attendance::create($attendance);
        return redirect()->route('home');

    }

}
