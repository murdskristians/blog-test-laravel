<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    Route::put('/blog_posts/{id}', [BlogPostController::class, 'update']);
    return $request->user();
});
