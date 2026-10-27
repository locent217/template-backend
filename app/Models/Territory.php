<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Territory extends Model
{
    use HasUuids;

    protected $fillable = ['name', 'code', 'active', 'island_id'];

    protected function casts(): array
    {
        return [
            'active' => 'boolean'
        ];
    }

    public function users()
    {
        return $this->hasMany(User::class, 'territory_id');
    }

    public function island()
    {
        return $this->belongsTo(Island::class, 'island_id');
    }

    public function regions()
    {
        return $this->hasMany(Region::class, 'territory_id');
    }
}
