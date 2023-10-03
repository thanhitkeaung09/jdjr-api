<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Users\DeleteController;
use App\Http\Controllers\V1\Users\GetLikedController;
use App\Http\Controllers\V1\Users\GetQuestionsController;
use App\Http\Controllers\V1\Users\RecentlyReadController;
use App\Http\Controllers\V1\Users\ShowController;
use App\Http\Controllers\V1\Users\UpdateController;
use Illuminate\Support\Facades\Route;

Route::get(
    uri: '/',
    action: ShowController::class,
)->name('show');

Route::put(
    uri: '/',
    action: UpdateController::class,
)->name('update');

Route::get(
    uri: '/liked-news',
    action: GetLikedController::class,
)->name('liked:news');

Route::get(
    uri: '/recently-read-news',
    action: RecentlyReadController::class,
)->name('recently:read:news');

Route::get(
    uri: '/questions',
    action: GetQuestionsController::class,
)->name('questions');

Route::delete(
    uri: '/{user}',
    action: DeleteController::class,
)->name('delete');
