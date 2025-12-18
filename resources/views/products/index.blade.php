@extends('layouts.main')

@section('title', 'Katalog Produk - NyarisBaru')

@section('content')

<div class="container"><!-- ⬅️ INI KUNCI UTAMA -->

<style>
    .page-header {
        margin: 40px 0 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .page-title {
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--text-main);
        letter-spacing: -0.02em;
    }
    .text-muted {
        color: #888;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .filter-bar {
        background:#fff;
        border:1px solid var(--border);
        padding:16px;
        border-radius:12px;
        margin-bottom:32px;
        display:flex;
        gap:12px;
        flex-wrap:wrap;
        align-items:center;
    }

    .form-control {
        border:1px solid var(--border);
        padding:10px 16px;
        border-radius:8px;
        font-size:0.9rem;
        background:#FDFDFD;
    }

    .search-input { flex:1; min-width:200px; }
    .select-input { min-width:150px; }

    .product-grid {
        display:grid;
        grid-template-columns:repeat(auto-fill,minmax(240px,1fr));
        gap:24px;
    }

    .product-card {
        background:#fff;
        border:1px solid var(--border);
        border-radius:16px;
        overflow:hidden;
        display:block;
        transition:.2s;
        position:relative;
    }

    .product-card:hover {
        transform:translateY(-4px);
        box-shadow:0 12px 24px rgba(0,0,0,.06);
    }

    .card-thumb {
        height:200px;
        background:#f1f5f9;
        display:flex;
        align-items:center;
        justify-content:center;
    }

    .card-details { padding:16px; }
    .card-category { font-size:.75rem; color:#888; text-transform:uppercase; }
    .card-name { font-size:1rem; font-weight:700; margin:8px 0; }
    .card-footer { display:flex; justify-content:space-between; }
    .card-price { color:var(--primary); font-weight:700; }
    .card-city { font-size:.8rem; color:#888; }

    .badge-condition {
        position:absolute;
        top:12px;
        right:12px;
        background:#1e293b;
        color:#fff;
        font-size:.7rem;
        padding:4px 8px;
        border-radius:6px;
    }

    .pagination-wrapper {
        margin: 24px 0 60px;
        display: flex;
        justify-content: center;
    }

    /* UL pagination bawaan Laravel */
    .pagination{
    display:flex !important;
    align-items:center;
    gap:10px;
    list-style:none;
    padding:0;
    margin:0;
    }

    /* Link/button */
    .pagination .page-link,
    .pagination a,
    .pagination span{
    display:inline-flex !important;
    align-items:center;
    justify-content:center;
    min-width:40px;
    height:40px;
    padding:0 14px;
    border-radius:12px;
    border:1px solid #e5e7eb;
    background:#fff;
    color:#0f172a;
    text-decoration:none;
    font-weight:600;
    }

    /* Active */
    .pagination .active > span,
    .pagination .page-item.active .page-link{
    background: var(--primary);
    border-color: var(--primary);
    color:#fff;
    }

    /* Disabled */
    .pagination .disabled > span{
    opacity:.5;
    cursor:not-allowed;
    }

    /* INI KUNCI: kecilin ikon panah SVG bawaan */
    .pagination svg{
    width:18px !important;
    height:18px !important;
    }

    /* Kalau panah masih “ngaco”, paksa jangan melebar */
    .pagination .page-link svg{
    flex:0 0 auto;
    }

</style>

{{-- HEADER --}}
<div class="page-header">
    <div>
        <h1 class="page-title">Katalog Produk</h1>
        <div class="text-muted">Temukan barang impianmu</div>
    </div>

    @auth
        <a href="{{ route('products.create') }}" class="btn btn-primary">+ Jual Barang</a>
    @endauth
</div>

{{-- FILTER --}}
<form method="GET" class="filter-bar">
    <input type="text" name="q" value="{{ request('q') }}" class="form-control search-input" placeholder="🔍 Cari nama barang...">
    <select name="category" class="form-control select-input">
        <option value="">Semua Kategori</option>

        @foreach($categories as $cat)
            <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>
                {{ $cat }}
            </option>
        @endforeach
    </select>
    <button class="btn btn-primary">Filter</button>
</form>

{{-- GRID --}}
<div class="product-grid">
@forelse($products as $product)
    <a href="{{ route('products.show',$product->id) }}" class="product-card">
        <span class="badge-condition">{{ $product->condition }}% New</span>

        <div class="card-thumb">
            @if($product->image_path)
                <img src="{{ asset('storage/'.$product->image_path) }}"
                     style="width:100%;height:100%;object-fit:cover;">
            @endif
        </div>

        <div class="card-details">
            <div class="card-category">{{ $product->category ?? 'Umum' }}</div>
            <div class="card-name">{{ $product->name }}</div>
            <div class="card-footer">
                <div class="card-price">Rp {{ number_format($product->price,0,',','.') }}</div>
                <div class="card-city">{{ $product->city }}</div>
            </div>
        </div>
    </a>
@empty
    <div style="grid-column:1/-1;text-align:center;color:#888;">
        Belum ada produk
    </div>
@endforelse
</div>

{{-- PAGINATION --}}
<div class="pagination-wrapper">
    {{ $products->onEachSide(1)->links('pagination::bootstrap-5') }}
</div>

</div><!-- ⬅️ END CONTAINER -->
@endsection
