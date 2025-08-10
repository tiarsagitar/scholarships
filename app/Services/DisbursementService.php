<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Award;
use App\Models\AwardAllocation;
use App\Models\DisbursementSchedule;
use App\Models\Disbursement;
use App\Models\ScholarshipBudget;
use Illuminate\Support\Facades\DB;
use Exception;

class DisbursementService
{
    public function markAsPaid($disbursementId, array $data)
    {
        return DB::transaction(function () use ($disbursementId, $data) {
            $existingDisbursement = Disbursement::where('idempotency', $data['idempotency'])->first();
            
            if ($existingDisbursement) {
                return $existingDisbursement;
            }

            $schedule = DisbursementSchedule::with('awardAllocation')->findOrFail($disbursementId);

            $this->validatePayment($schedule, $data['amount']);
            
            $disbursement = Disbursement::create([
                'disbursement_schedule_id' => $schedule->id,
                'award_id' => $schedule->awardAllocation->award_id,
                'cost_category_id' => $schedule->cost_category_id,
                'amount' => $data['amount'],
                'disbursed_at' => now(),
                'status' => 'paid',
                'idempotency' => $data['idempotency'],
                'notes' => $data['notes'] ?? null,
            ]);

            $totalDisbursed = $schedule->disbursements()->sum('amount');
            
            $schedule->paid_amount += $data['amount'];
            $schedule->save();

            $schedule->awardAllocation->disbursed_amount += $data['amount'];
            $schedule->awardAllocation->save();

            return $disbursement;
        });
    }

    public function getDisbursements(array $filters = [])
    {
        $query = Disbursement::with(['award', 'costCategory', 'disbursementSchedule']);

        if (isset($filters['status'])) {
            $query->status($filters['status']);
        }

        if (isset($filters['cost_category_id'])) {
            $query->where('cost_category_id', $filters['cost_category_id']);
        }

        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $query->byDateRange($filters['start_date'], $filters['end_date']);
        }

        return $query->orderBy($filters['sort_by'] ?? 'disbursed_at', $filters['sort_order'] ?? 'desc')
                    ->paginate($filters['per_page'] ?? 15);
    }

    private function validatePayment($schedule, $amount)
    {
        $totalDisbursed = $schedule->disbursements()->sum('amount');
        $remainingAmount = $schedule->scheduled_amount - $totalDisbursed;

        if ($amount > $remainingAmount) {
            throw new Exception('Payment amount exceeds remaining scheduled amount.');
        }
    }
}