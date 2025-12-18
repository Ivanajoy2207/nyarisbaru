<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ForumPost;
use App\Models\Transaction;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * ============================
     *  SHOW PROFILE PAGE
     * ============================
     */
    public function show()
    {

        $user = Auth::user();

        // ✅ mark notif transaksi sebagai read saat buka profil
        $user->unreadNotifications()
            ->where('data->type', 'transaction_status')
            ->update(['read_at' => now()]);

        // Produk yang dijual user
        $products = Product::where('user_id', $user->id)
            ->latest()
            ->take(12)
            ->get();

        // Diskusi forum user
        $posts = ForumPost::where('user_id', $user->id)
            ->latest()
            ->take(8)
            ->get();

        // Wishlist count
        $wishlistCount = $user->wishlist()->count();

        /**
         * ✅ TRANSAKSI PEMBELIAN (JANGAN HANYA COMPLETED)
         * Tampilkan semua transaksi buyer selain canceled
         */
        $transactionsBought = Transaction::with(['product', 'seller'])
            ->where('buyer_id', $user->id)
            ->where('status', '!=', 'canceled')
            ->latest()
            ->take(10)
            ->get();

        // Count pembelian (selain canceled)
        $boughtCount = Transaction::where('buyer_id', $user->id)
            ->where('status', '!=', 'canceled')
            ->count();

        // ============================
        // SELLER RATING (JIKA DIA JUALAN)
        // ============================
        $sellerProductIds = Product::where('user_id', $user->id)
            ->whereHas('transaction', fn($q) => $q->where('status', 'completed'))
            ->pluck('id');

        $sellerReviewCount = ProductReview::whereIn('product_id', $sellerProductIds)->count();
        $sellerAvgRating   = ProductReview::whereIn('product_id', $sellerProductIds)->avg('rating');

        $isTrustedSeller =
            $sellerReviewCount >= 3 &&
            $sellerAvgRating >= 4.5;

        // ✅ Data stats
        $stats = [
            'products_count'  => $products->count(),
            'posts_count'     => $posts->count(),
            'wishlist_count'  => $wishlistCount,
            'bought_count'    => $boughtCount,
        ];

        return view('profile.show', compact(
            'user',
            'products',
            'posts',
            'stats',
            'transactionsBought',
            'sellerReviewCount',
            'sellerAvgRating',
            'isTrustedSeller'
        ));
    }

    public function sellerReviews()
    {
        $user = Auth::user();

        $productIds = Product::where('user_id', $user->id)->pluck('id');

        $reviews = ProductReview::with('product', 'user')
            ->whereIn('product_id', $productIds)
            ->latest()
            ->get();

        $avgRating = $reviews->avg('rating');
        $reviewCount = $reviews->count();

        return view('profile.seller-reviews', compact(
            'reviews',
            'avgRating',
            'reviewCount'
        ));
    }
}
