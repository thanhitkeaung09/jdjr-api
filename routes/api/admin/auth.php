<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\LogoutController;
use Illuminate\Support\Facades\Route;

Route::post(
    uri: '/login',
    action: LoginController::class,
)->name('login');

Route::post(
    uri: '/logout',
    action: LogoutController::class,
)->name('logout')->middleware('auth:admin');
