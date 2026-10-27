<?php

namespace App\Http\Controllers;

use App\Http\Requests\Department\AddDepartmentRequest;
use App\Http\Requests\Department\UpdateDepartmentRequest;
use App\Modules\Department\Service\DepartmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function __construct(
        protected DepartmentService $service
    ) {}

    public function index(): JsonResponse
    {
        return response()->json([
            'data' => $this->service->all(),
        ]);
    }

    public function getById(string $id): JsonResponse
    {
        return response()->json([
            'data' => $this->service->getById($id),
        ]);
    }

    public function add(AddDepartmentRequest $request): JsonResponse
    {
        return response()->json([
            'message' => 'Department created successfully.',
            'data' => $this->service->create($request->validated()),
        ], 201);
    }

    public function update(UpdateDepartmentRequest $request, string $id): JsonResponse
    {
        $department = $this->service->update(
            $id,
            $request->validated()
        );

        return response()->json([
            'message' => 'Department updated successfully.',
            'data' => $department,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->service->delete($id);

        return response()->json([
            'message' => 'Department deleted successfully.',
        ]);
    }
}
