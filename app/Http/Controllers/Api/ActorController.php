<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use App\Http\Resources\Movie\MovieCollection;
use App\Http\Resources\Actor\ActorResource;
use App\Http\Resources\Actor\ActorCollection;
use App\Http\Requests\Actor\StoreActorRequest;
use App\Http\Requests\Actor\UpdateActorRequest;
use App\Http\Requests\Movie\MovieAttachActorRequest;
use App\Models\Actor;
use App\Models\Movie;

/**
 * @group Actor
 *
 * Endpoints for Actor entity
 */
class ActorController extends Controller
{

    /**
     * Create a new ActorController instance.
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
        $this->authorize('viewAny', Actor::class);

        $actors = Actor::search()->paginate($request->perPage)
            ->appends(request()->query());

        return response()->resource(new ActorCollection($actors));
    }

    /**
     * Store
     *
     * Store newly created actor.
     * @param  StoreActorRequest  $request
     * @return JsonResponse
     */
    public function store(StoreActorRequest $request): JsonResponse
    {
        $this->authorize('create', Actor::class);

        $actor = $request->fill(new Actor);

        $actor->save();
        $actor->loadIncludes();

        return response()->resource(new ActorResource($actor))
                ->message(__('crud.create', ['item' => __('model.Actor')]));
    }

    /**
     * Update
     *
     * Update specified actor.
     * @param  UpdateActorRequest  $request
     * @param  Actor $actor
     * @return JsonResponse
     */
    public function update(UpdateActorRequest $request, Actor $actor): JsonResponse
    {
        $this->authorize('update', $actor);

        $request->fill($actor);
        
        $actor->update();
        $actor->loadIncludes();

        return response()->resource(new ActorResource($actor))
                ->message(__('crud.update', ['item' => __('model.Actor')]));
    }
    /**
     * Show
     *
     * Display specified actor.
     * @param  Actor $actor
     * @return JsonResponse
     */
    public function show(Actor $actor): JsonResponse
    {
        $this->authorize('view', $actor);

        $actor->loadIncludes();

        return response()->resource(new ActorResource($actor));
    }

    /**
     * Destroy
     *
     * Remove specified actor.

     * @param  Actor  $actor
     * @return  JsonResponse
     */
    public function destroy(Actor $actor): JsonResponse
    {
        $this->authorize('delete', $actor);

        $actor->delete();

        return response()
                ->success(__('crud.delete', ['item' => __('model.Actor')]));
    }

    /**
     * Search Movies
     *
     * Get paginated list of Movies for specified actor.
     * @param Request $request
     * @param Actor $actor
     * @return JsonResponse
     */
    public function searchMovies(Request $request, Actor $actor): JsonResponse
    {
        $this->authorize('searchMovies', $actor);

        $movies = $actor->movies()
            ->search()->paginate($request->perPage)
            ->appends(request()->query());

        return response()->resource(new MovieCollection($movies));
    }

    /**
     * Attach Movie
     *
     * Attach Movie to existing actor.
     * @param MovieAttachActorRequest  $request
     * @param Actor  $actor
     * @param Movie  $movie
     * @return JsonResponse
     */
    public function attachMovie(MovieAttachActorRequest $request, Actor $actor, Movie $movie): JsonResponse
    {
        $this->authorize('attachMovie', [$actor, $movie]);

        $data = $request->only(array_keys($request->rules()));
        $actor->movies()->attach($movie, $data);
        $actor->loadIncludes();
        return response()->resource(new ActorResource($actor))
                ->setStatusCode(201)
                ->message(__('crud.attach', ['item' => __('model.Movie')]));
    }

    /**
     * Detach Movie
     *
     * Detach Movie from existing actor.

     * @param Actor  $actor
     * @param Movie  $movie
     * @return JsonResponse
     */
    public function detachMovie(Actor $actor, Movie $movie): JsonResponse
    {
        $this->authorize('detachMovie', [$actor, $movie]);

        $actor->movies()->detach($movie);

        return response()
                ->success(__('crud.detach', ['item' => __('model.Movie')]));
    }
}
