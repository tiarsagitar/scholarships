<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AwardResource extends JsonResource
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
            'amount' => $this->amount,
            'awarded_at' => $this->awarded_at,
            'notes' => $this->notes,
            'application' => new ApplicationResource($this->whenLoaded('application')),
            'allocations' => $this->whenLoaded('allocations', function() {
                return $this->allocations->map(function($allocation) {
                    return [
                        'id' => $allocation->id,
                        'allocated_amount' => $allocation->allocated_amount,
                        'disbursed_amount' => $allocation->disbursed_amount,
                        'cost_category' => new CostCategoryResource($allocation->costCategory),
                    ];
                });
            }),
            'total_disbursed' => $this->whenLoaded('allocations', fn() => $this->allocations->sum('disbursed_amount')),
            'remaining_amount' => $this->whenLoaded('allocations', fn() => $this->amount - $this->allocations->sum('disbursed_amount')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
