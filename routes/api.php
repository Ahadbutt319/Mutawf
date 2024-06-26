<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Bus\BusServiceProvider;
use App\Http\Controllers\SendSmsController;
use App\Http\Controllers\Api\FaqsController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AgentController;
use App\Http\Controllers\Api\HotelController;
use App\Http\Controllers\Api\FlightController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Api\AboutUsController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\GroundServicesContoller;
use App\Http\Controllers\Api\LanguageController;
use App\Http\Controllers\Api\UserCardController;
use App\Http\Controllers\Api\TransportController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Api\UmrahPackageController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\TermAndConditionController;
use App\Http\Controllers\Api\superadmin\ComplainController;
use App\Http\Controllers\Api\superadmin\SuperadmiController;
use App\Http\Controllers\Api\superadmin\ComplainTypeController;



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
        route::post('/add-ImageCategory', [AgentController::class, 'addImageCategory']);
        route::post('/become-operator', [AgentController::class, 'becomeAnOperator']);
        route::get('/operators', [AgentController::class, 'fetchOperators']);
        route::post('/delete-operator', [AgentController::class, 'deleteOperator']);
        route::post('/update-operator', [AgentController::class, 'updateOperator']);
        route::post('/add-card', [UserCardController::class, 'create']);
        route::post('/remove-card', [UserCardController::class, 'remove']);
        route::get('/cards', [UserCardController::class, 'showCards']);
        Route::get('/verification-status', [UserController::class, 'getVerificationStatus']);
        Route::post('/update-password', [UserController::class, 'updatePassword']);
        Route::get('/auth-data', [UserController::class, 'authData']);
        Route::post('/update-profile', [UserController::class, 'updateUser']);
        Route::post('/companies', [CompanyController::class, 'store']);
        //Visa  by agent
        Route::group(['prefix' => 'visa'], function () {
            route::post('/add', [AgentController::class, 'addVisa']);
            route::post('/delete', [AgentController::class, 'deleteVisa']);
            route::post('/update', [AgentController::class, 'updateVisa']);
        });
        //Transporatarion  by agent
        Route::group(['prefix' => 'transport'], function () {
            Route::post('/add', [TransportController::class, 'create']);
            Route::post('/detail', [TransportController::class, 'detail']);
            route::get('/index', [TransportController::class, 'index']);
            route::post('/update', [TransportController::class, 'update']);
            route::post('/delete', [TransportController::class, 'delete']);
        });
        //Hotel  by agent
        Route::group(['prefix' => 'hotel'], function () {
            route::post('/add', [AgentController::class, 'addHotel']);
            route::post('/delete', [AgentController::class, 'deleteHotel']);
            route::get('/index', [AgentController::class, 'getHotels']);
            route::post('/detail', [AgentController::class, 'getHotelDetail']);;
        });
        //Ummrah package by agent
        Route::group(['prefix' => 'ummrah-package'], function () {
            route::post('/create', [UmrahPackageController::class, 'create']);
            route::post('/update', [UmrahPackageController::class, 'update']);
            route::post('/delete', [UmrahPackageController::class, 'deletePackage']);
        });
        //ground services by agent
        Route::group(['prefix' => 'ground-service'], function () {
            Route::get('/index', [GroundServicesContoller::class, 'index']);
            Route::post('/create', [GroundServicesContoller::class, 'create']);
            Route::post('/update', [GroundServicesContoller::class, 'update']);
            Route::post('/delete', [GroundServicesContoller::class, 'delete']);
        });
        //  ALL Customer routes
        Route::group(['prefix' => 'customer'], function () {
            // transports
            Route::group(['prefix' => 'transport'], function () {
                Route::post('/search', [TransportController::class, 'search']);
                Route::post('/detail', [TransportController::class, 'detail']);
                route::get('/index', [TransportController::class, 'index']);
                Route::post('/book', [TransportController::class, 'booking']);
                Route::get('/booked', [TransportController::class, 'getAllBooking']);
            });
            //hotels
            Route::group(['prefix' => 'hotel'], function () {
                route::get('/index', [AgentController::class, 'getHotels']);
                route::post('/detail', [AgentController::class, 'getHotelDetail']);
                route::post('/search', [AgentController::class, 'searchHotel']);
                route::post('/booking', [BookingController::class, 'booking']);
            });
            // customer ground service
            Route::group(['prefix' => 'ground-service'], function () {
                Route::get('/index', [GroundServicesContoller::class, 'index']);
                route::post('/detail', [GroundServicesContoller::class, 'detail']);
                route::post('/search', [GroundServicesContoller::class, 'search']);
                route::post('/booking', [GroundServicesContoller::class, 'booking']);
            });
            route::post('/get-bookings', [BookingController::class, 'getBookingDetails']);
            route::get('/packages', [UmrahPackageController::class, 'index']);
            route::post('/package', [UmrahPackageController::class, 'detailpackage']);
            route::post('/serach/packages', [UmrahPackageController::class, 'searchpackage']);
            route::post('/book/package', [CustomerController::class, 'packagebooking']);
            route::get('/all-bookings', [CustomerController::class, 'getbookings']);
            route::post('/reach-us', [CustomerController::class, 'contactus']);
            route::get('/faqs', [FaqsController::class, 'index']);
            route::get('/content', [ContentController::class, 'index']);
            route::get('/cancellation', [ContentController::class, 'cancellation']);
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
                Route::post('/submit', [ComplainController::class, 'create']);
                Route::post('/status-change', [ComplainController::class, 'statuschange']);
                Route::post('/admin/action', [ComplainController::class, 'adminactiononcomplain']);
                Route::get('/all', [ComplainController::class, 'complainlist']);
                Route::post('/detail-view', [ComplainController::class, 'detailview']);
            });
        });
    });
});
