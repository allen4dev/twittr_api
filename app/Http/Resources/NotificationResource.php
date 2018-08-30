<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'type' => 'notifications',
            'id'   => $this->id,
            'attributes' => [
                'message' => $this->data['message'],
                'additional' => [
                    'content' => $this->data['additional']['content'],
                    'sender_avatar' => $this->data['additional']['sender_avatar'],
                    'sender_username' => $this->data['additional']['sender_username'],
                ],
                'action' => explode('\\', $this->resource->type)[2],
                'created_at' => (string) $this->created_at,
                'updated_at' => (string) $this->updated_at,
                'read_for_humans' => $this->created_at->diffForHumans(),
            ],
            'relationships' => [
                'type' => 'users',
                'id'   => $this->notifiable_id,
            ],
            'links' => [
                'self' => route('notifications.show', [ 'notification' => $this->id ]),
            ]
        ];
    }
}
