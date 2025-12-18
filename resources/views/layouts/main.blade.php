<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'NyarisBaru')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root{
            --bg-body:#F7F7F7;
            --bg-card:#FFFFFF;
            --border:#E1E1E1;
            --text-main:#1E2B3A;
            --text-body:#474747;
            --primary:#6BA7CC;
            --primary-hover:#5A8EB0;
            --nav-bg:rgba(255,255,255,0.95);
            --container-width:1140px;
        }

        *{margin:0;padding:0;box-sizing:border-box;}
        body{
            font-family:'Plus Jakarta Sans',sans-serif;
            background:var(--bg-body);
            color:var(--text-body);
            min-height:100vh;
            display:flex;
            flex-direction:column;
        }
        a{text-decoration:none;color:inherit;}

        .container{max-width:var(--container-width);margin:0 auto;padding:0 24px;}

        .navbar{
            background:var(--nav-bg);
            border-bottom:1px solid var(--border);
            position:sticky; top:0; z-index:100;
            backdrop-filter:saturate(180%) blur(10px);
        }
        .nav-inner{
            height:72px;
            display:flex;
            justify-content:space-between;
            align-items:center;
        }
        .brand{font-size:1.4rem;font-weight:800;letter-spacing:-0.03em;}
        .brand span{color:var(--primary);}

        .nav-links{
            display:flex;
            gap:32px;
            font-weight:600;
            font-size:0.95rem;
            align-items:center;
        }
        .nav-links a.active, .nav-links a:hover{color:var(--primary);}

        .btn{
            padding:10px 22px;
            border-radius:99px;
            font-weight:600;
            font-size:0.9rem;
            border:1px solid transparent;
            cursor:pointer;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap:8px;
        }
        .btn-primary{background:var(--primary);color:#fff;}
        .btn-primary:hover{background:var(--primary-hover);}
        .btn-outline{background:#fff;border-color:var(--border);}

        .nav-right{display:flex;gap:12px;align-items:center;}

        .nav-link{
            font-weight:600;
            font-size:0.95rem;
            color:var(--text-main);
            padding:8px 10px;
            border-radius:10px;
            display:inline-flex;
            align-items:center;
            gap:8px;
        }
        .nav-link:hover{background:#eef6fb;color:var(--primary);}

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

        .flash{
            position:fixed;
            top:90px; left:50%;
            transform:translateX(-50%);
            background:#EAF4FA;
            border:1px solid #D6E8F5;
            color:var(--text-main);
            padding:12px 18px;
            border-radius:12px;
            font-size:0.9rem;
            box-shadow:0 10px 24px rgba(0,0,0,0.08);
            z-index:999;
            animation:fadeOut 4s forwards;
        }
        @keyframes fadeOut{
            0%{opacity:1;}
            80%{opacity:1;}
            100%{opacity:0;transform:translate(-50%,-10px);}
        }

        footer{
            margin-top:auto;
            background:var(--text-main);
            color:#dbe4ea;
            padding:48px 0 24px;
        }
        .footer-link{
            color:#94a3b8;
            font-size:0.9rem;
            display:block;
            margin-bottom:8px;
        }
        .footer-link:hover{color:var(--primary);}

        @media(max-width:768px){ .nav-links{display:none;} }
    </style>
</head>

<body>
@php
    $unreadChatTotal = 0;
    $unreadNotifTotal = 0;

    if(auth()->check()){
        $meId = auth()->id();

        // 1) Total notif database (TransactionStatusChanged, ForumPostCommented, NewChatMessage, dll)
        $unreadNotifTotal = auth()->user()->unreadNotifications()->count();

        // 2) Total pesan chat belum dibaca (berdasarkan chat_messages.read_at)
        $unreadChatTotal = \App\Models\ChatMessage::query()
            ->whereNull('read_at')
            ->where('user_id', '!=', $meId)
            ->whereIn('chat_id', \App\Models\Chat::query()
                ->where('buyer_id', $meId)
                ->orWhere('seller_id', $meId)
                ->select('id')
            )
            ->count();
    }
@endphp

<header class="navbar">
    <div class="container nav-inner">
        <a href="{{ route('home') }}" class="brand">Nyaris<span>Baru</span>.</a>

        <nav class="nav-links">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
            <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">Belanja</a>
            <a href="{{ route('forum.index') }}" class="{{ request()->routeIs('forum.*') ? 'active' : '' }}">Forum</a>

            @auth
                <a href="{{ route('chat.index') }}" class="{{ request()->routeIs('chat.*') ? 'active' : '' }}">
                    Chat
                    @if($unreadChatTotal > 0)
                        <span class="badge">{{ $unreadChatTotal }}</span>
                    @endif
                </a>
            @endauth
        </nav>

        <div class="nav-right">
            @auth
                <a href="{{ route('profile.show') }}" class="nav-link">
                    Hi, {{ Auth::user()->name }}
                    @if($unreadNotifTotal > 0)
                        <span class="badge">{{ $unreadNotifTotal }}</span>
                    @endif
                </a>

                <a href="{{ route('products.create') }}" class="btn btn-primary">Jual</a>

                <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="btn btn-outline">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline">Masuk</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
            @endauth
        </div>
    </div>
</header>

@if(session('success'))
    <div class="flash">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="flash" style="background:#FDECEA;border-color:#F5C2C7;">{{ session('error') }}</div>
@endif

<main>
    @yield('content')
</main>

<footer>
    <div class="container" style="display:grid;grid-template-columns:2fr 1fr 1fr;gap:40px;">
        <div>
            <div class="brand" style="color:#fff;">Nyaris<span>Baru</span>.</div>
            <p style="font-size:0.9rem;opacity:0.8;max-width:320px;">
                Platform jual beli barang preloved berkualitas.
            </p>
        </div>
        <div>
            <strong>Menu</strong>
            <a class="footer-link" href="{{ route('products.index') }}">Katalog</a>
            <a class="footer-link" href="{{ route('forum.index') }}">Forum</a>
        </div>
        <div>
            <strong>Sosial</strong>
            <a class="footer-link">Instagram</a>
            <a class="footer-link">Twitter / X</a>
            <a class="footer-link">TikTok</a>
        </div>
    </div>

    <div class="container" style="text-align:center;font-size:0.85rem;opacity:0.6;margin-top:24px;">
        &copy; {{ date('Y') }} NyarisBaru Indonesia
    </div>
</footer>
</body>
</html>
