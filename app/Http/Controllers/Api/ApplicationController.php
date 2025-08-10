<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicationCreateRequest;
use App\Http\Requests\DocumentUploadRequest;
use App\Services\ApplicationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Helpers\ApiResponse;
use App\Http\Resources\ApplicationResource;

class ApplicationController extends Controller
{
    use AuthorizesRequests;

    protected $applicationService;

    public function __construct(ApplicationService $applicationService)
    {
        $this->applicationService = $applicationService;
    }

    public function store(ApplicationCreateRequest $request)
    {
        $this->authorize('create', Application::class);
        
        $existingApplication = $this->applicationService->getExistingApplication(
            Auth::id(),
            $request->scholarship_id
        );

        if ($existingApplication) {
            return ApiResponse::error('You have already applied to this scholarship', 409);
        }

        $application = $this->applicationService->create([
            'user_id' => Auth::id(),
            'scholarship_id' => $request->scholarship_id,
            'personal_statement' => $request->personal_statement,
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        return ApiResponse::success(
            $application->load(['scholarship', 'user']),
            'Application submitted successfully'
        );
    }

    public function uploadDocuments(DocumentUploadRequest $request, $id)
    {
        $this->authorize('uploadDocuments', Application::class);
        
        $uploadedDocuments = $this->applicationService->uploadDocuments(
            $id,
            Auth::id(),
            $request->documents,
            $request->names
        );

        if (!$uploadedDocuments) {
            return ApiResponse::error('Application not found', 404);
        }

        return ApiResponse::success($uploadedDocuments, 'Documents uploaded successfully');
    }

    public function myApplications(Request $request)
    {
        $this->authorize('viewOwn', Application::class);
        
        $filters = [
            'status' => $request->get('status'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
            'per_page' => $request->get('per_page', 15),
        ];

        $filters = array_filter($filters, function($value) {
            return $value !== null && $value !== '';
        });

        $applications = $this->applicationService->getUserApplications(Auth::id(), $filters);

        $resources = ApplicationResource::collection($applications);

        return ApiResponse::success($resources, 'Applications retrieved successfully');
    }

    public function show($id)
    {
        $application = $this->applicationService->find($id);
        $this->authorize('viewDetails', $application);

        $applicationDetails = $this->applicationService->getApplicationDetails($id, Auth::id());

        if (!$applicationDetails) {
            return ApiResponse::error('Application not found', 404);
        }

        $resources = ApplicationResource::make($applicationDetails['application']);

        return ApiResponse::success($resources, 'Application details retrieved successfully');
    }
}
