<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use App\Http\Resources\Actor\ActorCollection;
use App\Http\Resources\Director\DirectorCollection;
use App\Http\Resources\User\UserCollection;
use App\Http\Resources\Movie\MovieResource;
use App\Http\Resources\Movie\MovieCollection;
use App\Http\Requests\Movie\StoreMovieRequest;
use App\Http\Requests\Movie\UpdateMovieRequest;
use App\Http\Requests\Movie\MovieAttachActorRequest;
use App\Http\Requests\Movie\MovieAttachDirectorRequest;
use App\Models\Movie;
use App\Models\Actor;
use App\Models\Director;

/**
 * @group Movie
 *
 * Endpoints for Movie entity
 */
class MovieController extends Controller
{

    /**
     * Create a new MovieController instance.
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');

        $this->middleware('verified');
    }

    /**
     * Get paginated items, included advanced REST querying
     *
     * Display a listing of the item.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Movie::class);

        $movies = Movie::search()->paginate($request->perPage)
            ->appends(request()->query());

        return response()->resource(new MovieCollection($movies));
    }

    /**
     * Create item
     *
     * Store a newly created item in storage.
     *
     * @param  StoreMovieRequest  $request
     * @return JsonResponse
     */
    public function store(StoreMovieRequest $request): JsonResponse
    {
        $this->authorize('create', Movie::class);

        $movie = $request->fill(new Movie);

        $movie->save();
        $movie->loadIncludes();

        return response()->resource(new MovieResource($movie))
                ->message(__('crud.create', ['item' => __('model.Movie')]));
    }

    /**
     * Update item
     *
     * Update the specified item in storage.
     *
     * @param  UpdateMovieRequest  $request
     * @param  Movie $movie
     * @return JsonResponse
     */
    public function update(UpdateMovieRequest $request, Movie $movie): JsonResponse
    {
        $this->authorize('update', $movie);

        $request->fill($movie);
        
        $movie->update();
        $movie->loadIncludes();

        return response()->resource(new MovieResource($movie))
                ->message(__('crud.update', ['item' => __('model.Movie')]));
    }
    /**
     * Get Single Item
     *
     * Display the specified item.
     *
     * @param  Movie $movie
     * @return JsonResponse
     */
    public function show(Movie $movie): JsonResponse
    {
        $this->authorize('view', $movie);

        $movie->loadIncludes();

        return response()->resource(new MovieResource($movie));
    }

    /**
     * Remove item
     *
     * Remove the specified item from storage.
     *

     * @param  Movie  $movie
     * @return  JsonResponse
     */
    public function destroy(Movie $movie): JsonResponse
    {
        $this->authorize('delete', $movie);

        $movie->delete();

        return response()
                ->success(__('crud.delete', ['item' => __('model.Movie')]));
    }

    /**
     * Search Actors for Movie with given $id
     *
     * Actors from existing resource.
     *
     * @param Request $request
     * @param Movie $movie
     * @return JsonResponse
     */
    public function searchActors(Request $request, Movie $movie): JsonResponse
    {
        $this->authorize('searchActors', $movie);

        $actors = $movie->actors()
            ->search()->paginate($request->perPage)
            ->appends(request()->query());

        return response()->resource(new ActorCollection($actors));
    }

    /**
     * Search Directors for Movie with given $id
     *
     * Directors from existing resource.
     *
     * @param Request $request
     * @param Movie $movie
     * @return JsonResponse
     */
    public function searchDirectors(Request $request, Movie $movie): JsonResponse
    {
        $this->authorize('searchDirectors', $movie);

        $directors = $movie->directors()
            ->search()->paginate($request->perPage)
            ->appends(request()->query());

        return response()->resource(new DirectorCollection($directors));
    }

    /**
     * Search FavoritedUsers for Movie with given $id
     *
     * FavoritedUsers from existing resource.
     *
     * @param Request $request
     * @param Movie $movie
     * @return JsonResponse
     */
    public function searchFavoritedUsers(Request $request, Movie $movie): JsonResponse
    {
        $this->authorize('searchFavoritedUsers', $movie);

        $favoritedUsers = $movie->favoritedUsers()
            ->search()->paginate($request->perPage)
            ->appends(request()->query());

        return response()->resource(new UserCollection($favoritedUsers));
    }

    /**
     * Search WishlistedUsers for Movie with given $id
     *
     * WishlistedUsers from existing resource.
     *
     * @param Request $request
     * @param Movie $movie
     * @return JsonResponse
     */
    public function searchWishlistedUsers(Request $request, Movie $movie): JsonResponse
    {
        $this->authorize('searchWishlistedUsers', $movie);

        $wishlistedUsers = $movie->wishlistedUsers()
            ->search()->paginate($request->perPage)
            ->appends(request()->query());

        return response()->resource(new UserCollection($wishlistedUsers));
    }

    /**
     * Attach Actor
     *
     * Attach the Actor to existing resource.
     *
     * @param  MovieAttachActorRequest  $request
     * @param  Movie  $movie
     * @param  Actor  $actor
     * @return JsonResponse
     */
    public function attachActor(MovieAttachActorRequest $request, Movie $movie, Actor $actor): JsonResponse
    {
        $this->authorize('attachActor', [$movie, $actor]);

        $data = $request->only(array_keys($request->rules()));
        $movie->actors()->attach($actor, $data);
        $movie->loadIncludes();
        return response()->resource(new MovieResource($movie))
                ->setStatusCode(201)
                ->message(__('crud.attach', ['item' => __('model.Actor')]));
    }

    /**
     * Attach Director
     *
     * Attach the Director to existing resource.
     *
     * @param  MovieAttachDirectorRequest  $request
     * @param  Movie  $movie
     * @param  Director  $director
     * @return JsonResponse
     */
    public function attachDirector(MovieAttachDirectorRequest $request, Movie $movie, Director $director): JsonResponse
    {
        $this->authorize('attachDirector', [$movie, $director]);

        $data = $request->only(array_keys($request->rules()));
        $movie->directors()->attach($director, $data);
        $movie->loadIncludes();
        return response()->resource(new MovieResource($movie))
                ->setStatusCode(201)
                ->message(__('crud.attach', ['item' => __('model.Director')]));
    }

    /**
     * Detach Actor
     *
     * Detach the Actor from existing resource.
     *

     * @param  Movie  $movie
     * @param  Actor  $actor
     * @return JsonResponse
     */
    public function detachActor(Movie $movie, Actor $actor): JsonResponse
    {
        $this->authorize('detachActor', [$movie, $actor]);

        $movie->actors()->detach($actor);

        return response()
                ->success(__('crud.detach', ['item' => __('model.Actor')]));
    }

    /**
     * Detach Director
     *
     * Detach the Director from existing resource.
     *

     * @param  Movie  $movie
     * @param  Director  $director
     * @return JsonResponse
     */
    public function detachDirector(Movie $movie, Director $director): JsonResponse
    {
        $this->authorize('detachDirector', [$movie, $director]);

        $movie->directors()->detach($director);

        return response()
                ->success(__('crud.detach', ['item' => __('model.Director')]));
    }
}
