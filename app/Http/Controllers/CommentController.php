<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, BlogPost $blogPost)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        $comment = new Comment([
            'comment' => $request->comment,
            'user_id' => Auth::id(),
            'blog_post_id' => $blogPost->id,
        ]);

        $comment->save();

        return redirect()->route('blog_posts.show', $blogPost->id)->with('success', 'Comment added successfully.');
    }

    public function destroy(Comment $comment)
    {
        if ($comment->user_id != Auth::id()) {
            return redirect()->route('blog_posts.show', $comment->blog_post_id)->with('error', 'You can only delete your own comments.');
        }

        $comment->delete();

        return redirect()->route('blog_posts.show', $comment->blog_post_id)->with('success', 'Comment deleted successfully.');
    }
}
