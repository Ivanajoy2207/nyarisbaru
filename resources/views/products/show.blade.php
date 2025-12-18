@extends('layouts.main')

@section('title', $product->name . ' - NyarisBaru')

@section('content')
<div class="container">
    <style>
        .prod-layout{
            padding:40px 0 70px;
            display:grid;
            grid-template-columns:minmax(0,1.3fr) minmax(0,1.2fr);
            gap:32px;
            text-align:left;
        }

        .card-prod,.card-review{
            background:#fff;
            border-radius:20px;
            border:1px solid var(--border);
            padding:22px 22px 24px;
            box-shadow:0 16px 32px rgba(15,23,42,0.04);
        }

        /* sticky (desktop) */
        @media(min-width:901px){
            .card-prod,.card-review{
                position:sticky;
                top:90px;
                align-self:start;
            }
        }

        .prod-thumb{
            width:100%;
            border-radius:18px;
            overflow:hidden;
            background:#f1f5f9;
            display:flex;
            align-items:center;
            justify-content:center;
            min-height:260px;
        }
        .prod-thumb img{width:100%;height:100%;object-fit:cover;}

        .prod-tag{
            display:inline-flex;
            align-items:center;
            gap:6px;
            font-size:.8rem;
            padding:6px 10px;
            border-radius:999px;
            background:#EAF4FA;
            border:1px solid #D6E8F5;
            color:var(--text-main);
            margin-bottom:14px;
        }

        .prod-title{
            font-size:1.8rem;
            font-weight:800;
            color:var(--text-main);
            letter-spacing:-0.02em;
            margin-bottom:6px;
        }

        .prod-meta{
            font-size:.9rem;
            color:#6b7280;
            margin-top:10px;
            margin-bottom:14px;
        }

        .prod-price{
            font-size:1.5rem;
            font-weight:800;
            color:var(--primary);
            margin-bottom:8px;
        }

        .prod-condition{
            font-size:.9rem;
            color:#4b5563;
            margin-bottom:4px;
        }

        .prod-actions{
            display:flex;
            gap:10px;
            margin-top:18px;
            flex-wrap:wrap;
        }

        .prod-desc-title{
            font-size:1rem;
            font-weight:800;
            color:var(--text-main);
            margin-top:18px;
            margin-bottom:6px;
        }

        .prod-desc{
            font-size:.92rem;
            color:#4b5563;
            white-space:pre-line;
            word-break:break-word;
            overflow-wrap:anywhere;
        }

        /* badges */
        .badge{
            display:inline-flex;
            align-items:center;
            gap:6px;
            padding:6px 12px;
            border-radius:999px;
            font-size:.8rem;
            font-weight:800;
            white-space:nowrap;
        }
        .badge-sold{
            background:#fef2f2;
            border:1px solid #fecaca;
            color:#991b1b;
            margin-top:10px;
        }
        .badge-progress{
            background:#fff7ed;
            border:1px solid #fed7aa;
            color:#9a3412;
            margin-top:10px;
        }
        .badge-canceled{
            background:#eff6ff;
            border:1px solid #bfdbfe;
            color:#1e40af;
            margin-top:10px;
        }

        /* seller mini badges */
        .seller-badges{
            display:flex;
            flex-wrap:wrap;
            gap:8px;
            margin-top:8px;
            align-items:center;
        }
        .badge-mini{
            display:inline-flex;
            align-items:center;
            gap:6px;
            padding:4px 10px;
            border-radius:999px;
            font-size:.75rem;
            font-weight:800;
            white-space:nowrap;
        }
        .badge-trusted{
            background:#ecfdf5;
            border:1px solid #a7f3d0;
            color:#065f46;
        }
        .badge-rating{
            background:#fefce8;
            border:1px solid #fde68a;
            color:#92400e;
        }
        .badge-sub{
            font-weight:700;
            color:#6b7280;
        }

        /* wishlist danger */
        .btn-wishlist-danger{
            border-color:#fecaca !important;
            color:#b91c1c !important;
            background:#fff !important;
        }
        .btn-wishlist-danger:hover{
            background:#fff1f2 !important;
            border-color:#fca5a5 !important;
        }

        /* right panel UI */
        .panel-title{
            font-size:1rem;
            font-weight:900;
            color:var(--text-main);
            margin-bottom:8px;
        }
        .muted{ font-size:.88rem; color:#9ca3af; }
        .stars{ display:flex; gap:2px; line-height:1; }
        .stars span{ font-size:1rem; }
        .star-filled{ color:#fbbf24; }
        .star-empty{ color:#e5e7eb; }

        .select-rating,
        .textarea{
            width:100%;
            border-radius:12px;
            border:1px solid var(--border);
            padding:10px 12px;
            font-size:.9rem;
            outline:none;
            background:#FDFDFD;
            transition:.2s;
        }
        .textarea{ min-height:90px; resize:vertical; }

        .select-rating:focus,
        .textarea:focus{
            border-color:var(--primary);
            box-shadow:0 0 0 3px rgba(107,167,204,0.12);
            background:#fff;
        }

        .review-help{ font-size:.82rem; color:#6b7280; margin-top:6px; }

        .mini-card{
            border:1px solid var(--border);
            background:#f9fafb;
            border-radius:14px;
            padding:12px;
        }
        .mini-row{
            display:flex;
            justify-content:space-between;
            gap:12px;
            align-items:flex-start;
        }
        .mini-name{
            font-weight:800;
            color:var(--text-main);
            font-size:.92rem;
            line-height:1.2;
        }
        .mini-sub{ font-size:.82rem; color:#6b7280; margin-top:4px; }
        .mini-comment{
            margin-top:8px;
            font-size:.9rem;
            color:#374151;
            white-space:pre-line;
            word-break:break-word;
            overflow-wrap:anywhere;
            line-height:1.6;
        }

        @media(max-width:900px){
            .prod-layout{ grid-template-columns:minmax(0,1fr); }
        }
    </style>

    @php
        use App\Models\Transaction;
        use App\Models\Product as ProductModel;
        use App\Models\ProductReview;

        // Ambil transaksi TERBARU dari DB (lebih aman daripada relasi kalau relasinya belum latestOfMany)
        $latestTx = Transaction::where('product_id', $product->id)->latest()->first();

        // Transaksi AKTIF terbaru = bukan canceled
        $activeTx = Transaction::where('product_id', $product->id)
            ->where('status', '!=', 'canceled')
            ->latest()
            ->first();

        // Banner cancel muncul hanya kalau transaksi TERBARU itu canceled
        $isCanceledTx = ($latestTx && $latestTx->status === 'canceled');

        // biar kompatibel sama kode lama yang pakai $transaction
        $transaction = $activeTx;

        $desc = $product->description;

        // ====== Data reputasi penjual (tampilkan "pernah jual apa" + komentar pembeli) ======
        $sellerSoldProductIds = $seller
            ? ProductModel::where('user_id', $seller->id)
                ->whereHas('transaction', fn($q) => $q->where('status', 'completed'))
                ->pluck('id')
            : collect();

        // Review dari pembeli utk produk-produk seller yang sudah completed
        $sellerRecentReviews = $sellerSoldProductIds->count()
            ? ProductReview::with(['user', 'product'])
                ->whereIn('product_id', $sellerSoldProductIds)
                ->latest()
                ->take(6)
                ->get()
            : collect();

        // Produk-produk yang pernah terjual (completed) oleh seller (tampilan ringkas)
        $sellerSoldProducts = $seller
            ? ProductModel::query()
                ->where('user_id', $seller->id)
                ->whereHas('transaction', fn($q) => $q->where('status', 'completed'))
                ->latest()
                ->take(6)
                ->get()
            : collect();
    @endphp

    <section class="prod-layout">
        {{-- ================= LEFT: PRODUCT DETAIL ================= --}}
        <div class="card-prod">
            <div class="prod-thumb">
                @if($product->image_path)
                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}">
                @else
                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                @endif
            </div>

            <div style="margin-top:18px;">
                <div class="prod-tag">
                    {{ $product->category ?? 'Umum' }}
                    <span style="font-size:.75rem;color:#9ca3af;">• {{ $product->city ?? 'Lokasi tidak ada' }}</span>
                </div>

                <h1 class="prod-title">{{ $product->name }}</h1>

                {{-- STATUS PRODUK --}}
                @if($activeTx && $activeTx->status === 'completed')
                    <span class="badge badge-sold">❌ Produk sudah terjual</span>
                @elseif($activeTx && in_array($activeTx->status, ['pending','paid','shipped']))
                    <span class="badge badge-progress">⏳ Produk sedang dalam proses transaksi</span>
                @elseif($isCanceledTx)
                    <span class="badge badge-canceled">✅ Transaksi sebelumnya dibatalkan — produk tersedia lagi</span>
                @endif

                {{-- CANCEL BUTTON (hanya buyer & hanya pending) --}}
                @auth
                    @if($activeTx && $activeTx->status === 'pending' && auth()->id() === $activeTx->buyer_id)
                        <form action="{{ route('transactions.cancel', $activeTx->id) }}" method="POST" style="margin-top:10px;">
                            @csrf
                            <button type="submit" class="btn btn-outline" style="width:100%;">
                                Batalkan Pembelian
                            </button>
                        </form>
                    @endif
                @endauth

                <div class="prod-meta">
                    Kondisi: {{ $product->condition ?? '-' }}%
                    @if($product->buy_year) • Dibeli {{ $product->buy_year }} @endif

                    @if($seller)
                        <div style="margin-top:10px;">
                            <strong>Penjual:</strong> {{ $seller->name }}

                            <div class="seller-badges">
                                @if(!empty($isTrustedSeller) && $isTrustedSeller)
                                    <span class="badge-mini badge-trusted">✔ Trusted Seller</span>
                                @endif

                                @if(!empty($sellerReviewCount) && $sellerReviewCount > 0)
                                    <span class="badge-mini badge-rating">
                                        ⭐ {{ number_format($sellerAvgRating, 1) }}/5
                                        <span class="badge-sub">({{ $sellerReviewCount }} transaksi berhasil)</span>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <div class="prod-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>

                <div class="prod-condition">
                    @if(!empty($productReviewCount) && $productReviewCount > 0)
                        Rating produk: {{ number_format($productAvgRating, 1) }}/5 ({{ $productReviewCount }} ulasan)
                    @else
                        Belum ada rating produk
                    @endif
                </div>

                <div class="prod-actions">
                    @auth
                        <a href="{{ route('chat.withSeller', $product->id) }}" class="btn btn-primary">
                            Chat penjual
                        </a>

                        <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" style="margin:0;">
                            @csrf
                            @if(!empty($isWishlisted) && $isWishlisted)
                                <button type="submit" class="btn btn-outline btn-wishlist-danger">
                                    ❤️ Hapus dari wishlist
                                </button>
                            @else
                                <button type="submit" class="btn btn-outline">
                                    🤍 Tambah ke wishlist
                                </button>
                            @endif
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            Masuk untuk chat penjual
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline">
                            Login untuk wishlist
                        </a>
                    @endauth
                </div>

                {{-- ================= ESCROW / BUY BUTTON ================= --}}
                @auth
                    {{-- tampilkan status transaksi hanya kalau transaksi AKTIF --}}
                    @if($activeTx)
                        <div style="width:100%;margin-top:12px;font-size:0.85rem;color:#374151;">
                            <strong>Status Transaksi:</strong> {{ strtoupper($activeTx->status) }}
                            • <strong>Escrow:</strong> {{ strtoupper($activeTx->escrow_status) }}
                        </div>

                        @if($activeTx->status === 'pending')
                            <div style="margin-top:10px;padding:10px 12px;border-radius:12px;background:#eff6ff;border:1px solid #bfdbfe;color:#1e40af;font-size:0.85rem;">
                                💳 Menunggu pembayaran dari pembeli.
                            </div>
                        @endif

                        @if(auth()->id() === $activeTx->seller_id && $activeTx->status === 'paid')
                            <form action="{{ route('transactions.ship', $activeTx->id) }}" method="POST" style="margin-top:10px;">
                                @csrf
                                <button type="submit" class="btn btn-outline" style="width:100%;">
                                    Barang Sudah Dikirim
                                </button>
                            </form>
                        @endif

                        @if(auth()->id() === $activeTx->buyer_id && $activeTx->status === 'shipped')
                            <form action="{{ route('transactions.receive', $activeTx->id) }}" method="POST" style="margin-top:10px;">
                                @csrf
                                <button type="submit" class="btn btn-primary" style="width:100%;">
                                    Barang Sudah Diterima
                                </button>
                            </form>
                        @endif

                        @if($activeTx->status === 'completed')
                            <div style="margin-top:10px;padding:10px 12px;border-radius:12px;background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46;font-size:0.85rem;">
                                ✅ Transaksi selesai.
                            </div>
                        @endif
                    @endif

                    {{-- BUY BUTTON hanya muncul kalau tidak ada transaksi aktif & bukan seller --}}
                    @if(!$activeTx && auth()->id() !== $product->user_id)
                        <form action="{{ route('transactions.store', $product->id) }}" method="POST" style="margin-top:12px;">
                            @csrf
                            <button type="submit" class="btn btn-primary" style="width:100%;">
                                Beli Sekarang
                            </button>
                        </form>
                    @endif
                @endauth

                {{-- DESKRIPSI --}}
                <div class="prod-desc-title">Deskripsi produk</div>

                @if($desc)
                    <p id="desc-short" class="prod-desc" style="display:block;">
                        {{ \Illuminate\Support\Str::limit($desc, 180) }}
                    </p>

                    <p id="desc-full" class="prod-desc" style="display:none;">
                        {{ $desc }}
                    </p>

                    @if(strlen($desc) > 180)
                        <button id="toggle-desc"
                            style="margin-top:8px;border:none;background:none;color:var(--primary);font-weight:900;cursor:pointer;padding:0;">
                            Lihat selengkapnya
                        </button>
                    @endif
                @else
                    <p class="prod-desc">Penjual belum menambahkan deskripsi detail.</p>
                @endif
            </div>
        </div>

        {{-- ================= RIGHT: RATING & REPUTASI ================= --}}
        <div class="card-review">
            {{-- ===== RATING PRODUK ===== --}}
            <div style="margin-bottom:18px;">
                <div class="panel-title">Rating Produk</div>

                @if(!empty($productReviewCount) && $productReviewCount > 0)
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div style="font-size:2rem;font-weight:900;color:var(--text-main);">
                            {{ number_format($productAvgRating, 1) }}
                        </div>
                        <div>
                            <div class="stars">
                                @for($i=1;$i<=5;$i++)
                                    <span class="{{ $i <= round($productAvgRating) ? 'star-filled' : 'star-empty' }}">★</span>
                                @endfor
                            </div>
                            <div class="muted">Dari {{ $productReviewCount }} ulasan</div>
                        </div>
                    </div>
                @else
                    <div class="muted">Belum ada rating untuk produk ini</div>
                @endif

                {{-- LIST ULASAN PRODUK INI --}}
                @if($product->reviews && $product->reviews->count() > 0)
                    <div style="margin-top:12px;display:flex;flex-direction:column;gap:10px;">
                        @foreach($product->reviews->take(4) as $review)
                            <div class="mini-card">
                                <div class="mini-row">
                                    <div>
                                        <div class="mini-name">{{ $review->user->name ?? 'Pembeli' }}</div>
                                        <div class="mini-sub">
                                            <span class="stars" style="display:inline-flex;vertical-align:middle;">
                                                @for($i=1;$i<=5;$i++)
                                                    <span class="{{ $i <= (int)$review->rating ? 'star-filled' : 'star-empty' }}">★</span>
                                                @endfor
                                            </span>
                                            <span style="margin-left:8px;color:#9ca3af;">• {{ $review->created_at?->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                                @if(!empty($review->comment))
                                    <div class="mini-comment">{{ trim($review->comment) }}</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <hr style="margin:18px 0;">

            {{-- ===== REPUTASI PENJUAL ===== --}}
            <div style="margin-bottom:18px;">
                <div class="panel-title">Reputasi Penjual</div>

                @if(!empty($sellerReviewCount) && $sellerReviewCount > 0)
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div style="font-size:1.8rem;font-weight:900;color:var(--text-main);">
                            {{ number_format($sellerAvgRating, 1) }}
                        </div>

                        <div>
                            <div class="stars">
                                @for($i=1;$i<=5;$i++)
                                    <span class="{{ $i <= round($sellerAvgRating) ? 'star-filled' : 'star-empty' }}">★</span>
                                @endfor
                            </div>
                            <div class="muted">{{ $sellerReviewCount }} transaksi berhasil</div>
                        </div>
                    </div>

                    @if(!empty($isTrustedSeller) && $isTrustedSeller)
                        <div style="margin-top:10px;">
                            <span class="badge-mini badge-trusted">✔ Trusted Seller</span>
                        </div>
                    @endif
                @else
                    <div class="muted">Penjual ini belum memiliki ulasan</div>
                @endif

                

                @if($sellerRecentReviews->count() > 0)
                    <div style="margin-top:14px;">
                        <div style="font-weight:900;color:var(--text-main);margin-bottom:8px;">
                            Komentar pembeli sebelumnya
                        </div>

                        <div style="display:flex;flex-direction:column;gap:10px;">
                            @foreach($sellerRecentReviews as $sr)
                                <div class="mini-card">
                                    <div class="mini-row">
                                        <div>
                                            <div class="mini-name">{{ $sr->user->name ?? 'Pembeli' }}</div>
                                            <div class="mini-sub">
                                                Produk: <strong>{{ $sr->product->name ?? '-' }}</strong>
                                                <span style="margin-left:6px;color:#9ca3af;">• {{ $sr->created_at?->diffForHumans() }}</span>
                                            </div>
                                            <div class="stars" style="margin-top:6px;">
                                                @for($i=1;$i<=5;$i++)
                                                    <span class="{{ $i <= (int)$sr->rating ? 'star-filled' : 'star-empty' }}">★</span>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>

                                    @if(!empty($sr->comment))
                                        <div class="mini-comment">{{ trim($sr->comment) }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <hr style="margin:18px 0;">

            {{-- ===== FORM REVIEW ===== --}}
            <div>
                <div class="panel-title">Tulis Review</div>

                @auth
                    @if($transaction && $transaction->status === 'completed' && auth()->id() === $transaction->buyer_id)
                        <form action="{{ route('products.reviews.store', $product->id) }}" method="POST">
                            @csrf

                            <label style="font-size:.85rem;font-weight:900;color:var(--text-main);display:block;margin-bottom:6px;">
                                Rating kamu
                            </label>
                            <select name="rating" class="select-rating" style="margin-bottom:10px;">
                                <option value="">Pilih rating</option>
                                @for($i=5;$i>=1;$i--)
                                    <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                            @error('rating') <div style="font-size:.82rem;color:#b91c1c;margin-top:-6px;margin-bottom:10px;">{{ $message }}</div> @enderror

                            <label style="font-size:.85rem;font-weight:900;color:var(--text-main);display:block;margin-bottom:6px;">
                                Ulasan (opsional)
                            </label>
                            <textarea name="comment" class="textarea" placeholder="Ceritakan pengalamanmu membeli produk ini">{{ old('comment') }}</textarea>
                            @error('comment') <div style="font-size:.82rem;color:#b91c1c;margin-top:6px;">{{ $message }}</div> @enderror

                            <div class="review-help">Review yang jujur membantu pembeli lain mengambil keputusan ✨</div>

                            <button type="submit" class="btn btn-primary" style="margin-top:12px;width:100%;">
                                Kirim Review
                            </button>
                        </form>
                    @else
                        <div class="muted">Review hanya bisa diberikan setelah transaksi selesai.</div>
                    @endif
                @else
                    <div class="muted">
                        Untuk menulis review, silakan <a href="{{ route('login') }}" style="color:var(--primary);font-weight:900;">login</a> dulu.
                    </div>
                @endauth
            </div>
        </div>
    </section>

    {{-- SCRIPT TOGGLE DESKRIPSI --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('toggle-desc');
            const shortEl = document.getElementById('desc-short');
            const fullEl = document.getElementById('desc-full');

            if (!btn || !shortEl || !fullEl) return;

            btn.addEventListener('click', function () {
                const expanded = fullEl.style.display === 'block';
                if (expanded) {
                    fullEl.style.display = 'none';
                    shortEl.style.display = 'block';
                    btn.textContent = 'Lihat selengkapnya';
                } else {
                    fullEl.style.display = 'block';
                    shortEl.style.display = 'none';
                    btn.textContent = 'Lihat lebih sedikit';
                }
            });
        });
    </script>
</div>
@endsection
