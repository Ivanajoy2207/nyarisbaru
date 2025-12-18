<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        ProductReview::updateOrCreate(
            [
                'product_id' => $product->id,
                'user_id'    => Auth::id(),
            ],
            [
                'rating'     => $data['rating'],
                'comment'    => $data['comment'] ?? null,
            ]
        );

        return back()->with('success', 'Terima kasih, review kamu sudah tersimpan 🙌');
    }
}
