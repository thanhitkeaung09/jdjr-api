<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Notifications\IndexController;
use App\Http\Controllers\V1\Notifications\ReadController;
use Illuminate\Support\Facades\Route;

Route::get(
    uri: '/',
    action: IndexController::class,
)->name('index');

Route::post(
    uri: '/{notification}',
    action: ReadController::class,
)->name('read');
