<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Helpers\ApiResponse;
use App\Models\Scholarship;
use App\Models\Award;
use App\Models\Application;
use App\Models\Disbursement;
use Exception;

class ReportController extends Controller
{
    use AuthorizesRequests;

    public function scholarshipReport($id)
    {
        $this->authorize('reports.scholarships');

        try {
            $scholarship = Scholarship::findOrFail($id);
            
            $applications = Application::where('scholarship_id', $id)->get();
            $totalApplications = $applications->count();
            $approvedApplications = $applications->where('status', 'approved')->count();
            $rejectedApplications = $applications->where('status', 'rejected')->count();
            $pendingApplications = $applications->where('status', 'pending')->count();
            
            $disbursements = Disbursement::whereHas('award.application', function($query) use ($id) {
                $query->where('scholarship_id', $id);
            })->get();
            
            $totalDisbursements = $disbursements->count();
            $totalDisbursedAmount = $disbursements->where('status', 'paid')->sum('amount');
            $pendingDisbursements = $disbursements->where('status', 'pending')->count();
            $paidDisbursements = $disbursements->where('status', 'paid')->count();
            
            $report = [
                'scholarship' => $scholarship,
                'statistics' => [
                    'applications' => [
                        'total' => $totalApplications,
                        'approved' => $approvedApplications,
                        'rejected' => $rejectedApplications,
                        'pending' => $pendingApplications,
                        'approval_rate' => $totalApplications > 0 ? round(($approvedApplications / $totalApplications) * 100, 2) : 0
                    ],
                    'disbursements' => [
                        'total_count' => $totalDisbursements,
                        'paid_count' => $paidDisbursements,
                        'pending_count' => $pendingDisbursements,
                        'total_disbursed_amount' => $totalDisbursedAmount,
                        'disbursement_rate' => $totalDisbursements > 0 ? round(($paidDisbursements / $totalDisbursements) * 100, 2) : 0
                    ]
                ]
            ];

            return ApiResponse::success($report, 'Scholarship report retrieved successfully');
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), 400);
        }
    }

    public function awardReport($awardId)
    {
        $this->authorize('reports.awards');

        try {
            $award = Award::with([
                'student',
                'application.scholarship',
                'approvedBy',
                'allocations.costCategory'
            ])->findOrFail($awardId);
            
            $report = [
                'award' => $award,
            ];

            return ApiResponse::success($report, 'Award report retrieved successfully');
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), 400);
        }
    }
}
