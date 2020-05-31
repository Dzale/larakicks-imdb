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
     * Index
     *
     * Get paginated list of items.
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
     * Store
     *
     * Store newly created movie.
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
     * Update
     *
     * Update specified movie.
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
     * Show
     *
     * Display specified movie.
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
     * Destroy
     *
     * Remove specified movie.

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
     * Search Actors
     *
     * Get paginated list of Actors for specified movie.
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
     * Search Directors
     *
     * Get paginated list of Directors for specified movie.
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
     * Search FavoritedUsers
     *
     * Get paginated list of FavoritedUsers for specified movie.
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
     * Search WishlistedUsers
     *
     * Get paginated list of WishlistedUsers for specified movie.
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
     * Attach Actor to existing movie.
     * @param MovieAttachActorRequest  $request
     * @param Movie  $movie
     * @param Actor  $actor
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
     * Attach Director to existing movie.
     * @param MovieAttachDirectorRequest  $request
     * @param Movie  $movie
     * @param Director  $director
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
     * Detach Actor from existing movie.

     * @param Movie  $movie
     * @param Actor  $actor
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
     * Detach Director from existing movie.

     * @param Movie  $movie
     * @param Director  $director
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
