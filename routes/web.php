<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
});

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// New routes for BlogPosts and Categories
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/blog_posts', function () {
        return Inertia::render('BlogPosts');
    })->name('blog_posts.index');

    Route::get('/categories', function () {
        return Inertia::render('Categories');
    })->name('categories.index');
});

Route::prefix('api')->middleware(['auth', 'verified'])->group(function () {
    Route::resource('blog_posts', BlogPostController::class)->except(['create', 'edit']);
    Route::post('blog_posts/{post}/comments', [CommentController::class, 'store']);
    Route::delete('comments/{comment}', [CommentController::class, 'destroy']);
    Route::resource('categories', CategoryController::class)->except(['create', 'edit']);
});

require __DIR__.'/auth.php';
