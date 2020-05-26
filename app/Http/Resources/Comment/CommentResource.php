<?php

namespace App\Http\Resources\Comment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User\UserResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return  array_merge(
            parent::toArray($request),
            [
                /**
                 * Resources that can be included if requested.
                 */
                'creator' => new UserResource($this->whenLoaded('creator')),
            ]
        );
    }
}
