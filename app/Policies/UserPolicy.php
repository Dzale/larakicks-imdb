<?php

namespace App\Policies;

use App\Models\Movie;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     *
     * @param  User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  User $auth
     * @return mixed
     */
    public function update(User $user, User $auth)
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can search relation.
     *
     * @param  User  $user
     * @param  User $auth
     * @return mixed
     */
    public function searchFavoriteMovies(User $user, User $auth)
    {
        if ($user->can('user-search-favorite-movies')) {
            return Response::allow();
        }
    }

    /**
     * Determine whether the user can attach new relation.
     *
     * @param  User  $user
     * @param  User $auth
     * @param  Movie $movie
     * @return mixed
     */
    public function attachFavoriteMovie(User $user, User $auth, Movie $movie)
    {
        if ($user->can('user-attach-favorite-movie')) {
            return Response::allow();
        }
    }

    /**
     * Determine whether the user can detach new relation.
     *
     * @param  User  $user
     * @param  User $auth
     * @param  Movie $movie
     * @return mixed
     */
    public function detachFavoriteMovie(User $user, User $auth, Movie $movie)
    {
        if ($user->can('user-detach-favorite-movie')) {
            return Response::allow();
        }
    }

    /**
     * Determine whether the user can search relation.
     *
     * @param  User  $user
     * @param  User $auth
     * @return mixed
     */
    public function searchWishlistMovies(User $user, User $auth)
    {
        if ($user->can('user-search-wishlist-movies')) {
            return Response::allow();
        }
    }

    /**
     * Determine whether the user can attach new relation.
     *
     * @param  User  $user
     * @param  User $auth
     * @param  Movie $movie
     * @return mixed
     */
    public function attachWishlistMovie(User $user, User $auth, Movie $movie)
    {
        if ($user->can('user-attach-wishlist-movie')) {
            return Response::allow();
        }
    }

    /**
     * Determine whether the user can detach new relation.
     *
     * @param  User  $user
     * @param  User $auth
     * @param  Movie $movie
     * @return mixed
     */
    public function detachWishlistMovie(User $user, User $auth, Movie $movie)
    {
        if ($user->can('user-detach-wishlist-movie')) {
            return Response::allow();
        }
    }

    /**
     * Determine whether the user can search relation.
     *
     * @param  User  $user
     * @param  User $auth
     * @return mixed
     */
    public function searchComments(User $user, User $auth)
    {
        if ($user->can('user-search-comments')) {
            return Response::allow();
        }
    }
}
