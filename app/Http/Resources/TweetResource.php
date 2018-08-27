<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\TweetRelationshipResource;

use App\Http\Resources\UserResource;

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
                'related' => route('tweets.show', [ 'id' => $this->id ]),
            ],
            'relationships' => new TweetRelationshipResource($this->user)
        ];
    }

    public function with($request)
    {
        return [
            'included' => new UserResource($this->resource->user),
        ];
    }
}
