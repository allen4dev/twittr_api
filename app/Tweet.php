<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    protected $fillable = [ 'body', 'user_id' ];

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    public function path()
    {
        return '/api/tweets/' . $this->id;
    }

    public function addReply($body)
    {
        return $this->replies()->create([
            'body' => $body,
            'user_id' => auth()->id(),
        ]);
    }

    public function favorite()
    {
        $attributes = [ 'user_id' => auth()->id() ];

        if (! $this->isFavorited($attributes)) {
            $this->favorites()->create($attributes);
        }

        return $this;
    }

    public function unfavorite()
    {
        $attributes = [ 'user_id' => auth()->id() ];

        if ($this->isFavorited($attributes)) {
            $this->favorites()->where($attributes)->delete();
        }

        return $this;
    }

    public function isFavorited($attributes)
    {
        return $this->favorites()->where($attributes)->exists();
    }
}
