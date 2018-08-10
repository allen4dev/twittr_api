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
        'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

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

    public function followings()
    {
        return $this->hasMany(Following::class);
    }

    public function followers()
    {
        return $this->hasMany(Follower::class);
    }

    public function publishTweet($request)
    {
        return $this->tweets()->create([ 'body' => $request->body ]);
    }

    public function follow()
    {
        $attributes = [ 'follower_id' => auth()->id() ];

        if (! $this->isFollowed($attributes)) {
            event(new UserFollowed( auth()->user(), $this ));
        }
    }

    public function isFollowed($attributes)
    {
        return $this->followers()->where($attributes)->exists(); 
    }
}
