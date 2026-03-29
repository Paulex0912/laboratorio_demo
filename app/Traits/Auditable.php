<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function ($model) {
            self::logAction($model, 'created');
        });

        static::updated(function ($model) {
            self::logAction($model, 'updated');
        });

        static::deleted(function ($model) {
            self::logAction($model, 'deleted');
        });
    }

    protected static function logAction($model, $action)
    {
        $oldValues = [];
        $newValues = [];

        if ($action === 'created') {
            $newValues = $model->getAttributes();
        }
        elseif ($action === 'updated') {
            $newValues = $model->getDirty();
            foreach ($newValues as $key => $value) {
                $oldValues[$key] = $model->getOriginal($key);
            }
        }
        elseif ($action === 'deleted') {
            $oldValues = $model->getAttributes();
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'model_type' => get_class($model),
            'model_id' => $model->getKey(),
            'action' => $action,
            'old_values' => empty($oldValues) ? null : json_encode($oldValues),
            'new_values' => empty($newValues) ? null : json_encode($newValues),
            'ip_address' => request()->ip(),
        ]);
    }
}
