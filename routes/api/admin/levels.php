<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\Levels\DeleteController;
use App\Http\Controllers\Admin\Levels\IndexController;
use App\Http\Controllers\Admin\Levels\ShowController;
use App\Http\Controllers\Admin\Levels\StoreController;
use App\Http\Controllers\Admin\Levels\UpdateController;
use Illuminate\Support\Facades\Route;

Route::get(
    uri: '/',
    action: IndexController::class,
)->name('index')->middleware('can:view-levels');

Route::get(
    uri: '/{level}',
    action: ShowController::class,
)->name('show');

Route::post(
    uri: '/',
    action: StoreController::class,
)->name('store')->middleware('can:create-levels');
;

Route::put(
    uri: '/{level}',
    action: UpdateController::class,
)->name('update')->middleware('can:edit-levels');
;

Route::delete(
    uri: '/{level}',
    action: DeleteController::class,
)->name('delete')->middleware('can:delete-levels');
;
