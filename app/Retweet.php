<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Retweet extends Model
{
    protected $fillable = [ 'tweet_id' ];

    public function tweet()
    {
        return $this->belongsTo(Tweet::class);
    }
}
