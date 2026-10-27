<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserType\AddUserTypeRequest;
use App\Http\Requests\UserType\UpdateUserTypeRequest;
use App\Modules\UserType\Service\UserTypeService;
use Illuminate\Http\JsonResponse;

class UserTypeController extends Controller
{
    public function __construct(
        protected UserTypeService $service
    ) {}

    public function index(): JsonResponse
    {
        return response()->json(['data' => $this->service->all()]);
    }

    public function getById(string $id): JsonResponse
    {
        return response()->json(['data' => $this->service->getById($id)]);
    }

    public function add(AddUserTypeRequest $request): JsonResponse
    {
        return response()->json([
            'message' => 'UserType created successfully.',
            'data' => $this->service->create($request->validated()),
        ], 201);
    }

    public function update(UpdateUserTypeRequest $request, string $id): JsonResponse
    {
        return response()->json([
            'message' => 'UserType updated successfully.',
            'data' => $this->service->update($id, $request->validated()),
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->service->delete($id);

        return response()->json(['message' => 'UserType deleted successfully.']);
    }
}
