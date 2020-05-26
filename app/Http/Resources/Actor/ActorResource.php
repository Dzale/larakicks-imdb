<?php

namespace App\Http\Resources\Actor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Movie\MovieCollection;

class ActorResource extends JsonResource
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
                'movies' => new MovieCollection($this->whenLoaded('movies')),
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
                ]
            ] : []
        );
    }
}
