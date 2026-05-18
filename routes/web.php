<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\PromotionController as AdminPromotionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminOrderController::class, 'show'])->name('show');
        Route::delete('/{id}', [AdminOrderController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/', [AdminCustomerController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminCustomerController::class, 'show'])->name('show');
        Route::put('/{id}/role', [AdminCustomerController::class, 'updateRole'])->name('update-role');
    });
    Route::prefix('promotions')->name('promotions.')->group(function () {
    // Promotion CRUD
    Route::get('/', [AdminPromotionController::class, 'index'])->name('index');
    Route::get('/create', [AdminPromotionController::class, 'create'])->name('create');
    Route::post('/store', [AdminPromotionController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [AdminPromotionController::class, 'edit'])->name('edit');
    Route::put('/{id}', [AdminPromotionController::class, 'update'])->name('update');
    Route::delete('/{id}', [AdminPromotionController::class, 'destroy'])->name('destroy');
    
    // Promotion Products
    Route::get('/{promotionId}/products', [AdminPromotionController::class, 'products'])->name('products');
    Route::post('/{promotionId}/products', [AdminPromotionController::class, 'addProduct'])->name('add-product');
    Route::put('/{promotionId}/products/{productId}', [AdminPromotionController::class, 'updateProduct'])->name('update-product');
    Route::delete('/{promotionId}/products/{productId}', [AdminPromotionController::class, 'deleteProduct'])->name('delete-product');
});
});

require __DIR__ . '/auth.php';
