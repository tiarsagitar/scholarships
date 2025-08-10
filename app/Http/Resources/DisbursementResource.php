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
            'amount' => $this->amount,
            'disbursed_at' => $this->disbursed_at,
            'status' => $this->status,
            'notes' => $this->notes,
            'disbursement_schedule' => $this->whenLoaded('disbursementSchedule', function() {
                return [
                    'id' => $this->disbursementSchedule->id,
                    'scheduled_amount' => $this->disbursementSchedule->scheduled_amount,
                    'scheduled_date' => $this->disbursementSchedule->scheduled_date,
                    'description' => $this->disbursementSchedule->description,
                    'award_allocation' => $this->whenLoaded('disbursementSchedule.awardAllocation', function() {
                        return [
                            'id' => $this->disbursementSchedule->awardAllocation->id,
                            'cost_category' => new CostCategoryResource($this->disbursementSchedule->awardAllocation->costCategory),
                            'award' => [
                                'id' => $this->disbursementSchedule->awardAllocation->award->id,
                                'amount' => $this->disbursementSchedule->awardAllocation->award->amount,
                                'application' => [
                                    'scholarship' => new ScholarshipResource($this->disbursementSchedule->awardAllocation->award->application->scholarship),
                                ],
                            ],
                        ];
                    }),
                ];
            }),
            'receipts' => $this->whenLoaded('receipts', function() {
                return $this->receipts->map(function($receipt) {
                    return [
                        'id' => $receipt->id,
                        'original_name' => $receipt->original_name,
                        'file_size' => $receipt->file_size,
                        'mime_type' => $receipt->mime_type,
                        'description' => $receipt->description,
                        'uploaded_at' => $receipt->uploaded_at,
                        'download_url' => url('storage/receipts/' . $receipt->original_name),
                        'status' => $receipt->status,
                    ];
                });
            }),
        ];
    }
}
