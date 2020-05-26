<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Profile\ProfileResource;
use App\Http\Resources\Comment\CommentCollection;
use App\Http\Resources\Movie\MovieCollection;

class UserResource extends JsonResource
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
                'profile' => new ProfileResource($this->whenLoaded('profile')),
                'comments' => new CommentCollection($this->whenLoaded('comments')),
                'favoriteMovies' => new MovieCollection($this->whenLoaded('favoriteMovies')),
                'wishlistMovies' => new MovieCollection($this->whenLoaded('wishlistMovies')),
            ],
            $this->pivot ? [
                /**
                 * Pivot attributes that are shown when specific relations is loaded.
                 */
                'pivot' => [
                    'note' => $this->whenPivotLoaded('favorite_movie', function () {
                        return $this->pivot->note;
                    }),
                    'rate' => $this->whenPivotLoaded('favorite_movie', function () {
                        return $this->pivot->rate;
                    }),
                    'note' => $this->whenPivotLoaded('wishlist_movie', function () {
                        return $this->pivot->note;
                    }),
                ]
            ] : []
        );
    }
}
