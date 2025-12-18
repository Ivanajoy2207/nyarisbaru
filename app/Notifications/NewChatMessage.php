<?php

namespace App\Notifications;

use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewChatMessage extends Notification
{
    use Queueable;

    public function __construct(
        public Chat $chat,
        public ChatMessage $message
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'kind' => 'chat_message',
            'chat_id' => $this->chat->id,
            'product_id' => $this->chat->product_id,
            'from_user_id' => $this->message->user_id,
            'message' => str($this->message->message)->limit(120)->toString(),
            'url' => route('chat.show', $this->chat->id),
        ];
    }
}
