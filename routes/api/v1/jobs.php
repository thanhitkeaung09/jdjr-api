<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Jobs\IndexController;
use App\Http\Controllers\V1\Jobs\PopularSearchesController;
use App\Http\Controllers\V1\Jobs\RelatedJobsController;
use App\Http\Controllers\V1\Jobs\SearchController;
use App\Http\Controllers\V1\Jobs\ShowController;
use Illuminate\Support\Facades\Route;

Route::get(
    uri: '/',
    action: IndexController::class,
)->name('index');

Route::get(
    uri: '/search',
    action: SearchController::class,
)->name('search');

Route::get(
    uri: '/{job}',
    action: ShowController::class,
)->name('show');

Route::get(
    uri: '/{job}/related',
    action: RelatedJobsController::class,
)->name('related');

Route::get(
    uri: '/popular/searches',
    action: PopularSearchesController::class,
)->name('popular');
