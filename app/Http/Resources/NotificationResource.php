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
                'additional_information' => $this->data['additional'],
                'subject' => 'FollowedUser',
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
