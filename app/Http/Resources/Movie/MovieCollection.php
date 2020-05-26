<?php

namespace App\Http\Resources\Movie;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MovieCollection extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = MovieResource::class;
}
