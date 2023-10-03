<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Opts\ConfirmController;
use App\Http\Controllers\V1\Opts\ResendController;
use Illuminate\Support\Facades\Route;

Route::post(
    uri: '/',
    action: ConfirmController::class,
)->name('confirm');

Route::post(
    uri: '/resend',
    action: ResendController::class,
)->name('resend');
