<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use App\Http\Resources\Comment\CommentResource;
use App\Http\Resources\Comment\CommentCollection;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Models\Comment;

/**
 * @group Comment
 *
 * Endpoints for Comment entity
 */
class CommentController extends Controller
{

    /**
     * Create a new CommentController instance.
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
        $this->authorize('viewAny', Comment::class);

        $comments = Comment::search()->paginate($request->perPage)
            ->appends(request()->query());

        return response()->resource(new CommentCollection($comments));
    }

    /**
     * Create item
     *
     * Store a newly created item in storage.
     *
     * @param  StoreCommentRequest  $request
     * @return JsonResponse
     */
    public function store(StoreCommentRequest $request): JsonResponse
    {
        $this->authorize('create', Comment::class);

        $comment = $request->fill(new Comment);

        $comment->creator_id = auth()->user()->id;

        $comment->save();
        $comment->loadIncludes();

        return response()->resource(new CommentResource($comment))
                ->message(__('crud.create', ['item' => __('model.Comment')]));
    }

    /**
     * Update item
     *
     * Update the specified item in storage.
     *
     * @param  UpdateCommentRequest  $request
     * @param  Comment $comment
     * @return JsonResponse
     */
    public function update(UpdateCommentRequest $request, Comment $comment): JsonResponse
    {
        $this->authorize('update', $comment);

        $request->fill($comment);
        
        $comment->update();
        $comment->loadIncludes();

        return response()->resource(new CommentResource($comment))
                ->message(__('crud.update', ['item' => __('model.Comment')]));
    }
    /**
     * Get Single Item
     *
     * Display the specified item.
     *
     * @param  Comment $comment
     * @return JsonResponse
     */
    public function show(Comment $comment): JsonResponse
    {
        $this->authorize('view', $comment);

        $comment->loadIncludes();

        return response()->resource(new CommentResource($comment));
    }

    /**
     * Remove item
     *
     * Remove the specified item from storage.
     *

     * @param  Comment  $comment
     * @return  JsonResponse
     */
    public function destroy(Comment $comment): JsonResponse
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return response()
                ->success(__('crud.delete', ['item' => __('model.Comment')]));
    }
}
