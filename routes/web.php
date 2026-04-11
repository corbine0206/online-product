<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\ProductManagementController;
use App\Http\Controllers\Admin\SalesDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\SalesTransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Products routes
Route::get('products', [HomeController::class, 'index'])->name('products.index');

// Customer authentication routes
Route::get('register', [AuthController::class, 'showRegister'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::match(['get', 'post'], 'logout', [AuthController::class, 'logout'])->name('logout');

// Cart routes (accessible to all users)
Route::get('cart', [CartController::class, 'index'])->name('cart.index');
Route::post('cart/add', [CartController::class, 'add'])->name('cart.add');
Route::put('cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('cart/remove', [CartController::class, 'remove'])->name('cart.remove');

// Customer protected routes
Route::middleware('customer')->group(function () {
    Route::get('dashboard', [AuthController::class, 'dashboard'])->name('customer.dashboard');
    Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
});

// Sales staff routes
Route::middleware(['customer', 'role:sales'])->group(function () {
    Route::get('sales-dashboard', [Admin\SalesDashboardController::class, 'index'])->name('sales.dashboard');
    Route::get('customers', [CustomerController::class, 'index'])->name('customers.index')->middleware('permission:view_customers');
    Route::get('customers/{customer}', [CustomerController::class, 'show'])->name('customers.show')->middleware('permission:view_customer_details');
    Route::get('customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit')->middleware('permission:edit_customers');
    Route::put('customers/{customer}', [CustomerController::class, 'update'])->name('customers.update')->middleware('permission:edit_customers');
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
        
        // Product image management
        Route::delete('product-images/{productImage}', [ProductManagementController::class, 'deleteImage'])->name('product-images.delete');
        Route::put('product-images/{productImage}/set-primary', [ProductManagementController::class, 'setPrimaryImage'])->name('product-images.set-primary');
        
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
