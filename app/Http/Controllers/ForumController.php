<?php

namespace App\Http\Controllers;

use App\Models\ForumPost;
use App\Models\ForumComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ForumPostCommented;

class ForumController extends Controller
{
    public function index(Request $request)
    {
        $query = ForumPost::with('user')->withCount('comments');

        if ($search = $request->q) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%")
                  ->orWhere('tag', 'like', "%{$search}%");
            });
        }

        $posts = $query->latest()->paginate(9);

        return view('forum.index', compact('posts'));
    }

    public function create()
    {
        return view('forum.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tag'   => ['nullable', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:255'],
            'body'  => ['required', 'string', 'min:10'],
        ]);

        $validated['user_id'] = Auth::id();

        $post = ForumPost::create($validated);

        return redirect()
            ->route('forum.show', $post->id)
            ->with('success', 'Diskusi berhasil dibuat 🎉');
    }

    public function show(ForumPost $forum)
    {
        $forum->load(['user', 'comments.user']);

        // kalau kamu mau: saat buka post, tandai notif forum_comment utk post ini jadi read
        if (auth()->check()) {
            auth()->user()
                ->unreadNotifications()
                ->where('data->kind', 'forum_comment')
                ->where('data->forum_post_id', $forum->id)
                ->update(['read_at' => now()]);
        }

        return view('forum.show', [
            'post'     => $forum,
            'comments' => $forum->comments,
        ]);
    }

    public function comment(Request $request, ForumPost $forum)
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'min:3'],
        ]);

        $comment = ForumComment::create([
            'forum_post_id' => $forum->id,
            'user_id'       => Auth::id(),
            'body'          => $validated['body'],
        ]);

        // notif ke author post (kecuali komen diri sendiri)
        if ($forum->user_id !== Auth::id()) {
            $forum->user->notify(new ForumPostCommented($forum, $comment));
        }

        return back()->with('success', 'Komentar berhasil dikirim 💬');
    }
}
