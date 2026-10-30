<?php

namespace App\Modules\User\Service;

use App\Modules\User\Repository\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use App\Models\UserType;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $repository
    ) {}

    public function getAll()
    {
        return $this->repository->all();
    }

    public function getById(string $id)
    {
        return $this->repository->getById($id, ['department:id,name', 'userType:id,name', 'island:id,name', 'territory:id,name,island_id', 'territory.island:id,name', 'region:id,name,territory_id', 'region.territory:id,name,island_id', 'region.territory.island:id,name', 'branch:id,name,region_id', 'branch.region:id,name,territory_id', 'branch.region.territory:id,name,island_id', 'branch.region.territory.island:id,name']);
    }

    public function create(array $data)
    {
        $data = $this->normalizeClientAssignments($data);
        $record = $this->repository->create($data)->load(['department:id,name', 'userType:id,name', 'island:id,name', 'territory:id,name,island_id', 'territory.island:id,name', 'region:id,name,territory_id', 'region.territory:id,name,island_id', 'region.territory.island:id,name', 'branch:id,name,region_id', 'branch.region:id,name,territory_id', 'branch.region.territory:id,name,island_id', 'branch.region.territory.island:id,name']);
        app(\App\Support\Audit\AuditRecorder::class)->record('users.create', $record, [], [], [
            'service_class' => static::class, 'service_method' => __FUNCTION__,
            'service_file' => 'app/Modules/User/Service/UserService.php',
        ]);
        // The initial password is provided by the creating administrator.
        // Dispatch only after a successful transaction commit, never on rollback.
        $temporaryPassword = $data['password'];
        \Illuminate\Support\Facades\DB::afterCommit(function () use ($record, $temporaryPassword) {
            app(\App\Modules\Notification\Service\NotificationService::class)
                ->notifyNewUser($record, $temporaryPassword);
        });
        return $record;
    }

    public function update(string $id, array $data)
    {
        $data = $this->normalizeClientAssignments($data);
        $record = $this->repository->update($id, $data)->load(['department:id,name', 'userType:id,name', 'island:id,name', 'territory:id,name,island_id', 'territory.island:id,name', 'region:id,name,territory_id', 'region.territory:id,name,island_id', 'region.territory.island:id,name', 'branch:id,name,region_id', 'branch.region:id,name,territory_id', 'branch.region.territory:id,name,island_id', 'branch.region.territory.island:id,name']);
        app(\App\Support\Audit\AuditRecorder::class)->record('users.update', $record, [], [], [
            'service_class' => static::class, 'service_method' => __FUNCTION__,
            'service_file' => 'app/Modules/User/Service/UserService.php',
        ]);
        return $record;
    }

    public function delete(string $id)
    {
        return $this->repository->delete($id);
    }

    private function normalizeClientAssignments(array $data): array
    {
        $isClient = UserType::query()
            ->whereKey($data['user_type_id'] ?? null)
            ->where('code', 'CLIENT')
            ->exists();

        if ($isClient) {
            foreach (['department_id', 'branch_id', 'region_id', 'territory_id', 'island_id'] as $field) {
                $data[$field] = null;
            }
        }

        return $data;
    }

}
