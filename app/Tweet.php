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
}
