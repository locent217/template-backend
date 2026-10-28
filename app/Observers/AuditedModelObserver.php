<?php
namespace App\Observers;

use App\Support\Audit\AuditRecorder;
use Illuminate\Database\Eloquent\Model;

class AuditedModelObserver
{
    public bool $afterCommit = true;
    public function __construct(private AuditRecorder $audit) {}
    public function created(Model $model): void { $this->audit->record('model.created', $model, [], $model->getAttributes()); }
    public function updated(Model $model): void {
        $changes = $model->getChanges();
        unset($changes['updated_at']);
        if (!$changes) return;
        $previous = $model->getPrevious();
        $old = [];
        foreach (array_keys($changes) as $key) $old[$key] = $previous[$key] ?? null;
        $this->audit->record('model.updated', $model, $old, $changes);
    }
    public function deleted(Model $model): void { $this->audit->record('model.deleted', $model, $model->getAttributes()); }
}
