<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\FaceUserController;
use App\Http\Controllers\Api\FaceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/face-attendance', [AttendanceController::class, 'apiFaceAttendance']);
Route::post('/face-user', [FaceUserController::class, 'store']);

Route::post('/face/train', [FaceController::class, 'storeEncoding']);
Route::post('/face/detect-attendance', [FaceController::class, 'detectAndAttendance']);
