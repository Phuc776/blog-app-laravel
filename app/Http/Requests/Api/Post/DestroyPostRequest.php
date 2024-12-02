<?php

namespace App\Http\Requests\Api\Post;

use App\Http\Requests\BaseRequest;

class DestroyPostRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id' => 'required|integer|exists:posts,id', // Xác thực ID bài viết
        ];
    }
}
