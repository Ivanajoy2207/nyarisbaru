<?php

namespace App\Notifications;

use App\Models\ForumPost;
use App\Models\ForumComment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ForumPostCommented extends Notification
{
    use Queueable;

    public function __construct(
        public ForumPost $post,
        public ForumComment $comment
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'kind' => 'forum_comment',
            'forum_post_id' => $this->post->id,
            'comment_id' => $this->comment->id,
            'from_user_id' => $this->comment->user_id,
            'title' => $this->post->title,
            'body' => str($this->comment->body)->limit(120)->toString(),
            'url' => route('forum.show', $this->post->id) . '#comment-' . $this->comment->id,
        ];
    }
}
