@extends('layouts.main')

@section('title', 'Edit Profil - NyarisBaru')

@section('content')
<div class="container">
    <style>
        .pf-edit-wrap{
            max-width:640px;
            margin:40px auto 70px;
        }
        .pf-edit-card{
            background:#fff;
            border-radius:20px;
            border:1px solid var(--border);
            padding:24px 22px 26px;
            box-shadow:0 12px 26px rgba(15,23,42,0.04);
        }
        .pf-edit-title{
            font-size:1.6rem;
            font-weight:800;
            color:var(--text-main);
            margin-bottom:6px;
        }
        .pf-edit-sub{
            font-size:0.9rem;
            color:#6b7280;
            margin-bottom:18px;
        }
        .form-group{margin-bottom:16px;}
        .label{
            display:block;
            font-size:0.86rem;
            font-weight:600;
            color:var(--text-main);
            margin-bottom:6px;
        }
        .input,
        .textarea{
            width:100%;
            border-radius:10px;
            border:1px solid var(--border);
            background:#FDFDFD;
            padding:10px 12px;
            font-size:0.9rem;
            outline:none;
            transition:.2s;
        }
        .input:focus,
        .textarea:focus{
            border-color:var(--primary);
            box-shadow:0 0 0 3px rgba(107,167,204,0.12);
            background:#fff;
        }
        .textarea{
            min-height:90px;
            resize:vertical;
        }
        .error{font-size:0.8rem;color:#b91c1c;margin-top:4px;}
        .hint{font-size:0.8rem;color:#9ca3af;margin-top:4px;}

        .pf-avatar-edit{
            display:flex;
            align-items:center;
            gap:14px;
        }
        .pf-avatar-preview{
            width:60px;
            height:60px;
            border-radius:999px;
            background:#e5e7eb;
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight:700;
            font-size:1.3rem;
            color:#4b5563;
            overflow:hidden;
        }
        .pf-avatar-preview img{
            width:100%;height:100%;object-fit:cover;
        }
        .pf-edit-actions{
            display:flex;
            justify-content:flex-end;
            gap:10px;
            margin-top:10px;
        }
    </style>

    <div class="pf-edit-wrap">
        <h1 class="pf-edit-title">Edit profil</h1>
        <p class="pf-edit-sub">
            Perbarui nama, kota, bio, dan foto profil agar calon pembeli lebih percaya.
        </p>

        <div class="pf-edit-card">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                {{-- Avatar --}}
                <div class="form-group">
                    <label class="label">Foto profil</label>
                    <div class="pf-avatar-edit">
                        <div class="pf-avatar-preview">
                            @if($user->profile_photo_path)
                                <img src="{{ asset('storage/'.$user->profile_photo_path) }}" alt="{{ $user->name }}">
                            @else
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            @endif
                        </div>
                        <div style="flex:1;">
                            <input type="file" name="avatar" accept="image/*" class="input" style="padding:6px 10px;">
                            <div class="hint">Format: JPG, PNG, WEBP. Maksimal 2MB.</div>
                            @error('avatar')<div class="error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                {{-- Nama --}}
                <div class="form-group">
                    <label class="label">Nama lengkap</label>
                    <input type="text" name="name" class="input"
                           value="{{ old('name', $user->name) }}">
                    @error('name')<div class="error">{{ $message }}</div>@enderror
                </div>

                {{-- Kota --}}
                <div class="form-group">
                    <label class="label">Kota / domisili</label>
                    <input type="text" name="city" class="input"
                           placeholder="Contoh: Jakarta Selatan"
                           value="{{ old('city', $user->city) }}">
                    @error('city')<div class="error">{{ $message }}</div>@enderror
                </div>

                {{-- Bio --}}
                <div class="form-group">
                    <label class="label">Bio singkat</label>
                    <textarea name="bio" class="textarea"
                              placeholder="Contoh: Suka thrifting outer & sepatu, sering jual koleksi preloved di sini.">{{ old('bio', $user->bio) }}</textarea>
                    <div class="hint">Maksimal 200 karakter.</div>
                    @error('bio')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="pf-edit-actions">
                    <a href="{{ route('profile.show') }}" class="btn btn-outline">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
