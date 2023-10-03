<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Locations\IndexController;
use App\Http\Controllers\V1\Locations\ShowController;
use Illuminate\Support\Facades\Route;

Route::get(
    uri: '/',
    action: IndexController::class,
)->name('index');

Route::get(
    uri: '/{location}',
    action: ShowController::class,
)->name('show');
