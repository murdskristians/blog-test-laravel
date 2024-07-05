<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogPostController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/blog_posts/search', [BlogPostController::class, 'search']);
    Route::get('/blog_posts', [BlogPostController::class, 'index']);
    Route::post('/blog_posts', [BlogPostController::class, 'store']);
    Route::get('/blog_posts/{id}', [BlogPostController::class, 'show']);
    Route::put('/blog_posts/{id}', [BlogPostController::class, 'update']);


    Route::get('/categories', [CategoryController::class, 'index']);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    Route::put('/blog_posts/{id}', [BlogPostController::class, 'update']);
    return $request->user();
});
