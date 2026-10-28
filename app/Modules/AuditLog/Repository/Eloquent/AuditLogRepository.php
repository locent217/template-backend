<?php
namespace App\Modules\AuditLog\Repository\Eloquent;

use App\Models\AuditLog;
use App\Models\User;
use App\Modules\AuditLog\Repository\AuditLogRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;

class AuditLogRepository implements AuditLogRepositoryInterface
{
    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = AuditLog::query()->with('actor:id,first_name,middle_name,last_name,username')
            ->orderByDesc('created_at')->orderByDesc('id');
        foreach (['request_id', 'actor_id', 'event', 'ip_address'] as $field) {
            if (!empty($filters[$field])) $query->where($field, $filters[$field]);
        }
        if (!empty($filters['actor_search'])) {
            $term = addcslashes($filters['actor_search'], '%_\\');
            $query->where(function ($q) use ($term) {
                $q->where('actor_name', 'like', "%{$term}%")
                    ->orWhere('actor_username', 'like', "%{$term}%")
                    ->orWhereHas('actor', function ($users) use ($term) {
                        $users->where('first_name', 'like', "%{$term}%")
                            ->orWhere('last_name', 'like', "%{$term}%")
                            ->orWhere('username', 'like', "%{$term}%");
                    });
            });
        }
        if (!empty($filters['from'])) $query->where('created_at', '>=', $filters['from']);
        if (!empty($filters['to'])) $query->where('created_at', '<', Carbon::parse($filters['to'])->addDay()->toDateString());
        return $query->paginate($filters['per_page'] ?? 25);
    }

    public function find(string $id): Model
    {
        return AuditLog::query()->with('actor:id,first_name,middle_name,last_name,username')->findOrFail($id);
    }

    public function actors(string $search): Collection
    {
        $query = User::query()->select(['id', 'first_name', 'middle_name', 'last_name', 'username']);
        if ($search !== '') {
            $term = addcslashes($search, '%_\\');
            $query->where(function ($q) use ($term) {
                $q->where('first_name', 'like', "%{$term}%")
                    ->orWhere('last_name', 'like', "%{$term}%")
                    ->orWhere('username', 'like', "%{$term}%");
            });
        }
        return $query->orderBy('last_name')->limit(25)->get();
    }
}
