<?php

namespace App\Http\Controllers;

use App\Models\SellerReview;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerReviewController extends Controller
{
    public function store(Request $request, Transaction $transaction)
    {
        // 1. Cuma buyer
        if (Auth::id() !== $transaction->buyer_id) {
            abort(403);
        }

        // 2. Transaksi harus selesai
        if ($transaction->status !== 'completed') {
            return back()->with('error', 'Transaksi belum selesai.');
        }

        // 3. Cegah double review
        $exists = SellerReview::where('transaction_id', $transaction->id)->exists();
        if ($exists) {
            return back()->with('error', 'Kamu sudah memberi rating untuk penjual ini.');
        }

        // 4. Validasi
        $data = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:255',
        ]);

        // 5. Simpan
        SellerReview::create([
            'seller_id'      => $transaction->seller_id,
            'buyer_id'       => $transaction->buyer_id,
            'transaction_id' => $transaction->id,
            'rating'         => $data['rating'],
            'comment'        => $data['comment'] ?? null,
        ]);

        return back()->with('success', 'Rating penjual berhasil dikirim ⭐');
    }
}
