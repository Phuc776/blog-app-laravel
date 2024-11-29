<!-- resources/views/posts/show.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #333;
            text-align: center;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .post-content {
            margin-bottom: 20px;
        }

        .status {
            font-weight: bold;
            font-size: 1.2em;
            color: #555;
        }

        .action-buttons {
            text-align: center;
            margin-top: 20px;
        }

        .action-buttons a, .action-buttons form button {
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 5px;
        }

        .edit-btn {
            background-color: #f39c12;
            color: white;
            transition: background-color 0.3s;
        }

        .edit-btn:hover {
            background-color: #e67e22;
        }

        .delete-btn {
            background-color: #e74c3c;
            color: white;
            border: none;
            transition: background-color 0.3s;
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-align: center;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .back-link:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>{{ $post->title }}</h1>

        <div class="post-content">
            <p>{{ $post->content }}</p>
        </div>

        <p class="status"><strong>Trạng thái: </strong>{{ $post->status ? 'Đang hoạt động' : 'Đã xóa' }}</p>

        <!-- Nút "Sửa bài viết" và "Xóa bài viết" -->
        <div class="action-buttons">
            <a href="{{ route('posts.edit', $post->id) }}" class="edit-btn">Sửa</a>

            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete-btn">Xóa</button>
            </form>
        </div>

        <!-- Link quay lại danh sách bài viết -->
        <p><a href="{{ url('/posts') }}" class="back-link">Quay lại danh sách bài viết</a></p>
    </div>

</body>
</html>
