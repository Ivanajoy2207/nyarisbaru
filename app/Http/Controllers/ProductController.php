<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\ProductReview;


class ProductController extends Controller
{
    /**
     * Hanya create & store butuh login
     */
    public function __construct()
    {
        $this->middleware('auth')->only(['create', 'store']);
    }

    /**
     * GET /products
     * Katalog produk
     */
    public function index(Request $request)
    {
        $query = Product::query()
            ->whereDoesntHave('transaction', function($q){
                $q->where('status', '!=', 'canceled');
            });

        // ✅ Search
        if ($search = $request->get('q')) {
            $query->where('name', 'like', "%{$search}%");
        }

        // ✅ Category filter (STRING)
        if ($cat = $request->get('category')) {
            $query->where('category', $cat);
        }

        // ✅ Ambil daftar kategori dari database (buat dropdown)
        $categories = Product::query()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        // ✅ Pagination + biar query param (q, category) ikut kebawa saat pindah page
        $products = $query->latest()->paginate(12)->withQueryString();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * ✅ GET /products/{id}
     * DETAIL SATU PRODUK (INI YANG PENTING)
     */
    public function show(Product $product)
    {

        $product->load(['reviews.user', 'transaction', 'user']);

        $seller = $product->user;

        // ===== Product reputation (produk ini) =====
        $productReviews = $product->reviews;
        $productAvgRating = $productReviews->count() ? round($productReviews->avg('rating'), 1) : null;
        $productReviewCount = $productReviews->count();

        // ===== Seller reputation (semua produk seller yg transaksi completed) =====
        $sellerProductIds = Product::where('user_id', $seller->id)
            ->whereHas('transaction', fn($q) => $q->where('status', 'completed'))
            ->pluck('id');

        $sellerReviewCount = ProductReview::whereIn('product_id', $sellerProductIds)->count();
        $sellerAvgRating = ProductReview::whereIn('product_id', $sellerProductIds)->avg('rating');

        $isTrustedSeller =
            $sellerReviewCount >= 3 &&
            $sellerAvgRating >= 4.5;

        // ✅ List produk terjual seller (buat “riwayat jual apa saja”)
        $sellerSoldProducts = Product::where('user_id', $seller->id)
            ->whereHas('transaction', fn($q) => $q->where('status', 'completed'))
            ->latest()
            ->take(6)
            ->get();

        // ✅ List review terbaru pembeli untuk produk lain dari seller (buat “komentar pelanggan sebelumnya”)
        $sellerRecentReviews = ProductReview::with(['user', 'product'])
            ->whereIn('product_id', $sellerProductIds)
            ->latest()
            ->take(6)
            ->get();

        $isWishlisted = false;
        if (auth()->check()) {
            $isWishlisted = auth()->user()
                ->wishlist()
                ->where('product_id', $product->id)
                ->exists();
        }

        $activeTransaction = \App\Models\Transaction::with(['buyer','seller'])
            ->where('product_id', $product->id)
            ->where('status', '!=', 'canceled')
            ->latest()
            ->first();

        return view('products.show', compact(
            'product',
            'seller',
            'productReviews',
            'productAvgRating',
            'productReviewCount',
            'sellerAvgRating',
            'sellerReviewCount',
            'sellerSoldProducts',
            'sellerRecentReviews',
            'isWishlisted',
            'isTrustedSeller',
            'activeTransaction',
        ));
    }


    /**
     * GET /products/create
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * POST /products
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'price' => ['required', 'integer', 'min:1000'],
            'buy_year' => ['nullable', 'integer', 'min:2000', 'max:' . date('Y')],
            'condition' => ['required', 'integer', 'min:1', 'max:100'],
            'description' => ['nullable', 'string'],
            'photos' => ['nullable', 'array', 'max:5'],
            'photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $imagePath = null;

        if ($request->hasFile('photos')) {
            $imagePath = $request->file('photos')[0]->store('products', 'public');
        }

        $product = Product::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'category' => $validated['category'] ?? null,
            'city' => $validated['city'] ?? null,
            'price' => $validated['price'],
            'condition' => $validated['condition'],
            'buy_year' => $validated['buy_year'] ?? null,
            'description' => $validated['description'] ?? null,
            'image_path' => $imagePath,
        ]);

        return redirect()
            ->route('products.show', $product->id)
            ->with('success', 'Produk berhasil dipasang ✨');
    }
}
