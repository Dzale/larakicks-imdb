<?php

namespace App\Policies;

use App\Models\Director;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class DirectorPolicy
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
        if ($user->can('director-view-all')) {
            return Response::allow();
        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     * @param  Director $director
     * @return mixed
     */
    public function view(User $user, Director $director)
    {
        if ($user->can('director-view')) {
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
        if ($user->can('director-store')) {
            return Response::allow();
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  Director $director
     * @return mixed
     */
    public function update(User $user, Director $director)
    {
        if ($user->can('director-update')) {
            return Response::allow();
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param  Director $director
     * @return mixed
     */
    public function delete(User $user, Director $director)
    {
        if ($user->can('director-destroy')) {
            return Response::allow();
        }
    }

    /**
     * Determine whether the user can search relation.
     *
     * @param  User  $user
     * @param  Director $director
     * @return mixed
     */
    public function searchMovies(User $user, Director $director)
    {
        if ($user->can('director-search-movies')) {
            return Response::allow();
        }
    }

    /**
     * Determine whether the user can attach new relation.
     *
     * @param  User  $user
     * @param  Director $director
     * @param  Movie $movie
     * @return mixed
     */
    public function attachMovie(User $user, Director $director, Movie $movie)
    {
        if ($user->can('director-attach-movie')) {
            return Response::allow();
        }
    }

    /**
     * Determine whether the user can detach new relation.
     *
     * @param  User  $user
     * @param  Director $director
     * @param  Movie $movie
     * @return mixed
     */
    public function detachMovie(User $user, Director $director, Movie $movie)
    {
        if ($user->can('director-detach-movie')) {
            return Response::allow();
        }
    }
}
