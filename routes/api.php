<?php

use App\Http\Controllers\Api\Admin\AdminController;
use App\Http\Controllers\Api\Admin\DocumentController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\FacebookController;
use App\Http\Controllers\Api\Auth\GoogleController;
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

//Google routes
Route::group(['middleware' => ['web']],function() {
    Route::controller(GoogleController::class)->group(function () {
        Route::get('auth/google', 'redirectToGoogle')->name('auth.google');
        Route::get('auth/google/callback', 'handleGoogleCallback');
    });
});

//facebook routes
Route::group(['middleware' => ['web']],function(){
    Route::controller(FacebookController::class)->group(function(){
        Route::get('auth/facebook', 'redirectToFacebook')->name('auth.facebook');
        Route::get('auth/facebook/callback', 'handleFacebookCallback');
    });
});

//User routes
Route::post('/auth/login', [AuthController::class, 'userLogin'])->name('login');
Route::post('/change-password', [AuthController::class, 'changePassword']);
Route::get('/show-profile', [ProfileController::class, 'profile'])->middleware('auth:sanctum');
Route::put('/update-profile', [ProfileController::class, 'update'])->middleware('auth:sanctum');
Route::post('/upload-profile-image', [ProfileController::class, 'uploadProfileImage'])->middleware('auth:sanctum');
Route::delete('/delete-profile-image', [ProfileController::class, 'deleteProfileImage'])->middleware('auth:sanctum');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/documents', [DocumentController::class, 'store'])->middleware('auth:sanctum');


//Admin routes
Route::post('/admin/login', [AdminController::class, 'adminLogin'])->name('admin.login-admin');
Route::get('/get-users', [AdminController::class, 'getAllUsers'])->middleware('auth:api');
Route::post('/admin/change-status', [AdminController::class, 'changeStatus'])->middleware('auth:api');
Route::post('/admin/logout', [AdminController::class, 'adminLogout'])->middleware('auth:api');
Route::get('/get-documents', [DocumentController::class, 'index'])->middleware('auth:api');


