<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    use HasUuids;

    protected $fillable = ['user_id', 'permission_id', 'allowed'];

    protected function casts(): array
    {
        return [
            'allowed' => 'boolean'
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }
}
