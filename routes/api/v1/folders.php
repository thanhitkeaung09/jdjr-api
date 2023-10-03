<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Folders\DeleteController;
use App\Http\Controllers\V1\Folders\IndexController;
use App\Http\Controllers\V1\Folders\ShowController;
use App\Http\Controllers\V1\Folders\StoreController;
use App\Http\Controllers\V1\Folders\UpdateController;
use Illuminate\Support\Facades\Route;

Route::get(
    uri: '/',
    action: IndexController::class,
)->name('index');

Route::post(
    uri: '/',
    action: StoreController::class,
)->name('store');

Route::get(
    uri: '/{folder}',
    action: ShowController::class,
)->name('show');

Route::put(
    uri: '/{folder}',
    action: UpdateController::class,
)->name('update');

Route::delete(
    uri: '/{folder}',
    action: DeleteController::class,
)->name('destroy');
