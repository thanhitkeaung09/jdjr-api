<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AppVersions\IndexController;
use App\Http\Controllers\Admin\AppVersions\ShowController;
use App\Http\Controllers\Admin\AppVersions\UpdateController;
use Illuminate\Support\Facades\Route;

Route::get(
    uri: '/',
    action: IndexController::class,
)->name('index');

Route::get(
    uri: '/{appVersion}',
    action: ShowController::class,
)->name('show');

Route::put(
    uri: '/{appVersion}',
    action: UpdateController::class,
)->name('update');
