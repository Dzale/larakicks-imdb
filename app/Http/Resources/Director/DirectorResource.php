<?php

namespace App\Http\Resources\Director;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Movie\MovieCollection;

class DirectorResource extends JsonResource
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
            ]
        );
    }
}
