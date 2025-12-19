@extends('layouts.main')

@section('title', 'Profil Saya - NyarisBaru')

@section('content')
    <div class="container">
        <style>
            .profile-layout {
                padding: 40px 0 70px;
                display: grid;
                grid-template-columns: minmax(0, 1.2fr) minmax(0, 1.5fr);
                gap: 32px;
            }

            .pf-card {
                background: #fff;
                border-radius: 20px;
                border: 1px solid var(--border);
                padding: 22px 22px 24px;
                box-shadow: 0 12px 26px rgba(15, 23, 42, 0.04);
            }

            .pf-header-title {
                font-size: 1.7rem;
                font-weight: 800;
                color: var(--text-main);
                letter-spacing: -0.02em;
                margin-bottom: 4px;
            }

            .pf-header-sub {
                font-size: 0.9rem;
                color: #6b7280;
                margin-bottom: 18px;
            }

            .pf-main-row {
                display: flex;
                align-items: center;
                gap: 16px;
                margin-bottom: 22px;
            }

            .pf-avatar {
                width: 64px;
                height: 64px;
                border-radius: 999px;
                background: #e5e7eb;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 700;
                font-size: 1.4rem;
                color: #4b5563;
                overflow: hidden; /* penting biar img ikut bulat */
                flex: 0 0 auto;
            }

            .pf-avatar img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }

            .pf-name {
                font-size: 1.2rem;
                font-weight: 700;
            }

            .pf-email {
                font-size: 0.9rem;
                color: #6b7280;
            }

            .pf-meta {
                font-size: 0.85rem;
                color: #9ca3af;
            }

            .pf-stats {
                display: flex;
                gap: 16px;
                flex-wrap: wrap;
            }

            .pf-stat-box {
                padding: 12px 16px;
                border-radius: 12px;
                border: 1px solid var(--border);
                background: #f9fafb;
                min-width: 130px;
                text-decoration: none;
                color: inherit;
                transition: 0.2s ease;
            }

            .pf-stat-box:hover {
                background: #eef6fb;
                border-color: #cfe6f5;
            }

            .pf-stat-label {
                font-size: 0.8rem;
                color: #6b7280;
            }

            .pf-stat-value {
                font-size: 1.2rem;
                font-weight: 800;
            }

            .pf-section-title {
                font-size: 1rem;
                font-weight: 700;
                margin-bottom: 10px;
            }

            .pf-list {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }

            .pf-item {
                border-radius: 12px;
                border: 1px solid var(--border);
                padding: 10px 12px;
                display: flex;
                justify-content: space-between;
                text-decoration: none;
                color: inherit;
            }

            .pf-item-title {
                font-weight: 600;
            }

            .pf-item-meta {
                font-size: 0.8rem;
                color: #9ca3af;
            }

            .pf-empty {
                font-size: 0.85rem;
                color: #9ca3af;
            }

            @media(max-width:900px) {
                .profile-layout {
                    grid-template-columns: 1fr;
                }
            }
        </style>

        @php
            // dukung dua kemungkinan nama kolom (biar aman):
            // - avatar_path (punya kamu, keliatan dari error migrate)
            // - profile_photo_path (yang sempat kepake di controller)
            $avatarPath = $user->avatar_path ?? $user->profile_photo_path ?? null;
        @endphp

        <section class="profile-layout">
            {{-- KIRI --}}
            <div class="pf-card">
                <h1 class="pf-header-title">Profil Saya</h1>
                <p class="pf-header-sub">Ringkasan aktivitas kamu di NyarisBaru.</p>

                <div class="pf-main-row">
                    <div class="pf-avatar" style="overflow:hidden;">
                        @if(!empty($user->avatar_path))
                            <img
                                src="{{ asset('storage/' . $user->avatar_path) }}"
                                alt="Avatar"
                                style="width:100%;height:100%;object-fit:cover;"
                            >
                        @else
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        @endif
                    </div>


                    <div>
                        <div class="pf-name">{{ $user->name }}</div>

                        @if($isTrustedSeller)
                            <div style="
                                display:inline-flex;
                                align-items:center;
                                gap:6px;
                                padding:4px 10px;
                                border-radius:999px;
                                background:#ecfdf5;
                                border:1px solid #a7f3d0;
                                color:#065f46;
                                font-size:0.75rem;
                                font-weight:600;
                                margin-top:6px;
                            ">
                                Trusted Seller
                            </div>
                        @endif

                        <div class="pf-email">{{ $user->email }}</div>
                        <div class="pf-meta">
                            Bergabung sejak {{ $user->created_at->format('M Y') }}
                        </div>
                    </div>
                </div>

                {{-- RATING SEBAGAI PENJUAL --}}
                @if($sellerReviewCount > 0)
                    <a href="{{ route('seller.reviews') }}" style="
                        display:block;
                        margin-bottom:16px;
                        text-decoration:none;
                        color:inherit;
                        transition:0.15s;
                    " onmouseover="this.style.opacity='0.75'" onmouseout="this.style.opacity='1'">

                        <div style="font-size:0.85rem;color:#6b7280;">
                            Rating sebagai penjual
                        </div>

                        <div style="font-size:1.2rem;font-weight:700;color:#1e2b3a;">
                            ⭐ {{ number_format($sellerAvgRating, 1) }}/5
                            <span style="font-size:0.85rem;color:#6b7280;">
                                ({{ $sellerReviewCount }} ulasan)
                            </span>
                        </div>
                    </a>
                @else
                    <div style="margin-bottom:16px;font-size:0.85rem;color:#9ca3af;">
                        Belum ada rating sebagai penjual
                    </div>
                @endif

                {{-- STATS --}}
                <div class="pf-stats">
                    <div class="pf-stat-box">
                        <div class="pf-stat-label">Produk dijual</div>
                        <div class="pf-stat-value">{{ $stats['products_count'] }}</div>
                    </div>

                    <div class="pf-stat-box">
                        <div class="pf-stat-label">Diskusi forum</div>
                        <div class="pf-stat-value">{{ $stats['posts_count'] }}</div>
                    </div>

                    {{-- ✅ WISHLIST BISA DIKLIK --}}
                    <a href="{{ route('wishlist.index') }}" class="pf-stat-box">
                        <div class="pf-stat-label">Wishlist</div>
                        <div class="pf-stat-value">{{ $stats['wishlist_count'] }}</div>
                    </a>
                </div>

                <div style="margin-top:20px;display:flex;gap:10px;">
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline">Edit profil</a>
                    <a href="{{ route('products.create') }}" class="btn btn-primary">+ Pasang produk</a>
                </div>
            </div>

            {{-- KANAN --}}
            <div style="display:flex;flex-direction:column;gap:20px;">
                <div class="pf-card">
                    <div class="pf-section-title">Produk saya</div>
                    @if($products->isEmpty())
                        <p class="pf-empty">Belum ada produk.</p>
                    @else
                        <div class="pf-list">
                            @foreach($products as $product)
                                <a href="{{ route('products.show', $product->id) }}" class="pf-item">
                                    <div>
                                        <div class="pf-item-title">{{ $product->name }}</div>
                                        <div class="pf-item-meta">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </div>
                                    </div>
                                    <div class="pf-item-meta">
                                        {{ $product->created_at->diffForHumans() }}
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="pf-card">
                    <div class="pf-section-title">Diskusi saya</div>
                    @if($posts->isEmpty())
                        <p class="pf-empty">Belum ada diskusi.</p>
                    @else
                        <div class="pf-list">
                            @foreach($posts as $post)
                                <a href="{{ route('forum.show', $post->id) }}" class="pf-item">
                                    <div class="pf-item-title">{{ $post->title }}</div>
                                    <div class="pf-item-meta">
                                        {{ $post->created_at->diffForHumans() }}
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="pf-card">
                    <div class="pf-section-title" style="display:flex;align-items:center;gap:10px;">
                        Produk yang saya beli
                        @if(!empty($unreadTransactionNotif) && $unreadTransactionNotif > 0)
                            <span class="badge">{{ $unreadTransactionNotif }}</span>
                        @endif
                    </div>

                    @if($transactionsBought->isEmpty())
                        <p class="pf-empty">Belum ada pembelian.</p>
                    @else
                        <div class="pf-list">
                            @foreach($transactionsBought as $trx)
                                <a href="{{ route('products.show', $trx->product->id) }}" class="pf-item">
                                    <div>
                                        <div class="pf-item-title">
                                            {{ $trx->product->name }}
                                        </div>
                                        <div class="pf-item-meta">
                                            Rp {{ number_format($trx->product->price, 0, ',', '.') }}
                                            • Penjual: {{ $trx->seller->name }}
                                        </div>
                                    </div>

                                    <div class="pf-item-meta" style="text-align:right;">
                                        <div>{{ strtoupper($trx->status) }}</div>
                                        <div>{{ $trx->created_at->diffForHumans() }}</div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </section>
    </div>
@endsection
