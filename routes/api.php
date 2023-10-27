<?php

use App\Http\Controllers\Api\FlightController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\LanguageController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\SendSmsController;
use Illuminate\Bus\BusServiceProvider;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HotelController;
use App\Http\Controllers\Api\AgentController;

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





route::post('/search-location',[HotelController::class, 'searchLocation']);
route::post('/search-Hotel',[HotelController::class, 'getGeoId']);
route::post('/get-hotel',[HotelController::class, 'getHotelDetails']);


route::post('/search-Flight',[FlightController::class, 'getFlights']);
route::post('/search-Filter',[FlightController::class, 'getFilter']);
route::post('/search-airports',[FlightController::class, 'getAirports']);




Route::group(['middleware' => ['local']], function () {

    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/forgot-password', [ForgotPasswordController::class, 'forgotPassword']);
    Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword']);
    Route::post('/resend/email/verification', [VerificationController::class, 'resendEmailVerification']);
    Route::post('/resend/phone/verification', [VerificationController::class, 'resendPhoneVerification']);
    Route::post('/verify-email', [VerificationController::class, 'verifyEmail']);
    Route::post('/verify-phone', [VerificationController::class, 'verifyPhone']);
    Route::get('/locals', [LanguageController::class, 'index']);
    Route::post('locals', [LanguageController::class, 'changeLocale']);

    Route::group(['middleware' => ['auth:api', 'last_seen']], function () {

        route::get('/get-operators',[AgentController::class,'getOperators']);
        route::post('/become-operator',[AgentController::class,'becomeAnOperator']);
        route::post('/create-package',[AgentController::class,'addPackage']);
        route::get('/get-packages',[AgentController::class,'getGeneralPackage']);
        route::post('/add-hotels',[AgentController::class,'addHotel']);
        route::get('/get-hotels',[AgentController::class,'getHotels']);



        Route::get('/verification-status', [UserController::class, 'getVerificationStatus']);
        Route::post('/update-password', [UserController::class, 'updatePassword']);
        Route::get('/auth-data', [UserController::class, 'authData']);
        Route::post('/update-profile', [UserController::class, 'updateUser']);
        Route::post('/companies', [CompanyController::class, 'store']);

    });
});

