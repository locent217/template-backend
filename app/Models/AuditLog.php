<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AuditLog extends Model
{
    use HasUuids;
    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public $timestamps = false;
    protected $guarded = [];
    protected $casts = ['old_values' => 'array', 'new_values' => 'array', 'context' => 'array', 'created_at' => 'datetime'];
}
