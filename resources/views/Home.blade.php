<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laravel CRUD Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    @auth
    <!-- Check if user is authenticated -->
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Laravel CRUD</span>
            <div class="d-flex align-items-center">
                <span class="text-light me-3">Welcome, {{ auth()->user()->name }}!</span>
                <form action="/logout" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-light">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title mb-0">Create New Post</h3>
                    </div>
                    <div class="card-body">
                        <form action="/create-post" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="Enter post title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="body" class="form-label">Content</label>
                                <textarea class="form-control @error('body') is-invalid @enderror" id="body" name="body" rows="4" placeholder="Enter post content" required>{{ old('body') }}</textarea>
                                @error('body')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Create Record</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h3 class="card-title mb-0">All Posts</h3>
                    </div>
                    <div class="card-body">
                        @if(isset($posts) && $posts->isNotEmpty())
                            @foreach($posts as $post)
                                <div class="card mb-3 border-light">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $post->title }}</h5>
                                        <p class="card-subtitle mb-2 text-muted">By {{ optional($post->user)->name ?? 'Unknown' }}</p>
                                        <p class="card-text">{{ $post->body }}</p>
                                        <div class="d-flex gap-2">
                                            <a href="/edit-post/{{ $post->id }}" class="btn btn-sm btn-warning">Edit</a>
                                            <form action="/delete-post/{{ $post->id }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');" class="m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="alert alert-info" role="alert">
                                No posts found. Create your first post above!
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @else

    <!-- If user is not authenticated -->
    <div class="container py-5">
        <div class="row">
            <div class="col-md-6 mx-auto mb-5">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title mb-0">Register</h3>
                    </div>
                    <div class="card-body">
                        <form action="/register" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="reg-name" class="form-label">Full Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="reg-name" name="name" placeholder="Enter your name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="reg-email" class="form-label">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="reg-email" name="email" placeholder="Enter your email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="reg-password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="reg-password" name="password" placeholder="Enter your password" required>
                                @error('password')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Register</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mx-auto">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h3 class="card-title mb-0">Login</h3>
                    </div>
                    <div class="card-body">
                        @if($errors->has('loginname'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ $errors->first('loginname') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        <form action="/login" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="login-name" class="form-label">Name</label>
                                <input type="text" class="form-control @error('loginname') is-invalid @enderror" id="login-name" name="loginname" placeholder="Enter your name" value="{{ old('loginname') }}" required>
                                @error('loginname')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="login-password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('loginpassword') is-invalid @enderror" id="login-password" name="loginpassword" placeholder="Enter your password" required>
                                @error('loginpassword')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-success w-100">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endauth
    <!-- End authentication check -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
