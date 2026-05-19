<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\PromotionController as AdminPromotionController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\ProductTypeController as AdminProductTypeController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController;
use App\Http\Controllers\Admin\DeliveryController as AdminDeliveryController;

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
        Route::delete('/{id}', [AdminCustomerController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('promotions')->name('promotions.')->group(function () {
        Route::get('/', [AdminPromotionController::class, 'index'])->name('index');
        Route::get('/create', [AdminPromotionController::class, 'create'])->name('create');
        Route::post('/store', [AdminPromotionController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AdminPromotionController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminPromotionController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminPromotionController::class, 'destroy'])->name('destroy');

        Route::get('/{promotionId}/products', [AdminPromotionController::class, 'products'])->name('products');
        Route::post('/{promotionId}/products', [AdminPromotionController::class, 'addProduct'])->name('add-product');
        Route::put('/{promotionId}/products/{productId}', [AdminPromotionController::class, 'updateProduct'])->name('update-product');
        Route::delete('/{promotionId}/products/{productId}', [AdminPromotionController::class, 'deleteProduct'])->name('delete-product');
    });
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [AdminPaymentController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminPaymentController::class, 'show'])->name('show');
        Route::delete('/{id}', [AdminPaymentController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/sales', [AdminReportController::class, 'sales'])->name('sales');
        Route::get('/products', [AdminReportController::class, 'products'])->name('products');
        Route::get('/customers', [AdminReportController::class, 'customers'])->name('customers');
        Route::get('/revenue', [AdminReportController::class, 'revenue'])->name('revenue');
        Route::get('/inventory', [AdminReportController::class, 'inventory'])->name('inventory');
        Route::get('/deliveries', [AdminReportController::class, 'deliveries'])->name('deliveries');
    });


    // Brand management routes
    Route::resource('/brands', AdminBrandController::class);
    Route::post('brands/{brand}/toggle-status', [AdminBrandController::class, 'toggleStatus'])->name('brands.toggle-status');

    // Product Type management routes
    Route::resource('/product-types', AdminProductTypeController::class);

    // Product management routes (to be implemented)
     Route::resource('products', AdminProductController::class);
    Route::delete('products/image/{id}', [AdminProductController::class, 'deleteImage'])->name('products.delete-image');
    Route::post('products/{product}/toggle-status', [AdminProductController::class, 'toggleStatus'])->name('products.toggle-status');


// Feedback Routes
    Route::delete('feedback/bulk-delete', [AdminFeedbackController::class, 'bulkDelete'])->name('feedback.bulk-delete');
    Route::resource('feedback', AdminFeedbackController::class)->only(['index', 'show', 'destroy']);

Route::get('deliveries', [AdminDeliveryController::class, 'index'])->name('deliveries.index');
    Route::get('deliveries/{delivery}', [AdminDeliveryController::class, 'show'])->name('deliveries.show');
    Route::post('deliveries/{delivery}/update-status', [AdminDeliveryController::class, 'updateStatus'])->name('deliveries.update-status');
    Route::post('deliveries/bulk-update', [AdminDeliveryController::class, 'bulkUpdate'])->name('deliveries.bulk-update');
    Route::get('deliveries/{delivery}/tracking', [AdminDeliveryController::class, 'getTrackingInfo'])->name('deliveries.tracking');



});

require __DIR__ . '/auth.php';
