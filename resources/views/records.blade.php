<html>
<head>
    <meta charset="utf-8">
    <title>All Records</title>
</head>
<body>
    <h1>All Posts</h1>

    <a href="/">Back</a>

    @if($posts->isEmpty())
        <p>No posts found.</p>
    @else
        <ul>
            @foreach($posts as $post)
                <li>
                    <strong>{{ $post->title }}</strong> by {{ optional($post->user)->name ?? 'Unknown' }}
                    <p>{{ $post->body }}</p>
                </li>
            @endforeach
        </ul>
    @endif
</body>
</html>