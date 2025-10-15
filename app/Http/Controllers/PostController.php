<?php

namespace App\Http\Controllers;

use App\Actions\Post\CreatePostAction;
use App\Actions\Post\DeletePostAction;
use App\Actions\Post\GetUserPostsAction;
use App\Actions\Post\UpdatePostAction;
use App\DTOs\Post\CreatePostDTO;
use App\DTOs\Post\UpdatePostDTO;
use App\Http\Requests\Post\CreatePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use App\Repositories\Contracts\PostRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct(
        private readonly PostRepositoryInterface $postRepository
    ) {}

    public function index(Request $request): ResourceCollection
    {
        $filters = $request->only(['is_active', 'user_id', 'created_at', 'sort', 'order']);
        $perPage = min($request->get('per_page', 15), 100);

        $posts = $this->postRepository->paginate($filters, $perPage);

        return PostResource::collection($posts);
    }

    public function store(CreatePostRequest $request, CreatePostAction $action)
    {
        $dto = CreatePostDTO::fromRequest($request);
        $post = $action->execute($dto);

        $resource = new PostResource($post->load('user'));
        return $resource->response()->setStatusCode(201);
    }

    public function show(Post $post): PostResource
    {
        return new PostResource($post->load(['user', 'comments.user']));
    }

    public function update(UpdatePostRequest $request, Post $post, UpdatePostAction $action): PostResource
    {
        $dto = UpdatePostDTO::fromRequest($request);
        $updatedPost = $action->execute($post, $dto);

        return new PostResource($updatedPost);
    }

    public function destroy(Post $post, DeletePostAction $action): PostResource
    {
        $this->authorize('delete', $post);

        $action->execute($post);

        return new PostResource($post);
    }

    public function userPosts(User $user, GetUserPostsAction $action): ResourceCollection
    {
        $posts = $action->getAllByUserId($user->id);

        return PostResource::collection($posts);
    }

    public function userActivePosts(User $user, GetUserPostsAction $action): ResourceCollection
    {
        $posts = $action->getActiveByUserId($user->id);

        return PostResource::collection($posts);
    }

    public function myPosts(Request $request, GetUserPostsAction $action): ResourceCollection
    {
        $posts = $action->getAllByUserId($request->user()->id);

        return PostResource::collection($posts);
    }
}
