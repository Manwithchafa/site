<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Audit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class AuditableObserver
{
    public function created(Model $model)
    {
        $this->record('created', $model, null, $model->getAttributes());
    }

    public function updated(Model $model)
    {
        $this->record('updated', $model, $model->getOriginal(), $model->getChanges());
    }

    public function deleted(Model $model)
    {
        $this->record('deleted', $model, $model->getOriginal(), null);
    }

    protected function record(string $event, Model $model, $old = null, $new = null): void
    {
        $user = Auth::user();

        // Audits (detailed)
        try {
            Audit::create([
                'user_id' => $user->id ?? null,
                'auditable_type' => get_class($model),
                'auditable_id' => $model->getKey(),
                'event' => $event,
                'old_values' => $old ? Arr::except($old, ['password']) : null,
                'new_values' => $new ? Arr::except($new, ['password']) : null,
                'ip' => request()?->ip(),
            ]);
        } catch (\Throwable $e) {
            report($e);
        }

        // Activities (user friendly)
        try {
            Activity::create([
                'user_id' => $user->id ?? null,
                'event' => $event,
                'subject_type' => get_class($model),
                'subject_id' => $model->getKey(),
                'properties' => $new ?? null,
                'ip' => request()?->ip(),
                'user_agent' => request()?->userAgent(),
            ]);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
