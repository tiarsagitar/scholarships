<?php

namespace App\Repositories;

use App\Models\Disbursement;
use Illuminate\Support\Facades\DB;

class DisbursementRepository
{
    public function findById($id)
    {
        return Disbursement::find($id);
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