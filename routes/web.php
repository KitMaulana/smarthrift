<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\CourierController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Welcome and Authentication
Route::get('/welcome', [AuthController::class, 'welcome'])->name('welcome');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public pages (accessible without login)
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/explore', [PublicController::class, 'explore'])->name('explore');
Route::get('/product/{id}', [PublicController::class, 'showProduct'])->name('product.show');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    
    // Shared Chat Routes (both buyer and seller use this)
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/room/{userId}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/room/{userId}/send', [ChatController::class, 'send'])->name('chat.send');

    // Buyer/Pelanggan Routes
    Route::middleware(['role:pelanggan'])->group(function () {
        Route::get('/profile', [BuyerController::class, 'profile'])->name('buyer.profile');
        Route::get('/profile/edit', [BuyerController::class, 'editProfile'])->name('buyer.edit_profile');
        Route::post('/profile/edit', [BuyerController::class, 'updateProfile'])->name('buyer.update_profile');
        
        Route::get('/checkout/{productId}', [BuyerController::class, 'checkoutForm'])->name('buyer.checkout');
        Route::post('/checkout/{productId}', [BuyerController::class, 'checkout']);
        
        Route::get('/purchases', [BuyerController::class, 'purchases'])->name('buyer.purchases');
        Route::get('/notifications', [BuyerController::class, 'notifications'])->name('buyer.notifications');
        Route::post('/order/{orderId}/confirm', [BuyerController::class, 'confirmDelivery'])->name('buyer.confirm_delivery');
        Route::post('/order/{orderId}/return', [BuyerController::class, 'requestReturn'])->name('buyer.request_return');
    });

    // Seller/Penjual Routes
    Route::middleware(['role:penjual'])->group(function () {
        Route::get('/seller', [SellerController::class, 'dashboard'])->name('seller.dashboard');
        Route::get('/seller/sell', [SellerController::class, 'createProduct'])->name('seller.create_product');
        Route::post('/seller/sell', [SellerController::class, 'storeProduct'])->name('seller.store_product');
        Route::get('/seller/sales', [SellerController::class, 'sales'])->name('seller.sales');
    });

    // Courier/Kurir Routes
    Route::middleware(['role:kurir'])->group(function () {
        Route::get('/courier', [CourierController::class, 'dashboard'])->name('courier.dashboard');
        Route::post('/courier/order/{orderId}/status', [CourierController::class, 'updateStatus'])->name('courier.update_status');
    });

    // Admin Routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
        Route::post('/admin/users/{id}/role', [AdminController::class, 'updateUserRole'])->name('admin.update_role');
        Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.delete_user');
        
        Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products');
        Route::post('/admin/products/{id}/approve', [AdminController::class, 'approveProduct'])->name('admin.approve_product');
        Route::delete('/admin/products/{id}', [AdminController::class, 'deleteProduct'])->name('admin.delete_product');
        
        Route::get('/admin/transactions', [AdminController::class, 'transactions'])->name('admin.transactions');
        Route::post('/admin/transactions/{id}/approve-shipping', [AdminController::class, 'approveShipping'])->name('admin.approve_shipping');
        Route::post('/admin/transactions/{id}/approve-return', [AdminController::class, 'approveReturn'])->name('admin.approve_return');
        Route::post('/admin/transactions/{id}/reject-return', [AdminController::class, 'rejectReturn'])->name('admin.reject_return');
    });
});
