<?php

namespace App\Traits;

use App\Activity;

trait RecordActivity {

    protected static function bootRecordActivity()
    {
        if (auth()->guest()) return;

        foreach (static::getActivitiesToRecord() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }
    }

    public function recordActivity($event)
    {
        $this->activity()->create([
            'user_id' => auth()->id(),
            'action'  => $this->getActivityType($event),
        ]);
    }

    protected static function getActivitiesToRecord()
    {
        return ['created'];
    }
    
    public function getActivityType($event)
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());
        return "{$event}_{$type}";
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject');
    }
}