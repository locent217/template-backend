<?php

namespace App\Http\Controllers;

use App\Http\Requests\Island\AddIslandRequest;
use App\Http\Requests\Island\UpdateIslandRequest;
use App\Modules\Island\Service\IslandService;
use Illuminate\Http\JsonResponse;

class IslandController extends Controller
{
    public function __construct(
        protected IslandService $service
    ) {}

    public function index(): JsonResponse
    {
        return response()->json(['data' => $this->service->all()]);
    }

    public function getById(string $id): JsonResponse
    {
        return response()->json(['data' => $this->service->getById($id)]);
    }

    public function add(AddIslandRequest $request): JsonResponse
    {
        return response()->json([
            'message' => 'Island created successfully.',
            'data' => $this->service->create($request->validated()),
        ], 201);
    }

    public function update(UpdateIslandRequest $request, string $id): JsonResponse
    {
        return response()->json([
            'message' => 'Island updated successfully.',
            'data' => $this->service->update($id, $request->validated()),
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->service->delete($id);

        return response()->json(['message' => 'Island deleted successfully.']);
    }
}
