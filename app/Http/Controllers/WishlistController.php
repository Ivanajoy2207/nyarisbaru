<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * LIST WISHLIST USER
     */
    public function index()
    {
        $products = Auth::user()
            ->wishlist()
            ->latest()
            ->get();

        return view('wishlist.index', compact('products'));
    }

    /**
     * TOGGLE WISHLIST
     */
    public function toggle($id)
    {
        $user = Auth::user();

        $exists = $user->wishlist()
            ->where('product_id', $id)
            ->exists();

        if ($exists) {
            $user->wishlist()->detach($id);
            return back()->with('success', 'Produk dihapus dari wishlist.');
        }

        $user->wishlist()->attach($id);
        return back()->with('success', 'Produk berhasil ditambahkan ke wishlist!');
    }
}
