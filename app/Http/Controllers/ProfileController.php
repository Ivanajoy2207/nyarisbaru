<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ForumPost;
use App\Models\Transaction;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * SHOW PROFILE PAGE
     */
    public function show()
    {
        $user = Auth::user();

        // ✅ Mark notif transaksi sebagai read saat buka profil
        // (notif kamu pakai "kind", bukan "type")
        $user->unreadNotifications()
            ->where('data->kind', 'transaction_status')
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

        // ✅ TRANSAKSI PEMBELIAN: tampilkan semua selain canceled
        $transactionsBought = Transaction::with(['product', 'seller'])
            ->where('buyer_id', $user->id)
            ->where('status', '!=', 'canceled')
            ->latest()
            ->take(10)
            ->get();

        $boughtCount = Transaction::where('buyer_id', $user->id)
            ->where('status', '!=', 'canceled')
            ->count();

        // SELLER RATING
        $sellerProductIds = Product::where('user_id', $user->id)
            ->whereHas('transaction', fn($q) => $q->where('status', 'completed'))
            ->pluck('id');

        $sellerReviewCount = ProductReview::whereIn('product_id', $sellerProductIds)->count();
        $sellerAvgRating   = ProductReview::whereIn('product_id', $sellerProductIds)->avg('rating');

        $isTrustedSeller = $sellerReviewCount >= 3 && $sellerAvgRating >= 4.5;

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

    /**
     * EDIT PROFILE FORM
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * SAVE PROFILE UPDATE
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'city'   => ['nullable', 'string', 'max:100'],
            'bio'    => ['nullable', 'string', 'max:200'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            // hapus avatar lama kalau ada
            if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
                Storage::disk('public')->delete($user->avatar_path);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar_path'] = $path; // ✅ konsisten pakai avatar_path
        }

        $user->update($data);

        return redirect()
            ->route('profile.show')
            ->with('success', 'Profil berhasil diperbarui ✨');
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
