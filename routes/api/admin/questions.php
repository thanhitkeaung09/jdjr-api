<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\Questions\DeleteController;
use App\Http\Controllers\Admin\Questions\IndexController;
use App\Http\Controllers\Admin\Questions\ShowController;
use App\Http\Controllers\Admin\Questions\UpdateController;
use Illuminate\Support\Facades\Route;

Route::get(
    uri: '/{answer}/all',
    action: IndexController::class,
)->name('index')->middleware('can:view-questions');

Route::get(
    uri: '/{question}',
    action: ShowController::class,
)->name('show');

Route::put(
    uri: '/{question}',
    action: UpdateController::class,
)->name('update')->middleware('can:edit-questions');

Route::delete(
    uri: '/{question}',
    action: DeleteController::class,
)->name('delete')->middleware('can:delete-questions');
