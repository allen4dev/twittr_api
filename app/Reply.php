<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\RecordActivity;

class Reply extends Model
{
    use RecordActivity;

    protected $fillable = [ 'body', 'user_id', 'tweet_id' ];
}
