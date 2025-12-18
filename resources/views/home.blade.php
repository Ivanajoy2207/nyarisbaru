@extends('layouts.main')

@section('title', 'NyarisBaru - Barang Lama, Cerita Baru')

@section('content')

<style>
    .home-wrapper {
        padding: 48px 0 72px;
    }

    /* === HERO === */
    .hero {
        display: grid;
        grid-template-columns: minmax(0, 1.4fr) minmax(0, 1fr);
        gap: 40px;
        align-items: center;
        margin-bottom: 56px;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 14px;
        border-radius: 999px;
        background: #EAF4FA;
        border: 1px solid #D6E8F5;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 18px;
    }

    .hero-title {
        font-size: 3rem;
        line-height: 1.1;
        font-weight: 800;
        color: var(--text-main);
        letter-spacing: -0.03em;
        margin-bottom: 16px;
    }

    .hero-title span {
        color: var(--primary);
    }

    .hero-text {
        font-size: 1.02rem;
        color: var(--text-body);
        max-width: 480px;
        margin-bottom: 24px;
    }

    .hero-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }

    .hero-stats {
        display: flex;
        gap: 24px;
        flex-wrap: wrap;
        font-size: 0.85rem;
        color: #6b7280;
    }

    .hero-stat-number {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-main);
    }

    /* Hero right card (produk terbaru mini) */
    .hero-right-card {
        background: #FFFFFF;
        border-radius: 20px;
        border: 1px solid var(--border);
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        padding: 20px 20px 18px;
    }

    .hrc-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        font-size: 0.9rem;
    }

    .hrc-title {
        font-weight: 700;
        color: var(--text-main);
    }

    .hrc-link {
        color: var(--primary);
        font-weight: 600;
        font-size: 0.85rem;
    }

    .hrc-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .hrc-thumb {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        flex-shrink: 0;
        overflow: hidden;
    }

    .hrc-info-title {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-main);
    }

    .hrc-info-price {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--primary);
    }

    .hrc-empty {
        font-size: 0.85rem;
        color: #9ca3af;
        padding-top: 8px;
    }

    /* === CATEGORIES === */
    .category-section {
        margin-bottom: 40px;
    }

    .cat-label {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 10px;
    }

    .cat-pill-row {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .cat-pill {
        padding: 8px 18px;
        border-radius: 999px;
        border: 1px solid var(--border);
        background: #fff;
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--text-body);
        cursor: pointer;
        transition: 0.2s;
    }

    .cat-pill:hover {
        border-color: var(--primary);
        color: var(--primary);
        box-shadow: 0 4px 10px rgba(148, 163, 184, 0.25);
    }

    .cat-pill.is-main {
        border-color: var(--primary);
        background: #EAF4FA;
        color: var(--text-main);
    }

    /* === PRODUCT GRID SECTION === */
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 20px;
    }

    .section-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--text-main);
    }

    .section-sub {
        font-size: 0.9rem;
        color: #6b7280;
    }

    .section-link {
        color: var(--primary);
        font-weight: 600;
        font-size: 0.9rem;
    }

    .home-product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
        gap: 20px;
    }

    .hp-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid var(--border);
        overflow: hidden;
        display: block;
        transition: 0.2s;
        position: relative;
    }

    .hp-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 26px rgba(15, 23, 42, 0.06);
        border-color: #cbd5e1;
    }

    .hp-thumb {
        height: 170px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        overflow: hidden;
    }

    .hp-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .hp-body {
        padding: 14px 14px 16px;
    }

    .hp-meta {
        font-size: 0.8rem;
        color: #9ca3af;
        margin-bottom: 4px;
    }

    .hp-name {
        font-size: 0.96rem;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 6px;
    }

    .hp-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 4px;
    }

    .hp-price {
        font-weight: 700;
        font-size: 1rem;
        color: var(--primary);
    }

    .hp-city {
        font-size: 0.8rem;
        color: #9ca3af;
    }

    .hp-condition {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 0.75rem;
        background: rgba(30, 43, 58, 0.9);
        color: #fff;
        padding: 4px 8px;
        border-radius: 999px;
    }

    /* SDG strip kecil di home */
    .sdg-strip {
        margin-top: 48px;
        padding: 18px 20px;
        border-radius: 16px;
        border: 1px solid var(--border);
        background: #fff;
        display: flex;
        align-items: center;
        gap: 18px;
    }

    .sdg-number {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: #f97316;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
    }

    .sdg-text-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--text-main);
    }

    .sdg-text-body {
        font-size: 0.85rem;
        color: #6b7280;
    }

    @media (max-width: 900px) {
        .hero {
            grid-template-columns: minmax(0, 1fr);
        }
    }

    @media (max-width: 640px) {
        .hero-title {
            font-size: 2.3rem;
        }
        .hero {
            margin-bottom: 40px;
        }
        .hero-actions {
            justify-content: flex-start;
        }
    }
</style>

<div class="home-wrapper container">

    {{-- HERO SECTION --}}
    <section class="hero">
        <div>
            <div class="hero-badge">
                <span>♻️ Sustainable Marketplace</span>
                <span>Preloved • Fashion • Beauty</span>
            </div>

            <h1 class="hero-title">
                Barang Lama, <span>Cerita Baru.</span>
            </h1>

            <p class="hero-text">
                Temukan dan jual barang preloved berkualitas dengan cara yang lebih aman,
                estetik, dan ramah lingkungan. Cocok untuk mahasiswa yang mau tetap stylish tanpa boros.
            </p>

            <div class="hero-actions">
                <a href="{{ route('products.index') }}" class="btn btn-primary" style="padding-inline:28px;">
                    Mulai Belanja
                </a>
                <a href="{{ route('products.create') }}" class="btn btn-outline" style="padding-inline:28px;">
                    Jual Barang
                </a>
            </div>

            <div class="hero-stats">
                <div>
                    <div class="hero-stat-number">
                        {{ \App\Models\Product::whereDoesntHave('transaction')->count() }}
                    </div>
                    <div>Produk preloved siap dipilih</div>
                </div>
                <div>
                    <div class="hero-stat-number">100%</div>
                    <div>Transaksi lewat sistem “Dana Aman” (konsep)</div>
                </div>
            </div>
        </div>

        <div class="hero-right-card">
            <div class="hrc-header">
                <div class="hrc-title">Baru Diupload</div>
                <a href="{{ route('products.index') }}" class="hrc-link">Lihat Semua →</a>
            </div>

            @php
                $latest = $products->take(4);
            @endphp

            @forelse($latest as $p)
                <a href="{{ route('products.show', $p->id) }}" class="hrc-item">
                    <div class="hrc-thumb">
                        @if($p->image_path)
                            <img src="{{ asset('storage/'.$p->image_path) }}"
                                 alt="{{ $p->name }}"
                                 style="width:100%;height:100%;object-fit:cover;border-radius:10px;">
                        @else
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                 stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                <polyline points="21 15 16 10 5 21"></polyline>
                            </svg>
                        @endif
                    </div>
                    <div style="flex:1;">
                        <div class="hrc-info-title">{{ Str::limit($p->name, 32) }}</div>
                        <div class="hrc-info-price">Rp {{ number_format($p->price, 0, ',', '.') }}</div>
                        <div style="font-size:0.78rem;color:#9ca3af;">
                            {{ $p->category ?? 'Umum' }} • {{ $p->city ?? 'Lokasi' }}
                        </div>
                    </div>
                </a>
            @empty
                <div class="hrc-empty">
                    Belum ada produk. Yuk jadi yang pertama upload barang preloved ✨
                </div>
            @endforelse
        </div>
    </section>

    {{-- KATEGORI POPULER --}}
    <section class="category-section">
        <div class="cat-label">Kategori populer</div>

        <div class="cat-pill-row">
            {{-- tombol "Semua" --}}
            <a class="cat-pill is-main" href="{{ route('products.index') }}">
                Semua Kategori
            </a>

            {{-- tombol kategori dari controller --}}
            @foreach($categories as $cat)
                <a class="cat-pill" href="{{ route('products.index', ['category' => $cat]) }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>
    </section>



    {{-- PRODUK TERBARU GRID --}}
    <section>
        <div class="section-header">
            <div>
                <div class="section-title">Terbaru di NyarisBaru</div>
                <div class="section-sub">Beberapa produk yang baru saja diupload seller.</div>
            </div>
            <a href="{{ route('products.index') }}" class="section-link">
                Lihat semua produk →
            </a>
        </div>

        <div class="home-product-grid">
            @forelse($products as $product)
                <a href="{{ route('products.show', $product->id) }}" class="hp-card">
                    <span class="hp-condition">{{ $product->condition }}%</span>
                    <div class="hp-thumb">
                        @if($product->image_path)
                            <img src="{{ asset('storage/'.$product->image_path) }}"
                                 alt="{{ $product->name }}">
                        @else
                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.5"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                <polyline points="21 15 16 10 5 21"></polyline>
                            </svg>
                        @endif
                    </div>
                    <div class="hp-body">
                        <div class="hp-meta">
                            {{ $product->category ?? 'Umum' }} • {{ $product->city ?? 'Lokasi' }}
                        </div>
                        <div class="hp-name">{{ Str::limit($product->name, 40) }}</div>
                        <div class="hp-footer">
                            <div class="hp-price">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </div>
                            <div class="hp-city">
                                {{ $product->condition }}% kondisi
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <p style="color:#9ca3af;font-size:0.9rem;">
                    Belum ada produk yang tampil. Setelah kamu upload barang pertama, daftar ini akan terisi otomatis.
                </p>
            @endforelse
        </div>
    </section>

    {{-- STRIP SDG SINGKAT --}}
    <section class="sdg-strip">
        <div class="sdg-number">12</div>
        <div>
            <div class="sdg-text-title">Responsible Consumption & Production</div>
            <div class="sdg-text-body">
                Setiap transaksi preloved membantu mengurangi produksi baru dan limbah tekstil.
                NyarisBaru mendukung gaya hidup hemat sekaligus lebih berkelanjutan.
            </div>
        </div>
    </section>
</div>
@endsection
