<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class CommentPolicy
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
        if ($user->can('comment-view-all')) {
            return Response::allow();
        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     * @param  Comment $comment
     * @return mixed
     */
    public function view(User $user, Comment $comment)
    {
        if ($user->id == $comment->creator_id) {
            return Response::allow();
        }
        if ($user->can('comment-view')) {
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
        if ($user->can('comment-store')) {
            return Response::allow();
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  Comment $comment
     * @return mixed
     */
    public function update(User $user, Comment $comment)
    {
        if ($user->id == $comment->creator_id) {
            return Response::allow();
        }
        if ($user->can('comment-update')) {
            return Response::allow();
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param  Comment $comment
     * @return mixed
     */
    public function delete(User $user, Comment $comment)
    {
        if ($user->id == $comment->creator_id) {
            return Response::allow();
        }
        if ($user->can('comment-destroy')) {
            return Response::allow();
        }
    }
}
