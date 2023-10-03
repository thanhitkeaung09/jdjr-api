<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\Skills\DeleteController;
use App\Http\Controllers\Admin\Skills\IndexController;
use App\Http\Controllers\Admin\Skills\ShowController;
use App\Http\Controllers\Admin\Skills\StoreController;
use App\Http\Controllers\Admin\Skills\UpdateController;
use Illuminate\Support\Facades\Route;

Route::get(
    uri: '/',
    action: IndexController::class,
)->name('index')->middleware('permission:view-skills|create-jobs|edit-jobs');

Route::get(
    uri: '/{skill}',
    action: ShowController::class,
)->name('show');

Route::post(
    uri: '/',
    action: StoreController::class,
)->name('store')->middleware('can:create-skills');

Route::put(
    uri: '/{skill}',
    action: UpdateController::class,
)->name('update')->middleware('can:edit-skills');

Route::delete(
    uri: '/{skill}',
    action: DeleteController::class,
)->name('delete')->middleware('can:delete-skills');
