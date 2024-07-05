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

    public function store(BlogPostRequest $request)
    {
        try {
            $blogPost = BlogPost::create([
                'title' => $request->title,
                'content' => $request->content,
                'user_id' => Auth::id(), // Associate the post with the authenticated user
            ]);

            if ($request->has('categories')) {
                $blogPost->categories()->sync($request->categories);
            }

            return response()->json($blogPost, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json($e->errors(), 422);
        }
    }

    public function update(BlogPostRequest $request, BlogPost $blogPost)
    {
        // Ensure the authenticated user is the owner of the post
        if ($blogPost->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $blogPost->update($request->only('title', 'content'));

            if ($request->has('categories')) {
                $blogPost->categories()->sync($request->categories);
            }

            return response()->json($blogPost);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json($e->errors(), 422);
        }
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
}
