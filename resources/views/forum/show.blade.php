@extends('layouts.main')

@section('title', $post->title . ' - Forum NyarisBaru')

@section('content')
<div class="container">

    <style>
        .fs-layout{
            padding:40px 0 60px;
            display:grid;
            grid-template-columns:minmax(0,2fr) minmax(0,1.1fr);
            gap:28px;
        }
        .fs-main,.fs-side{min-width:0;}

        .fs-card{
            background:#fff;border-radius:20px;
            border:1px solid var(--border);
            padding:22px 22px 24px;
            box-shadow:0 10px 24px rgba(0,0,0,0.03);
        }
        .fs-tag{
            display:inline-block;
            font-size:0.78rem;
            padding:4px 10px;
            border-radius:999px;
            background:#e0f2fe;
            color:#0f172a;
            margin-bottom:10px;
        }
        .fs-title{
            font-size:1.6rem;
            font-weight:800;
            color:var(--text-main);
            letter-spacing:-0.02em;
            margin-bottom:6px;
        }
        .fs-meta{
            font-size:0.85rem;
            color:#9ca3af;
            margin-bottom:16px;
        }
        .fs-body{
            font-size:0.95rem;
            color:var(--text-body);
            line-height:1.7;
        }

        .fs-comments-title{
            font-size:1rem;
            font-weight:700;
            color:var(--text-main);
            margin:24px 0 10px;
        }
        .comment{
            border-top:1px solid #e5e7eb;
            padding-top:12px;
            margin-top:12px;
        }
        .comment-header{
            display:flex;
            justify-content:space-between;
            font-size:0.8rem;
            color:#9ca3af;
            margin-bottom:4px;
        }
        .comment-body{
            font-size:0.9rem;
            color:var(--text-body);
        }

        .comment-form-card{
            margin-top:20px;
            background:#f9fafb;
            border-radius:16px;
            border:1px solid #e5e7eb;
            padding:16px 16px 18px;
        }
        .label{font-size:0.9rem;font-weight:600;color:var(--text-main);margin-bottom:6px;display:block;}
        .textarea{
            width:100%;min-height:100px;resize:vertical;
            padding:10px 12px;border-radius:10px;
            border:1px solid var(--border);background:#FDFDFD;
            outline:none;font-size:0.9rem;transition:.2s;
        }
        .textarea:focus{
            border-color:var(--primary);
            box-shadow:0 0 0 3px rgba(107,167,204,0.15);
            background:#fff;
        }
        .error{font-size:0.8rem;color:#b91c1c;margin-top:4px;}

        .fs-side-card{
            background:#fff;border-radius:18px;
            border:1px solid var(--border);
            padding:18px 18px 20px;
        }
        .side-heading{
            font-size:0.95rem;font-weight:700;color:var(--text-main);margin-bottom:6px;
        }
        .side-text{
            font-size:0.85rem;color:#6b7280;
        }

        @media(max-width:900px){
            .fs-layout{grid-template-columns:minmax(0,1fr);}
        }
    </style>

    <section class="fs-layout">
        {{-- KIRI: Post + komentar --}}
        <div class="fs-main">
            <article class="fs-card">
                @if($post->tag)
                    <div class="fs-tag">#{{ $post->tag }}</div>
                @endif

                <h1 class="fs-title">{{ $post->title }}</h1>
                <div class="fs-meta">
                    Dibuat oleh {{ $post->user->name ?? 'Pengguna' }}
                    • {{ $post->created_at->diffForHumans() }}
                    • {{ $post->comments->count() }} komentar
                </div>

                <div class="fs-body">
                    {!! nl2br(e($post->body)) !!}
                </div>

                {{-- KOMENTAR --}}
                <h2 class="fs-comments-title">Komentar</h2>

                @forelse($comments as $comment)
                    <div class="comment">
                        <div class="comment-header">
                            <span>{{ $comment->user->name ?? 'Pengguna' }}</span>
                            <span>{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="comment-body">
                            {!! nl2br(e($comment->body)) !!}
                        </div>
                    </div>
                @empty
                    <p style="font-size:0.9rem;color:#9ca3af;margin-top:8px;">
                        Belum ada komentar. Jadilah yang pertama meninggalkan tanggapan 💬
                    </p>
                @endforelse

                {{-- FORM KOMENTAR --}}
                <div class="comment-form-card">
                    @auth
                        <form action="{{ route('forum.comment', $post->id) }}" method="POST">
                            @csrf
                            <label class="label">Tulis komentar</label>
                            <textarea name="body" class="textarea" placeholder="Berikan jawaban, tips, atau pengalamanmu...">{{ old('body') }}</textarea>
                            @error('body')<div class="error">{{ $message }}</div>@enderror

                            <div style="margin-top:10px;display:flex;justify-content:flex-end;">
                                <button type="submit" class="btn btn-primary">Kirim</button>
                            </div>
                        </form>
                    @else
                        <p style="font-size:0.85rem;color:#6b7280;">
                            Kamu harus <a href="{{ route('login') }}" style="color:var(--primary);font-weight:600;">login</a> terlebih dahulu untuk berkomentar.
                        </p>
                    @endauth
                </div>
            </article>
        </div>

        {{-- KANAN: Info kecil --}}
        <aside class="fs-side">
            <div class="fs-side-card">
                <div class="side-heading">Tips aman diskusi</div>
                <p class="side-text">
                    Jangan pernah share data pribadi sensitif di forum (nomor kartu, password, dll).
                    Simpan detail transaksi lewat fitur chat & sistem resmi NyarisBaru.
                </p>
            </div>
        </aside>
    </section>
</div>
@endsection
