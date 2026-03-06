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
    Route::get('clients', [ClientController::class, 'index'])
        ->middleware('permission:clients.view');

    Route::post('clients', [ClientController::class, 'store'])
        ->middleware('permission:clients.create');

    Route::put('clients/{client}', [ClientController::class, 'update'])
        ->middleware('permission:clients.update');

    Route::delete('clients/{client}', [ClientController::class, 'destroy'])
        ->middleware('permission:clients.update');

    // Sales
    Route::get('sales', [SaleController::class, 'index'])
        ->middleware('permission:reports.view');

    Route::post('sales', [SaleController::class, 'store'])
        ->middleware('permission:sales.create');

    Route::post('sales/{sale}/approve', [SaleController::class, 'approve'])
        ->middleware('permission:sales.approve');
});