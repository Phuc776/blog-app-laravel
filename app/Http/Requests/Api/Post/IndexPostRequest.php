<?php

namespace App\Http\Requests\Api\Post;

use App\Http\Requests\BaseRequest;

class IndexPostRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'page' => 'nullable|integer|min:1', // Trang hiện tại
            'per_page' => 'nullable|integer|min:1', // Số bài viết mỗi trang
            'sort' => 'nullable|string|in:title,created_at', // Sắp xếp theo tiêu chí
            'order' => 'nullable|string|in:asc,desc', // Thứ tự sắp xếp
        ];
    }
}
