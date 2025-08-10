<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ScholarshipService;
use App\Helpers\ApiResponse;
use App\Http\Requests\ScholarshipCreateRequest;
use App\Http\Requests\ScholarshipUpdateRequest;
use App\Http\Requests\ScholarshipBudgetCreateRequest;
use App\Models\Scholarship;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Resources\BudgetResource;
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
        
        $scholarships = $this->scholarshipService->paginate($request->input('per_page', 15));
        return ApiResponse::success($scholarships);
    }

    public function store(ScholarshipCreateRequest $request)
    {
        $this->authorize('create', Scholarship::class);

        $data = $request->validated();
        $scholarship = $this->scholarshipService->create($data);
        return ApiResponse::success($scholarship, 'Scholarship created successfully.');
    }

    public function show($id)
    {
        $scholarship = $this->scholarshipService->find($id);
        
        if (!$scholarship) {
            return ApiResponse::error('Scholarship not found.', 404);
        }

        $this->authorize('view', $scholarship);
        
        return ApiResponse::success($scholarship);
    }

    public function update(ScholarshipUpdateRequest $request, $id)
    {
        $scholarship = $this->scholarshipService->find($id);
        
        if (!$scholarship) {
            return ApiResponse::error('Scholarship not found.', 404);
        }

        $this->authorize('update', $scholarship);

        $data = $request->validated();
        $updatedScholarship = $this->scholarshipService->update($id, $data);
        
        return ApiResponse::success($updatedScholarship, 'Scholarship updated successfully.');
    }

    public function destroy($id)
    {
        $scholarship = $this->scholarshipService->find($id);
        
        if (!$scholarship) {
            return ApiResponse::error('Scholarship not found.', 404);
        }

        $this->authorize('delete', $scholarship);

        $deleted = $this->scholarshipService->delete($id);
        
        if ($deleted) {
            return ApiResponse::success(null, 'Scholarship deleted successfully.');
        }
        
        return ApiResponse::error('Failed to delete scholarship.', 500);
    }

    public function viewBudgets($id)
    {

        $scholarship = $this->scholarshipService->find($id);
        
        if (!$scholarship) {
            return ApiResponse::error('Scholarship not found.', 404);
        }
        $this->authorize('viewBudget', $scholarship);

        $budgets = $this->scholarshipService->getBudgets($id);
        
        return ApiResponse::success([
            'scholarship' => ScholarshipResource::make($scholarship),
            'budgets' => BudgetResource::collection($budgets),
        ], 'Budget summary retrieved successfully.');
    }

    public function setBudget(ScholarshipBudgetCreateRequest $request, $id)
    {
        $scholarship = $this->scholarshipService->find($id);
        
        if (!$scholarship) {
            return ApiResponse::error('Scholarship not found.', 404);
        }
        $this->authorize('setBudget', $scholarship);

        $data = $request->validated();
        $result = $this->scholarshipService->setBudgets($id, $data['budgets']);
        
        if ($result) {
            return ApiResponse::success(null, 'Budgets set successfully.');
        }
        
        return ApiResponse::error('Failed to set budgets.', 500);
    }
}
