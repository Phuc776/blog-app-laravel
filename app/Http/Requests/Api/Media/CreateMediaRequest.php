<?php

namespace App\Http\Requests\Api\Media;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class CreateMediaRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'post_id' => ['required','integer'],
            'type' => ['required','integer'],
            'file_urls' => ['required','array'],
            'file_urls.*' => ['required','file'],
        ];
    }
}
