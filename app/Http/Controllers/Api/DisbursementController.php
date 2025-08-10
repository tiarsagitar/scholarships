<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\ReceiptUploadRequest;
use App\Http\Resources\DisbursementResource;
use App\Services\DisbursementService;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Auth;

class DisbursementController extends Controller
{
    use AuthorizesRequests;
    
    protected $disbursementService;

    public function __construct(DisbursementService $disbursementService)
    {
        $this->disbursementService = $disbursementService;
    }

    public function uploadReceipt(ReceiptUploadRequest $request, $disbursementId)
    {
        $this->authorize('uploadReceipt', Disbursement::class);
        
        $receipt = $this->disbursementService->uploadReceipt(
            $disbursementId,
            Auth::id(),
            $request->file('receipt'),
            $request->description
        );

        if (!$receipt) {
            return ApiResponse::error('Disbursement not found or you are not authorized', 404);
        }

        return ApiResponse::success([
            'id' => $receipt->id,
            'original_name' => $receipt->original_name,
            'file_size' => $receipt->file_size,
            'description' => $receipt->description,
            'uploaded_at' => $receipt->uploaded_at,
        ], 'Receipt uploaded successfully');
    }

    public function show(Request $request, $disbursementId)
    {
        $this->authorize('viewDetails', Disbursement::class);
        
        $disbursement = $this->disbursementService->getDisbursementDetails($disbursementId, Auth::id());

        if (!$disbursement) {
            return ApiResponse::error('Disbursement not found', 404);
        }

        $resource = new DisbursementResource($disbursement);

        return ApiResponse::success($resource, 'Disbursement details retrieved successfully');
    }
}
