<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

use App\Http\Resources\TweetRelationshipResource;

use App\Http\Resources\ReplyResource;
use App\Http\Resources\UserResource;

use App\Reply;
use App\User;

class TweetResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'type' => 'tweets',
            'id'   => (string) $this->id,
            'attributes' => [
                'body' => $this->body,
            ],
            'links' => [
                'self' => route('tweets.show', [ 'id' => $this->id ]),
            ],
            'relationships' => new TweetRelationshipResource($this),
        ];
    }

    public function with($request)
    {
        if (! $request->include) return [];

        $includes = collect();
        
        // ! Refactor
        if ($this->resource->relationLoaded('user'))
        {
            $user = $this->resource->user;
            
            $includes->push($user);
        }

        if ($this->resource->relationLoaded('replies'))
        {
            $replies = $this->resource->replies;
            $includes = $includes->merge($replies);
        }
        
        return [
            'included' => $this->withIncluded($includes->unique()),
        ];
    }

    public function withIncluded(Collection $included)
    {
        return $included->map(function ($include) {
            if ($include instanceof User) {
                return new UserResource($include);
            }
            
            if ($include instanceof Reply) {
                return new ReplyResource($include);
            }
        });
    }
}
