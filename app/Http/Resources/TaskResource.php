<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'end_date' => $this->end_date,
            'priority' => $this->priority,
            'status' => $this->status,


            // 'project'  => new ProjectResource($this->whenLoaded('project')),
            // 'comments' => CommentResource::collection($this->whenLoaded('comments')),
        ];
    }
}
