<?php

namespace App\Http\Controllers;

use App\Actions\Auth\LoginAction;
use App\Actions\Auth\LogoutAction;
use App\Actions\Auth\RegisterAction;
use App\DTOs\Auth\LoginDTO;
use App\DTOs\Auth\RegisterDTO;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(RegisterRequest $request, RegisterAction $action): UserResource
    {
        $dto = RegisterDTO::fromRequest($request);
        $result = $action->execute($dto);

        return new UserResource($result['user']);
    }

    public function login(LoginRequest $request, LoginAction $action): JsonResponse
    {
        $dto = LoginDTO::fromRequest($request);
        $result = $action->execute($dto);

        return response()->json(['token' => $result['token']], 200);
    }

    public function logout(Request $request, LogoutAction $action): UserResource
    {
        $action->execute($request->user());

        return new UserResource($request->user());
    }
}
