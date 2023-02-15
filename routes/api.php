<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['cors']], function(){
    Route::post("contact", [App\Http\Controllers\ContactController::class, 'sendContact']);
    Route::post("user/create", [App\Http\Controllers\AuthController::class, 'register']);
    Route::post("user/login", [App\Http\Controllers\AuthController::class, 'login']);
    Route::post('password/email',  [App\Http\Controllers\ForgotPasswordController::class, '__invoke']);
    Route::post('password/code/check', [App\Http\Controllers\CodeCheckController::class, '__invoke']);
    Route::post('password/reset', [App\Http\Controllers\ResetPasswordController::class, '__invoke']);
});

Route::group(['middleware' => ['cors', 'auth:api']], function(){
    Route::post("admin/user/logout", [App\Http\Controllers\AuthController::class, 'logout']);
    Route::post("admin/user/details", [App\Http\Controllers\AuthController::class, 'details']);
    Route::post("admin/user/update", [App\Http\Controllers\AuthController::class, 'update']);

    //users
    Route::get('admin/users', [App\Http\Controllers\Admin\UserController::class, 'index']);
    Route::post('admin/users/create', [App\Http\Controllers\Admin\UserController::class, 'create']);
    Route::get('admin/users/bin', [App\Http\Controllers\Admin\UserController::class, 'bin']);
    Route::get('admin/users/{id}', [App\Http\Controllers\Admin\UserController::class, 'show']);
    Route::post('admin/users/{id}/update', [App\Http\Controllers\Admin\UserController::class, 'update']);
    Route::post('admin/users/{id}/delete', [App\Http\Controllers\Admin\UserController::class, 'delete']);
    Route::post('admin/users/{id}/restore', [App\Http\Controllers\Admin\UserController::class, 'restore']);

    //products
    // Route::get('admin/products', [App\Http\Controllers\Admin\ProductController::class, 'index']);
    // Route::post('admin/products/create', [App\Http\Controllers\Admin\ProductController::class, 'create']);
    // Route::get('admin/products/bin', [App\Http\Controllers\Admin\ProductController::class, 'bin']);
    // Route::get('admin/products/{id}', [App\Http\Controllers\Admin\ProductController::class, 'show']);
    // Route::post('admin/products/{id}/update', [App\Http\Controllers\Admin\ProductController::class, 'update']);
    // Route::post('admin/products/{id}/delete', [App\Http\Controllers\Admin\ProductController::class, 'delete']);
    // Route::post('admin/products/{id}/restore', [App\Http\Controllers\Admin\ProductController::class, 'restore']);
});