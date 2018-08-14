<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\RecordActivity;
use App\Traits\Favoritable;

class Tweet extends Model
{
    use Favoritable;
    use RecordActivity;

    protected $fillable = [ 'body', 'user_id' ];
    // protected $with = ['user'];

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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
