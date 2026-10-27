<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasUuids;

    protected $fillable = ['name', 'code', 'module_id'];

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }
}
