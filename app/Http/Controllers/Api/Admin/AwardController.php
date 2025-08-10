<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Helpers\ApiResponse;
use App\Services\AwardService;
use App\Models\Award;
use App\Models\Application;
use Exception;
use App\Http\Requests\AwardCreateRequest;
use App\Http\Requests\AwardDisbursmentScheduleCreateRequest;

class AwardController extends Controller
{
    use AuthorizesRequests;

    protected $awardService;

    public function __construct(AwardService $awardService)
    {
        $this->awardService = $awardService;
    }

    public function store(AwardCreateRequest $request, $applicationId)
    {
        $this->authorize('create', Award::class);

        try {
            $data = $request->validated();
            $award = $this->awardService->createAward($applicationId, $data);
            return ApiResponse::success($award, 'Award created successfully');
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), 400);
        }
    }

    public function createDisbursementSchedule(AwardDisbursmentScheduleCreateRequest $request, $awardId)
    {
        $this->authorize('createDisbursementSchedule', Award::class);

        try {
            $data = $request->validated();
            $schedules = $this->awardService->createDisbursementSchedule($awardId, $data);
            return ApiResponse::success($schedules, 'Disbursement schedules created successfully');
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), 400);
        }
    }
}
