<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentMeetingResource extends JsonResource
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
        'meeting_id' => $this->meeting_id,
        'comment' => $this->comment,
        'user' => $this->whenLoaded('user', function () {
            return [
                'id'    => $this->user->id,
                'name'  => $this->user->full_name,
                'image' => $this->user->image,
            ];
        }),

    ];
}

}
