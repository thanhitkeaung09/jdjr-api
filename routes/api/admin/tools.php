<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\Tools\DeleteController;
use App\Http\Controllers\Admin\Tools\IndexController;
use App\Http\Controllers\Admin\Tools\ShowController;
use App\Http\Controllers\Admin\Tools\StoreController;
use App\Http\Controllers\Admin\Tools\UpdateController;
use Illuminate\Support\Facades\Route;

Route::get(
    uri: '/',
    action: IndexController::class,
)->name('index')->middleware('permission:view-tools|create-jobs|edit-jobs');

Route::get(
    uri: '/{tool}',
    action: ShowController::class,
)->name('show');

Route::post(
    uri: '/',
    action: StoreController::class,
)->name('store')->middleware('can:create-tools');

Route::put(
    uri: '/{tool}',
    action: UpdateController::class,
)->name('update')->middleware('can:edit-tools');

Route::delete(
    uri: '/{tool}',
    action: DeleteController::class,
)->name('delete')->middleware('can:delete-tools');
