<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    protected $fillable = [ 'body', 'user_id' ];

    public function path()
    {
        return '/api/tweets/' . $this->id;
    }
}
