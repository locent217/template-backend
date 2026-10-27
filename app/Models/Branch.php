<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasUuids;
    protected $fillable = ['name', 'code', 'address', 'active', 'region_id', 'region_id'];

    protected function casts(): array
    {
        return [
            'active' => 'boolean'
        ];
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'branch_id');
    }
}
