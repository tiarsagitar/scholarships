<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
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
            'user_id' => $this->user_id,
            'scholarship_id' => $this->scholarship_id,
            'personal_statement' => $this->personal_statement,
            'status' => $this->status,
            'submitted_at' => $this->submitted_at,
            'scholarship' => new ScholarshipResource($this->whenLoaded('scholarship')),
            'user' => new UserResource($this->whenLoaded('user')),
            'reviewer' => new UserResource($this->whenLoaded('reviewer')),
            'reviewed_at' => $this->reviewed_at,
            'reviewer_comments' => $this->reviewer_comments,
            'application_documents' => ApplicationDocumentResource::collection($this->whenLoaded('applicationDocuments'))
        ];
    }
}
