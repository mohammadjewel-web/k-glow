<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubcategoryManagementController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\OrderController;


// Frontend Routes
Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::get('/shop', [FrontendController::class, 'shop'])->name('shop');
Route::get('/product/{slug}', [FrontendController::class, 'product'])->name('product.details');
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');

// Static Pages
Route::get('/about-us', function () {
    return view('frontend.about-us');
})->name('about-us');

Route::get('/contact-us', function () {
    return view('frontend.contact-us');
})->name('contact-us');

Route::post('/contact/submit', [\App\Http\Controllers\ContactController::class, 'submit'])->name('contact.submit');

// OTP Verification Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/verification/method', [\App\Http\Controllers\Auth\VerificationController::class, 'showVerificationMethodPage'])->name('verification.method');
    Route::get('/verify/{type}', [\App\Http\Controllers\Auth\VerificationController::class, 'showVerificationPage'])->name('verification.page');
    Route::post('/verification/send', [\App\Http\Controllers\Auth\VerificationController::class, 'sendOTP'])->name('verification.send');
    Route::post('/verification/resend', [\App\Http\Controllers\Auth\VerificationController::class, 'resendOTP'])->name('verification.resend');
    Route::post('/verification/verify', [\App\Http\Controllers\Auth\VerificationController::class, 'verifyOTP'])->name('verification.verify');
    Route::get('/verification/skip', [\App\Http\Controllers\Auth\VerificationController::class, 'skipVerification'])->name('verification.skip');
});

Route::get('/privacy-policy', function () {
    return view('frontend.privacy-policy');
})->name('privacy-policy');

Route::get('/return-refund', function () {
    return view('frontend.return-refund');
})->name('return-refund');

Route::get('/terms-conditions', function () {
    return view('frontend.terms-conditions');
})->name('terms-conditions');

// Cart API Routes
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/data', [CartController::class, 'getCart'])->name('cart.data');
Route::get('/cart/count', [CartController::class, 'getCartCountApi'])->name('cart.count');
Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.apply-coupon');
Route::post('/cart/remove-coupon', [CartController::class, 'removeCoupon'])->name('cart.remove-coupon');

// API Routes for AJAX
Route::get('/api/products/{id}', [FrontendController::class, 'getProduct'])->name('api.product');
Route::get('/api/cart-items', [CartController::class, 'getCartItems'])->name('api.cart.items');

// Public review routes
Route::get('/api/products/{id}/reviews', [ReviewController::class, 'index'])->name('api.product.reviews');
Route::get('/api/products/{id}/can-review', [ReviewController::class, 'canReview'])->name('api.product.can-review');
Route::post('/api/reviews/{id}/helpful', [ReviewController::class, 'toggleHelpful'])->name('api.review.helpful');

// Public coupon routes
Route::post('/api/coupons/validate', [\App\Http\Controllers\CouponController::class, 'validateCoupon'])->name('api.coupons.validate');
Route::post('/api/coupons/calculate', [\App\Http\Controllers\CouponController::class, 'calculateDiscount'])->name('api.coupons.calculate');

// SSLCommerz Payment routes (public) - without CSRF protection
Route::post('payment/initiate', [\App\Http\Controllers\PaymentController::class, 'initiatePayment'])->name('payment.initiate');
// Route::match(['get', 'post'], 'success', [\App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success'); // Disabled - using payment/success instead
// Route::match(['get', 'post'], 'fail', [\App\Http\Controllers\PaymentController::class, 'fail'])->name('payment.fail'); // Disabled - using payment/fail instead
// Route::match(['get', 'post'], 'cancel', [\App\Http\Controllers\PaymentController::class, 'cancel'])->name('payment.cancel'); // Disabled - using payment/cancel instead
Route::post('ipn', [\App\Http\Controllers\PaymentController::class, 'ipn'])->name('payment.ipn');
Route::get('payment/status', [\App\Http\Controllers\PaymentController::class, 'getStatus'])->name('payment.status');

// Public payment result pages (no auth required)
Route::match(['get', 'post'], 'payment/success', [\App\Http\Controllers\PaymentController::class, 'showSuccessPage'])->name('payment.success.page');
Route::match(['get', 'post'], 'payment/fail', [\App\Http\Controllers\PaymentController::class, 'showFailPage'])->name('payment.fail.page');
Route::match(['get', 'post'], 'payment/cancel', [\App\Http\Controllers\PaymentController::class, 'showCancelPage'])->name('payment.cancel.page');

// Debug route to test payment success page
Route::get('test-payment-success', function() {
    return view('frontend.payment-success', ['orderData' => null]);
})->name('test.payment.success');

// Debug route to test payment callback
Route::post('test-payment-callback', function(\Illuminate\Http\Request $request) {
    \Log::info('Test payment callback received:', $request->all());
    return redirect()->route('payment.success.page');
})->name('test.payment.callback');

// Manual payment completion for testing
Route::get('complete-payment/{order_id}', function($order_id) {
    try {
        $order = \App\Models\Order::find($order_id);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }
        
        $order->update([
            'payment_status' => 'completed',
            'order_status' => 'confirmed',
            'payment_reference' => 'MANUAL_' . time(),
            'payment_date' => now()
        ]);
        
        // Clear user's cart if authenticated
        if (\Illuminate\Support\Facades\Auth::check()) {
            \App\Models\Cart::where('user_id', \Illuminate\Support\Facades\Auth::id())->delete();
        }
        
        \Log::info('Manual payment completion for order:', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'payment_status' => 'completed'
        ]);
        
        return redirect()->route('payment.success.page')
            ->with('success', 'Payment completed successfully! Order #' . $order->order_number);
            
    } catch (\Exception $e) {
        \Log::error('Manual payment completion error: ' . $e->getMessage());
        return response()->json(['error' => 'Payment completion failed'], 500);
    }
})->name('complete.payment');

// Webhook endpoints for external services
Route::post('webhook/sslcommerz', [\App\Http\Controllers\WebhookController::class, 'sslcommerzWebhook'])->name('webhook.sslcommerz');
Route::post('webhook/test', [\App\Http\Controllers\WebhookController::class, 'testWebhook'])->name('webhook.test');
Route::get('/api/coupons/available', [\App\Http\Controllers\CouponController::class, 'getAvailableCoupons'])->name('api.coupons.available');

// Public notification routes
Route::get('/api/notifications', [NotificationController::class, 'index'])->name('api.notifications.index');
Route::get('/api/notifications/recent', [NotificationController::class, 'recent'])->name('api.notifications.recent');
Route::get('/api/notifications/count', [NotificationController::class, 'count'])->name('api.notifications.count');
Route::post('/api/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('api.notifications.read');
Route::post('/api/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('api.notifications.read-all');
Route::delete('/api/notifications/{notification}', [NotificationController::class, 'destroy'])->name('api.notifications.destroy');

// Test route for debugging
Route::get('/api/test-product/{id}', function($id) {
    try {
        $product = \App\Models\Product::find($id);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'status' => 'success'
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

// Test route to add product to cart
Route::get('/test-add-to-cart/{id}', function($id) {
    $product = \App\Models\Product::find($id);
    if ($product) {
        $cart = session()->get('cart', []);
        $cart[$id] = [
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => 1,
            'thumbnail' => $product->thumbnail,
            'sku' => $product->sku
        ];
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Product added to cart for testing');
    }
    return redirect()->back()->with('error', 'Product not found');
});

// Test route for review submission
Route::get('/test-review/{id}', function($id) {
    $user = \App\Models\User::first();
    if ($user) {
        Auth::login($user);
        return redirect()->route('product.details', \App\Models\Product::find($id)->slug);
    }
    return 'No user found';
});



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');







Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [ProfileController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Customer Dashboard Routes (with verification check)
    Route::prefix('customer')->name('customer.')->middleware('verified')->group(function () {
        Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
        Route::get('/orders', [CustomerController::class, 'orders'])->name('orders');
        Route::get('/orders/{id}', [CustomerController::class, 'orderDetails'])->name('order.details');
        Route::post('/orders/{id}/cancel', [CustomerController::class, 'cancelOrder'])->name('order.cancel');
        Route::get('/profile', [CustomerController::class, 'profile'])->name('profile');
        Route::post('/profile', [CustomerController::class, 'updateProfile'])->name('profile.update');
        
        // Wishlist routes
        Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
        Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
        Route::post('/wishlist/remove', [WishlistController::class, 'remove'])->name('wishlist.remove');
        Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
        Route::get('/wishlist/check', [WishlistController::class, 'check'])->name('wishlist.check');
        Route::get('/wishlist/count', [WishlistController::class, 'count'])->name('wishlist.count');
        
        // Review routes
        Route::get('/reviews', [ReviewController::class, 'userReviews'])->name('reviews');
        Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
        
        // Notification routes
        Route::get('/notifications', function() {
            return view('customer.notifications');
        })->name('notifications');
        Route::put('/reviews/{id}', [ReviewController::class, 'update'])->name('reviews.update');
        Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    });
});





// Admin Routes - Protected with authentication and authorization
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Roles & Permissions (Admin only)
    Route::middleware([\Spatie\Permission\Middleware\RoleMiddleware::class . ':admin'])->group(function () {
    Route::get('/roles', [RoleController::class, 'index'])->name('admin.roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('admin.roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('admin.roles.store');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('admin.roles.edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('admin.roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('admin.roles.destroy');
    });

    // Users Role Assignment (Admin only)
    Route::middleware([\Spatie\Permission\Middleware\RoleMiddleware::class . ':admin'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}/edit-roles', [UserController::class, 'editRoles'])->name('admin.users.edit-roles');
    Route::put('/users/{user}/update-roles', [UserController::class, 'updateRoles'])->name('admin.users.update-roles');
    });

    // Category CRUD (Manager and Admin)
    Route::middleware([\App\Http\Middleware\CheckPermission::class . ':manage categories'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
        Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });

    // Subcategories CRUD routes (Manager and Admin)
    Route::middleware([\App\Http\Middleware\CheckPermission::class . ':manage categories'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('subcategories', [SubcategoryManagementController::class, 'index'])->name('subcategories.index');
        Route::get('subcategories/create', [SubcategoryManagementController::class, 'create'])->name('subcategories.create');
        Route::post('subcategories', [SubcategoryManagementController::class, 'store'])->name('subcategories.store');
        Route::get('subcategories/{subcategory}', [SubcategoryManagementController::class, 'show'])->name('subcategories.show');
        Route::get('subcategories/{subcategory}/edit', [SubcategoryManagementController::class, 'edit'])->name('subcategories.edit');
        Route::put('subcategories/{subcategory}', [SubcategoryManagementController::class, 'update'])->name('subcategories.update');
        Route::delete('subcategories/{subcategory}', [SubcategoryManagementController::class, 'destroy'])->name('subcategories.destroy');
    });

    // Brand CRUD routes (Manager and Admin)
    Route::middleware([\App\Http\Middleware\CheckPermission::class . ':manage brand'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('brands', BrandController::class);
    });

    // Product CRUD routes (Manager and Admin)
    Route::middleware([\App\Http\Middleware\CheckPermission::class . ':manage products'])->prefix('admin')->name('admin.')->group(function(){
        Route::get('products', [ProductController::class, 'index'])->name('products.index');
        Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('products', [ProductController::class, 'store'])->name('products.store');
        Route::get('products/{id}', [ProductController::class, 'show'])->name('products.show');
        Route::get('products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('products/{id}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    });

    // Coupon CRUD routes (Admin only)
    Route::middleware([\Spatie\Permission\Middleware\RoleMiddleware::class . ':admin'])->prefix('admin')->name('admin.')->group(function(){
        Route::get('coupons', [\App\Http\Controllers\Admin\CouponController::class, 'index'])->name('coupons.index');
        Route::get('coupons/create', [\App\Http\Controllers\Admin\CouponController::class, 'create'])->name('coupons.create');
        Route::post('coupons', [\App\Http\Controllers\Admin\CouponController::class, 'store'])->name('coupons.store');
        Route::get('coupons/{coupon}', [\App\Http\Controllers\Admin\CouponController::class, 'show'])->name('coupons.show');
        Route::get('coupons/{coupon}/edit', [\App\Http\Controllers\Admin\CouponController::class, 'edit'])->name('coupons.edit');
        Route::put('coupons/{coupon}', [\App\Http\Controllers\Admin\CouponController::class, 'update'])->name('coupons.update');
        Route::delete('coupons/{coupon}', [\App\Http\Controllers\Admin\CouponController::class, 'destroy'])->name('coupons.destroy');
        
    // Admin Dashboard routes
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('stats', [AdminController::class, 'getStats'])->name('stats');
    Route::get('recent-activity', [AdminController::class, 'getRecentActivity'])->name('recent-activity');
    
    // Inventory Management routes
    Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('inventory/history', [InventoryController::class, 'history'])->name('inventory.history');
    Route::get('inventory/{inventory}', [InventoryController::class, 'show'])->name('inventory.show');
    Route::post('inventory/{inventory}/update-stock', [InventoryController::class, 'updateStock'])->name('inventory.update-stock');
    Route::post('inventory/bulk-update', [InventoryController::class, 'bulkUpdate'])->name('inventory.bulk-update');
    Route::get('inventory/low-stock', [InventoryController::class, 'lowStock'])->name('inventory.low-stock');
    Route::get('inventory/out-of-stock', [InventoryController::class, 'outOfStock'])->name('inventory.out-of-stock');
    Route::get('inventory/statistics', [InventoryController::class, 'statistics'])->name('inventory.statistics');
    Route::get('inventory/{inventory}/movements', [InventoryController::class, 'movements'])->name('inventory.movements');
    Route::post('inventory/create-for-product/{product}', [InventoryController::class, 'createForProduct'])->name('inventory.create-for-product');
    Route::put('inventory/{inventory}/settings', [InventoryController::class, 'updateSettings'])->name('inventory.update-settings');
    
    // Order Management routes
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{id}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::get('orders/export', [OrderController::class, 'export'])->name('orders.export');
    Route::get('orders/{id}/print', [OrderController::class, 'print'])->name('orders.print');
    
    // Product Management routes
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    
    // Category Management routes - handled by middleware group above
    
    // Brand Management routes - handled by resource route above
    
    // User Management routes
    Route::get('users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
    Route::delete('users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
    
    // Notification Management routes
    Route::get('notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('notifications/create', [\App\Http\Controllers\Admin\NotificationController::class, 'create'])->name('notifications.create');
    Route::post('notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'store'])->name('notifications.store');
    Route::get('notifications/{notification}', [\App\Http\Controllers\Admin\NotificationController::class, 'show'])->name('notifications.show');
    Route::get('notifications/{notification}/edit', [\App\Http\Controllers\Admin\NotificationController::class, 'edit'])->name('notifications.edit');
    Route::put('notifications/{notification}', [\App\Http\Controllers\Admin\NotificationController::class, 'update'])->name('notifications.update');
    Route::delete('notifications/{notification}', [\App\Http\Controllers\Admin\NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::post('notifications/{notification}/read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('notifications/read-all', [\App\Http\Controllers\Admin\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    
    // Reports routes
    Route::get('reports', function() { 
        return view('admin.reports.index'); 
    })->name('reports.index');
    
    // Settings routes
    Route::get('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::get('settings/get', [\App\Http\Controllers\Admin\SettingsController::class, 'getSettings'])->name('settings.get');
    Route::get('settings/group/{group}', [\App\Http\Controllers\Admin\SettingsController::class, 'getSettingsByGroup'])->name('settings.get.group');
    Route::post('settings/update', [\App\Http\Controllers\Admin\SettingsController::class, 'updateSettings'])->name('settings.update');
    Route::post('settings/upload', [\App\Http\Controllers\Admin\SettingsController::class, 'uploadFile'])->name('settings.upload');
    Route::delete('settings/delete/{key}', [\App\Http\Controllers\Admin\SettingsController::class, 'deleteSetting'])->name('settings.delete');
    Route::post('settings/reset', [\App\Http\Controllers\Admin\SettingsController::class, 'resetSettings'])->name('settings.reset');
    Route::post('settings/clear-cache', [\App\Http\Controllers\Admin\SettingsController::class, 'clearCache'])->name('settings.clear-cache');
    
    // Verification Settings API
    Route::get('settings/verification/get', [\App\Http\Controllers\Admin\SettingsController::class, 'getVerificationSettings'])->name('settings.verification.get');
    Route::post('settings/verification/update', [\App\Http\Controllers\Admin\SettingsController::class, 'updateVerificationSettings'])->name('settings.verification.update');
    Route::post('settings/verification/test-sms', [\App\Http\Controllers\Admin\SettingsController::class, 'testSMS'])->name('settings.verification.test-sms');
    
    // Slider routes
    Route::resource('sliders', \App\Http\Controllers\Admin\SliderController::class);
    Route::post('sliders/{slider}/toggle', [\App\Http\Controllers\Admin\SliderController::class, 'toggleStatus'])->name('sliders.toggle');
    
    // Slogan routes
    Route::resource('slogans', \App\Http\Controllers\Admin\SloganController::class);
    Route::post('slogans/{slogan}/toggle', [\App\Http\Controllers\Admin\SloganController::class, 'toggleStatus'])->name('slogans.toggle');
    
    // Profile routes
    Route::get('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('profile.index');
    Route::post('profile/update', [\App\Http\Controllers\Admin\ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('profile/avatar', [\App\Http\Controllers\Admin\ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::post('profile/password', [\App\Http\Controllers\Admin\ProfileController::class, 'changePassword'])->name('profile.password');
    Route::get('profile/stats', [\App\Http\Controllers\Admin\ProfileController::class, 'getStats'])->name('profile.stats');
    
    // Payment routes
    Route::get('payments', [\App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/{order}', [\App\Http\Controllers\Admin\PaymentController::class, 'show'])->name('payments.show');
    Route::post('payments/{order}/status', [\App\Http\Controllers\Admin\PaymentController::class, 'updateStatus'])->name('payments.status');
    Route::get('payments/export', [\App\Http\Controllers\Admin\PaymentController::class, 'export'])->name('payments.export');
    Route::get('payments/stats', [\App\Http\Controllers\Admin\PaymentController::class, 'getStats'])->name('payments.stats');
    });
    
    
    // AJAX route for subcategories (accessible to authenticated users)
    Route::get('get-subcategories/{category}', [ProductController::class, 'getSubcategories']);
    
    // Order success route
    Route::get('order/success/{order}', [\App\Http\Controllers\CheckoutController::class, 'success'])->name('order.success');
    
    // Debug route to check subcategory data
    Route::get('debug-subcategories', function() {
        $subcategories = \App\Models\Subcategory::with('category')->get();
        return response()->json([
            'total_subcategories' => $subcategories->count(),
            'subcategories' => $subcategories->map(function($sub) {
                return [
                    'id' => $sub->id,
                    'name' => $sub->name,
                    'category_id' => $sub->category_id,
                    'category_name' => $sub->category ? $sub->category->name : 'No Category',
                    'status' => $sub->status ? 'Active' : 'Inactive'
                ];
            })
        ]);
    });
    
    // Test route to get subcategories for a specific category
    Route::get('test-subcategories/{categoryId}', function($categoryId) {
        $subcategories = \App\Models\Subcategory::where('category_id', $categoryId)
                            ->where('status', 1)
                            ->get(['id', 'name']);
        
        return response()->json([
            'category_id' => $categoryId,
            'subcategories_count' => $subcategories->count(),
            'subcategories' => $subcategories
        ]);
    });
});

require __DIR__.'/auth.php';