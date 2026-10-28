<?php
namespace App\Modules\AuditLog\Repository;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface AuditLogRepositoryInterface
{
    public function paginate(array $filters): LengthAwarePaginator;
    public function find(string $id): Model;
    public function actors(string $search): Collection;
}
