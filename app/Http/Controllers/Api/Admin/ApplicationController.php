<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Helpers\ApiResponse;
use App\Http\Resources\ApplicationResource;
use App\Services\ApplicationService;

class ApplicationController extends Controller
{
    use AuthorizesRequests;

    protected $applicationService;

    public function __construct(ApplicationService $applicationService)
    {
        $this->applicationService = $applicationService;
    }

    public function index(Request $request)
    {
        $this->authorize('listAll', Application::class);

        $filters = [
            'status' => $request->get('status'),
            'scholarship_id' => $request->get('scholarship_id'),
            'user_id' => $request->get('user_id'),
            'sort_by' => $request->get('sort_by', 'created_at'),
            'sort_order' => $request->get('sort_order', 'desc'),
            'page' => $request->get('page', 1),
            'per_page' => $request->get('per_page', 15),
        ];

        $filters = array_filter($filters, function($value) {
            return $value !== null && $value !== '';
        });

        $applications = $this->applicationService->getApplications($filters);

        $resources = ApplicationResource::collection($applications);

        return ApiResponse::success($resources, 'Applications retrieved successfully');
    }

    public function review(Request $request, int $applicationId)
    {
        $this->authorize('review', Application::class);

        $data = $request->validate([
            'status' => 'required|in:approved,rejected',
            'reviewer_comments' => 'nullable|string|max:1000',
        ]);

        $data['reviewed_at'] = now();
        $data['reviewer_id'] = auth()->id();
        
        $application = $this->applicationService->update($applicationId, $data);

        return ApiResponse::success(new ApplicationResource($application), 'Application reviewed successfully');
    }
}
