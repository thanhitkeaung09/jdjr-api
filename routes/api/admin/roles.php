<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\Roles\DeleteController;
use App\Http\Controllers\Admin\Roles\IndexController;
use App\Http\Controllers\Admin\Roles\ShowController;
use App\Http\Controllers\Admin\Roles\StoreController;
use App\Http\Controllers\Admin\Roles\UpdateController;
use Illuminate\Support\Facades\Route;

Route::get(
    uri: '/',
    action: IndexController::class,
)->name('index')->middleware('can:view-roles');

Route::get(
    uri: '/{role}',
    action: ShowController::class,
)->name('show');

Route::post(
    uri: '/',
    action: StoreController::class,
)->name('store')->middleware('can:create-roles');

Route::put(
    uri: '/{role}',
    action: UpdateController::class,
)->name('update')->middleware('can:edit-roles');

Route::delete(
    uri: '/{role}',
    action: DeleteController::class,
)->name('destroy')->middleware('can:delete-roles');
