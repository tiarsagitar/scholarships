<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReceiptUploadRequest;
use App\Http\Resources\AwardResource;
use App\Http\Resources\DisbursementResource;
use App\Services\AwardService;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Award;

class AwardController extends Controller
{
    use AuthorizesRequests;

    protected $awardService;

    public function __construct(AwardService $awardService)
    {
        $this->awardService = $awardService;
    }

    public function myAwards(Request $request)
    {
        $this->authorize('listOwn', Award::class);
        
        $filters = [
            'status' => $request->get('status'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
            'per_page' => $request->get('per_page', 15),
        ];

        $filters = array_filter($filters, function($value) {
            return $value !== null && $value !== '';
        });

        $awards = $this->awardService->getUserAwards(Auth::id(), $filters);

        $resources = AwardResource::collection($awards);

        return ApiResponse::success($resources, 'Awards retrieved successfully');
    }

    public function awardDisbursements(Request $request, $awardId)
    {
        $this->authorize('viewDisbursements', Award::class);
        
        $award = $this->awardService->getAwardDisbursements($awardId, Auth::id());

        if (!$award) {
            return ApiResponse::error('Award not found', 404);
        }

        $disbursements = [];
        foreach ($award->allocations as $allocation) {
            foreach ($allocation->disbursementSchedules as $schedule) {
                foreach ($schedule->disbursements as $disbursement) {
                    $disbursements[] = $disbursement;
                }
            }
        }

        $resources = DisbursementResource::collection(collect($disbursements));

        return ApiResponse::success([
            'award' => new AwardResource($award),
            'disbursements' => $resources
        ], 'Award disbursements retrieved successfully');
    }
}
