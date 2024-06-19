<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ForgotPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::group(['prefix' => '/'], function () {
    Route::controller(HomeController::class)->group(function () {
        Route::get('/', 'index')->name('home');
        Route::post('/', 'attendance')->name('attendance');
        Route::get('/data', 'getDataAttendance')->name('attendanceData');
        Route::post('/send', 'sendRequest')->name('sendRequest');
        Route::get('/show', 'showRequest')->name('showRequest');
        Route::get('/reject/{id}', 'reject')->name('reject');
        Route::get('/approve/{id}', 'approve')->name('approve');

    });
    Route::controller(AuthController::class)->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login')->name('loginPost');

        Route::get('/logout', 'logout')->name('logout');

        Route::get('/confirmPass', 'showConfirmPass')->name('confirm');
        Route::post('/confirmPass', 'confirmPass')->name('confirmPass');
    });

    Route::controller(ForgotPasswordController::class)->group(function () {
        Route::get('/forgot-password', 'forgotPassword')->name('password.forgot-password');
        Route::post('/process-forgot-password', 'processForgotPassword')->name('password.processForgotPassword');
        Route::get('/reset-password/{token}', 'resetPassword')->name('password.resetPassword');
        Route::post('/process-reset-password/{token}', 'processResetPassword')->name('password.processResetPassword');
    });

    Route::middleware('CheckAdmin')->controller(AccountController::class)->group(function () {
        Route::get('/account', 'index')->name('account.index');
        Route::get('/account/create', 'create')->name('account.create');
        Route::post('/account', 'store')->name('account.store');
        Route::get('/account/edit/{userId}', 'edit')->name('account.edit');
        Route::put('/users/{userId}', 'update')->name('account.update');
        Route::delete('/users/{userId}','destroy')->name('account.delete');

        // show calendar of user
        Route::get('/account/attendance/{user}', 'showAttendance')->name('account.attendance');
        Route::get('/attendance/{user}', 'getAttendance')->name('account.attendanceData');
    });
});
