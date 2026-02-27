<?php

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
