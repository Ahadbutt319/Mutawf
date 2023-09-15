<?php


use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\SendSmsController;
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

Route::get('/sms',[SendSmsController::class,'sendSMS']);

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'forgotPassword']);
Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword']);
Route::post('/resend/email/verification', [VerificationController::class, 'resendEmailVerification']);
Route::post('/resend/phone/verification', [VerificationController::class, 'resendPhoneVerification']);
Route::post('/verify-email', [VerificationController::class, 'verifyEmail']);
Route::post('/verify-phone', [VerificationController::class, 'verifyPhone']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/user', function () {
        return auth()->user;
    });
});
