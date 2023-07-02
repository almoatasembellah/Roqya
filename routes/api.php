<?php

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
Route::post('/auth/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], static function () {

//Auth routes
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::put('/update-profile', [ProfileController::class, 'update']);
    Route::get('/show-profile', [ProfileController::class, 'show']);
    Route::post('/logout', [AuthController::class, 'logout']);

});

