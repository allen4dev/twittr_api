<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

use App\Http\Resources\PhotoResource;
use App\Http\Resources\UserResource;

use App\User;

class PhotoCollection extends ResourceCollection
{
    public $user;

    public function __construct($resource, User $user = null)
    {
        $this->user = $user;

        parent::__construct($resource);
    }
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => PhotoResource::collection($this->collection),
        ];
    }

    public function with($request)
    {
        return [
            'links' => [ 'self' => route('users.photos', [ 'user' => $this->user->id ]) ],
            'included' => new UserResource($this->user),
        ];
    }
}
