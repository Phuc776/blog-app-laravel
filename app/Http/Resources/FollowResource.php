<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FollowResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        return [
            'followed_id' => $this->followed_id,
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'role_id' => $this->role_id,
        ];
    }
}
