<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Skills\IndexController;
use Illuminate\Support\Facades\Route;

Route::post(
    uri: '/categories',
    action: IndexController::class,
)->name('index');
