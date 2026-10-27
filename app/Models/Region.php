<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasUuids;

    protected $fillable = ['name', 'code', 'active', 'territory_id'];

    protected function casts(): array
    {
        return [
            'active' => 'boolean'
        ];
    }

    public function users()
    {
        return $this->hasMany(User::class, 'island_id');
    }

    public function territory()
    {
        return $this->belongsTo(Territory::class, 'territory_id');
    }

    public function branches()
    {
        return $this->hasMany(Branch::class, 'region_id');
    }
}
