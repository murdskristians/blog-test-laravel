<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogPostController extends Controller
{
    public function index()
    {
        $posts = BlogPost::with(['user', 'comments.user'])->get();
        return response()->json($posts);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post = new BlogPost();
        $post->title = $request->input('title');
        $post->content = $request->input('content');
        $post->user_id = auth()->id();
        $post->save();

        return response()->json($post, 201);
    }

    public function show($id)
    {
        $post = BlogPost::with(['user', 'comments.user'])->findOrFail($id);
        return response()->json($post);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post = BlogPost::findOrFail($id);
        $post->title = $request->input('title');
        $post->content = $request->input('content');
        $post->save();

        return response()->json($post);
    }

    public function destroy($id)
    {
        $post = BlogPost::findOrFail($id);
        $post->delete();

        return response()->json(null, 204);
    }
}
