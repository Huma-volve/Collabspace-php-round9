<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeetingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'subject'    => $this->subject,
               'date' => Carbon::parse($this->date)->format('Y-m-d'),

    'start_time' => Carbon::parse($this->start_time)->format('H:i'),
        'end_time'   => $this->calculateEndTime(),
            'duration_minutes' => $this->duration,
            'note' => $this->note,
            'zoom' => [
    'meeting_id' => $this->zoom_meeting_id,
    'join_url'   => $this->join_url,
],


            'users' => $this->whenLoaded('users', function () {
                return $this->users->map(fn ($user) => [
                    'id'    => $user->id,
                    'name'  => $user->full_name,
                    'image' => $user->image,
                ]);
            }),
        ];
    }
}
