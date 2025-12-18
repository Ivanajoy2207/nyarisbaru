@extends('layouts.main')

@section('title', 'Forum Komunitas - NyarisBaru')

@section('content')
<div class="container">

    <style>
        .forum-header{
            margin:40px 0 24px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:16px;
        }
        .forum-title{
            font-size:1.8rem;
            font-weight:800;
            color:var(--text-main);
            letter-spacing:-0.02em;
        }
        .forum-sub{
            font-size:0.95rem;
            color:#6b7280;
        }

        .forum-search{
            display:flex;
            gap:10px;
            flex-wrap:wrap;
        }
        .forum-search input{
            border:1px solid var(--border);
            border-radius:999px;
            padding:9px 16px;
            font-size:0.9rem;
            background:#FDFDFD;
            outline:none;
            min-width:220px;
        }
        .forum-search input:focus{
            border-color:var(--primary);
            box-shadow:0 0 0 3px rgba(107,167,204,0.15);
            background:#fff;
        }

        .forum-grid{
            display:grid;
            grid-template-columns:repeat(auto-fill,minmax(260px,1fr));
            gap:20px;
            margin-bottom:36px;
        }
        .forum-card{
            background:#fff;
            border:1px solid var(--border);
            border-radius:16px;
            padding:16px 16px 18px;
            transition:.2s;
            display:flex;
            flex-direction:column;
            gap:8px;
        }
        .forum-card:hover{
            transform:translateY(-3px);
            box-shadow:0 10px 24px rgba(0,0,0,.04);
            border-color:#cbd5e1;
        }
        .forum-tag{
            font-size:0.75rem;
            padding:3px 9px;
            border-radius:999px;
            background:#e0f2fe;
            color:#0f172a;
            display:inline-block;
            margin-bottom:4px;
        }
        .forum-card-title{
            font-size:1rem;
            font-weight:700;
            color:var(--text-main);
            margin-bottom:2px;
        }
        .forum-card-title a{
            color:inherit;
            text-decoration:none;
        }
        .forum-card-title a:hover{
            text-decoration:underline;
        }

        .forum-card-excerpt {
            font-size: 0.92rem;
            color: #6b7280;
            line-height: 1.45;

            display: -webkit-box;
            -webkit-line-clamp: 2;      /* hanya tampil 2 baris */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            max-height: 2.9em;
        }

        .forum-card-more{
            text-align:right;
            margin-top:2px;
            font-size:0.85rem;
        }
        .forum-card-more a{
            color:var(--primary);
            font-weight:700;
            text-decoration:none;
        }
        .forum-card-more a:hover{
            text-decoration:underline;
        }

        .forum-card-meta{
            display:flex;
            justify-content:space-between;
            align-items:center;
            font-size:0.78rem;
            color:#9ca3af;
            margin-top:6px;
        }

        .forum-empty{
            text-align:center;
            color:#9ca3af;
            padding:60px 20px;
        }

        .pagination-wrapper{
            margin-top:10px;
            display:flex;
            justify-content:center;
        }
        .pagination{display:flex;gap:8px;list-style:none;}
        .page-item .page-link{
            padding:8px 14px;font-size:.85rem;font-weight:600;
            background:#fff;border:1px solid var(--border);
            border-radius:8px;color:var(--text-main);transition:.2s;
        }
        .page-item .page-link:hover{
            background:#f8fafc;border-color:#cbd5e1;color:var(--primary);
        }
        .page-item.active .page-link{
            background:var(--primary);border-color:var(--primary);color:#fff;
            box-shadow:0 4px 10px rgba(107,167,204,.4);
        }
    </style>

    <header class="forum-header">
        <div>
            <h1 class="forum-title">Forum Komunitas</h1>
            <p class="forum-sub">
                Sharing pengalaman belanja preloved, tips styling, dan cara aman transaksi.
            </p>
        </div>

        <div class="forum-search">
            <form action="{{ route('forum.index') }}" method="GET" style="display:flex;gap:10px;">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="🔍 Cari topik atau tag...">
                <button class="btn btn-outline" type="submit">Cari</button>
            </form>

            @auth
                <a href="{{ route('forum.create') }}" class="btn btn-primary">+ Buat Diskusi</a>
            @endauth
        </div>
    </header>

    @if($posts->isEmpty())
        <div class="forum-empty">
            Belum ada diskusi. Jadi yang pertama untuk mulai ngobrol di komunitas NyarisBaru ✨
            @auth
                <div style="margin-top:10px;">
                    <a href="{{ route('forum.create') }}" class="btn btn-primary">Mulai Diskusi</a>
                </div>
            @endauth
        </div>
    @else
        <section class="forum-grid">
            @foreach($posts as $post)
                <article class="forum-card">
                    @if($post->tag)
                        <div class="forum-tag">#{{ $post->tag }}</div>
                    @endif

                    <h2 class="forum-card-title">
                        <a href="{{ route('forum.show', $post->id) }}">
                            {{ $post->title }}
                        </a>
                    </h2>

                    <div class="forum-card-excerpt">
                        {!! strip_tags($post->body) !!}
                    </div>

                    @if(strlen(strip_tags($post->body)) > 120)
                        <div class="forum-card-more">
                            <a href="{{ route('forum.show', $post->id) }}">
                                ...lihat selengkapnya
                            </a>
                        </div>
                    @endif

                    <div class="forum-card-meta">
                        <span>{{ $post->user->name ?? 'Anonim' }}</span>
                        <span>{{ $post->comments_count }} komentar • {{ $post->created_at->diffForHumans() }}</span>
                    </div>
                </article>
            @endforeach
        </section>

        <div class="pagination-wrapper">
            {{ $posts->links() }}
        </div>
    @endif
</div>
@endsection
