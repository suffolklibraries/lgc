<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserDashboardController;
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

    Route::get('register', [RegisterController::class, 'index'])->name('register');
    Route::post('register', [RegisterController::class, 'store'])
        ->name('register.store')
        ->middleware(ProtectAgainstSpam::class);

    Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function() {
        Route::get('/', [UserDashboardController::class, 'index'])->name('index');
        Route::get('my-details', [UserDashboardController::class, 'details'])->name('my-details.index');
        Route::post('my-details', [UserDashboardController::class, 'updateDetails'])->name('my-details.update');

        Route::get('my-organisation', [UserDashboardController::class, 'organisation'])->name('my-organisation.index');
        Route::post('my-organisation', [UserDashboardController::class, 'updateOrganisationDetails'])->name('my-organisation.update');
    })->middleware(['auth']);

    Route::post('logout', LogoutController::class)->name('logout')->middleware(['auth']);
});

Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('login', [LoginController::class, 'store'])->name('login.store')->middleware(ProtectAgainstSpam::class);
