<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [App\Http\Controllers\AdminController::class, 'login'])->name('admin.login');
    Route::post('/login', [App\Http\Controllers\AdminController::class, 'authenticate'])->name('admin.authenticate');
    Route::post('/logout', [App\Http\Controllers\AdminController::class, 'logout'])->name('admin.logout');
    Route::get('/set-language/{locale}', [App\Http\Controllers\AdminController::class, 'setLanguage'])->name('admin.set_language');

    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
        
        // Modules
        Route::resource('users', App\Http\Controllers\AdminUserController::class, ['as' => 'admin']);
        Route::resource('products', App\Http\Controllers\AdminProductController::class, ['as' => 'admin']);
        Route::post('/categories', [App\Http\Controllers\AdminProductController::class, 'storeCategory'])->name('admin.categories.store');
        Route::delete('/categories/{id}', [App\Http\Controllers\AdminProductController::class, 'destroyCategory'])->name('admin.categories.destroy');
        
        Route::resource('orders', App\Http\Controllers\AdminOrderController::class, ['as' => 'admin']);
        Route::resource('payments', App\Http\Controllers\AdminPaymentController::class, ['as' => 'admin'])->only(['index', 'destroy']);
        Route::post('/payments/settings', [App\Http\Controllers\AdminPaymentController::class, 'updateSettings'])->name('admin.payments.settings.update');

        Route::resource('service_prices', App\Http\Controllers\AdminServicePriceController::class, ['as' => 'admin'])->only(['index', 'update']);
        Route::resource('hotel_bookings', App\Http\Controllers\AdminHotelBookingController::class, ['as' => 'admin'])->only(['index', 'update', 'destroy']);
        Route::resource('shave_bath_bookings', App\Http\Controllers\AdminShaveBathBookingController::class, ['as' => 'admin'])->only(['index', 'update', 'destroy']);

        Route::resource('top_rated', App\Http\Controllers\AdminTopRatedController::class, ['as' => 'admin']);
        Route::resource('sliders', App\Http\Controllers\AdminSliderController::class, ['as' => 'admin']);
        
        Route::resource('settings', App\Http\Controllers\AdminSettingController::class, ['as' => 'admin'])->only(['index', 'destroy']);
        Route::post('/settings/logo', [App\Http\Controllers\AdminSettingController::class, 'updateLogo'])->name('admin.settings.logo.update');
        
        Route::resource('notifications', App\Http\Controllers\AdminNotificationController::class, ['as' => 'admin'])->only(['index', 'destroy']);
        Route::post('/notifications/send', [App\Http\Controllers\AdminNotificationController::class, 'send'])->name('admin.notifications.send');
        
        Route::resource('chats', App\Http\Controllers\AdminChatRequestController::class, ['as' => 'admin'])->only(['index', 'update', 'destroy']);
    });
});
