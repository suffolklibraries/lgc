<?php

use App\Http\Controllers\Api\EventController;
use App\Http\Middleware\TokenAuthentication;
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

Route::middleware(TokenAuthentication::class)->group(function () {
    Route::get('/events', [EventController::class, 'index']);
});
