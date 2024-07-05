<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Http\Requests\BlogPostRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogPostController extends Controller
{
    public function index()
    {
        $blogPosts = BlogPost::with(['user', 'comments.user', 'categories'])->get();
        return response()->json($blogPosts);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'categories' => 'array|exists:categories,id', // assuming categories is an array of category IDs
        ]);

        $post = new BlogPost();
        $post->title = $request->input('title');
        $post->content = $request->input('content');
        $post->user_id = Auth::id();
        $post->save();

        $post->categories()->sync($request->input('categories', []));

        return response()->json($post, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'categories' => 'array|exists:categories,id',
        ]);

        $post = BlogPost::findOrFail($id);

        if ($post->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $post->title = $request->input('title');
        $post->content = $request->input('content');
        $post->save();

        $post->categories()->sync($request->input('categories', []));

        return response()->json(['message' => 'Post updated successfully', 'post' => $post]);
    }

    public function destroy(BlogPost $blogPost)
    {
        // Ensure the authenticated user is the owner of the post
        if ($blogPost->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $blogPost->delete();

        return response()->json(null, 204);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $posts = BlogPost::where('title', 'LIKE', "%{$keyword}%")
            ->orWhere('content', 'LIKE', "%{$keyword}%")
            ->with('categories', 'user', 'comments.user')
            ->get();

        return response()->json($posts);
    }

    public function show($id)
    {
        $post = BlogPost::with('categories', 'user', 'comments.user')->findOrFail($id);
        return response()->json($post);
    }

}
