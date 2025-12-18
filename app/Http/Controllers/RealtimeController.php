<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;

class RealtimeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Return badge counts (JSON) buat navbar & inbox chat
     */
    public function badges(Request $request)
    {
        $userId = auth()->id();

        // total unread chat message untuk user ini (semua thread)
        $unreadChatTotal = Chat::query()
            ->where(function ($q) use ($userId) {
                $q->where('buyer_id', $userId)->orWhere('seller_id', $userId);
            })
            ->withCount(['messages as unread_total' => function ($q) use ($userId) {
                $q->whereNull('read_at')->where('user_id', '!=', $userId);
            }])
            ->get()
            ->sum('unread_total');

        // total unread notifications (database notif)
        $unreadNotifTotal = auth()->user()->unreadNotifications()->count();

        return response()->json([
            'unreadChatTotal' => (int) $unreadChatTotal,
            'unreadNotifTotal' => (int) $unreadNotifTotal,
        ]);
    }
}
