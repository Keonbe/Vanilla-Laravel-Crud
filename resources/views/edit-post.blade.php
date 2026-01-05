<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Edit Post</h1>
    <form action="/edit-post/{{ $post->id }}" method="POST">
        @csrf
        @method('PUT')

        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="{{ old('title', $post->title) }}" required>
        <br><br>

        <label for="body">Body:</label>
        <textarea id="body" name="body" required>{{ old('body', $post->body) }}</textarea>
        <br><br>

        <button type="submit">Update Post</button>
    </form>
</body>
</html>