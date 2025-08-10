<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CostCategoryService;
use App\Helpers\ApiResponse;
use App\Http\Requests\CostCategoryCreateRequest;
use App\Http\Requests\CostCategoryUpdateRequest;
use App\Models\CostCategory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CostCategoryController extends Controller
{
    use AuthorizesRequests;
    
    protected $costCategoryService;

    public function __construct(CostCategoryService $costCategoryService)
    {
        $this->costCategoryService = $costCategoryService;
    }

    public function index(Request $request)
    {
        $this->authorize('list', CostCategory::class);

        $costCategories = $this->costCategoryService->paginate($request->input('per_page', 15));
        return ApiResponse::success($costCategories, 'Data retrieved successfully.');
    }

    public function store(CostCategoryCreateRequest $request)
    {
        $this->authorize('create', CostCategory::class);

        $data = $request->validated();
        $costCategory = $this->costCategoryService->create($data);
        return ApiResponse::success($costCategory, 'Data created successfully.');
    }
}
