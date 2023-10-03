<?php

declare(strict_types=1);

use App\Http\Controllers\V1\News\IndexController;
use App\Http\Controllers\V1\News\LikeController;
use App\Http\Controllers\V1\News\ReadController;
use App\Http\Controllers\V1\News\ShowController;
use Illuminate\Support\Facades\Route;

Route::get(
    uri: '/',
    action: IndexController::class,
)->name('index');

Route::get(
    uri: '/{news}',
    action: ShowController::class,
)->name('show');

Route::post(
    uri: '/{news}/likes',
    action: LikeController::class,
)->name('like');

Route::post(
    uri: '/{news}/reads',
    action: ReadController::class,
)->name('like');
