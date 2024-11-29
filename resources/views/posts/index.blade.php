<!-- resources/views/posts/index.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách bài viết</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f9;
        }
        h1 {
            color: #333;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            background-color: #fff;
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        a {
            text-decoration: none;
        }
        .btn {
            padding: 8px 15px;
            border-radius: 5px;
            color: white;
            display: inline-block;
            text-align: center;
        }
        .btn-primary {
            background-color: #4CAF50;
        }
        .btn-warning {
            background-color: #f39c12;
        }
        .btn-primary:hover, .btn-warning:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>

    <h1>Danh sách bài viết</h1>

    <!-- Nút "Thêm bài viết" -->
    <a href="{{ route('posts.create') }}" class="btn btn-primary">Thêm bài viết</a>

    <ul>
        @foreach ($posts as $post)
            <li>
                <a href="{{ url('/posts/' . $post->id) }}">{{ $post->title }}</a>

                <!-- Nút "Sửa bài viết" -->
                <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning" style="margin-left: 10px;">Sửa</a>
            </li>
        @endforeach
    </ul>

</body>
</html>
