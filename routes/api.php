<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::get('/', function () {
    return 'hello world';
});


// merchant auth
Route::post('merchant/register', [\App\Http\Controllers\MerchantAuthController::class, 'register']);
Route::post('merchant/login', [\App\Http\Controllers\MerchantAuthController::class, 'login']);


// volunteer auth
Route::post('volunteer/register', [\App\Http\Controllers\VolunteerAuthController::class, 'register']);
Route::post('volunteer/login', [\App\Http\Controllers\VolunteerAuthController::class, 'login']);


// admin auth
Route::post('admin/register', [\App\Http\Controllers\AdminAuthController::class, 'register']);
Route::post('admin/login', [\App\Http\Controllers\AdminAuthController::class, 'login']);


// protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('auth:admin')->post('admin/logout', [\App\Http\Controllers\AdminAuthController::class, 'logout']);
    Route::middleware('auth:web')->post('merchant/logout', [\App\Http\Controllers\MerchantAuthController::class, 'logout']);
    Route::middleware('auth:volunteer')->post('volunteer/logout', [\App\Http\Controllers\VolunteerAuthController::class, 'logout']);
});
