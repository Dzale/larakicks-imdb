<?php

namespace App\Http\Resources\Director;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DirectorCollection extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = DirectorResource::class;
}
