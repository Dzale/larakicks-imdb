<?php

namespace App\Policies;

use App\Models\Movie;
use App\Models\Actor;
use App\Models\Director;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class MoviePolicy
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
        if ($user->can('movie-view-all')) {
            return Response::allow();
        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     * @param  Movie $movie
     * @return mixed
     */
    public function view(User $user, Movie $movie)
    {
        if ($user->can('movie-view')) {
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
        if ($user->can('movie-store')) {
            return Response::allow();
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  Movie $movie
     * @return mixed
     */
    public function update(User $user, Movie $movie)
    {
        if ($user->can('movie-update')) {
            return Response::allow();
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param  Movie $movie
     * @return mixed
     */
    public function delete(User $user, Movie $movie)
    {
        if ($user->can('movie-destroy')) {
            return Response::allow();
        }
    }

    /**
     * Determine whether the user can search relation.
     *
     * @param  User  $user
     * @param  Movie $movie
     * @return mixed
     */
    public function searchActors(User $user, Movie $movie)
    {
        if ($user->can('movie-search-actors')) {
            return Response::allow();
        }
    }

    /**
     * Determine whether the user can attach new relation.
     *
     * @param  User  $user
     * @param  Movie $movie
     * @param  Actor $actor
     * @return mixed
     */
    public function attachActor(User $user, Movie $movie, Actor $actor)
    {
        if ($user->can('movie-attach-actor')) {
            return Response::allow();
        }
    }

    /**
     * Determine whether the user can detach new relation.
     *
     * @param  User  $user
     * @param  Movie $movie
     * @param  Actor $actor
     * @return mixed
     */
    public function detachActor(User $user, Movie $movie, Actor $actor)
    {
        if ($user->can('movie-detach-actor')) {
            return Response::allow();
        }
    }

    /**
     * Determine whether the user can search relation.
     *
     * @param  User  $user
     * @param  Movie $movie
     * @return mixed
     */
    public function searchDirectors(User $user, Movie $movie)
    {
        if ($user->can('movie-search-directors')) {
            return Response::allow();
        }
    }

    /**
     * Determine whether the user can attach new relation.
     *
     * @param  User  $user
     * @param  Movie $movie
     * @param  Director $director
     * @return mixed
     */
    public function attachDirector(User $user, Movie $movie, Director $director)
    {
        if ($user->can('movie-attach-director')) {
            return Response::allow();
        }
    }

    /**
     * Determine whether the user can detach new relation.
     *
     * @param  User  $user
     * @param  Movie $movie
     * @param  Director $director
     * @return mixed
     */
    public function detachDirector(User $user, Movie $movie, Director $director)
    {
        if ($user->can('movie-detach-director')) {
            return Response::allow();
        }
    }
}
