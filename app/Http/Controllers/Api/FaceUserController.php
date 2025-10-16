<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class FaceUserController extends Controller
{
    public function store(Request $request)
    {
        $name = $request->input('name');

        if (!$name) {
            return response()->json(['error' => 'Missing name'], 400);
        }

        // Kiểm tra nếu user đã tồn tại
        $existing = User::where('name', $name)->first();
        if ($existing) {
            return response()->json([
                'message' => 'User already exists',
                'id' => $existing->id,
                'name' => $existing->name,
            ]);
        }

        // Tạo user mới
        $email = strtolower(str_replace(' ', '.', $name)) . '@autogen.local';
        $password = bcrypt('face_login'); // mật khẩu mặc định
        $role = 'user'; // tùy hệ thống bạn có thể đổi

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $role,
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'id' => $user->id,
            'name' => $user->name,
        ]);
    }
}
