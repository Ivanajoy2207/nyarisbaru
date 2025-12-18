<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Notifications\NewChatMessage;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if($this->app->environment('production')) {
            URL::forceScheme('https');
        }
        
        View::composer('*', function ($view) {
            if (!auth()->check()) return;

            $userId = auth()->id();

            // total unread chat message (bukan dari notifications)
            $unreadChatTotal = DB::table('chat_messages')
                ->join('chats', 'chats.id', '=', 'chat_messages.chat_id')
                ->whereNull('chat_messages.read_at')
                ->where('chat_messages.user_id', '!=', $userId)
                ->where(function ($q) use ($userId) {
                    $q->where('chats.buyer_id', $userId)
                    ->orWhere('chats.seller_id', $userId);
                })
                ->count();

            // notif non-chat (transaction/forum), biar tidak double count chat notif
            $unreadNotifTotalNonChat = auth()->user()->unreadNotifications()
                ->where('type', '!=', NewChatMessage::class)
                ->count();

            $view->with([
                'unreadChatTotal' => $unreadChatTotal,
                'unreadNotifTotalNonChat' => $unreadNotifTotalNonChat,
            ]);
        });
    }
}
