<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasUuids;

    protected $fillable = ['name', 'code', 'active', 'description', 'category'];

    protected function casts(): array
    {
        return [
            'active' => 'boolean'
        ];
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'module_id');
    }
}
