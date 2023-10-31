<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\UnauthorizedResponse;

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

Route::post('/register',[App\Http\Controllers\Api\Auth\AuthController::class, 'register']);
Route::post('/login',[App\Http\Controllers\Api\Auth\AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group( function () {
    Route::get('/user', [\App\Http\Controllers\Api\Auth\UserController::class, 'user']);
    Route::post('/logout', [\App\Http\Controllers\Api\Auth\AuthController::class, 'logout']);
    Route::resource('motivation', \App\Http\Controllers\Api\MotivationController::class);
});


// Middleware untuk respons JSON pada kasus Unauthorized
Route::get('unauthorized', function () {
    return response()->json([
        'code' => 401,
        'status' => 'UNAUTHORIZED',
        'message' => 'Authentication required',
    ], 401);
})->middleware('unauthorized')->name('unauthorized');

