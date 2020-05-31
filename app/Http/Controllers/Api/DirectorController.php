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
     * Index
     *
     * Get paginated list of items.
     * @param Request $request
     * @return JsonResponse
     * @authenticated
     * @apiResourceCollection App\Http\Resources\Director\DirectorResource
     * @apiResourceModel App\Models\Director paginate=10
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Director::class);

        $directors = Director::search()->paginate($request->perPage)
            ->appends(request()->query());

        return response()->resource(new DirectorCollection($directors));
    }

    /**
     * Store
     *
     * Store newly created director.
     * @param  StoreDirectorRequest  $request
     * @return JsonResponse
     * @authenticated
     * @apiResource App\Http\Resources\Director\DirectorResource
     * @apiResourceModel App\Models\Director
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
     * Update
     *
     * Update specified director.
     * @param  UpdateDirectorRequest  $request
     * @param  Director $director
     * @return JsonResponse
     * @authenticated
     * @apiResource App\Http\Resources\Director\DirectorResource
     * @apiResourceModel App\Models\Director
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
     * Show
     *
     * Display specified director.
     * @param  Director $director
     * @return JsonResponse
     * @authenticated
     * @apiResource App\Http\Resources\Director\DirectorResource
     * @apiResourceModel App\Models\Director
     */
    public function show(Director $director): JsonResponse
    {
        $this->authorize('view', $director);

        $director->loadIncludes();

        return response()->resource(new DirectorResource($director));
    }

    /**
     * Destroy
     *
     * Remove specified director.

     * @param  Director  $director
     * @return  JsonResponse
     * @authenticated
     */
    public function destroy(Director $director): JsonResponse
    {
        $this->authorize('delete', $director);

        $director->delete();

        return response()
                ->success(__('crud.delete', ['item' => __('model.Director')]));
    }

    /**
     * Search Movies
     *
     * Get paginated list of Movies for specified director.
     * @param Request $request
     * @param Director $director
     * @return JsonResponse
     * @authenticated
     * @apiResourceCollection App\Http\Resources\Movie\MovieResource
     * @apiResourceModel App\Models\Movie paginate=10
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
     * Attach Movie to existing director.
     * @param MovieAttachDirectorRequest  $request
     * @param Director  $director
     * @param Movie  $movie
     * @return JsonResponse
     * @authenticated
     * @apiResource App\Http\Resources\Movie\MovieResource
     * @apiResourceModel App\Models\Movie
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
     * Detach Movie from existing director.

     * @param Director  $director
     * @param Movie  $movie
     * @return JsonResponse
     * @authenticated
     */
    public function detachMovie(Director $director, Movie $movie): JsonResponse
    {
        $this->authorize('detachMovie', [$director, $movie]);

        $director->movies()->detach($movie);

        return response()
                ->success(__('crud.detach', ['item' => __('model.Movie')]));
    }
}
