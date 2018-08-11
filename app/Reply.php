<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\RecordActivity;
use App\Traits\Favoritable;

class Reply extends Model
{
    use RecordActivity;
    use Favoritable;

    protected $fillable = [ 'body', 'user_id', 'tweet_id' ];

    public function path()
    {
        return '/api/replies/' . $this->id;
    }
}
