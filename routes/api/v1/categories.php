<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Categories\IndexController;
use App\Http\Controllers\V1\Categories\JobTitlesController;
use App\Http\Controllers\V1\Categories\ShowController;
use Illuminate\Support\Facades\Route;

Route::get(
    uri: '/',
    action: IndexController::class,
)->name('index');

Route::post(
    uri: '/job-titles',
    action: JobTitlesController::class,
)->name('job-titles');

Route::get(
    uri: '/{category}/subcategories',
    action: ShowController::class,
)->name('show');
