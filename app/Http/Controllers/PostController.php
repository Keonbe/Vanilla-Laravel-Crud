<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // Delete a post - Delete
    public function deletePost(Post $post) {
        // Ensure only owner can delete by using user_id check
        if (auth()->id() !== $post->user_id) {
            abort(403); // if not owner, abort with 403 Forbidden
            return redirect('/')->with('error', 'Unauthorized access to delete this post.');
        }
        // if owner, proceed to allow delete
        $post->delete(); // delete the post
        return redirect('/')->with('success', 'Post deleted successfully!'); // redirect with success message
    }

    // Update a post - Update
    public function updatePost(Post $post, Request $request) {
    // Ensure only owner can delete by using user_id check
    if (auth()->id() !== $post->user_id) {
            abort(403); // if not owner, abort with 403 Forbidden
            return redirect('/')->with('error', 'Unauthorized access to update this post.');
        }

        $incomingData = $request->validate([
            'title' => 'required|min:5|max:100',
            'body' => 'required|min:10',
        ]); // validate incoming data; must follow rules in between braces for each field - title min:5, body min:10

        $incomingData['title'] = strip_tags($incomingData['title']); // Basic sanitization
        $incomingData['body'] = strip_tags($incomingData['body']); // Basic sanitization

        $post->update($incomingData); // update the post after validation and sanitization
        return redirect('/')->with('success', 'Post updated successfully!');
    }

    // Edit form - Read
    public function showEditForm(Post $post){
        // Ensure only owner can delete by using user_id check
        if (auth()->id() !== $post->user_id) {
            abort(403);
            return redirect('/')->with('error', 'Unauthorized access to edit this post.');
        }

        return view('edit-post', ['post' => $post]); // return the edit-post view with the post data
    }

    // Create post - Create
    public function createPost(Request $request) {
        $incomingData = $request->validate([
            'title' => 'required|min:5|max:100',
            'body' => 'required|min:10',
        ]); // validate incoming data; must follow rules in between braces for each field - title min:5, body min:10

        $incomingData['title'] = strip_tags($incomingData['title']); // Basic sanitization
        $incomingData['body'] = strip_tags($incomingData['body']);
        $incomingData['user_id'] = auth()->id(); // Associate post with logged-in user

        Post::create($incomingData); // Assuming Post model is imported and fillable properties are set
        return redirect('/')->with('success', 'Post created successfully!');
    }

    // Display all posts - Read
    public function index()
    {
        $posts = Post::with('user')->latest()->get(); // load user relationship
        return view('records', ['posts' => $posts]); // return the records view with all posts
    }

    // Update post - Update
    public function update(Request $request, Post $post)
    {
        $incomingData = $request->validate([
            'title' => 'required|min:5|max:100',
            'body' => 'required|min:10',
        ]); 

        // Basic sanitization
        $incomingData['title'] = strip_tags($incomingData['title']);
        $incomingData['body'] = strip_tags($incomingData['body']);

        // Ensure only owner can update
        if ($post->user_id !== auth()->id()) {
            abort(403);
        }

        $post->update($incomingData);

        return redirect('/records')->with('success', 'Post updated successfully!');
    }


}
