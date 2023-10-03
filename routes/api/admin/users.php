<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\Users\DeleteController;
use App\Http\Controllers\Admin\Users\IndexController;
use App\Http\Controllers\Admin\Users\ShowController;
use App\Http\Controllers\Admin\Users\UpdateController;
use Illuminate\Support\Facades\Route;

Route::get(
    uri: '/',
    action: IndexController::class,
)->name('index')->middleware('can:view-users');

Route::get(
    uri: '/{user}',
    action: ShowController::class,
)->name('show');

Route::put(
    uri: '/{user}',
    action: UpdateController::class,
)->name('update')->middleware('can:edit-users');

Route::delete(
    uri: '/{user}',
    action: DeleteController::class,
)->name('destroy')->middleware('can:delete-users');
