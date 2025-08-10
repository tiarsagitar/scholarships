<?php

namespace App\Services;

use App\Repositories\CostCategoryRepository;
use App\Models\CostCategory;

/**
 * Class CostCategoryService
 * Handles business logic related to costCategorys.
 */

class CostCategoryService
{
    protected $costCategoryRepository;

    public function __construct(CostCategoryRepository $costCategoryRepository)
    {
        $this->costCategoryRepository = $costCategoryRepository;
    }

    /**
     * Paginate costCategorys.
     *
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginate(int $perPage = 15)
    {
        return $this->costCategoryRepository->paginate($perPage);
    }

    /**
     * Create a new costCategory.
     *
     * @param array $data
     * @return CostCategory
     */
    public function create(array $data): CostCategory
    {
        return $this->costCategoryRepository->create($data);
    }

    /**
     * Find a costCategory by ID.
     *
     * @param int $id
     * @return CostCategory|null
     */
    public function find(int $id): ?CostCategory
    {
        return $this->costCategoryRepository->find($id);
    }

    /**
     * Update a costCategory.
     *
     * @param int $id
     * @param array $data
     * @return CostCategory|null
     */
    public function update(int $id, array $data): ?CostCategory
    {
        $costCategory = $this->costCategoryRepository->find($id);
        if (!$costCategory) {
            return null;
        }
        
        return $this->costCategoryRepository->update($costCategory, $data);
    }

    /**
     * Delete a costCategory.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $costCategory = $this->costCategoryRepository->find($id);
        if (!$costCategory) {
            return false;
        }
        
        return $this->costCategoryRepository->delete($costCategory);
    }
}