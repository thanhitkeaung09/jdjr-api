<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\Permissions\IndexController;
use Illuminate\Support\Facades\Route;

Route::get(
    uri: '/',
    action: IndexController::class,
)->name('index');
