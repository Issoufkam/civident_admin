<?php

namespace App\Traits;

use App\Models\Activity;

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            Activity::create([
                'description' => $model->getActivityDescription('created'),
                'subject_id' => $model->id,
                'subject_type' => get_class($model),
                'causer_id' => auth()->id(),
                'causer_type' => auth()->user() ? get_class(auth()->user()) : null
            ]);
        });

        // Ajouter les mêmes pour updated/deleted
    }

    abstract protected function getActivityDescription($event);
}
