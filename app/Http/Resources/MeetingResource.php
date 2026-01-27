<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeetingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'subject' => $this->subject,
            'date' => $this->date,
            'duration' => gmdate('H:i', strtotime($this->end_time) - strtotime($this->start_time)),
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'note' => $this->note,
            'users' => $this->whenLoaded('users', function () {
    return $this->users->map(function ($user) {
        return [
            'id'   => $user->id,
            'name' => $user->full_name,
            'image' => $user->image,
        ];
    });
}),

            
        ];
    }
}
