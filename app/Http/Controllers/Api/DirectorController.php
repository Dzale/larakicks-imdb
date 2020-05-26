<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use App\Http\Resources\Movie\MovieCollection;
use App\Http\Resources\Director\DirectorResource;
use App\Http\Resources\Director\DirectorCollection;
use App\Http\Requests\Director\StoreDirectorRequest;
use App\Http\Requests\Director\UpdateDirectorRequest;
use App\Http\Requests\Movie\MovieAttachDirectorRequest;
use App\Models\Director;
use App\Models\Movie;

/**
 * @group Director
 *
 * Endpoints for Director entity
 */
class DirectorController extends Controller
{

    /**
     * Create a new DirectorController instance.
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
        $this->authorize('viewAny', Director::class);

        $directors = Director::search()->paginate($request->perPage)
            ->appends(request()->query());

        return response()->resource(new DirectorCollection($directors));
    }

    /**
     * Create item
     *
     * Store a newly created item in storage.
     *
     * @param  StoreDirectorRequest  $request
     * @return JsonResponse
     */
    public function store(StoreDirectorRequest $request): JsonResponse
    {
        $this->authorize('create', Director::class);

        $director = $request->fill(new Director);

        $director->save();
        $director->loadIncludes();

        return response()->resource(new DirectorResource($director))
                ->message(__('crud.create', ['item' => __('model.Director')]));
    }

    /**
     * Update item
     *
     * Update the specified item in storage.
     *
     * @param  UpdateDirectorRequest  $request
     * @param  Director $director
     * @return JsonResponse
     */
    public function update(UpdateDirectorRequest $request, Director $director): JsonResponse
    {
        $this->authorize('update', $director);

        $request->fill($director);
        
        $director->update();
        $director->loadIncludes();

        return response()->resource(new DirectorResource($director))
                ->message(__('crud.update', ['item' => __('model.Director')]));
    }
    /**
     * Get Single Item
     *
     * Display the specified item.
     *
     * @param  Director $director
     * @return JsonResponse
     */
    public function show(Director $director): JsonResponse
    {
        $this->authorize('view', $director);

        $director->loadIncludes();

        return response()->resource(new DirectorResource($director));
    }

    /**
     * Remove item
     *
     * Remove the specified item from storage.
     *

     * @param  Director  $director
     * @return  JsonResponse
     */
    public function destroy(Director $director): JsonResponse
    {
        $this->authorize('delete', $director);

        $director->delete();

        return response()
                ->success(__('crud.delete', ['item' => __('model.Director')]));
    }

    /**
     * Search Movies for Director with given $id
     *
     * Movies from existing resource.
     *
     * @param Request $request
     * @param Director $director
     * @return JsonResponse
     */
    public function searchMovies(Request $request, Director $director): JsonResponse
    {
        $this->authorize('searchMovies', $director);

        $movies = $director->movies()
            ->search()->paginate($request->perPage)
            ->appends(request()->query());

        return response()->resource(new MovieCollection($movies));
    }

    /**
     * Attach Movie
     *
     * Attach the Movie to existing resource.
     *
     * @param  MovieAttachDirectorRequest  $request
     * @param  Director  $director
     * @param  Movie  $movie
     * @return JsonResponse
     */
    public function attachMovie(MovieAttachDirectorRequest $request, Director $director, Movie $movie): JsonResponse
    {
        $this->authorize('attachMovie', [$director, $movie]);

        $data = $request->only(array_keys($request->rules()));
        $director->movies()->attach($movie, $data);
        $director->loadIncludes();
        return response()->resource(new DirectorResource($director))
                ->setStatusCode(201)
                ->message(__('crud.attach', ['item' => __('model.Movie')]));
    }

    /**
     * Detach Movie
     *
     * Detach the Movie from existing resource.
     *

     * @param  Director  $director
     * @param  Movie  $movie
     * @return JsonResponse
     */
    public function detachMovie(Director $director, Movie $movie): JsonResponse
    {
        $this->authorize('detachMovie', [$director, $movie]);

        $director->movies()->detach($movie);

        return response()
                ->success(__('crud.detach', ['item' => __('model.Movie')]));
    }
}
