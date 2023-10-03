<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\Locations\DeleteController;
use App\Http\Controllers\Admin\Locations\IndexController;
use App\Http\Controllers\Admin\Locations\ShowController;
use App\Http\Controllers\Admin\Locations\StoreController;
use App\Http\Controllers\Admin\Locations\UpdateController;
use Illuminate\Support\Facades\Route;

Route::get(
    uri: '/',
    action: IndexController::class,
)->name('index')->middleware('permission:view-locations|create-jobs|edit-jobs');

Route::get(
    uri: '/{location}',
    action: ShowController::class,
)->name('show');

Route::post(
    uri: '/',
    action: StoreController::class,
)->name('store')->middleware('can:create-locations');

Route::put(
    uri: '/{location}',
    action: UpdateController::class,
)->name('update')->middleware('can:edit-locations');

Route::delete(
    uri: '/{location}',
    action: DeleteController::class,
)->name('delete')->middleware('can:delete-locations');
