<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Questions\StoreController;
use Illuminate\Support\Facades\Route;

Route::post(
    uri: '/',
    action: StoreController::class,
)->name('store');
