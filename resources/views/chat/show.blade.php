@extends('layouts.main')

@section('title', 'Chat dengan ' . ($chat->buyer_id === auth()->id() ? $chat->seller->name : $chat->buyer->name))

@section('content')
<div class="container">
    <style>
        .chat-wrapper{
            max-width:800px;
            margin:32px auto 70px;
        }
        .chat-card{
            background:#fff;
            border-radius:20px;
            border:1px solid var(--border);
            padding:18px 18px 20px;
            box-shadow:0 16px 32px rgba(15,23,42,0.04);
            display:flex;
            flex-direction:column;
            height:520px;
        }
        .chat-header{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:10px;
        }
        .chat-peer-name{
            font-size:1rem;
            font-weight:700;
            color:var(--text-main);
        }
        .chat-header-sub{
            font-size:0.8rem;
            color:#9ca3af;
        }
        .chat-product-tag{
            font-size:0.8rem;
            padding:6px 10px;
            border-radius:999px;
            background:#f1f5f9;
            color:#4b5563;
        }
        .chat-messages{
            flex:1;
            margin-top:12px;
            padding:10px;
            border-radius:16px;
            background:#f9fafb;
            overflow-y:auto;
            display:flex;
            flex-direction:column;
            gap:8px;
        }
        .msg-row{
            display:flex;
            max-width:80%;
        }
        .msg-row.me{justify-content:flex-end;margin-left:auto;}
        .msg-row.other{justify-content:flex-start;margin-right:auto;}
        .msg-bubble{
            border-radius:18px;
            padding:8px 12px;
            font-size:0.9rem;
            line-height:1.4;
            word-break:break-word;
            overflow-wrap:anywhere;
        }
        .msg-me{
            background:var(--primary);
            color:#fff;
            border-bottom-right-radius:4px;
        }
        .msg-other{
            background:#e5e7eb;
            color:#111827;
            border-bottom-left-radius:4px;
        }
        .msg-meta{
            font-size:0.7rem;
            color:#9ca3af;
            margin-top:2px;
        }
        .chat-form{
            margin-top:10px;
            display:flex;
            gap:10px;
        }
        .chat-input{
            flex:1;
            border-radius:999px;
            border:1px solid var(--border);
            padding:10px 14px;
            font-size:0.9rem;
            outline:none;
            background:#FDFDFD;
        }
        .chat-input:focus{
            border-color:var(--primary);
            box-shadow:0 0 0 3px rgba(107,167,204,0.12);
            background:#fff;
        }
        @media(max-width:768px){
            .chat-card{height:480px;}
            .msg-row{max-width:100%;}
        }
    </style>

    <div class="chat-wrapper">
        <div class="chat-card">
            <div class="chat-header">
                @php
                    $me = auth()->user();
                    $peer = $chat->buyer_id === $me->id ? $chat->seller : $chat->buyer;
                @endphp

                <div>
                    <div class="chat-peer-name">{{ $peer->name }}</div>
                    <div class="chat-header-sub">
                        Chat tentang: {{ $chat->product->name ?? 'Produk' }}
                    </div>
                </div>
                @if($chat->product)
                    <a href="{{ route('products.show', $chat->product->id) }}" class="chat-product-tag">
                        Lihat produk
                    </a>
                @endif
            </div>

            <div class="chat-messages">
                @forelse($chat->messages as $message)
                    @php
                        $isMe = $message->user_id === $me->id;
                    @endphp
                    <div class="msg-row {{ $isMe ? 'me' : 'other' }}">
                        <div>
                            <div class="msg-bubble {{ $isMe ? 'msg-me' : 'msg-other' }}">
                                {{ $message->message }}
                            </div>
                            <div class="msg-meta">
                                {{ $isMe ? 'Kamu' : $message->user->name }} •
                                {{ $message->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="font-size:0.85rem;color:#9ca3af;text-align:center;margin-top:40px;">
                        Belum ada pesan. Kirim pesan pertama ke {{ $peer->name }} ✨
                    </div>
                @endforelse
            </div>

            <form action="{{ route('chat.store', $chat->id) }}" method="POST" class="chat-form">
                @csrf
                <input type="text" name="message" class="chat-input"
                       placeholder="Tulis pesan... Contoh: Kak, boleh kirim foto detail bagian kerah?"
                       autocomplete="off">
                <button type="submit" class="btn btn-primary">Kirim</button>
            </form>
        </div>
    </div>
</div>

<script>
setInterval(() => {
    fetch(location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => res.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            document.querySelector('.chat-messages').innerHTML =
                doc.querySelector('.chat-messages').innerHTML;
        });
}, 4000);
</script>

@endsection
