<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\BaseRequest;

class ResendVerificationRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => 'required|string|email',
        ];
    }
}