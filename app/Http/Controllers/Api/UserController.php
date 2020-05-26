<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\User\UpdatePasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\Movie\MovieCollection;
use App\Http\Resources\Comment\CommentCollection;
use App\Http\Resources\User\UserResource;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\UserAttachFavoriteMovieRequest;
use App\Http\Requests\User\UserAttachWishlistMovieRequest;
use App\Models\User;
use App\Models\Movie;
use App\Models\Comment;
use Illuminate\Auth\Events\Registered;
use App\Models\Profile;

/**
 * @group User
 *
 * Endpoints for User entity
 */
class UserController extends Controller
{

    /**
     * Create a new UserController instance.
     * @return void
     */
    public function __construct()
    {
        $this->middleware(
            'auth:sanctum',
            [
                'except' =>
                    [
                        'store'
                    ]
            ]
        );

        $this->middleware(
            'verified',
            [
                'except' =>
                    [
                        'store'
                    ]
            ]
        );
    }

    /**
     * Return currently logged in user
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        $user = auth()->user();
        $user->loadIncludes();

        return response()->resource(new UserResource($user));
    }

    /**
     * Store a newly created resource in storage.
     * @param  StoreUserRequest $request
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = \DB::transaction(function () use ($request) {
            $user = $request->fill(new User);
            $user->password = bcrypt($request->password);

            $user->save();

            $profile = $request->fill(new Profile);
            if ($avatar = $request->file('avatar')) {
                $profile->avatar = $avatar->store(config('storage.profiles.avatar'));
            }

            $profile->user()->associate($user);
            $profile->save();

            $user->assignRole('admin');
            $user->assignRole('user');

            return $user;
        });

        event(new Registered($user));
        return response()->success(__('auth.success_registration'));
    }

    /**
     * Update profile
     * @param  UpdateUserRequest  $request
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request): JsonResponse
    {
        $user = \DB::transaction(function () use ($request) {
            $user = auth()->user();

            $user->update();
            $profile = $request->fill($user->profile);
            
            if ($request->file('avatar')) {
                $avatar = $profile->getOriginal('avatar');
                if ($avatar != 'user.png') {
                    \Storage::delete($avatar);
                }
                $profile->avatar = $request->file("avatar")->store(config("storage.profiles.avatar"));
            }
            $profile->update();

            return $user;
        });

        return response()->resource(new UserResource($user));
    }

    /**
     * Update password
     * @return JsonResponse
     */
    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $user = auth()->user();
        if (!password_verify($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => [__('passwords.invalid')],
            ]);
        }

        $user->update(['password' => $request->new_password]);

        return response()->resource(new UserResource($user));
    }

    /**
     * Search for FavoriteMovies
     *
     * FavoriteMovies from existing resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function searchFavoriteMovies(Request $request): JsonResponse
    {
        $user = $request->user();
        $this->authorize('searchFavoriteMovies', $user);

        $favoriteMovies = $user->favoriteMovies()->search()->paginate($request->perPage)
            ->appends(request()->query());

        return response()->resource(new MovieCollection($favoriteMovies));
    }
    /**
     * Search for WishlistMovies
     *
     * WishlistMovies from existing resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function searchWishlistMovies(Request $request): JsonResponse
    {
        $user = $request->user();
        $this->authorize('searchWishlistMovies', $user);

        $wishlistMovies = $user->wishlistMovies()->search()->paginate($request->perPage)
            ->appends(request()->query());

        return response()->resource(new MovieCollection($wishlistMovies));
    }
    /**
     * Search for Comments
     *
     * Comments from existing resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function searchComments(Request $request): JsonResponse
    {
        $user = $request->user();
        $this->authorize('searchComments', $user);

        $comments = $user->comments()->search()->paginate($request->perPage)
            ->appends(request()->query());

        return response()->resource(new CommentCollection($comments));
    }
    /**
     * Attach FavoriteMovie
     *
     * Attach the FavoriteMovie to existing User.
     *
     * @param  UserAttachFavoriteMovieRequest  $request
     * @param  Movie  $movie
     * @return JsonResponse
     */
    public function attachFavoriteMovie(UserAttachFavoriteMovieRequest $request, Movie $movie): JsonResponse
    {
        $user = $request->user();
        $this->authorize('attachFavoriteMovie', [$user, $movie]);

        $data = $request->only(array_keys($request->rules()));
        $user->favoriteMovies()->attach($movie, $data);
        $user->loadIncludes();

        return response()->resource(new UserResource($user))
                ->setStatusCode(201)
                ->message(__('crud.attach', [
                    'item' => __('model.Movie')
                ]));
    }

    /**
     * Attach WishlistMovie
     *
     * Attach the WishlistMovie to existing User.
     *
     * @param  UserAttachWishlistMovieRequest  $request
     * @param  Movie  $movie
     * @return JsonResponse
     */
    public function attachWishlistMovie(UserAttachWishlistMovieRequest $request, Movie $movie): JsonResponse
    {
        $user = $request->user();
        $this->authorize('attachWishlistMovie', [$user, $movie]);

        $data = $request->only(array_keys($request->rules()));
        $user->wishlistMovies()->attach($movie, $data);
        $user->loadIncludes();

        return response()->resource(new UserResource($user))
                ->setStatusCode(201)
                ->message(__('crud.attach', [
                    'item' => __('model.Movie')
                ]));
    }

    /**
     * Detach FavoriteMovie
     *
     * Detach the specified resource from existing resource.
     *
     * @param  Movie  $movie
     * @return JsonResponse
     */
    public function detachFavoriteMovie(Movie $movie): JsonResponse
    {
        $user = auth()->user();
        $this->authorize('detachFavoriteMovie', [$user, $movie]);

        $user->favoriteMovies()->detach($movie);
        return response()->success(__('crud.detach', ['item' => __('model.Movie')]));
    }
    /**
     * Detach WishlistMovie
     *
     * Detach the specified resource from existing resource.
     *
     * @param  Movie  $movie
     * @return JsonResponse
     */
    public function detachWishlistMovie(Movie $movie): JsonResponse
    {
        $user = auth()->user();
        $this->authorize('detachWishlistMovie', [$user, $movie]);

        $user->wishlistMovies()->detach($movie);
        return response()->success(__('crud.detach', ['item' => __('model.Movie')]));
    }
}
