<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\News\DeleteController;
use App\Http\Controllers\Admin\News\IndexController;
use App\Http\Controllers\Admin\News\ShowController;
use App\Http\Controllers\Admin\News\StoreController;
use App\Http\Controllers\Admin\News\UpdateController;
use Illuminate\Support\Facades\Route;

Route::get(
    uri: '/',
    action: IndexController::class,
)->name('index')->middleware('can:view-news');

Route::get(
    uri: '/{news}',
    action: ShowController::class,
)->name('show');

Route::post(
    uri: '/',
    action: StoreController::class,
)->name('store')->middleware('can:create-news');

Route::put(
    uri: '/{news}',
    action: UpdateController::class,
)->name('update')->middleware('can:edit-news');

Route::delete(
    uri: '/{news}',
    action: DeleteController::class,
)->name('delete')->middleware('can:delete-news');
