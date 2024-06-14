<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\CreateAccount;
use App\Mail\UpdateAccount;
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
        if ($user == null) {
            $message = 'User not found.';
            session()->flash('error', $message);

            return response()->json([
                'status' => true,
                'message' => $message,
            ]);
        }
        $user->delete();

        $message = 'Deleted user successfully.';
        session()->flash('success', $message);

        return response()->json([
            'status' => true,
            'message' => $message,
        ]);
    }
}
