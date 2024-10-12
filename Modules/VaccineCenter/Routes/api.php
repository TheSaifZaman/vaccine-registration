<?php

use Illuminate\Support\Facades\Route;
use Modules\VaccineCenter\Http\Controllers\VaccineCenterController;

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
    Route::get('dropdown/vaccine-centers', [VaccineCenterController::class, 'indexOfDropdown'])->name('vaccine_centers.index_of_dropdown');
});
