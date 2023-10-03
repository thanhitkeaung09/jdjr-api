<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Experiences\AllLevelsController;
use App\Http\Controllers\V1\Experiences\IndexController;
use Illuminate\Support\Facades\Route;

Route::get(
    uri: '/',
    action: IndexController::class,
)->name('index');

Route::get(
    uri: '/levels',
    action: AllLevelsController::class,
)->name('levels')->middleware('auth:sanctum');
