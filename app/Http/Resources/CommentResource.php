<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => new UserResource($this->whenLoaded('user')),
            'commentable' => $this->when(
                $this->relationLoaded('commentable') && $this->commentable,
                function () {
                    return [
                        'id' => $this->commentable->id,
                        'type' => class_basename($this->commentable_type),
                    ];
                }
            ),
            'replies_count' => $this->when(
                $this->relationLoaded('replies') && $this->replies,
                fn() => $this->replies->count()
            ),
            'replies' => CommentResource::collection($this->whenLoaded('allReplies')),
        ];
    }
}
