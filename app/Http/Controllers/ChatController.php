<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Product;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewChatMessage;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * CHAT INBOX (opsional, kalau route /chats kamu pakai ChatInboxController)
     */
    public function index()
    {
        return redirect()->route('chat.index');
    }

    /**
     * BUKA / BUAT CHAT DARI PRODUK
     */
    public function withSeller(Product $product)
    {
        $buyer  = Auth::user();
        $seller = $product->user;

        if (!$seller || $seller->id === $buyer->id) {
            return redirect()
                ->route('products.show', $product->id)
                ->with('error', 'Kamu tidak bisa chat dirimu sendiri.');
        }

        $chat = Chat::firstOrCreate([
            'product_id' => $product->id,
            'buyer_id'   => $buyer->id,
            'seller_id'  => $seller->id,
        ]);

        return redirect()->route('chat.show', $chat->id);
    }

    /**
     * TAMPILAN CHAT DETAIL
     */
    public function show(Chat $chat)
    {
        abort_unless(auth()->id() === $chat->buyer_id || auth()->id() === $chat->seller_id, 403);

        $meId = auth()->id();

        // 1) tandai pesan lawan sebagai dibaca
        ChatMessage::where('chat_id', $chat->id)
            ->where('user_id', '!=', $meId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // 2) tandai NOTIFIKASI chat ini sebagai dibaca (biar badge notif gak nyangkut)
        auth()->user()->unreadNotifications()
            ->where('type', NewChatMessage::class)
            ->where('data->chat_id', $chat->id)
            ->update(['read_at' => now()]);

        $chat->load(['product', 'buyer', 'seller']);

        $messages = ChatMessage::with('user')
            ->where('chat_id', $chat->id)
            ->oldest()
            ->take(50)
            ->get();

        return view('chat.show', compact('chat', 'messages'));
    }

    /**
     * KIRIM PESAN (route: chat.store)
     */
    public function store(Request $request, Chat $chat)
    {
        abort_unless(auth()->id() === $chat->buyer_id || auth()->id() === $chat->seller_id, 403);

        $data = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $msg = ChatMessage::create([
            'chat_id' => $chat->id,
            'user_id' => auth()->id(),
            'message' => $data['message'],
            'read_at' => null,
        ]);

        // supaya chat naik ke atas inbox
        $chat->touch();

        // notif ke lawan bicara (database notification)
        $otherUserId = auth()->id() === $chat->buyer_id ? $chat->seller_id : $chat->buyer_id;
        $otherUser = User::find($otherUserId);
        if ($otherUser) {
            $otherUser->notify(new NewChatMessage($chat, $msg));
        }

        return redirect()->route('chat.show', $chat->id);
    }
}
