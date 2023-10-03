<?php

declare(strict_types=1);

use App\Http\Controllers\V1\AppVersions\ShowController;
use Illuminate\Support\Facades\Route;

Route::get(
    uri: '/',
    action: ShowController::class,
)->name('show');
