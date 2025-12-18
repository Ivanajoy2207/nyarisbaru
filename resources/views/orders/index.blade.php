@extends('layouts.main')

@section('title', 'Pesanan Saya - NyarisBaru')

@section('content')
<div class="container" style="max-width:900px;margin:40px auto;">

    <h2 style="font-weight:800;margin-bottom:24px;">
        📦 Pesanan Saya
    </h2>

    @forelse($transactions as $t)
        <div style="
            display:grid;
            grid-template-columns:100px 1fr auto;
            gap:16px;
            background:#fff;
            border:1px solid var(--border);
            border-radius:18px;
            padding:16px;
            margin-bottom:14px;
            align-items:center;
        ">

            {{-- FOTO --}}
            <div style="
                width:100px;
                height:80px;
                border-radius:12px;
                background:#f1f5f9;
                overflow:hidden;
                display:flex;
                align-items:center;
                justify-content:center;
            ">
                @if($t->product->image_path)
                    <img src="{{ asset('storage/'.$t->product->image_path) }}"
                         style="width:100%;height:100%;object-fit:cover;">
                @else
                    <span style="font-size:0.75rem;color:#9ca3af;">No Image</span>
                @endif
            </div>

            {{-- INFO --}}
            <div>
                <div style="font-weight:700;font-size:1rem;">
                    {{ $t->product->name }}
                </div>

                <div style="font-size:0.85rem;color:#6b7280;margin-top:2px;">
                    Penjual: {{ $t->seller->name ?? '-' }}
                </div>

                <div style="font-size:0.9rem;font-weight:700;color:var(--primary);margin-top:6px;">
                    Rp {{ number_format($t->product->price, 0, ',', '.') }}
                </div>

                {{-- STATUS BADGE --}}
                <div style="margin-top:8px;">
                    @if($t->status === 'pending')
                        <span style="background:#eff6ff;color:#1e40af;
                            padding:4px 10px;border-radius:999px;font-size:0.75rem;">
                            💳 Menunggu Pembayaran
                        </span>
                    @elseif($t->status === 'paid')
                        <span style="background:#fff7ed;color:#9a3412;
                            padding:4px 10px;border-radius:999px;font-size:0.75rem;">
                            📌 Dibayar
                        </span>
                    @elseif($t->status === 'shipped')
                        <span style="background:#fefce8;color:#92400e;
                            padding:4px 10px;border-radius:999px;font-size:0.75rem;">
                            🚚 Dikirim
                        </span>
                    @elseif($t->status === 'completed')
                        <span style="background:#ecfdf5;color:#065f46;
                            padding:4px 10px;border-radius:999px;font-size:0.75rem;">
                            ✅ Selesai
                        </span>
                    @endif
                </div>
            </div>

            {{-- AKSI --}}
            <div style="text-align:right;">
                <a href="{{ route('products.show', $t->product_id) }}"
                   class="btn btn-outline"
                   style="font-size:0.85rem;">
                    Lihat Detail
                </a>
            </div>

        </div>

    @empty
        <div style="
            background:#fff;
            border:1px dashed var(--border);
            border-radius:18px;
            padding:40px;
            text-align:center;
            color:#9ca3af;
        ">
            Kamu belum memiliki pesanan.
        </div>
    @endforelse

</div>
@endsection
