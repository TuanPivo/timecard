<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\FaceEncoding;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class FaceController extends Controller
{
    public function storeEncoding(Request $request)
    {
        $request->validate([
            'user_name' => 'required',
            'encoding' => 'required|array'
        ]);

        $user = User::where('name', $request->user_name)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        FaceEncoding::updateOrCreate(
            ['user_id' => $user->id],
            ['encoding' => json_encode($request->encoding)]
        );

        return response()->json(['message' => 'Face encoding saved']);
    }

    public function detectAndAttendance(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Not logged in'], 401);
        }

        if (!$request->hasFile('frame')) {
            return response()->json(['error' => 'Missing frame'], 400);
        }

        $user = Auth::user();
        $file = $request->file('frame');

        // Gửi ảnh sang Python để lấy encoding khuôn mặt
        $response = Http::attach(
            'frame',
            file_get_contents($file),
            $file->getClientOriginalName()
        )->post('http://face_service:5000/extract_encoding');

        if (!$response->ok()) {
            return response()->json(['error' => 'Cannot detect face'], 400);
        }

        $faceData = $response->json();
        $encoding = $faceData['encoding'];

        // Lấy encoding user đang login
        $userFace = FaceEncoding::where('user_id', $user->id)->first();

        if (!$userFace) {
            return response()->json(['error' => 'User has no trained data'], 422);
        }

        // So sánh encoding bằng cosine distance
        $dbEnc = json_decode($userFace->encoding);
        $distance = 0;
        for ($i = 0; $i < 128; $i++) {
            $distance += pow($encoding[$i] - $dbEnc[$i], 2);
        }
        $distance = sqrt($distance);

        if ($distance > 0.45) { // ngưỡng tùy chỉnh
            return response()->json(['error' => 'Face mismatch'], 401);
        }

        // Ghi chấm công (giống nút bấm)
        Attendance::create([
            'user_id' => $user->id,
            'date' => now(),
            'type' => 'checkin', // hoặc check-out tùy giao diện gửi
            'status' => 'success'
        ]);

        return response()->json(['message' => 'Attendance recorded']);
    }
}