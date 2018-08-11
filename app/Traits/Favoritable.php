<?php

namespace App\Traits;

use App\Favorite;

trait Favoritable {

  public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    public function favorite()
    {
        $attributes = [ 'user_id' => auth()->id(), 'type' => $this->getFavoritedType() ];

        if (! $this->isFavorited($attributes)) {
            $this->favorites()->create($attributes);
        }

        return $this;
    }

    public function unfavorite()
    {
        $attributes = [ 'user_id' => auth()->id(), 'type' => $this->getFavoritedType() ];

        if ($this->isFavorited($attributes)) {
            $this->favorites()->where($attributes)->delete();
        }

        return $this;
    }

    public function isFavorited($attributes)
    {
        return $this->favorites()->where($attributes)->exists();
    }

    public function getFavoritedType()
    {
        return strtolower((new \ReflectionClass($this))->getShortName());
    }
}