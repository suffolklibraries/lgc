<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventSubmissionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('submit-event', [EventSubmissionController::class, 'store'])
    ->name('event-submission.store')
    ->middleware(ProtectAgainstSpam::class);

Route::group(['as' => 'user.'], function(){
    Route::get('register', [UserController::class, 'register'])->name('register');
    Route::post('register', [UserController::class, 'store'])
        ->name('register.store')
        ->middleware(ProtectAgainstSpam::class);

    Route::get('dashboard', function() {
        ddd(Auth::user());
    })->name('dashboard');
});
