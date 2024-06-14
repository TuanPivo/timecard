<?php

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\HomeController;

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

        Route::get('/changePass', 'showFormChangePass')->name('showPass');
        Route::post('/changePass', 'changePass')->name('changePass');
    });

    Route::controller(AccountController::class)->group(function () {
        Route::get('/account', 'index')->name('account.index');
        Route::get('/account/create', 'create')->name('account.create');
        Route::post('/account', 'store')->name('account.store');
        Route::get('/account/edit/{userId}', 'edit')->name('account.edit');
        Route::put('/users/{userId}', 'update')->name('account.update');
        Route::delete('/users/{userId}','destroy')->name('account.delete');
    });

    Route::controller(CalendarController::class)->group(function () {
        Route::get('/calendar', 'index')->name('calendar.index');
        // Route::get('/calendar/show', 'getcalendar')->name('calendar.calendar');
    });
});
