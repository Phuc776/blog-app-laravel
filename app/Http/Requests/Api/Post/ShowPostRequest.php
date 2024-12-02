<?php

namespace App\Http\Requests\Api\Post;

use App\Http\Requests\BaseRequest;

class ShowPostRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            // Bạn không cần kiểm tra 'id' trong body nữa.
            // ID sẽ được kiểm tra khi gọi phương thức trong controller.
        ];
    }

    // Chúng ta có thể sử dụng phương thức 'authorize' nếu muốn kiểm tra quyền truy cập người dùng (nếu có)
    public function authorize()
    {
        return true;
    }
}
