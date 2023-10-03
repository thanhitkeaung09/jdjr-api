<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Auth\AppleLoginController;
use App\Http\Controllers\V1\Auth\EmailLoginController;
use App\Http\Controllers\V1\Auth\EmailRegisterController;
use App\Http\Controllers\V1\Auth\ForgotPasswordController;
use App\Http\Controllers\V1\Auth\LogoutController;
use App\Http\Controllers\V1\Auth\SocialLoginController;
use Illuminate\Support\Facades\Route;

Route::post(
    uri: 'apple-login',
    action: AppleLoginController::class,
)->name('apple.login');

Route::post(
    uri: '/{type}/login',
    action: SocialLoginController::class,
)->name('login');

Route::post(
    uri: '/register',
    action: EmailRegisterController::class,
)->name('register');

Route::post(
    uri: '/email-login',
    action: EmailLoginController::class,
)->name('email:login');

Route::post(
    uri: '/forgot-password',
    action: ForgotPasswordController::class,
)->name('forgot-password');

Route::delete(
    uri: '/logout',
    action: LogoutController::class,
)->name('logout')->middleware('auth:sanctum');
