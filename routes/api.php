<?php

use App\Http\Controllers\Api\Admin\AdminController;
use App\Http\Controllers\Api\Admin\DocumentController;
use App\Http\Controllers\Api\Therapist\ConclaveController;
use App\Http\Controllers\Api\Therapist\TherapistController;
use App\Http\Controllers\Api\User\AuthController;
use App\Http\Controllers\Api\User\GoogleController;
use App\Http\Controllers\Api\User\ProfileController;
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
//Auth routes
Route::get('/auth/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
Route::post('/auth/register', [AuthController::class, 'register']);

//Google routes
Route::group(['middleware' => ['web']],function() {
    Route::controller(GoogleController::class)->group(function () {
        Route::get('auth/google', 'redirectToGoogle')->name('auth.google');
        Route::get('auth/google/callback', 'handleGoogleCallback');
    });
});


//User routes
Route::post('/auth/login', [AuthController::class, 'userLogin'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/change-password', [ProfileController::class, 'changePassword']);
    Route::get('/show-profile', [ProfileController::class, 'profile']);
    Route::put('/update-profile', [ProfileController::class, 'update']);
    Route::post('/upload-profile-image', [ProfileController::class, 'uploadProfileImage']);
    Route::delete('/delete-profile-image', [ProfileController::class, 'deleteProfileImage']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/documents', [DocumentController::class, 'store']);
    Route::get('/search-conclave', [ConclaveController::class, 'search']);
});


//Admin routes
Route::post('/admin/login', [AdminController::class, 'adminLogin'])->name('admin.login-admin');
Route::middleware('auth:sanctum')->group(function (){

});
Route::get('/get-users', [AdminController::class, 'getAllUsers'])->middleware('auth:api');
Route::post('/admin/change-status', [AdminController::class, 'changeStatus'])->middleware('auth:api');
Route::post('/admin/logout', [AdminController::class, 'adminLogout'])->middleware('auth:api');
Route::get('/get-documents', [DocumentController::class, 'index'])->middleware('auth:api');


//Therapist routes
Route::post('/auth/therapist/login', [AuthController::class, 'therapistLogin'])->name('Therapist-login');
Route::middleware('auth:api')->group(function() {
Route::get('/therapist-profile', [TherapistController::class, 'therapistProfile'])->name('Therapist-profile');
    //conclave routes
    Route::post('/create-conclave', [ConclaveController::class, 'store']);
    Route::get('/all-conclaves', [ConclaveController::class, 'index']);
    Route::get('/my-conclaves', [ConclaveController::class, 'ownConclaves']);
    Route::get('/specific-conclaves',[ConclaveController::class, 'getTherapistConclaves']);
    Route::put('/conclaves/{id}', [ConclaveController::class, 'update']);
    Route::delete('/conclaves/{id}', [ConclaveController::class, 'destroy']);
});

//Route::get('/conclaves/top-rated', [ConclaveController::class, 'topRated']);
//Route::get('/conclaves/upcoming', [ConclaveController::class, 'upcoming']);
//Route::get('/conclaves/newest', [ConclaveController::class, 'newest']);


//facebook routes
//Route::group(['middleware' => ['web']],function(){
//    Route::controller(FacebookController::class)->group(function(){
//        Route::get('auth/facebook', 'redirectToFacebook')->name('auth.facebook');
//        Route::get('auth/facebook/callback', 'handleFacebookCallback');
//    });
//});
