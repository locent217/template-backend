<?php

namespace App\Http\Controllers;

use App\Http\Requests\Branch\AddBranchRequest;
use App\Http\Requests\Branch\UpdateBranchRequest;
use App\Modules\Branch\Service\BranchService;
use Illuminate\Http\JsonResponse;

class BranchController extends Controller
{
    public function __construct(
        protected BranchService $service
    ) {}

    public function index(): JsonResponse
    {
        return response()->json(['data' => $this->service->all()]);
    }

    public function getById(string $id): JsonResponse
    {
        return response()->json(['data' => $this->service->getById($id)]);
    }

    public function add(AddBranchRequest $request): JsonResponse
    {
        return response()->json([
            'message' => 'Branch created successfully.',
            'data' => $this->service->create($request->validated()),
        ], 201);
    }

    public function update(UpdateBranchRequest $request, string $id): JsonResponse
    {
        return response()->json([
            'message' => 'Branch updated successfully.',
            'data' => $this->service->update($id, $request->validated()),
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->service->delete($id);

        return response()->json(['message' => 'Branch deleted successfully.']);
    }
}
