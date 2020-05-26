<?php

namespace App\Policies;

use App\Models\Actor;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ActorPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        if ($user->can('actor-view-all')) {
            return Response::allow();
        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     * @param  Actor $actor
     * @return mixed
     */
    public function view(User $user, Actor $actor)
    {
        if ($user->can('actor-view')) {
            return Response::allow();
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->can('actor-store')) {
            return Response::allow();
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  Actor $actor
     * @return mixed
     */
    public function update(User $user, Actor $actor)
    {
        if ($user->can('actor-update')) {
            return Response::allow();
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param  Actor $actor
     * @return mixed
     */
    public function delete(User $user, Actor $actor)
    {
        if ($user->can('actor-destroy')) {
            return Response::allow();
        }
    }

    /**
     * Determine whether the user can search relation.
     *
     * @param  User  $user
     * @param  Actor $actor
     * @return mixed
     */
    public function searchMovies(User $user, Actor $actor)
    {
        if ($user->can('actor-search-movies')) {
            return Response::allow();
        }
    }

    /**
     * Determine whether the user can attach new relation.
     *
     * @param  User  $user
     * @param  Actor $actor
     * @param  Movie $movie
     * @return mixed
     */
    public function attachMovie(User $user, Actor $actor, Movie $movie)
    {
        if ($user->can('actor-attach-movie')) {
            return Response::allow();
        }
    }

    /**
     * Determine whether the user can detach new relation.
     *
     * @param  User  $user
     * @param  Actor $actor
     * @param  Movie $movie
     * @return mixed
     */
    public function detachMovie(User $user, Actor $actor, Movie $movie)
    {
        if ($user->can('actor-detach-movie')) {
            return Response::allow();
        }
    }
}
