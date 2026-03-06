<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\SaleController;
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

Route::middleware('auth:sanctum')->group(function () {
    // Clients
    Route::get('clients', [ClientController::class, 'index']);
    Route::post('clients', [ClientController::class, 'store']);
    Route::put('clients/{client}', [ClientController::class, 'update']);
    Route::delete('clients/{client}', [ClientController::class, 'destroy']);

    // Sales
    Route::get('sales', [SaleController::class, 'index']);
    Route::post('sales', [SaleController::class, 'store']);
    Route::post('sales/{sale}/approve', [SaleController::class, 'approve']);
});