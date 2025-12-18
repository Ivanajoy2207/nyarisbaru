@extends('layouts.main')

@section('title', 'Rating Saya sebagai Penjual')

@section('content')
<div class="container" style="padding:40px 0">

    <h1 style="font-size:1.6rem;font-weight:800;margin-bottom:6px">
        Rating Saya sebagai Penjual
    </h1>

    <p style="color:#6b7280;margin-bottom:20px">
        ⭐ {{ number_format($avgRating ?? 0, 1) }}/5 dari {{ $reviewCount }} review
    </p>

    @if($reviews->isEmpty())
        <p style="color:#9ca3af">Belum ada review dari pembeli.</p>
    @else
        <div style="display:flex;flex-direction:column;gap:14px">
            @foreach($reviews as $review)
                <div style="
                    border:1px solid var(--border);
                    border-radius:12px;
                    padding:14px;
                    background:#fff;
                ">
                    <div style="display:flex;justify-content:space-between">
                        <strong>{{ $review->user->name ?? 'Pembeli' }}</strong>
                        <span style="color:#9ca3af;font-size:0.85rem">
                            {{ $review->created_at->diffForHumans() }}
                        </span>
                    </div>

                    <div style="font-size:0.85rem;color:#6b7280;margin-bottom:6px">
                        Produk: {{ $review->product->name }}
                    </div>

                    <div style="color:#fbbf24">
                        @for($i=1;$i<=5;$i++)
                            {{ $i <= $review->rating ? '★' : '☆' }}
                        @endfor
                    </div>

                    @if($review->comment)
                        <p style="margin-top:6px;color:#374151">
                            {{ $review->comment }}
                        </p>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
