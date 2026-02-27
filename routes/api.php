<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register all of the routes for your API. These
| routes are loaded by the framework within a group which is assigned
| the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/status', function () {
    return [
        'status' => 'ok',
    ];
});

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])
        ->middleware('auth:sanctum');
    Route::get('userAutenticate', [AuthController::class, 'userAutenticate'])
        ->middleware('auth:sanctum');
});