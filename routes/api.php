<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Sms\SmsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::prefix('auth')
    ->middleware('guest')
    ->group(function () {

        // Auth İşlemleri
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
        Route::post('logout', [AuthController::class, 'logout'])
            ->middleware('auth:api')
            ->withoutMiddleware('guest');
        Route::post('token/refresh', [AuthController::class, 'refreshToken'])->withoutMiddleware('guest');
    });


Route::middleware(['auth:customer'])->group(function () {
    Route::apiResource('sms', SmsController::class);
});
