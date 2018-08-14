<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
                'username' => $this->username,
                'email' => $this->email,
                'fullname' => $this->fullname,
                'profile_image' => $this->profile_image,
                'contact_info' => $this->contact_info,
            ],
            'links' => [
                'self' => route('users.show', ['user' => $this->id]),
            ]
        ];
    }
}
