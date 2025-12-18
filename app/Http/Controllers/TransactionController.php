<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\TransactionStatusChanged;

class TransactionController extends Controller
{
    // BUY PRODUCT (ESCROW START)
    public function store(Product $product)
    {
        if ($product->user_id === auth()->id()) {
            return back()->with('error', 'Tidak bisa membeli produk sendiri.');
        }

        // transaksi aktif = status bukan canceled
        $hasActiveTransaction = Transaction::where('product_id', $product->id)
            ->where('status', '!=', 'canceled')
            ->exists();

        if ($hasActiveTransaction) {
            return back()->with('error', 'Produk sudah dalam proses transaksi.');
        }

        $transaction = Transaction::create([
            'product_id' => $product->id,
            'buyer_id' => auth()->id(),
            'seller_id' => $product->user_id,
            'status' => 'pending',
            'escrow_status' => 'held',
        ]);

        return redirect()->route('payment.show', $transaction->id);
    }


    // SELLER KIRIM BARANG
    public function ship(Transaction $transaction)
    {
        abort_if(auth()->id() !== $transaction->seller_id, 403);

        $transaction->update(['status' => 'shipped']);

        // notif ke buyer
        $buyer = User::find($transaction->buyer_id);
        if ($buyer) $buyer->notify(new TransactionStatusChanged($transaction, 'Penjual mengirim barang'));

        return back()->with('success', 'Barang ditandai sebagai dikirim.');
    }

    // BUYER TERIMA BARANG (ESCROW RELEASE)
    public function receive(Transaction $transaction)
    {
        abort_if(auth()->id() !== $transaction->buyer_id, 403);

        $transaction->update([
            'status' => 'completed',
            'escrow_status' => 'released',
        ]);

        // notif ke seller
        $seller = User::find($transaction->seller_id);
        if ($seller) $seller->notify(new TransactionStatusChanged($transaction, 'Pembeli mengkonfirmasi barang diterima'));

        return back()->with('success', 'Barang diterima. Dana dilepas ke penjual.');
    }


    // Batalin transaksi
    public function cancel(Transaction $transaction)
    {
        abort_if(auth()->id() !== $transaction->buyer_id, 403);

        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Transaksi tidak bisa dibatalkan karena sudah diproses.');
        }

        $transaction->update([
            'status' => 'canceled',
            'escrow_status' => 'held',
        ]);

        $seller = \App\Models\User::find($transaction->seller_id);
        if ($seller) $seller->notify(new TransactionStatusChanged($transaction, 'Pembeli membatalkan transaksi'));

        return redirect()
            ->route('products.show', $transaction->product_id)
            ->with('success', 'Transaksi dibatalkan. Produk kembali tersedia.');
    }


    public function index()
    {
        $transactions = Transaction::with(['product', 'seller'])
            ->where('buyer_id', auth()->id())
            ->where('status', '!=', 'canceled') // ✅ tampilkan semua kecuali canceled
            ->latest()
            ->get();

        return view('orders.index', compact('transactions'));
    }

}
