<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\Jobs\DeleteController;
use App\Http\Controllers\Admin\Jobs\IndexController;
use App\Http\Controllers\Admin\Jobs\PopularController;
use App\Http\Controllers\Admin\Jobs\ShowController;
use App\Http\Controllers\Admin\Jobs\StoreController;
use App\Http\Controllers\Admin\Jobs\UpdateController;
use Illuminate\Support\Facades\Route;

Route::get(
    uri: '/',
    action: IndexController::class,
)->name('index')->middleware('can:view-jobs');

Route::get(
    uri: '/{job}',
    action: ShowController::class,
)->name('show');

Route::post(
    uri: '/',
    action: StoreController::class,
)->name('store')->middleware('can:create-jobs');

Route::put(
    uri: '/{job}',
    action: UpdateController::class,
)->name('update')->middleware('can:edit-jobs');

Route::delete(
    uri: '/{job}',
    action: DeleteController::class,
)->name('delete')->middleware('can:delete-jobs');

Route::get(
    uri: '/{job}/popular',
    action: PopularController::class,
)->name('popular')->middleware('can:jobs-popular');
