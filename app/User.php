<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

use App\Events\UserFollowed;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'avatar_url',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getTweetsCountAttribute()
    {
        return $this->tweets()->count();
    }

    public function getFollowersCountAttribute()
    {
        return $this->followers()->count();
    }

    public function getFollowingsCountAttribute()
    {
        return $this->followings()->count();
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
 
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function path()
    {
        return '/api/users/' . $this->id;
    }

    public function tweets()
    {
        return $this->hasMany(Tweet::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function followers()
    {
        return $this->belongsTomany(User::class, 'followers', 'following_id', 'follower_id');
    }

    public function followings()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'following_id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function retweets()
    {
        /**
         * !Potential Many to Many to get all users who retweet the tweet
         */
        return $this->hasMany(Retweet::class);
    }

    public function publishTweet($request)
    {
        return $this->tweets()->create([ 'body' => $request->body ]);
    }

    public function follow()
    {
        $attributes = [ 'follower_id' => auth()->id() ];

        if (! $this->isFollowed($attributes)) {
            $this->followers()->attach(auth()->id());
        }
    }

    public function unfollow()
    {
        $attributes = [ 'follower_id' => auth()->id() ];

        if ($this->isFollowed($attributes)) {
            $this->followers()->detach(auth()->id());
        }
    }
    
    public function isFollowed($attributes)
    {
        return $this->followers()->where($attributes)->exists(); 
    }
}
