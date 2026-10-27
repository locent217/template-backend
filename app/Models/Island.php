<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Island extends Model
{
    use HasUuids;

    protected $fillable = ['name', 'code', 'active'];

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

    public function territories()
    {
        return $this->hasMany(Territory::class, 'territory_id');
    }
}
