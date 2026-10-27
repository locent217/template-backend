<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\AddUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Modules\User\Service\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        protected UserService $service
    ) {}

    public function index(): JsonResponse
    {
        return response()->json([
            'data' => $this->service->getAll(),
        ]);
    }

    public function show(string $id): JsonResponse
    {
        return response()->json([
            'data' => $this->service->getById($id),
        ]);
    }

    public function add(AddUserRequest $request): JsonResponse
    {
        return response()->json([
            'message' => 'User created successfully.',
            'data' => $this->service->create(
                $request->validated()
            ),
        ], 201);
    }

    public function update(
        UpdateUserRequest $request,
        string $id
    ): JsonResponse {
        return response()->json([
            'message' => 'User updated successfully.',
            'data' => $this->service->update(
                $id,
                $request->validated()
            ),
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->service->delete($id);

        return response()->json([
            'message' => 'User deleted successfully.',
        ]);
    }
}
