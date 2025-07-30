<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FileController;
use App\Http\Controllers\Admin\MediaManagerController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingsController;
use Illuminate\Support\Facades\Route;

// Admin Routes
Route::prefix('dashboard')->name('admin.')->group(function () {

    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);

    // Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    // Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    // Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    // Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

    // Authenticated Routes
    Route::middleware('admin.auth')->group(function () {
        Route::post('logout', [LoginController::class, 'logout'])->name('logout');

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // // Profile Management
        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

        // Admin management
        Route::resource('admins', AdminController::class);

        // Role management
        Route::resource('roles', RoleController::class);

        Route::post('settings/update', [SettingsController::class, 'update'])->name('settings.update');
        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('settings/clear-cache', [SettingsController::class, 'clearCache'])->name('settings.clear-cache');

        Route::get('media', [MediaManagerController::class, 'mediaManager'])->name('media.index');
        Route::post('media/upload', [MediaManagerController::class, 'uploadMedia'])->name('media.upload');
        Route::delete('media/{id}', [MediaManagerController::class, 'deleteMedia'])->name('media.delete');

        Route::resource('categories', CategoryController::class);
        Route::get('categories-tree', [CategoryController::class, 'tree'])->name('categories.tree');
        Route::post('categories-reorder', [CategoryController::class, 'reorder'])->name('categories.reorder');
        Route::get('categories-search/{query?}', [CategoryController::class, 'search'])->name('categories.search');

        // File management
        Route::resource('files', FileController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);
        Route::get('files/{file}/download', [FileController::class, 'download'])->name('files.download');
        Route::get('files/statistics', [FileController::class, 'statistics'])->name('files.statistics');


    });

});
