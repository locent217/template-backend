<?php
namespace App\Modules\AuditLog\Service;

use App\Modules\AuditLog\Repository\AuditLogRepositoryInterface;

class AuditLogService
{
    public function __construct(private AuditLogRepositoryInterface $repository) {}
    public function list(array $filters) { return $this->repository->paginate($filters); }
    public function get(string $id) { return $this->repository->find($id); }
    public function actors(string $search) { return $this->repository->actors(trim($search)); }
}
