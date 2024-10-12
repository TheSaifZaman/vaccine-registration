<?php

use Illuminate\Support\Facades\Route;

use Modules\Registration\Http\Controllers\RegistrationController;
use Modules\Registration\Http\Controllers\SearchController;

Route::get('/', [RegistrationController::class, 'create'])->name('registration.create');
Route::get('/search', [SearchController::class, 'index'])->name('search.index');

