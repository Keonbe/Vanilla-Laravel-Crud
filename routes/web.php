<?php

use App\Models\Post;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
// Import from Controller to use in routes

// Home Route
Route::get('/', function () {
    //$posts = Post::all(); // Just to test DB connection
    //$posts = Post::where('user_id', auth()_->id())->get(); // Get posts for logged-in user only
    $posts = [];
    if (auth()->check()) {
        $posts = auth()->user()->manyPosts()->latest()->get(); // Get posts for logged-in user only, latest first
    }

    return view('home', ['posts' => $posts]); // loads resources/views/Home.blade.php
});

// User Authentication Routes
Route::post('/register', [UserController::class, 'register']);
Route::post('/logout', [UserController::class, 'logout']);
Route::post('/login', [UserController::class, 'login']);

/*
Route::post('/register', function () {
    return "Form Submitted"; //
});
*/

// ---

// Blog Post Routes:

// Accept both `/createpost` and `/create-post` from the view/forms
Route::post('/createpost', [PostController::class, 'createPost']);
Route::post('/create-post', [PostController::class, 'createPost']);

// View all records
Route::get('/records', [PostController::class, 'index']);

// Edit post form + update
Route::get('/edit-post/{post}', [PostController::class, 'showEditForm']);
Route::put('/edit-post/{post}', [PostController::class, 'update']);
Route::get('/edit-post/{id}', [PostController::class, 'showEditForm']);
Route::put('/edit-post/{id}', [PostController::class, 'updatePost']);
Route::delete('/delete-post/{post}', [PostController::class, 'deletePost']);

