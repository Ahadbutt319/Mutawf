<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Bus\BusServiceProvider;
use App\Http\Controllers\SendSmsController;
use App\Http\Controllers\api\FaqsController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AgentController;
use App\Http\Controllers\Api\HotelController;
use App\Http\Controllers\Api\FlightController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\api\AboutUsController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\api\ContentController;
use App\Http\Controllers\api\CustomerController;
use App\Http\Controllers\Api\LanguageController;
use App\Http\Controllers\api\superadmin\ComplainController;
use App\Http\Controllers\Api\UserCardController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\api\TermAndConditionController;
use App\Http\Controllers\api\superadmin\SuperadmiController;
use App\Http\Controllers\api\superadmin\ComplainTypeController;

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





route::post('/search-location', [HotelController::class, 'searchLocation']);
route::post('/search-Hotel', [HotelController::class, 'getGeoId']);
route::post('/get-hotel', [HotelController::class, 'getHotelDetails']);


route::post('/search-Flight', [FlightController::class, 'getFlights']);
route::post('/search-Filter', [FlightController::class, 'getFilter']);
route::post('/search-airports', [FlightController::class, 'getAirports']);




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

        route::get('/get-operators', [AgentController::class, 'getOperators']);
        route::post('/become-operator', [AgentController::class, 'becomeAnOperator']);
        route::post('/create-package', [AgentController::class, 'addPackage']);
        route::get('/packages', [AgentController::class, 'getGeneralPackage']);
        route::post('/add-hotels', [AgentController::class, 'addHotel']);
        route::get('/get-hotels', [AgentController::class, 'getHotels']);
        route::post('/add-ImageCategory', [AgentController::class, 'addImageCategory']);
        route::post('/become-operator', [AgentController::class, 'becomeAnOperator']);
        route::get('/operators', [AgentController::class, 'fetchOperators']);
        route::post('/add-transportation', [AgentController::class, 'addTransportation']);
        route::post('/add-visa', [AgentController::class, 'addVisa']);
        route::post('/delete-visa', [AgentController::class, 'deleteVisa']);
        route::post('/delete-package', [AgentController::class, 'deletePackage']);
        route::post('/delete-operator', [AgentController::class, 'deleteOperator']);
        route::post('/add-card', [UserCardController::class, 'create']);
        route::post('/remove-card', [UserCardController::class, 'remove']);
        route::get('/cards', [UserCardController::class, 'showCards']);


        route::get('/get-operators',[AgentController::class,'getOperators']);
        route::post('/become-operator',[AgentController::class,'becomeAnOperator']);
        route::post('/create-package',[AgentController::class,'addPackage']);
        route::get('/packages',[AgentController::class,'getGeneralPackage']);
        route::post('/add-hotels',[AgentController::class,'addHotel']);
        route::get('/get-hotels',[AgentController::class,'getHotels']);
        route::post('/add-ImageCategory',[AgentController::class,'addImageCategory']);
        route::post('/become-operator',[AgentController::class,'becomeAnOperator']);
        route::get('/operators',[AgentController::class,'fetchOperators']);
        route::post('/add-transportation',[AgentController::class,'addTransportation']);
        route::post('/add-visa',[AgentController::class,'addVisa']);
        route::post('/delete-visa',[AgentController::class,'deleteVisa']);
        route::post('/delete-package',[AgentController::class,'deletePackage']);
        route::post('/delete-operator',[AgentController::class,'deleteOperator']);
        route::post('/update-package',[AgentController::class,'updatePackage']);




        route::post('/add-card',[UserCardController::class,'create']);
        route::post('/remove-card',[UserCardController::class,'remove']);

        route::get('/cards',[UserCardController::class,'showCards']);





        Route::get('/verification-status', [UserController::class, 'getVerificationStatus']);
        Route::post('/update-password', [UserController::class, 'updatePassword']);
        Route::get('/auth-data', [UserController::class, 'authData']);
        Route::post('/update-profile', [UserController::class, 'updateUser']);
        Route::post('/companies', [CompanyController::class, 'store']);
        //  ALL Customer routes
        Route::group(['prefix' => 'customer'], function () {
            route::get('/packages', [CustomerController::class, 'getpackages']);
            route::post('/package', [CustomerController::class, 'detailpackage']);
            route::post('/serach/packages', [CustomerController::class, 'searchpackage']);
            route::get('/faqs', [FaqsController::class, 'index']);
            route::get('/content', [ContentController::class, 'index']);
            route::get('/complain-types', [ComplainTypeController::class, 'index']);
        });
        // All admin  routes
        Route::group(['prefix' => 'admin'], function () {
            // about page content
            Route::group(['prefix' => 'about'], function () {
                route::get('/content', [AboutUsController::class, 'index']);
                route::post('/create', [AboutUsController::class, 'create']);
                route::get('destroy/{aboutUs}', [AboutUsController::class, 'destroy']);
                route::post('/update/{aboutUs}', [AboutUsController::class, 'update']);
            });
            // terms and condition content
            Route::group(['prefix' => 'termsandcondition'], function () {
                route::get('/index', [TermAndConditionController::class, 'index']);
                route::post('/create', [TermAndConditionController::class, 'create']);
                route::get('destroy/{termAndCondition}', [TermAndConditionController::class, 'destroy']);
                route::post('/update/{termAndCondition}', [TermAndConditionController::class, 'update']);
            });
            // content
            Route::group(['prefix' => 'content'], function () {
                route::get('/index', [ContentController::class, 'index']);
                route::post('/create', [ContentController::class, 'create']);
                route::get('/destroy/{id}', [ContentController::class, 'destroy']);
                route::post('/update/{id}', [ContentController::class, 'update']);
            });
            // faqs
            Route::group(['prefix' => 'faqs'], function () {
                route::get('/index', [FaqsController::class, 'index']);
                route::post('/create', [FaqsController::class, 'create']);
                route::get('/destroy/{id}', [FaqsController::class, 'destroy']);
                route::post('/update', [FaqsController::class, 'update']);
            });
            // customer list
            Route::group(['prefix' => 'customers'], function () {
                route::get('/index', [SuperadmiController::class, 'customerslist']);
            });
            // E-care
            Route::group(['prefix' => 'complain'], function () {
                // Complain types
                Route::group(['prefix' => 'complain-types'], function () {
                    route::get('/index', [ComplainTypeController::class, 'index']);
                    route::post('/create', [ComplainTypeController::class, 'create']);
                    route::get('/destroy/{id}', [ComplainTypeController::class, 'destroy']);
                    route::post('/update', [ComplainTypeController::class, 'update']);
                });
                Route::post('/submit', [ComplainController::class , 'create']);
                Route::post('/status-change', [ComplainController::class , 'statuschange']);
                Route::post('/admin/action', [ComplainController::class , 'adminactiononcomplain']);
                Route::get('/all', [ComplainController::class , 'complainlist']);
                Route::post('/detail-view', [ComplainController::class , 'detailview']);
            });
        });
    });
});
