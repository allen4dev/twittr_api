<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

use App\Http\Resources\NotificationResource;

class NotificationCollection extends ResourceCollection
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
            'data' => NotificationResource::collection($this->collection),
        ];
    }

    public function with($request)
    {
        return [
            'links' => [ 'self' => route('notifications.unread') ],
        ];
    }
}
