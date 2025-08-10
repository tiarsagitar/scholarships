<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DisbursementResource extends JsonResource
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
            'disbursement_schedule_id' => $this->disbursement_schedule_id,
            'award_id' => $this->award_id,
            'cost_category_id' => $this->cost_category_id,
            'amount' => $this->amount,
            'disbursed_at' => $this->disbursed_at,
            'status' => $this->status,
            'idempotency' => $this->idempotency,
            'notes' => $this->notes,
            'disbursement_schedule' => $this->whenLoaded('disbursementSchedule') ? $this->disbursementSchedule->only(['id', 'scheduled_amount', 'paid_amount']) : null,
            'award' => new AwardResource($this->whenLoaded('award')),
            'cost_category' => new CostCategoryResource($this->whenLoaded('costCategory')),
        ];
    }
}
