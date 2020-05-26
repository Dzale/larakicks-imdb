<?php

namespace App\Http\Resources\Movie;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Actor\ActorCollection;
use App\Http\Resources\Director\DirectorCollection;
use App\Http\Resources\User\UserCollection;

class MovieResource extends JsonResource
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
                'actors' => new ActorCollection($this->whenLoaded('actors')),
                'directors' => new DirectorCollection($this->whenLoaded('directors')),
                'favoritedUsers' => new UserCollection($this->whenLoaded('favoritedUsers')),
                'wishlistedUsers' => new UserCollection($this->whenLoaded('wishlistedUsers')),
            ],
            $this->pivot ? [
                /**
                 * Pivot attributes that are shown when specific relations is loaded.
                 */
                'pivot' => [
                    'role' => $this->whenPivotLoaded('movies_actors', function () {
                        return $this->pivot->role;
                    }),
                    'role_type' => $this->whenPivotLoaded('movies_actors', function () {
                        return $this->pivot->role_type;
                    }),
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
