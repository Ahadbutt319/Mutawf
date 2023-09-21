<?php

use App\Services\ResponseService;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/not-authenticated', function () {
    return ResponseService::unauthorizedErrorResponse('Please login first.');
})->name('not-authenticated');

Route::get('/', function () {
    return view('welcome');
});
