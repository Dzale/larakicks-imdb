<?php

namespace App\Http\Resources\Actor;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ActorCollection extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = ActorResource::class;
}
