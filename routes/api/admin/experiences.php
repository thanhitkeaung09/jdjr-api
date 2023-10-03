<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\Experiences\DeleteController;
use App\Http\Controllers\Admin\Experiences\IndexController;
use App\Http\Controllers\Admin\Experiences\ShowController;
use App\Http\Controllers\Admin\Experiences\StoreController;
use App\Http\Controllers\Admin\Experiences\UpdateController;
use Illuminate\Support\Facades\Route;

Route::get(
    uri: '/',
    action: IndexController::class,
)->name('index')->middleware('permission:view-experiences|create-jobs|edit-jobs');

Route::get(
    uri: '/{experience}',
    action: ShowController::class,
)->name('show');

Route::post(
    uri: '/',
    action: StoreController::class,
)->name('store')->middleware('can:create-experiences');

Route::put(
    uri: '/{experience}',
    action: UpdateController::class,
)->name('update')->middleware('can:edit-experiences');

Route::delete(
    uri: '/{experience}',
    action: DeleteController::class,
)->name('delete')->middleware('can:delete-experiences');
