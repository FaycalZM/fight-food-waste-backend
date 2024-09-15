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


// admin READs
Route::get('admin/all_merchants', [\App\Http\Controllers\UsersManagementController::class, 'all_users']);
Route::get('admin/merchants/{id}', [\App\Http\Controllers\UsersManagementController::class, 'get_user']);
Route::get('admin/merchants/{id}/approve_membership', [\App\Http\Controllers\UsersManagementController::class, 'approve_subscription']);


Route::get('admin/all_skills', [\App\Http\Controllers\UsersManagementController::class, 'all_skills']);
Route::get('admin/skills/{id}', [\App\Http\Controllers\UsersManagementController::class, 'get_skill']);


Route::get('admin/all_volunteers', [\App\Http\Controllers\UsersManagementController::class, 'all_volunteers']);
Route::get('admin/volunteers/{id}', [\App\Http\Controllers\UsersManagementController::class, 'get_volunteer']);


Route::get('admin/all_collections', [\App\Http\Controllers\UsersManagementController::class, 'all_collections']);
Route::get('admin/collections/{id}', [\App\Http\Controllers\UsersManagementController::class, 'get_collection']);
Route::post('admin/add_collection', [\App\Http\Controllers\UsersManagementController::class, 'create_collection']);


Route::get('admin/all_stocks', [\App\Http\Controllers\UsersManagementController::class, 'all_stocks']);
Route::get('admin/stocks/{id}', [\App\Http\Controllers\UsersManagementController::class, 'get_stock']);
Route::post('admin/add_stock', [\App\Http\Controllers\UsersManagementController::class, 'create_stock']);
Route::get('admin/stocks/{id}/search', [\App\Http\Controllers\UsersManagementController::class, 'find_product']);


Route::get('admin/all_products', [\App\Http\Controllers\UsersManagementController::class, 'all_products']);
Route::get('admin/products/{id}', [\App\Http\Controllers\UsersManagementController::class, 'get_product']);
Route::post('admin/add_product', [\App\Http\Controllers\UsersManagementController::class, 'create_product']);

Route::get('admin/all_distributions', [\App\Http\Controllers\UsersManagementController::class, 'all_distributions']);
Route::get('admin/distributions/{id}', [\App\Http\Controllers\UsersManagementController::class, 'get_distribution']);

Route::get('admin/all_beneficiaries', [\App\Http\Controllers\UsersManagementController::class, 'all_beneficiaries']);
Route::get('admin/beneficiaries/{id}', [\App\Http\Controllers\UsersManagementController::class, 'get_beneficiary']);



// protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('auth:admin')->post('admin/logout', [\App\Http\Controllers\AdminAuthController::class, 'logout']);
    Route::middleware('auth:web')->post('merchant/logout', [\App\Http\Controllers\MerchantAuthController::class, 'logout']);
    Route::middleware('auth:volunteer')->post('volunteer/logout', [\App\Http\Controllers\VolunteerAuthController::class, 'logout']);
});
