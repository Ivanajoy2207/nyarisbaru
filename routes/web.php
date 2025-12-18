<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    ProductController,
    ProfileController,
    ForumController,
    ProductReviewController,
    ChatController,
    ChatInboxController,
    WishlistController,
    TransactionController,
    PaymentController,
    SellerReviewController,
    NotificationController,
    RealtimeController,
};

/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| PRODUCTS
|--------------------------------------------------------------------------
*/
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// ⚠️ HARUS DI ATAS {product}
Route::middleware('auth')->group(function () {
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');

    // legacy / optional
    Route::post('/products/{product}/reviews', [ProductReviewController::class, 'store'])
        ->name('products.reviews.store');
});

// ⬇️ ROUTE DINAMIS PALING BAWAH
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

/*
|--------------------------------------------------------------------------
| CHAT
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/products/{product}/chat', [ChatController::class, 'withSeller'])
        ->name('chat.withSeller');

    Route::get('/chats', [ChatInboxController::class, 'index'])
        ->name('chat.index');

    Route::get('/chat/{chat}', [ChatController::class, 'show'])
        ->name('chat.show');

    Route::post('/chat/{chat}/messages', [ChatController::class, 'store'])
        ->name('chat.store');
});


/*
|--------------------------------------------------------------------------
| NOTIFICATIONS
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');

    // ✅ realtime badge
    Route::get('/notification-counts', [NotificationController::class, 'counts'])
        ->name('notifications.counts');
});


Route::middleware('auth')->group(function () {
    Route::get('/realtime/badges', [RealtimeController::class, 'badges'])
        ->name('realtime.badges');
});


/*
|--------------------------------------------------------------------------
| WISHLIST
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/wishlist/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
});

/*
|--------------------------------------------------------------------------
| FORUM
|--------------------------------------------------------------------------
*/
Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
Route::get('/forum/create', [ForumController::class, 'create'])->middleware('auth')->name('forum.create');
Route::post('/forum', [ForumController::class, 'store'])->middleware('auth')->name('forum.store');
Route::get('/forum/{forum}', [ForumController::class, 'show'])->name('forum.show');
Route::post('/forum/{forum}/comment', [ForumController::class, 'comment'])->middleware('auth')->name('forum.comment');

/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/seller/reviews', [ProfileController::class, 'sellerReviews'])
    ->name('seller.reviews')
    ->middleware('auth');

/*
|--------------------------------------------------------------------------
| TRANSACTIONS (ESCROW)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/products/{product}/buy', [TransactionController::class, 'store'])->name('transactions.store');
    Route::post('/transactions/{transaction}/ship', [TransactionController::class, 'ship'])->name('transactions.ship');
    Route::post('/transactions/{transaction}/receive', [TransactionController::class, 'receive'])->name('transactions.receive');
    Route::post('/transactions/{transaction}/cancel', [TransactionController::class, 'cancel'])->name('transactions.cancel');
    Route::get('/orders', [TransactionController::class, 'index'])->name('orders.index');
});

/*
|--------------------------------------------------------------------------
| PAYMENT
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/payment/{transaction}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{transaction}/pay', [PaymentController::class, 'pay'])->name('payment.pay');
});

/*
|--------------------------------------------------------------------------
| SELLER REVIEW
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->post(
    '/transactions/{transaction}/review-seller',
    [SellerReviewController::class, 'store']
)->name('seller.review.store');


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
