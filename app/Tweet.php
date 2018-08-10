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
        $this->favorites()->create([
            'user_id' => auth()->id(),
        ]);

        return $this;
    }
}
