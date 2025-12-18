<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Support\Facades\DB;

class ChatInboxController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $userId = auth()->id();

        $chats = Chat::query()
            ->with(['product', 'buyer', 'seller'])
            ->where(function ($q) use ($userId) {
                $q->where('buyer_id', $userId)
                  ->orWhere('seller_id', $userId);
            })
            ->withCount(['messages as unread_count' => function ($q) use ($userId) {
                $q->whereNull('read_at')
                  ->where('user_id', '!=', $userId);
            }])
            ->addSelect([
                'last_message_at' => DB::table('chat_messages')
                    ->selectRaw('MAX(created_at)')
                    ->whereColumn('chat_id', 'chats.id'),
            ])
            ->orderByDesc('last_message_at')
            ->get();

        return view('chat.index', compact('chats'));
    }
}
