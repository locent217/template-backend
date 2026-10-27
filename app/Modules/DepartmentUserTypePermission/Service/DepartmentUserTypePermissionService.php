<?php

namespace App\Modules\DepartmentUserTypePermission\Service;

use App\Modules\DepartmentUserTypePermission\Repository\DepartmentUserTypePermissionRepositoryInterface;

class DepartmentUserTypePermissionService
{
    public function __construct(
        protected DepartmentUserTypePermissionRepositoryInterface $modelRepository
    ) {}

    public function all(): array
    {
        return $this->modelRepository->all()->toArray();
    }

    public function getById(string $id)
    {
        return $this->modelRepository->getById($id, ['department:id,name', 'userType:id,name', 'permission:id,name,code,module_id', 'permission.module:id,name']) ?? abort(404, 'Record not found.');
    }

    public function create(array $data)
    {
        return $this->modelRepository->create($data)->load(['department:id,name', 'userType:id,name', 'permission:id,name,code,module_id', 'permission.module:id,name']);
    }

    /**
     * Create multiple permission assignments for one department/user type.
     * Existing assignments are skipped, not duplicated.
     */
    public function createMany(array $data): array
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($data): array {
            $created = [];
            $skipped = [];

            foreach ($data['permission_ids'] as $permissionId) {
                $record = \App\Models\DepartmentUserTypePermission::query()->firstOrCreate([
                    'department_id' => $data['department_id'],
                    'user_type_id' => $data['user_type_id'],
                    'permission_id' => $permissionId,
                ]);

                if ($record->wasRecentlyCreated) {
                    $created[] = $record->load(['department:id,name', 'userType:id,name', 'permission:id,name,code,module_id', 'permission.module:id,name']);
                } else {
                    $skipped[] = $permissionId;
                }
            }

            return [
                'created' => $created,
                'created_count' => count($created),
                'skipped_permission_ids' => $skipped,
                'skipped_count' => count($skipped),
            ];
        });
    }

    public function update(string $id, array $data)
    {
        return $this->modelRepository->update($id, $data)->load(['department:id,name', 'userType:id,name', 'permission:id,name,code,module_id', 'permission.module:id,name']);
    }

    public function delete(string $id)
    {
        return $this->modelRepository->delete($id);
    }
}
