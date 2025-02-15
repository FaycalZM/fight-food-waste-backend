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



Route::get('admin/all_merchants', [\App\Http\Controllers\UsersManagementController::class, 'all_users']);
Route::get('admin/merchants/{id}', [\App\Http\Controllers\UsersManagementController::class, 'get_user']);
Route::get('admin/merchants/{id}/approve_membership', [\App\Http\Controllers\UsersManagementController::class, 'approve_merchant_subscription']);
Route::get('admin/merchants/{id}/reject_subscription', [\App\Http\Controllers\UsersManagementController::class, 'reject_merchant_subscription']);
Route::get('admin/merchants/{id}/delete', [\App\Http\Controllers\UsersManagementController::class, 'delete_merchant']);
Route::get('merchant/request', [\App\Http\Controllers\UsersManagementController::class, 'request_service']);


Route::get('admin/all_skills', [\App\Http\Controllers\UsersManagementController::class, 'all_skills']);
Route::get('admin/skills/{id}', [\App\Http\Controllers\UsersManagementController::class, 'get_skill']);


Route::get('admin/all_volunteers', [\App\Http\Controllers\UsersManagementController::class, 'all_volunteers']);
Route::get('admin/volunteers/{id}', [\App\Http\Controllers\UsersManagementController::class, 'get_volunteer']);
Route::get('admin/volunteers/{id}/approve_membership', [\App\Http\Controllers\UsersManagementController::class, 'approve_volunteer_subscription']);
Route::get('admin/volunteers/{id}/reject_subscription', [\App\Http\Controllers\UsersManagementController::class, 'reject_volunteer_subscription']);
Route::get('admin/volunteers/{id}/delete', [\App\Http\Controllers\UsersManagementController::class, 'delete_volunteer']);



Route::get('admin/all_collections', [\App\Http\Controllers\UsersManagementController::class, 'all_collections']);
Route::get('admin/collections/{id}', [\App\Http\Controllers\UsersManagementController::class, 'get_collection']);
Route::post('admin/add_collection', [\App\Http\Controllers\UsersManagementController::class, 'create_collection']);
Route::get('admin/collections/{id}/start', [\App\Http\Controllers\UsersManagementController::class, 'start_collection']);
Route::get('admin/collections/{id}/close', [\App\Http\Controllers\UsersManagementController::class, 'close_collection']);
Route::get('admin/collections/{id}/delete', [\App\Http\Controllers\UsersManagementController::class, 'delete_collection']);
Route::post('admin/collections/{id}/products/add', [\App\Http\Controllers\UsersManagementController::class, 'add_product_to_collection']);

Route::get('admin/collections/{id}/pdf', [\App\Http\Controllers\UsersManagementController::class, 'generate_collection_report']);


Route::get('admin/all_stocks', [\App\Http\Controllers\UsersManagementController::class, 'all_stocks']);
Route::get('admin/stocks/{id}', [\App\Http\Controllers\UsersManagementController::class, 'get_stock']);
Route::post('admin/add_stock', [\App\Http\Controllers\UsersManagementController::class, 'create_stock']);
Route::get('admin/stocks/{id}/search', [\App\Http\Controllers\UsersManagementController::class, 'find_product']);



Route::get('admin/all_products', [\App\Http\Controllers\UsersManagementController::class, 'all_products']);
Route::get('admin/products/{id}', [\App\Http\Controllers\UsersManagementController::class, 'get_product']);
Route::post('admin/add_product', [\App\Http\Controllers\UsersManagementController::class, 'create_product']);


Route::get('admin/all_distributions', [\App\Http\Controllers\UsersManagementController::class, 'all_distributions']);
Route::get('admin/distributions/{id}', [\App\Http\Controllers\UsersManagementController::class, 'get_distribution']);
Route::post('admin/add_distribution', [\App\Http\Controllers\UsersManagementController::class, 'create_distribution']);
Route::get('admin/distributions/{id}/start', [\App\Http\Controllers\UsersManagementController::class, 'start_distribution']);
Route::get('admin/distributions/{id}/close', [\App\Http\Controllers\UsersManagementController::class, 'close_distribution']);
Route::get('admin/distributions/{id}/delete', [\App\Http\Controllers\UsersManagementController::class, 'delete_distribution']);

Route::get('admin/distributions/{id}/pdf', [\App\Http\Controllers\UsersManagementController::class, 'generate_distribution_report']);


Route::get('admin/all_beneficiaries', [\App\Http\Controllers\UsersManagementController::class, 'all_beneficiaries']);
Route::get('admin/beneficiaries/{id}', [\App\Http\Controllers\UsersManagementController::class, 'get_beneficiary']);



Route::post('merchant/{id}/service', [\App\Http\Controllers\UsersManagementController::class, 'request_service']);


Route::get('volunteer/{id}/schedule', [\App\Http\Controllers\UsersManagementController::class, 'get_schedule']);
Route::get('volunteer/{id}/all_schedules', [\App\Http\Controllers\UsersManagementController::class, 'get_all_schedules']);

// protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('auth:admin')->post('admin/logout', [\App\Http\Controllers\AdminAuthController::class, 'logout']);
    Route::middleware('auth:web')->post('merchant/logout', [\App\Http\Controllers\MerchantAuthController::class, 'logout']);
    Route::middleware('auth:volunteer')->post('volunteer/logout', [\App\Http\Controllers\VolunteerAuthController::class, 'logout']);
});
