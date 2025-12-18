@extends('layouts.main')

@section('title', 'Notifikasi')

@section('content')
<div class="container" style="max-width:700px;padding:40px 0;">
    <h2 style="font-weight:800;margin-bottom:20px;">🔔 Notifikasi</h2>

    @forelse($notifications as $notif)
        <div style="
            padding:14px 16px;
            border:1px solid #e5e7eb;
            border-radius:12px;
            margin-bottom:10px;
            background:#fff;
        ">
            <div style="font-size:.9rem;">
                {{ $notif->data['message'] ?? 'Notifikasi' }}
            </div>
            <div style="font-size:.75rem;color:#9ca3af;margin-top:6px;">
                {{ $notif->created_at->diffForHumans() }}
            </div>
        </div>
    @empty
        <div style="color:#9ca3af;">Belum ada notifikasi.</div>
    @endforelse
</div>
@endsection
