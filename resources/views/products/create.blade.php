@extends('layouts.main')

@section('title', 'Jual Barang - NyarisBaru')

@section('content')
<div class="container">

    @php
        // ✅ Satu sumber kategori (biar konsisten)
        $categoryOptions = [
            'Fashion Wanita',
            'Fashion Pria',
            'Beauty & Skincare',
            'Sepatu & Sneakers',
            'Tas & Aksesoris',
            'Elektronik & Gadget',
            'Buku & Alat Kuliah',
            'Hobi (kamera, musik, game)',
            'Peralatan Rumah / Kost',
            'Bayi & Anak',
            'Olahraga',
            'Kesehatan (non-medis)',
        ];
    @endphp

    <style>
        .pc-layout{
            padding:40px 0 60px;
            max-width:900px;
            margin:0 auto;
        }
        .pc-header{ margin-bottom:22px; }
        .pc-title{
            font-size:1.8rem;
            font-weight:800;
            color:var(--text-main);
            letter-spacing:-0.02em;
            margin-bottom:6px;
        }
        .pc-sub{ font-size:0.95rem; color:#6b7280; }

        .pc-card{
            background:#fff;
            border-radius:20px;
            border:1px solid var(--border);
            padding:24px 24px 26px;
            box-shadow:0 10px 24px rgba(0,0,0,0.03);
        }

        .pc-grid{
            display:grid;
            grid-template-columns: minmax(0,1.4fr) minmax(0,1fr);
            gap:24px;
        }

        .form-group{ margin-bottom:16px; }
        .label{
            font-size:0.9rem;
            font-weight:600;
            color:var(--text-main);
            margin-bottom:6px;
            display:block;
        }
        .input,.textarea,.select{
            width:100%;
            padding:10px 12px;
            border-radius:10px;
            border:1px solid var(--border);
            background:#FDFDFD;
            outline:none;
            font-size:0.9rem;
            transition:.2s;
        }
        .textarea{ min-height:140px; resize:vertical; }
        .input:focus,.textarea:focus,.select:focus{
            border-color:var(--primary);
            box-shadow:0 0 0 3px rgba(107,167,204,0.15);
            background:#fff;
        }

        .hint{ font-size:0.8rem; color:#9ca3af; margin-top:4px; }
        .error{ font-size:0.8rem; color:#b91c1c; margin-top:4px; }

        .pc-actions{
            margin-top:18px;
            display:flex;
            justify-content:flex-end;
            gap:10px;
        }

        .upload-box{
            border:1px dashed var(--border);
            border-radius:14px;
            padding:16px;
            background:#f9fafb;
            font-size:0.86rem;
            color:#6b7280;
        }

        @media(max-width:900px){
            .pc-grid{ grid-template-columns:minmax(0,1fr); }
        }
    </style>

    <section class="pc-layout">
        <div class="pc-header">
            <h1 class="pc-title">Jual Barang Preloved-mu</h1>
            <p class="pc-sub">
                Isi detail barang dengan jujur supaya pembeli makin percaya dan transaksi berjalan lancar ✨
            </p>
        </div>

        <div class="pc-card">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="pc-grid">
                    {{-- KIRI: INFO UTAMA --}}
                    <div>
                        {{-- Nama --}}
                        <div class="form-group">
                            <label class="label">Nama Barang</label>
                            <input type="text" name="name" class="input"
                                   value="{{ old('name') }}" placeholder="Contoh: Blazer linen oversized">
                            @error('name')<div class="error">{{ $message }}</div>@enderror
                        </div>

                        {{-- Kategori + Kota --}}
                        <div class="form-group" style="display:grid;grid-template-columns:1.1fr 1fr;gap:12px;">
                            <div>
                                <label class="label">Kategori</label>
                                <select name="category" class="select">
                                    <option value="">Pilih kategori</option>

                                    @foreach($categoryOptions as $cat)
                                        <option value="{{ $cat }}" @selected(old('category') === $cat)>
                                            {{ $cat }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="hint">Pilih yang paling mendekati biar gampang dicari buyer.</div>
                                @error('category')<div class="error">{{ $message }}</div>@enderror
                            </div>

                            <div>
                                <label class="label">Kota</label>
                                <input type="text" name="city" class="input"
                                       value="{{ old('city') }}" placeholder="Contoh: Jakarta Selatan">
                                @error('city')<div class="error">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- Harga + Tahun Beli --}}
                        <div class="form-group" style="display:grid;grid-template-columns:1.1fr 1fr;gap:12px;">
                            <div>
                                <label class="label">Harga (Rp)</label>
                                <input type="number" name="price" class="input"
                                       value="{{ old('price') }}" placeholder="Contoh: 150000">
                                <div class="hint">Harga asli / harga setelah nego awal.</div>
                                @error('price')<div class="error">{{ $message }}</div>@enderror
                            </div>

                            <div>
                                <label class="label">Tahun Beli</label>
                                <input type="number" name="buy_year" class="input"
                                       value="{{ old('buy_year') }}" placeholder="2022">
                                @error('buy_year')<div class="error">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- Kondisi --}}
                        <div class="form-group">
                            <label class="label">Kondisi Barang (%)</label>
                            <input type="number" name="condition" class="input"
                                   value="{{ old('condition', 90) }}" min="1" max="100">
                            <div class="hint">
                                Contoh: 95% = sangat mulus, 80% = ada sedikit tanda pemakaian.
                            </div>
                            @error('condition')<div class="error">{{ $message }}</div>@enderror
                        </div>

                        {{-- Deskripsi --}}
                        <div class="form-group">
                            <label class="label">Deskripsi</label>
                            <textarea name="description" class="textarea"
                                      placeholder="Ceritakan detail ukuran, bahan, kekurangan, atau kelebihan barang.">{{ old('description') }}</textarea>
                            @error('description')<div class="error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- KANAN: FOTO --}}
                    <div>
                        <label class="label">Foto Barang</label>
                        <div class="upload-box">
                            <p style="margin-bottom:8px;">
                                Upload hingga <strong>5 foto</strong> yang jelas dan tidak blur.
                            </p>
                            <input type="file" name="photos[]" multiple accept="image/*" class="input" style="padding:8px;">
                            <div class="hint">
                                Minimal 1 foto. Gunakan background yang bersih agar barang terlihat jelas.
                            </div>
                            @error('photos')<div class="error">{{ $message }}</div>@enderror
                            @error('photos.*')<div class="error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="pc-actions">
                    <a href="{{ route('products.index') }}" class="btn btn-outline">Batal</a>
                    <button type="submit" class="btn btn-primary">Pasang Iklan</button>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection
