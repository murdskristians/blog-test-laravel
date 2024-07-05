<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use App\Http\Requests\BlogPostRequest;

class BlogPostController extends Controller
{
    public function index()
    {
        $blogPosts = BlogPost::with(['user', 'comments.user', 'categories'])->get();
        return response()->json($blogPosts);
    }

    public function store(BlogPostRequest $request)
    {
        try {
            $blogPost = BlogPost::create($request->only('title', 'content', 'user_id'));

            if ($request->has('categories')) {
                $blogPost->categories()->sync($request->categories);
            }

            return response()->json($blogPost, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json($e->errors(), 422);
        }
    }

    public function show(BlogPost $blogPost)
    {
        return response()->json($blogPost->load(['user', 'comments.user', 'categories']));
    }

    public function update(Request $request, BlogPost $blogPost)
    {
        $this->authorize('update', $blogPost);

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string'
        ]);

        $blogPost->update($request->only(['title', 'content']));

        if ($request->has('category_id')) {
            $blogPost->categories()->sync($request->category_id);
        }

        return response()->json($blogPost);
    }

    public function destroy($id)
    {
        $post = BlogPost::findOrFail($id);

        if ($post->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $post->delete();
        return response()->json(['success' => true]);
    }
}
