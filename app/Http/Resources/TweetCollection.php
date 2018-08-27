<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

use App\Http\Resources\TweetResource;

use App\User;

class TweetCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => TweetResource::collection($this->collection),
        ];
    }

    public function with($request)
    {
        $users = $this->collection->map(function ($tweet) {
            return $tweet->user;
        });

        // Merge more collections to include
        // Ex: $users->merge($comments)->unique();
        $includes = $users->unique();

        return [
            'included' => $this->withIncluded($includes),
        ];
    }

    public function withIncluded(Collection $included)
    {
        return $included->map(function ($include) {
            if ($include instanceof User) {
                return new UserResource($include);
            }
            // more includes
        });
    }
}
