<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function ($model) {
            static::logActivity('created', $model);
        });

        static::updated(function ($model) {
            static::logActivity('updated', $model);
        });

        static::deleted(function ($model) {
            static::logActivity('deleted', $model);
        });
    }

    protected static function logActivity($action, $model)
    {
        if (!auth()->check()) {
            return;
        }

        $user = auth()->user();

        // Only log for non-superadmins
        if ($user->isSuperadmin()) {
            return;
        }

        $changes = null;

        if ($action === 'updated') {
            $changes = [
                'before' => array_intersect_key($model->getOriginal(), $model->getDirty()),
                'after' => $model->getDirty(),
            ];
        } elseif ($action === 'created') {
            $changes = ['after' => $model->getAttributes()];
        } elseif ($action === 'deleted') {
            $changes = ['before' => $model->getAttributes()];
        }

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->getKey(),
            'changes' => $changes,
        ]);
    }
}
