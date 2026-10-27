<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasUuids;
    protected $fillable = ['name', 'code', 'address', 'active'];

    protected function casts(): array
    {
        return [
            'active' => 'boolean'
        ];
    }

    public function users()
    {
        return $this->hasMany(User::class, 'branch_id');
    }
}
