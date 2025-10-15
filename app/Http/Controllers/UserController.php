<?php

namespace App\Http\Controllers;

use App\Actions\User\DeleteUserAction;
use App\Actions\User\UpdateUserAction;
use App\DTOs\User\UpdateUserDTO;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(Request $request): UserResource
    {
        return new UserResource($request->user());
    }

    public function update(UpdateUserRequest $request, UpdateUserAction $action): UserResource
    {
        $dto = UpdateUserDTO::fromRequest($request);
        $user = $action->execute($request->user(), $dto);

        return new UserResource($user);
    }

    public function destroy(Request $request, DeleteUserAction $action): UserResource
    {
        $user = $request->user();
        $action->execute($user);

        return new UserResource($user);
    }
}
