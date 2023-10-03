<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Images\ShowController;
use Illuminate\Support\Facades\Route;

Route::get(
    uri: '/{path}',
    action: ShowController::class
)->where('path', '.+')->name('show');
