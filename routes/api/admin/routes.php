<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

/**
 * Auth Routes
 */
Route::prefix('auth')->as('auth:')->group(
    base_path('/routes/api/admin/auth.php')
);

Route::middleware('auth:admin')->group(function (): void {

    /**
     * App Versions Routes
     */
    Route::prefix('app-versions')->as('app-versions:')->group(
        base_path('/routes/api/admin/app-versions.php')
    );

    /**
     * Users Routes
     */
    Route::prefix('users')->as('users:')->group(
        base_path('/routes/api/admin/users.php')
    );

    /**
     * Admins Routes
     */
    Route::prefix('admins')->as('admins:')->group(
        base_path('/routes/api/admin/admins.php')
    );

    /**
     * Roles Routes
     */
    Route::prefix('roles')->as('roles:')->group(
        base_path('/routes/api/admin/roles.php')
    );

    /**
     * Permissions Routes
     */
    Route::prefix('permissions')->as('permissions:')->group(
        base_path('/routes/api/admin/permissions.php')
    );

    /**
     * Categories (Interests) Routes
     */
    Route::prefix('categories')->as('categories:')->group(
        base_path('/routes/api/admin/categories.php')
    );

    /**
     * Subcategories Routes
     */
    Route::prefix('subcategories')->as('subcategories:')->group(
        base_path('/routes/api/admin/subcategories.php')
    );

    /**
     * News Routes
     */
    Route::prefix('news')->as('news:')->group(
        base_path('/routes/api/admin/news.php')
    );

    /**
     * Skills Routes
     */
    Route::prefix('skills')->as('skills:')->group(
        base_path('/routes/api/admin/skills.php')
    );

    /**
     * Tools Routes
     */
    Route::prefix('tools')->as('tools:')->group(
        base_path('/routes/api/admin/tools.php')
    );

    /**
     * Experiences Routes
     */
    Route::prefix('experiences')->as('experiences:')->group(
        base_path('/routes/api/admin/experiences.php')
    );

    /**
     * Levels Routes
     */
    Route::prefix('levels')->as('levels:')->group(
        base_path('/routes/api/admin/levels.php')
    );

    /**
     * Locations Routes
     */
    Route::prefix('locations')->as('locations:')->group(
        base_path('/routes/api/admin/locations.php')
    );

    /**
     * Questions Routes
     */
    Route::prefix('questions')->as('questions:')->group(
        base_path('/routes/api/admin/questions.php')
    );

    /**
     * Jobs Routes
     */
    Route::prefix('jobs')->as('jobs:')->group(
        base_path('/routes/api/admin/jobs.php')
    );

    Route::get('/dashboard', DashboardController::class)->name('dashboard');
});
