@extends('layouts.main')

@section('title', 'Buat Diskusi - Forum NyarisBaru')

@section('content')
<div class="container">

    <style>
        .fc-wrap{
            max-width:800px;
            margin:40px auto 60px;
        }
        .fc-title{
            font-size:1.8rem;
            font-weight:800;
            color:var(--text-main);
            letter-spacing:-0.02em;
            margin-bottom:6px;
        }
        .fc-sub{
            font-size:0.95rem;
            color:#6b7280;
            margin-bottom:20px;
        }
        .fc-card{
            background:#fff;
            border-radius:20px;
            border:1px solid var(--border);
            padding:22px 22px 24px;
            box-shadow:0 10px 24px rgba(0,0,0,0.03);
        }
        .form-group{margin-bottom:16px;}
        .label{font-size:0.9rem;font-weight:600;color:var(--text-main);margin-bottom:6px;display:block;}
        .input,.textarea{
            width:100%;padding:10px 12px;border-radius:10px;
            border:1px solid var(--border);background:#FDFDFD;
            outline:none;font-size:0.9rem;transition:.2s;
        }
        .textarea{min-height:160px;resize:vertical;}
        .input:focus,.textarea:focus{
            border-color:var(--primary);
            box-shadow:0 0 0 3px rgba(107,167,204,0.15);
            background:#fff;
        }
        .hint{font-size:0.8rem;color:#9ca3af;margin-top:4px;}
        .error{font-size:0.8rem;color:#b91c1c;margin-top:4px;}
        .fc-actions{
            margin-top:18px;
            display:flex;
            justify-content:flex-end;
            gap:10px;
        }
    </style>

    <section class="fc-wrap">
        <h1 class="fc-title">Buat Diskusi Baru</h1>
        <p class="fc-sub">
            Tanyakan apa saja seputar preloved, styling, pengalaman belanja, atau tips merawat barangmu.
        </p>

        <div class="fc-card">
            <form action="{{ route('forum.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="label">Tag / Topik (opsional)</label>
                    <input type="text" name="tag" class="input" value="{{ old('tag') }}" placeholder="Contoh: fashion, elektronik, keamanan-transaksi">
                    <div class="hint">Membantu orang lain menemukan diskusi yang relevan.</div>
                    @error('tag')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="label">Judul Diskusi</label>
                    <input type="text" name="title" class="input" value="{{ old('title') }}" placeholder="Contoh: Tips cek keaslian sneakers preloved?">
                    @error('title')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="label">Isi Diskusi</label>
                    <textarea name="body" class="textarea" placeholder="Jelaskan detail pertanyaan atau cerita kamu...">{{ old('body') }}</textarea>
                    @error('body')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="fc-actions">
                    <a href="{{ route('forum.index') }}" class="btn btn-outline">Batal</a>
                    <button type="submit" class="btn btn-primary">Posting Diskusi</button>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection
