<?php

use Illuminate\Support\Facades\Route;
use Modules\Registration\Http\Controllers\RegistrationController;
use Modules\Registration\Http\Controllers\SearchController;

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

Route::prefix(CURRENT_API_VERSION)->group(function () {
    Route::post('registrations', [RegistrationController::class, 'store'])->name('registration.store');
    Route::post('search', [SearchController::class, 'search'])->name('search.result');
});
