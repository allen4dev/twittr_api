<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\UserIdentifierResource;

class PhotoRelationshipsResource extends JsonResource
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
            "user" => [
                "links" => [
                    "related" => route('users.show', [ 'id' => $this->id ])
                ],
                "data" => new UserIdentifierResource($this),
            ]
        ];
    }
}
