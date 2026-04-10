<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\ProductManagementController;
use App\Http\Controllers\Admin\SalesDashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\SalesTransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Auth routes (public)
    Route::get('login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AdminAuthController::class, 'handleLogin'])->name('handle-login');
    Route::post('logout', [AdminAuthController::class, 'handleLogout'])->name('logout');

    // Protected routes
    Route::middleware('admin')->group(function () {
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // User management
        Route::resource('users', UserManagementController::class);
        
        // Product management
        Route::resource('products', ProductManagementController::class);
        
        // Customer management
        Route::resource('customers', CustomerController::class);
        
        // Role management
        Route::resource('roles', RoleController::class);
        
        // Permission management
        Route::resource('permissions', PermissionController::class);
        
        // Sales transaction management
        Route::resource('sales-transactions', SalesTransactionController::class);
        
        // Sales dashboard
        Route::get('sales-dashboard', [SalesDashboardController::class, 'index'])
            ->middleware('permission:view_reports')
            ->name('sales-dashboard.index');
        Route::get('sales-dashboard/export/{format}', [SalesDashboardController::class, 'exportReport'])
            ->middleware('permission:view_reports')
            ->name('sales-dashboard.export');
    });
});
