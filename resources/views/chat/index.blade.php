@extends('layouts.main')

@section('title', 'Chat - NyarisBaru')

@section('content')
<div class="container">
    <style>
        .chat-list-wrap{
            padding:40px 0 70px;
            max-width:820px;
            margin:0 auto;
        }
        .chat-title{
            font-size:1.7rem;
            font-weight:800;
            color:var(--text-main);
            letter-spacing:-0.02em;
            margin-bottom:4px;
        }
        .chat-sub{
            font-size:0.9rem;
            color:#6b7280;
            margin-bottom:20px;
        }
        .chat-card{
            background:#fff;
            border-radius:20px;
            border:1px solid var(--border);
            padding:18px 18px 20px;
            box-shadow:0 12px 26px rgba(15,23,42,0.04);
        }
        .chat-item{
            padding:10px 8px;
            border-radius:12px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:10px;
            transition:.15s;
        }
        .chat-item + .chat-item{
            border-top:1px solid #f1f5f9;
        }
        .chat-item:hover{
            background:#f9fafb;
        }
        .chat-main{
            display:flex;
            align-items:center;
            gap:10px;
            min-width:0;
        }
        .chat-avatar{
            width:40px;height:40px;
            border-radius:999px;
            background:#e5e7eb;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:0.9rem;
            font-weight:700;
            color:#4b5563;
            flex:0 0 auto;
        }
        .chat-name{
            font-size:0.95rem;
            font-weight:700;
            color:var(--text-main);
        }
        .chat-product{
            font-size:0.85rem;
            color:#6b7280;
            white-space:nowrap;
            overflow:hidden;
            text-overflow:ellipsis;
            max-width:420px;
        }
        .chat-right{
            display:flex;
            align-items:center;
            gap:10px;
            flex:0 0 auto;
        }
        .chat-meta{
            font-size:0.8rem;
            color:#9ca3af;
            text-align:right;
            white-space:nowrap;
        }
        .badge{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            min-width:20px;
            height:20px;
            padding:0 6px;
            border-radius:999px;
            font-size:0.75rem;
            font-weight:800;
            background:#ef4444;
            color:#fff;
            line-height:1;
        }
        .chat-empty{
            font-size:0.9rem;
            color:#9ca3af;
            padding:6px 2px;
        }
    </style>

    <div class="chat-list-wrap">
        <h1 class="chat-title">Chat</h1>
        <p class="chat-sub">
            Lihat semua percakapanmu sebagai pembeli maupun penjual di NyarisBaru.
        </p>

        <div class="chat-card" id="chatInbox">
            @if($chats->isEmpty())
                <p class="chat-empty">
                    Belum ada chat. Coba klik tombol <strong>Chat penjual</strong> dari halaman produk 😊
                </p>
            @else
                @foreach($chats as $chat)
                    @php
                        $me = auth()->user();
                        $other = $chat->buyer_id === $me->id ? $chat->seller : $chat->buyer;
                        $lastAt = $chat->last_message_at ?? $chat->updated_at;
                    @endphp

                    <a href="{{ route('chat.show', $chat->id) }}" class="chat-item">
                        <div class="chat-main">
                            <div class="chat-avatar">
                                {{ strtoupper(substr($other->name, 0, 1)) }}
                            </div>
                            <div style="min-width:0;">
                                <div class="chat-name">{{ $other->name }}</div>
                                <div class="chat-product">
                                    {{ $chat->product->name ?? 'Percakapan umum' }}
                                </div>
                            </div>
                        </div>

                        <div class="chat-right">
                            @if(($chat->unread_count ?? 0) > 0)
                                <span class="badge">{{ $chat->unread_count }}</span>
                            @endif
                            <div class="chat-meta">
                                {{ \Carbon\Carbon::parse($lastAt)->diffForHumans() }}
                            </div>
                        </div>
                    </a>
                @endforeach
            @endif
        </div>
    </div>
</div>

{{-- “Realtime” inbox: polling ringan --}}
<script>
setInterval(() => {
    fetch("{{ route('chat.index') }}", { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => res.text())
        .then(html => {
            const doc = new DOMParser().parseFromString(html, 'text/html');
            const next = doc.querySelector('#chatInbox');
            const now = document.querySelector('#chatInbox');
            if (next && now) now.innerHTML = next.innerHTML;
        });
}, 4000);
</script>
@endsection
