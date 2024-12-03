<?php

namespace Database\Seeders;

use App\Models\Post;  // Đảm bảo import model Post
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Tạo dữ liệu mẫu cho bảng posts
        Post::create([
            'user_id' => 1,  // ID người dùng (giả sử người dùng có ID = 1)
            'title' => 'First Post',
            'content' => 'This is the content of the first post.',
            'status' => true,  // Bài viết đang hoạt động
        ]);

        Post::create([
            'user_id' => 2,  // Giả sử có người dùng khác có ID = 2
            'title' => 'Second Post',
            'content' => 'Content of the second post goes here.',
            'status' => true,
        ]);

        Post::create([
            'user_id' => 1,  // Người dùng 1
            'title' => 'Third Post',
            'content' => 'The third post content.',
            'status' => false,  // Bài viết không hiển thị
        ]);
    }
}
