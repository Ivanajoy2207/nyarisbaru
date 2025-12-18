<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // HALAMAN PEMBAYARAN
    public function show(Transaction $transaction)
    {
        // hanya buyer yang boleh bayar
        abort_if(auth()->id() !== $transaction->buyer_id, 403);

        return view('payment.show', compact('transaction'));
    }

    // PROSES BAYAR (SIMULASI)
    public function pay(Transaction $transaction)
    {
        abort_if(auth()->id() !== $transaction->buyer_id, 403);

        // update status escrow
        $transaction->update([
            'status' => 'paid',
            'escrow_status' => 'held',
        ]);

        return redirect()
            ->route('products.show', $transaction->product_id)
            ->with('success', 'Pembayaran berhasil. Dana ditahan oleh sistem (Escrow).');
    }
}
