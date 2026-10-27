<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentUserTypePermission\AddDepartmentUserTypePermissionRequest;
use App\Http\Requests\DepartmentUserTypePermission\UpdateDepartmentUserTypePermissionRequest;
use App\Modules\DepartmentUserTypePermission\Service\DepartmentUserTypePermissionService;
use Illuminate\Http\JsonResponse;

class DepartmentUserTypePermissionController extends Controller
{
    public function __construct(
        protected DepartmentUserTypePermissionService $service
    ) {}

    public function index(): JsonResponse
    {
        return response()->json(['data' => $this->service->all()]);
    }

    public function getById(string $id): JsonResponse
    {
        return response()->json(['data' => $this->service->getById($id)]);
    }

    public function add(AddDepartmentUserTypePermissionRequest $request): JsonResponse
    {
        return response()->json([
            'message' => 'Default permissions processed successfully (existing assignments skipped).',
            'data' => $this->service->createMany($request->validated()),
        ], 201);
    }

    public function update(UpdateDepartmentUserTypePermissionRequest $request, string $id): JsonResponse
    {
        return response()->json([
            'message' => 'DepartmentUserTypePermission updated successfully.',
            'data' => $this->service->update($id, $request->validated()),
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->service->delete($id);

        return response()->json(['message' => 'DepartmentUserTypePermission deleted successfully.']);
    }
}
