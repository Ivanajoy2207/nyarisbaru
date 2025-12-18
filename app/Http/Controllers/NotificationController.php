<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // halaman list notifikasi (kalau kamu tetap mau punya halaman ini)
    public function index()
    {
        $user = Auth::user();

        $notifications = $user->notifications()->latest()->get();

        // tandai semua terbaca
        $user->unreadNotifications->markAsRead();

        return view('notifications.index', compact('notifications'));
    }

    // ✅ endpoint buat realtime badge
    public function counts()
    {
        $user = Auth::user();
        $userId = $user->id;

        // total unread chat (semua chat yg melibatkan user)
        $unreadChatTotal = DB::table('chat_messages')
            ->join('chats', 'chat_messages.chat_id', '=', 'chats.id')
            ->whereNull('chat_messages.read_at')
            ->where('chat_messages.user_id', '!=', $userId)
            ->where(function ($q) use ($userId) {
                $q->where('chats.buyer_id', $userId)->orWhere('chats.seller_id', $userId);
            })
            ->count();

        // total unread notif database (transaction/forum/chat notif kamu yang pakai database channel)
        $unreadNotifTotal = $user->unreadNotifications()->count();

        return response()->json([
            'unreadChatTotal' => (int) $unreadChatTotal,
            'unreadNotifTotal' => (int) $unreadNotifTotal,
        ]);
    }
}
