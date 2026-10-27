<?php

namespace App\Http\Controllers;

use App\Http\Requests\Region\AddRegionRequest;
use App\Http\Requests\Region\UpdateRegionRequest;
use App\Modules\Region\Service\RegionService;
use Illuminate\Http\JsonResponse;

class RegionController extends Controller
{
    public function __construct(
        protected RegionService $service
    ) {}

    public function index(): JsonResponse
    {
        return response()->json(['data' => $this->service->all()]);
    }

    public function getById(string $id): JsonResponse
    {
        return response()->json(['data' => $this->service->getById($id)]);
    }

    public function add(AddRegionRequest $request): JsonResponse
    {
        return response()->json([
            'message' => 'Region created successfully.',
            'data' => $this->service->create($request->validated()),
        ], 201);
    }

    public function update(UpdateRegionRequest $request, string $id): JsonResponse
    {
        return response()->json([
            'message' => 'Region updated successfully.',
            'data' => $this->service->update($id, $request->validated()),
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->service->delete($id);

        return response()->json(['message' => 'Region deleted successfully.']);
    }
}
