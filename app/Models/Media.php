<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    // Tên bảng trong database (nếu khác với tên mặc định "medias")
    protected $table = 'media';

    // Các cột có thể được thêm/sửa trong database
    protected $fillable = [
        'post_id',
        'type',
        'file_url',
        'created_at',
        'updated_at'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
