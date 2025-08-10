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

class AwardService
{
    public function createAward($applicationId, array $data)
    {
        $application = Application::findOrFail($applicationId);
        
        if (!$application->isApproved()) {
            throw new Exception('Only approved applications can be awarded.');
        }

        return DB::transaction(function () use ($application, $data) {
            $award = Award::create([
                'application_id' => $application->id,
                'student_id' => $application->user_id,
                'approved_by' => auth()->id(),
                'amount' => collect($data['allocations'])->sum('amount'),
                'awarded_at' => now(),
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($data['allocations'] as $allocation) {
                $this->validateAllocation($application->scholarship_id, $allocation);
                
                AwardAllocation::create([
                    'application_id' => $application->id,
                    'award_id' => $award->id,
                    'cost_category_id' => $allocation['cost_category_id'],
                    'allocated_amount' => $allocation['amount'],
                    'disbursed_amount' => 0,
                ]);

                $scholarshipBudget = ScholarshipBudget::where('scholarship_id', $application->scholarship_id)
                    ->where('cost_category_id', $allocation['cost_category_id'])
                    ->first();

                if ($scholarshipBudget) {
                    $scholarshipBudget->committed_amount += $allocation['amount'];
                    $scholarshipBudget->save();
                }
            }

            return $award->load('allocations.costCategory');
        });
    }

    public function createDisbursementSchedule($awardId, array $data)
    {
        $award = Award::findOrFail($awardId);
        
        return DB::transaction(function () use ($award, $data) {
            $schedules = [];
            
            foreach ($data['schedules'] as $scheduleData) {
                $awardAllocation = $this->validateSchedule($scheduleData);
                
                $schedule = DisbursementSchedule::create([
                    'award_allocation_id' => $awardAllocation->id,
                    'cost_category_id' => $awardAllocation->cost_category_id,
                    'scheduled_amount' => $scheduleData['amount'],
                    'scheduled_date' => $scheduleData['scheduled_date'],
                    'description' => $scheduleData['description'] ?? null
                ]);
                
                $schedules[] = $schedule;
            }
            
            return $schedules;
        });
    }

    private function validateAllocation($scholarshipId, array $allocation)
    {
        $budget = ScholarshipBudget::where('scholarship_id', $scholarshipId)
                                 ->where('cost_category_id', $allocation['cost_category_id'])
                                 ->first();

        if (!$budget) {
            throw new Exception('No budget found for this cost category.');
        }

        if (($budget->committed_amount + $allocation['amount']) > $budget->planned_amount) {
            throw new Exception('Allocation would exceed planned budget for this category. remains: ' . ($budget->planned_amount - $budget->committed_amount));
        }
    }

    private function validateSchedule(array $scheduleData)
    {
        $allocation = AwardAllocation::findOrfail($scheduleData['award_allocation_id']);

        if (!$allocation) {
            throw new Exception('No allocation found for this cost category.');
        }

        $totalScheduled = DisbursementSchedule::where('award_allocation_id', $scheduleData['award_allocation_id'])
                                            ->sum('scheduled_amount');

        if (($totalScheduled + $scheduleData['amount']) > $allocation->allocated_amount) {
            throw new Exception('Schedule would exceed committed amount for this category.');
        }

        return $allocation;
    }
}