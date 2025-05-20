<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->logActivity('created');
        });

        static::updated(function ($model) {
            $model->logActivity('updated');
        });

        static::deleted(function ($model) {
            $model->logActivity('deleted');
        });
    }

    public function logActivity(string $event)
    {
        ActivityLog::create([
            'description' => $this->getActivityDescription($event),
            'subject_id' => $this->id,
            'subject_type' => get_class($this),
            'causer_id' => auth()->id() ?? null,
        ]);
    }

    abstract public function getActivityDescription(string $event): string;
}
