<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\Categories\DeleteController;
use App\Http\Controllers\Admin\Categories\DeleteSubcategoryController;
use App\Http\Controllers\Admin\Categories\IndexController;
use App\Http\Controllers\Admin\Categories\ShowController;
use App\Http\Controllers\Admin\Categories\ShowSubcategoryController;
use App\Http\Controllers\Admin\Categories\StoreController;
use App\Http\Controllers\Admin\Categories\StoreSubcategoryController;
use App\Http\Controllers\Admin\Categories\UpdateController;
use App\Http\Controllers\Admin\Categories\UpdateSubcategoryController;
use Illuminate\Support\Facades\Route;

Route::get(
    uri: '/',
    action: IndexController::class,
)->name('index')->middleware('can:view-categories');

Route::get(
    uri: '/{category}',
    action: ShowController::class,
)->name('show');

Route::post(
    uri: '/',
    action: StoreController::class,
)->name('store')->middleware('can:create-categories');

Route::put(
    uri: '/{category}',
    action: UpdateController::class,
)->name('update')->middleware('can:edit-categories');

Route::delete(
    uri: '/{category}',
    action: DeleteController::class,
)->name('delete')->middleware('can:delete-categories');

Route::get(
    uri: '/{category}/subcategories/{subcategory}',
    action: ShowSubcategoryController::class,
)->name('subcatgories:show')->middleware('can:view-subcategories');

Route::post(
    uri: '/{category}/subcategories',
    action: StoreSubcategoryController::class,
)->name('subcatgories:store')->middleware('can:create-subcategories');

Route::put(
    uri: '/{category}/subcategories/{subcategory}',
    action: UpdateSubcategoryController::class,
)->name('subcatgories:update')->middleware('can:edit-subcategories');

Route::delete(
    uri: '/{category}/subcategories/{subcategory}',
    action: DeleteSubcategoryController::class,
)->name('subcatgories:destroy')->middleware('can:delete-subcategories');
