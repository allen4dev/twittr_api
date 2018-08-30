<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use Illuminate\Support\Collection;

use App\Http\Transformers\IncludeTransformer;

use App\Http\Resources\TweetResource;

use App\Tweet;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'type' => 'users',
            'id'   => (string) $this->id,
            'attributes' => [
                'username'      => $this->username,
                'email'         => $this->email,
                'avatar_url'    => $this->avatar_url,
                'fullname'      => $this->fullname,
                'profile_image' => $this->profile_image,
                'contact_info'  => $this->contact_info,
            ],
            'links' => [
                'self' => route('users.show', ['user' => $this->id]),
            ]
        ];
    }

    public function with($request)
    {
        if (! $request->include) return [];

        $includes = IncludeTransformer::includeData($this->resource, $request->include);
        
        return [
            'included' => $this->withIncluded($includes->unique()),
        ];
    }

    public function withIncluded(Collection $included)
    {
        return $included->map(function ($include) {
            if ($include instanceof Tweet) {
                return new TweetResource($include);
            }
        });
    }
}
