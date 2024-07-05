<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use Inertia\Inertia;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BlogPostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return Inertia::render('Welcome');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/blog_posts', function () {
        return Inertia::render('BlogPosts');
    })->name('blog_posts.index');

    Route::get('/categories', function () {
        return Inertia::render('Categories');
    })->name('categories.index');
});

Route::prefix('api')->middleware(['auth', 'verified'])->group(function () {
    Route::resource('blog_posts', BlogPostController::class)->except(['create', 'edit'])->names([
        'index' => 'api.blog_posts.index',
        'show' => 'api.blog_posts.show',
        'store' => 'api.blog_posts.store',
        'update' => 'api.blog_posts.update',
        'destroy' => 'api.blog_posts.destroy'
    ]);
    Route::post('blog_posts/{post}/comments', [CommentController::class, 'store']);
    Route::delete('comments/{comment}', [CommentController::class, 'destroy']);
    Route::resource('categories', CategoryController::class)->except(['create', 'edit'])->names([
        'index' => 'api.categories.index',
        'show' => 'api.categories.show',
        'store' => 'api.categories.store',
        'update' => 'api.categories.update',
        'destroy' => 'api.categories.destroy'
    ]);
});

Route::put('/api/comments/{id}', [CommentController::class, 'update']);
Route::middleware('auth:api')->group(function () {
    Route::apiResource('comments', CommentController::class);
});

Route::delete('/blog_posts/{id}', [BlogPostController::class, 'destroy']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


require __DIR__.'/auth.php';
