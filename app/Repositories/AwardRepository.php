<?php

namespace App\Repositories;

use App\Models\Award;
use App\Models\Disbursement;
use Illuminate\Support\Facades\DB;

class AwardRepository
{
    public function getUserAwards($userId, array $filters = [])
    {
        $query = Award::where('student_id', $userId)
            ->with(['application.scholarship', 'allocations.costCategory'])
            ->where('amount', '>', 0); // Only approved awards with amounts

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['date_from'])) {
            $query->where('awarded_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('awarded_at', '<=', $filters['date_to']);
        }

        $perPage = $filters['per_page'] ?? 15;
        
        return $query->orderBy('awarded_at', 'desc')->paginate($perPage);
    }

    public function getAwardDisbursements($awardId, $userId)
    {
        return Award::where('id', $awardId)
            ->where('student_id', $userId)
            ->with([
                'allocations.disbursementSchedules.disbursements' => function($query) {
                    $query->orderBy('disbursed_at', 'asc');
                },
                'allocations.costCategory'
            ])
            ->first();
    }

    public function getDisbursementDetails($disbursementId, $userId)
    {
        return Disbursement::whereHas('disbursementSchedule.awardAllocation.award', function($query) use ($userId) {
                $query->where('student_id', $userId);
            })
            ->with([
                'disbursementSchedule.awardAllocation.award.application.scholarship',
                'disbursementSchedule.awardAllocation.costCategory',
                'receipts'
            ])
            ->where('id', $disbursementId)
            ->first();
    }
}