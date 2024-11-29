<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh Sửa Bài Viết</title>
</head>
<body>
    <h1>Chỉnh Sửa Bài Viết</h1>

    <!-- Hiển thị thông báo thành công nếu có -->
    @if(session('success'))
        <div class="success-message">{{ session('success') }}</div>
    @endif

    <!-- Form chỉnh sửa bài viết -->
    <div class="container">
        <form action="{{ route('posts.update', $post->id) }}" method="POST">
            @csrf
            @method('PUT') <!-- Thêm phương thức PUT vì chúng ta sẽ sửa bài viết -->
            
            <div class="form-group">
                <label for="user_id">User ID:</label>
                <input type="number" name="user_id" value="{{ old('user_id', $post->user_id) }}" required>
            </div>

            <div class="form-group">
                <label for="title">Tiêu Đề:</label>
                <input type="text" name="title" value="{{ old('title', $post->title) }}" required>
            </div>

            <div class="form-group">
                <label for="content">Nội Dung:</label>
                <textarea name="content" required>{{ old('content', $post->content) }}</textarea>
            </div>

            <div class="form-group">
                <label for="status">Trạng Thái:</label>
                <select name="status" required>
                    <option value="1" {{ $post->status == 1 ? 'selected' : '' }}>Đang hoạt động</option>
                    <option value="0" {{ $post->status == 0 ? 'selected' : '' }}>Đã xóa</option>
                </select>
            </div>

            <button type="submit">Cập Nhật Bài Viết</button>
        </form>

        <!-- Link quay lại danh sách bài viết -->
        <a href="{{ route('posts.index') }}" class="back-link">Quay lại danh sách bài viết</a>
    </div>
</body>
</html>
