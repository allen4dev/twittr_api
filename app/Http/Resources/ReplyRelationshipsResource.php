<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\TweetIdentifierResource;

class ReplyRelationshipsResource extends JsonResource
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
            "tweet" => [
                "links" => [
                    "related" => route('tweets.show', [ 'id' => $this->id ])
                ],
                "data" => new TweetIdentifierResource($this),
            ]
        ];
    }
}
