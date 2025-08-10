<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\ScholarshipService;
use App\Helpers\ApiResponse;
use App\Http\Resources\ScholarshipResource;

class ScholarshipController extends Controller
{
    use AuthorizesRequests;
    
    protected $scholarshipService;

    public function __construct(ScholarshipService $scholarshipService)
    {
        $this->scholarshipService = $scholarshipService;
    }

    public function index(Request $request)
    {
        $this->authorize('list', Scholarship::class);
        
        $scholarships = $this->scholarshipService->getActiveScholarships();

        $response = ScholarshipResource::collection($scholarships);

        return ApiResponse::success($response, 'Active scholarships retrieved successfully.');
    }
}
