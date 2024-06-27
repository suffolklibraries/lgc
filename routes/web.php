<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\EventSubmissionController;
use App\Http\Controllers\InappropriateContentReportController;
use App\Http\Controllers\SavedAddressController;
use App\Http\Middleware\MustBeLoggedIn;

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

    Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.', 'middleware' => MustBeLoggedIn::class], function() {
        Route::get('/', [UserDashboardController::class, 'index'])->name('index');
        Route::get('my-details', [UserDashboardController::class, 'details'])->name('my-details.index');
        Route::post('my-details', [UserDashboardController::class, 'updateDetails'])->name('my-details.update');

        Route::get('my-organisation', [UserDashboardController::class, 'organisation'])->name('my-organisation.index');
        Route::post('my-organisation', [UserDashboardController::class, 'updateOrganisationDetails'])->name('my-organisation.update');

        Route::get('my-events', [UserDashboardController::class, 'events'])->name('my-events.index');
        Route::get('my-events/create', [UserDashboardController::class, 'createEvent'])->name('my-events.create');
        Route::get('my-events/{entryId}/edit', [UserDashboardController::class, 'editEvent'])->name('my-events.edit');
        Route::post('my-events/{entryId}/update', [UserDashboardController::class, 'updateEvent'])->name('my-events.update')->middleware(ProtectAgainstSpam::class);
        Route::delete('my-events/{entryId}/delete', [UserDashboardController::class, 'deleteEvent'])->name('my-events.delete')->middleware(ProtectAgainstSpam::class);
        Route::post('my-events/store', [UserDashboardController::class, 'storeEvent'])->name('my-events.store')->middleware(ProtectAgainstSpam::class);
        Route::post('my-events/save-draft/{entryId?}', [UserDashboardController::class, 'saveDraft'])->name('my-events.save-draft')->middleware(ProtectAgainstSpam::class);
        Route::post('my-events/{entryId}/update-draft', [UserDashboardController::class, 'updateDraft'])->name('my-events.update-draft')->middleware(ProtectAgainstSpam::class);

        Route::group([
            'prefix' => 'addresses',
            'as' => 'saved-addresses.',
            'middleware' => MustBeLoggedIn::class
        ], function() {

            Route::get('/', [SavedAddressController::class, 'index'])->name('index');
            Route::get('create', [SavedAddressController::class, 'create'])->name('create');
            Route::get('edit/{id}', [SavedAddressController::class, 'edit'])->name('edit');

            Route::middleware(ProtectAgainstSpam::class)->group(function() {
                Route::post('store', [SavedAddressController::class, 'store'])->name('store');
                Route::post('update/{id}', [SavedAddressController::class, 'update'])->name('update');
                Route::delete('delete/{id}', [SavedAddressController::class, 'delete'])->name('delete');
            });

        });
    });

    Route::post('logout', LogoutController::class)->name('logout')->middleware(MustBeLoggedIn::class);
});

Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('login', [LoginController::class, 'store'])->name('login.store')->middleware(ProtectAgainstSpam::class);

Route::get('password/reset', [ForgotPasswordController::class, 'index'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email')->middleware(ProtectAgainstSpam::class);

Route::post('report-content/{entryId}', [InappropriateContentReportController::class, 'store'])
    ->name('report-content')
    ->middleware(ProtectAgainstSpam::class);
