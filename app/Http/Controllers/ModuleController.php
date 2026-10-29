<?php

namespace App\Http\Controllers;

use App\Http\Requests\Module\AddModuleRequest;
use App\Http\Requests\Module\UpdateModuleRequest;
use App\Modules\Module\Service\ModuleService;
use Illuminate\Http\JsonResponse;

class ModuleController extends Controller
{
    public function __construct(
        protected ModuleService $service
    ) {}

    public function categories(): JsonResponse
    {
        return response()->json(['data' => $this->service->getCategories()]);
    }

    public function index(): JsonResponse
    {
        return response()->json(['data' => $this->service->all()]);
    }

    public function getById(string $id): JsonResponse
    {
        return response()->json(['data' => $this->service->getById($id)]);
    }

    public function add(AddModuleRequest $request): JsonResponse
    {
        return response()->json([
            'message' => 'Module created successfully.',
            'data' => $this->service->create($request->validated()),
        ], 201);
    }

    public function update(UpdateModuleRequest $request, string $id): JsonResponse
    {
        return response()->json([
            'message' => 'Module updated successfully.',
            'data' => $this->service->update($id, $request->validated()),
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->service->delete($id);

        return response()->json(['message' => 'Module deleted successfully.']);
    }
}
