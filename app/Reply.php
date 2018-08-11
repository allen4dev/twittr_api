<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\RecordActivity;

class Reply extends Model
{
    use RecordActivity;

    protected $fillable = [ 'body', 'user_id', 'tweet_id' ];

    public function path()
    {
        return '/api/replies/' . $this->id;
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
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
