<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ForumPost;
use App\Models\ForumComment;
use App\Models\User;
use App\Notifications\ForumPostCommented;

class ForumCommentController extends Controller
{

    public function store(Request $request, ForumPost $post)
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:3000'],
        ]);

        $comment = ForumComment::create([
            'forum_post_id' => $post->id,
            'user_id' => auth()->id(),
            'body' => $data['body'],
        ]);

        // notif ke author post (kalau bukan diri sendiri)
        if ($post->user_id !== auth()->id()) {
            $author = User::find($post->user_id);
            if ($author) $author->notify(new ForumPostCommented($post, $comment));
        }

        return back()->with('success', 'Komentar terkirim.');
    }

}
