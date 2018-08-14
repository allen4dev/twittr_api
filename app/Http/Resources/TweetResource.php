<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TweetResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'type' => 'tweets',
            'id'   => (string) $this->id,
            'attributes' => [
                'body' => $this->body,
            ]
        ];
    }
}
