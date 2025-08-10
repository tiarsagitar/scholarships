<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Helpers\ApiResponse;
use App\Services\DisbursementService;
use App\Models\Award;
use Exception;
use App\Http\Requests\DisbursementPaidRequest;
use App\Http\Resources\DisbursementResource;

class DisbursementController extends Controller
{
    use AuthorizesRequests;

    protected $disbursementService;

    public function __construct(DisbursementService $disbursementService)
    {
        $this->disbursementService = $disbursementService;
    }

    public function markAsPaid(DisbursementPaidRequest $request, $disbursementId)
    {
        $this->authorize('markAsPaid', Disbursement::class);

        $data = $request->validated();

        try {
            $disbursement = $this->disbursementService->markAsPaid($disbursementId, $data);
            return ApiResponse::success($disbursement, 'Disbursement marked as paid');
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), 400);
        }
    }

    public function index(Request $request)
    {
        $this->authorize('view', Disbursement::class);

        $filters = [
            'status' => $request->get('status'),
            'cost_category_id' => $request->get('category'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'sort_by' => $request->get('sort_by', 'disbursed_at'),
            'sort_order' => $request->get('sort_order', 'desc'),
            'per_page' => $request->get('per_page', 15),
        ];

        $filters = array_filter($filters, function($value) {
            return $value !== null && $value !== '';
        });

        try {
            $disbursements = $this->disbursementService->getDisbursements($filters);

            $resource = DisbursementResource::collection($disbursements);
            return ApiResponse::success($disbursements, 'Disbursements retrieved successfully');
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), 400);
        }
    }
}
