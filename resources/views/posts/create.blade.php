<!-- resources/views/posts/create.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Bài Viết</title>
</head>
<body>
    <h1>Thêm Bài Viết Mới</h1>

    <!-- Hiển thị thông báo thành công nếu có -->
    @if(session('success'))
        <div class="success-message">{{ session('success') }}</div>
    @endif

    <!-- Form thêm bài viết -->
    <div class="container">
        <form action="{{ route('posts.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="user_id">User ID:</label>
                <input type="number" name="user_id" required>
            </div>

            <div class="form-group">
                <label for="title">Tiêu Đề:</label>
                <input type="text" name="title" required>
            </div>

            <div class="form-group">
                <label for="content">Nội Dung:</label>
                <textarea name="content" required></textarea>
            </div>

            <div class="form-group">
                <label for="status">Trạng Thái:</label>
                <select name="status" required>
                    <option value="1">Đang hoạt động</option>
                    <option value="0">Đã xóa</option>
                </select>
            </div>

            <button type="submit">Lưu Bài Viết</button>
        </form>
    </div>

</body>
</html>
