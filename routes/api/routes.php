<?php

declare(strict_types=1);

use App\Http\Controllers\FallbackController;
use Illuminate\Support\Facades\Route;

/**
 * Version-1
 */
Route::prefix('v1')->as('v1:')->group(static function (): void {
    /**
     * Auth
     */
    Route::prefix('auth')->as('auth:')->middleware('check.app.key')->group(
        base_path('/routes/api/v1/auth.php')
    );

    /**
     * Ping
     */
    Route::prefix('ping')->as('ping:')->group(
        base_path('/routes/api/v1/ping.php')
    );

    /**
     * Users
     */
    Route::prefix('users')->as('users:')->middleware(['check.app.key', 'auth:sanctum'])->group(
        base_path('/routes/api/v1/users.php')
    );

    /**
     * OTP
     */
    Route::prefix('otp')->as('otp:')->middleware('check.app.key')->group(
        base_path('/routes/api/v1/otp.php')
    );

    /**
     * Categories (User Interests)
     */
    Route::prefix('categories')->as('categories:')->middleware('check.app.key')->group(
        base_path('/routes/api/v1/categories.php')
    );

    /**
     * Experiences
     */
    Route::prefix('experiences')->as('experiences:')->middleware('check.app.key')->group(
        base_path('/routes/api/v1/experiences.php')
    );

    /**
     * Skills
     */
    Route::prefix('skills')->as('skills:')->middleware('check.app.key')->group(
        base_path('/routes/api/v1/skills.php')
    );

    /**
     * Folders (User Folders)
     */
    Route::prefix('folders')->as('folders:')->middleware(['check.app.key', 'auth:sanctum'])->group(
        base_path('/routes/api/v1/folders.php')
    );

    /**
     * News
     */
    Route::prefix('news')->as('news:')->middleware(['check.app.key', 'auth:sanctum'])->group(
        base_path('/routes/api/v1/news.php')
    );

    /**
     * Locations
     */
    Route::prefix('locations')->as('locations:')->middleware(['check.app.key', 'auth:sanctum'])->group(
        base_path('/routes/api/v1/locations.php')
    );

    /**
     * Jobs
     */
    Route::prefix('jobs')->as('jobs:')->middleware(['check.app.key', 'auth:sanctum'])->group(
        base_path('/routes/api/v1/jobs.php')
    );

    /**
     * Saves
     */
    Route::prefix('saves')->as('saves:')->middleware(['check.app.key', 'auth:sanctum'])->group(
        base_path('/routes/api/v1/saves.php')
    );

    /**
     * App Versions
     */
    Route::prefix('app-versions')->as('app-versions:')->middleware(['check.app.key'])->group(
        base_path('/routes/api/v1/app-versions.php')
    );

    /**
     * Notifications
     */
    Route::prefix('notifications')->as('notifications:')->middleware(['check.app.key', 'auth:sanctum'])->group(
        base_path('/routes/api/v1/notifications.php')
    );

    /**
     * Questions
     */
    Route::prefix('questions')->as('questions:')->middleware(['check.app.key', 'auth:sanctum'])->group(
        base_path('/routes/api/v1/questions.php')
    );

    /**
     * Image
     */
    Route::prefix('images')->as('images:')->group(
        base_path('/routes/api/v1/images.php')
    );
});

/**
 * Admin Routes
 */
Route::prefix('admin')->as('admin:')->group(
    base_path('/routes/api/admin/routes.php')
);

/**
 * Fallback
 */
Route::fallback(
    action: FallbackController::class,
);
