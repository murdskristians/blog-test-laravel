<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;

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
            'category_id' => 'required|exists:categories,id'
        ]);

        $blogPost = BlogPost::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => auth()->id()
        ]);

        // Attach categories
        $blogPost->categories()->attach($request->category_id);

        return response()->json($blogPost, 201);
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

    public function destroy(BlogPost $blogPost)
    {
        $this->authorize('delete', $blogPost);
        $blogPost->delete();

        return response()->json(null, 204);
    }
}
