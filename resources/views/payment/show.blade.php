@extends('layouts.main')

@section('title', 'Pembayaran - NyarisBaru')

@section('content')
<div class="container" style="max-width:600px;margin:40px auto;">

    <div style="
        background:#fff;
        border-radius:20px;
        border:1px solid var(--border);
        padding:24px;
        box-shadow:0 16px 32px rgba(15,23,42,0.04);
    ">

        <h2 style="font-size:1.4rem;font-weight:800;margin-bottom:16px;">
            Pembayaran
        </h2>

        <div style="margin-bottom:16px;">
            <strong>Produk</strong><br>
            {{ $transaction->product->name }}
        </div>

        <div style="margin-bottom:16px;">
            <strong>Harga</strong><br>
            Rp {{ number_format($transaction->product->price, 0, ',', '.') }}
        </div>

        <div style="margin-bottom:16px;">
            <strong>Penjual</strong><br>
            {{ $transaction->seller->name ?? '-' }}
        </div>

        <div style="
            background:#f0f9ff;
            border:1px solid #bae6fd;
            border-radius:12px;
            padding:12px;
            font-size:0.9rem;
            color:#075985;
            margin-bottom:20px;
        ">
            Dana akan ditahan oleh sistem dan hanya dilepas ke penjual setelah barang diterima.
        </div>

        <form action="{{ route('payment.pay', $transaction->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary" style="width:100%;">
                Bayar Sekarang
            </button>
        </form>

        <div style="margin-top:12px;text-align:center;">
            <form action="{{ route('transactions.cancel', $transaction->id) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit"
                        style="border:none;background:none;font-size:0.85rem;color:#6b7280;cursor:pointer;">
                    Batalkan & kembali
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
