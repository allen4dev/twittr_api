<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

use App\Http\Resources\ReplyResource;

use App\Tweet;

class ReplyCollection extends ResourceCollection
{
    protected $tweet;

    public function __construct($resource, Tweet $tweet = null)
    {
        $this->tweet = $tweet;

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
            'data' => ReplyResource::collection($this->collection),
        ];
    }

    public function with($request)
    {
        return [
            'links' => [ 'self' => route('tweets.replies', [ 'tweet' => $this->tweet->id ]) ]
        ];
    }
}
