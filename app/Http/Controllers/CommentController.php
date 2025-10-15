<?php

namespace App\Http\Controllers;

use App\Actions\Comment\CreateCommentAction;
use App\Actions\Comment\DeleteCommentAction;
use App\Actions\Comment\GetCommentRepliesAction;
use App\Actions\Comment\UpdateCommentAction;
use App\DTOs\Comment\CreateCommentDTO;
use App\DTOs\Comment\UpdateCommentDTO;
use App\Http\Requests\Comment\CreateCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Repositories\Contracts\CommentRepositoryInterface;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct(
        private CommentRepositoryInterface $commentRepository
    ) {}

    public function index(Request $request): ResourceCollection
    {
        $filters = $request->only(['user_id', 'commentable_type', 'commentable_id', 'created_at', 'sort', 'order']);
        $perPage = min($request->get('per_page', 15), 100);

        $comments = $this->commentRepository->paginate($filters, $perPage);

        return CommentResource::collection($comments);
    }

    public function store(CreateCommentRequest $request, CreateCommentAction $action): CommentResource
    {
        $dto = CreateCommentDTO::fromRequest($request);
        $comment = $action->execute($dto);

        return new CommentResource($comment->load(['user', 'commentable']));
    }

    public function show(Comment $comment): CommentResource
    {
        return new CommentResource($comment->load(['user', 'commentable', 'allReplies.user']));
    }

    public function update(UpdateCommentRequest $request, Comment $comment, UpdateCommentAction $action): CommentResource
    {
        $dto = UpdateCommentDTO::fromRequest($request);
        $updatedComment = $action->execute($comment, $dto);

        return new CommentResource($updatedComment);
    }

    public function destroy(Comment $comment, DeleteCommentAction $action): CommentResource
    {
        $this->authorize('delete', $comment);

        $action->execute($comment);

        return new CommentResource($comment);
    }

    public function storeForPost(CreateCommentRequest $request, Post $post, CreateCommentAction $action): CommentResource
    {
        $dto = CreateCommentDTO::fromPostComment($request, $post->id);
        $comment = $action->execute($dto);

        return new CommentResource($comment->load(['user', 'commentable']));
    }

    public function storeReply(CreateCommentRequest $request, Comment $comment, CreateCommentAction $action): CommentResource
    {
        $dto = CreateCommentDTO::fromCommentReply($request, $comment->id);
        $reply = $action->execute($dto);

        return new CommentResource($reply->load(['user', 'commentable']));
    }

    public function postComments(Post $post): ResourceCollection
    {
        $comments = $this->commentRepository->getByPostId($post->id);

        return CommentResource::collection($comments);
    }

    public function commentReplies(Comment $comment): ResourceCollection
    {
        $replies = $this->commentRepository->getRepliesByCommentId($comment->id);

        return CommentResource::collection($replies);
    }

    public function userComments(User $user): ResourceCollection
    {
        $comments = $this->commentRepository->getByUserId($user->id);

        return CommentResource::collection($comments);
    }

    public function myComments(Request $request): ResourceCollection
    {
        $comments = $this->commentRepository->getByUserId($request->user()->id);

        return CommentResource::collection($comments);
    }
}
