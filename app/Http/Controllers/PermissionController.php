<?php

namespace App\Http\Controllers;

use App\Http\Requests\Permission\AddPermissionRequest;
use App\Http\Requests\Permission\UpdatePermissionRequest;
use App\Modules\Permission\Service\PermissionService;
use Illuminate\Http\JsonResponse;

class PermissionController extends Controller
{
    public function __construct(
        protected PermissionService $service
    ) {}

    public function index(): JsonResponse
    {
        return response()->json(['data' => $this->service->all()]);
    }

    public function getById(string $id): JsonResponse
    {
        return response()->json(['data' => $this->service->getById($id)]);
    }

    public function add(AddPermissionRequest $request): JsonResponse
    {
        return response()->json([
            'message' => 'Permission created successfully.',
            'data' => $this->service->create($request->validated()),
        ], 201);
    }

    public function update(UpdatePermissionRequest $request, string $id): JsonResponse
    {
        return response()->json([
            'message' => 'Permission updated successfully.',
            'data' => $this->service->update($id, $request->validated()),
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->service->delete($id);

        return response()->json(['message' => 'Permission deleted successfully.']);
    }
}
