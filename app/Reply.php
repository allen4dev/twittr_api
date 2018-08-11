<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $fillable = [ 'body', 'user_id', 'tweet_id' ];

    protected static function boot()
    {
        if (auth()->guest()) return;

        static::created(function ($model) {
            $model
                ->activity()
                ->create([
                    'user_id' => auth()->id(),
                    'type'    => 'created_' . strtolower((new \ReflectionClass($model))->getShortName()),
                ]);
        });
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject');
    }
}
