<?php

use App\Http\Controllers\Api\Admin\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
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
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/therapist/login', [AuthController::class, 'therapistLogin'])->name('Therapist-login');
//Auth routes

//facebook routes
Route::controller(FacebookController::class)->group(function(){
    Route::get('auth/facebook', 'redirectToFacebook')->name('auth.facebook');
    Route::get('auth/facebook/callback', 'handleFacebookCallback');
});

//User routes
Route::post('/auth/login', [AuthController::class, 'userLogin'])->name('login');
Route::post('/change-password', [AuthController::class, 'changePassword']);
Route::put('/update-profile', [ProfileController::class, 'update'])->middleware('auth:sanctum');
Route::get('/show-profile', [ProfileController::class, 'profile'])->middleware('auth:sanctum');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


//Admin routes
Route::post('/admin/login', [AdminController::class, 'adminLogin'])->name('admin.login-admin');
Route::get('/get-users', [AdminController::class, 'getAllUsers'])->middleware('auth:api');
Route::post('/admin/change-status', [AdminController::class, 'changeStatus'])->middleware('auth:api');
Route::post('/admin/logout', [AdminController::class, 'adminLogout'])->middleware('auth:api');

