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
     * Index
     *
     * Get paginated list of items.
     * @param Request $request
     * @return JsonResponse
     * @authenticated
     * @apiResourceCollection App\Http\Resources\Comment\CommentResource
     * @apiResourceModel App\Models\Comment paginate=10
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Comment::class);

        $comments = Comment::search()->paginate($request->perPage)
            ->appends(request()->query());

        return response()->resource(new CommentCollection($comments));
    }

    /**
     * Store
     *
     * Store newly created comment.
     * @param  StoreCommentRequest  $request
     * @return JsonResponse
     * @authenticated
     * @apiResource App\Http\Resources\Comment\CommentResource
     * @apiResourceModel App\Models\Comment
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
     * Update
     *
     * Update specified comment.
     * @param  UpdateCommentRequest  $request
     * @param  Comment $comment
     * @return JsonResponse
     * @authenticated
     * @apiResource App\Http\Resources\Comment\CommentResource
     * @apiResourceModel App\Models\Comment
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
     * Show
     *
     * Display specified comment.
     * @param  Comment $comment
     * @return JsonResponse
     * @authenticated
     * @apiResource App\Http\Resources\Comment\CommentResource
     * @apiResourceModel App\Models\Comment
     */
    public function show(Comment $comment): JsonResponse
    {
        $this->authorize('view', $comment);

        $comment->loadIncludes();

        return response()->resource(new CommentResource($comment));
    }

    /**
     * Destroy
     *
     * Remove specified comment.

     * @param  Comment  $comment
     * @return  JsonResponse
     * @authenticated
     */
    public function destroy(Comment $comment): JsonResponse
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return response()
                ->success(__('crud.delete', ['item' => __('model.Comment')]));
    }
}
