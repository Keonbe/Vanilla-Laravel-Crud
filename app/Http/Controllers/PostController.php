<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function deletePost(Post $post) {
        // Ensure only owner can delete
        if (auth()->id() !== $post->user_id) {
            abort(403);
        }
        $post->delete();
        return redirect('/')->with('success', 'Post deleted successfully!');
    }

    public function updatePost(Post $post, Request $request) {
        if (auth()->id() !== $post->user_id) {
            abort(403);
            return redirect('/')->with('error', 'Unauthorized access to update this post.');
        }

        $incomingData = $request->validate([
            'title' => 'required|min:5|max:100',
            'body' => 'required|min:10',
        ]);

        $incomingData['title'] = strip_tags($incomingData['title']); // Basic sanitization
        $incomingData['body'] = strip_tags($incomingData['body']);

        $post->update($incomingData);
        return redirect('/')->with('success', 'Post updated successfully!');
    }

    public function showEditForm(Post $post){
        if (auth()->id() !== $post->user_id) {
            abort(403);
            return redirect('/')->with('error', 'Unauthorized access to edit this post.');
        }

        return view('edit-post', ['post' => $post]);
    }

    public function createPost(Request $request) {
        $incomingData = $request->validate([
            'title' => 'required|min:5|max:100',
            'body' => 'required|min:10',
        ]);

        $incomingData['title'] = strip_tags($incomingData['title']); // Basic sanitization
        $incomingData['body'] = strip_tags($incomingData['body']);
        $incomingData['user_id'] = auth()->id(); // Associate post with logged-in user
        
        Post::create($incomingData); // Assuming Post model is imported and fillable properties are set
        return redirect('/')->with('success', 'Post created successfully!');
    }
    
    public function index()
    {
        $posts = Post::with('user')->latest()->get();
        return view('records', ['posts' => $posts]);
    }

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
