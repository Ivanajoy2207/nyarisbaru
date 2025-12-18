@extends('layouts.main')
@section('title', 'Wishlist Saya')

@section('content')
<div class="container" style="padding:40px;">
    <h2 style="font-weight:800;font-size:1.6rem;margin-bottom:20px;">Wishlist Saya ❤️</h2>

    @if($products->isEmpty())
        <p style="color:#9ca3af;">Belum ada produk disimpan. Coba tambahkan dulu! ✨</p>
    @else
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:18px;">
            @foreach($products as $p)
                <a href="{{ route('products.show',$p->id) }}" style="border:1px solid #ddd;border-radius:12px;padding:10px;display:block;">
                    <img src="{{ asset('storage/'.$p->image_path) }}" style="width:100%;border-radius:8px;">
                    <div style="font-weight:700;margin-top:6px;">{{ $p->name }}</div>
                    <div style="color:#64748b;">Rp {{ number_format($p->price,0,',','.') }}</div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
