<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public static function apiPaginate($query, $request)
    {
        $pageSize = $request->page_size ?? 4;
        return static::collection($query->paginate($pageSize)->appends($request->query()))
            ->response()
            ->getdata();
    }
}
