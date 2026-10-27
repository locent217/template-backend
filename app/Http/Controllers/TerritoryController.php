<?php

namespace App\Http\Controllers;

use App\Http\Requests\Territory\AddTerritoryRequest;
use App\Http\Requests\Territory\UpdateTerritoryRequest;
use App\Modules\Territory\Service\TerritoryService;
use Illuminate\Http\JsonResponse;

class TerritoryController extends Controller
{
    public function __construct(
        protected TerritoryService $service
    ) {}

    public function index(): JsonResponse
    {
        return response()->json(['data' => $this->service->all()]);
    }

    public function getById(string $id): JsonResponse
    {
        return response()->json(['data' => $this->service->getById($id)]);
    }

    public function add(AddTerritoryRequest $request): JsonResponse
    {
        return response()->json([
            'message' => 'Territory created successfully.',
            'data' => $this->service->create($request->validated()),
        ], 201);
    }

    public function update(UpdateTerritoryRequest $request, string $id): JsonResponse
    {
        return response()->json([
            'message' => 'Territory updated successfully.',
            'data' => $this->service->update($id, $request->validated()),
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->service->delete($id);

        return response()->json(['message' => 'Territory deleted successfully.']);
    }
}
