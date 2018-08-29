<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\ReplyRelationshipsResource;
use App\Http\Resources\TweetResource;

use App\Tweet;

class ReplyResource extends JsonResource
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
            'type' => 'replies',
            'id'   => (string) $this->id,
            'attributes' => [
                'body' => $this->body,
            ],
            'links' => [
                'self' => route('replies.show', [ 'reply' => $this->id ])
            ],
            'relationships' => new ReplyRelationshipsResource($this->tweet),
        ];
    }
}
