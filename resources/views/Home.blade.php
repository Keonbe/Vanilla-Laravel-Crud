<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

    @auth 
    <!-- Check if user is authenticated -->
    <h1>Welcome, {{ auth()->user()->name }}!</h1>
    <form action="/logout" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>

    <div style="text-align: center; border: 2px solid black;">
        <h1>CRUD Operations</h1>
        <br>
        <h2>Create Record</h2>
        <form action="/create-post" method="POST">
            @csrf 
            <!-- CSRF token for security  -->
            <input type="text" name="title" placeholder="Enter Title">
            <input type="text" name="body" placeholder="Enter Body Content">
            <button type="submit">Create Record</button>
        </form>

        <br><br>
        <h2>View Records</h2>
        <form action="/records" method="GET">
            <button type="submit">View All Records</button>
        </form>
    </div>

    <div style="margin-top: 20px; color: black;">
        <h2>All Posts</h2>
        @if(isset($posts) && $posts->isNotEmpty())
            @foreach($posts as $post)
                <div style="border: 1px solid black; padding: 10px; margin-bottom: 10px;">
                    <strong>{{ $post->title }}</strong> by {{ optional($post->user)->name ?? 'Unknown' }}
                    <p>{{ $post->body }}</p>
                    <p> <a href="/edit-post/{{ $post->id }}">Edit Post</a> </p>
                    <form action="/delete-post/{{ $post->id }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete Post</button>
                </div>
            @endforeach
        @else
            <p>No posts found.</p>
        @endif
    </div>

    @else
    
    <!-- If user is not authenticated -->
    <div style="text-align: center; border: 2px solid black;">
        <h1>Welcome to Laravel</h1>
        <form action="/register" method="POST">
            @csrf
            <input type="text" name="name" placeholder="Enter your name">
            <input type="email" name="email" placeholder="Enter your email">
            <input type="password" name="password" placeholder="Enter your password">
            <button type="submit">Register</button>
        </form>
    </div>

    <div style="text-align: center; border: 2px solid black;">
        <h1>Login</h1>
        <form action="/login" method="POST">
            @csrf
            <input type="text" name="loginname" placeholder="Enter your name">
            <input type="password" name="loginpassword" placeholder="Enter your password">
            <button type="submit">Login</button>
        </form>
    </div>
    @endauth 
    <!-- End authentication check -->
</body>
</html>