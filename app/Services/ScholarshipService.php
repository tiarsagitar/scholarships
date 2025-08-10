<?php

namespace App\Services;

use App\Repositories\ScholarshipRepository;
use App\Models\Scholarship;
use App\Models\ScholarshipBudget;

/**
 * Class ScholarshipService
 * Handles business logic related to scholarships.
 */

class ScholarshipService
{
    protected $scholarshipRepository;

    public function __construct(ScholarshipRepository $scholarshipRepository)
    {
        $this->scholarshipRepository = $scholarshipRepository;
    }

    /**
     * Paginate scholarships.
     *
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginate(int $perPage = 15)
    {
        return $this->scholarshipRepository->paginate($perPage);
    }

    /**
     * Create a new scholarship.
     *
     * @param array $data
     * @return Scholarship
     */
    public function create(array $data): Scholarship
    {
        return $this->scholarshipRepository->create($data);
    }

    /**
     * Find a scholarship by ID.
     *
     * @param int $id
     * @return Scholarship|null
     */
    public function find(int $id): ?Scholarship
    {
        return $this->scholarshipRepository->find($id);
    }

    /**
     * Update a scholarship.
     *
     * @param int $id
     * @param array $data
     * @return Scholarship|null
     */
    public function update(int $id, array $data): ?Scholarship
    {
        $scholarship = $this->scholarshipRepository->find($id);
        if (!$scholarship) {
            return null;
        }
        
        return $this->scholarshipRepository->update($scholarship, $data);
    }

    /**
     * Delete a scholarship.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $scholarship = $this->scholarshipRepository->find($id);
        if (!$scholarship) {
            return false;
        }
        
        return $this->scholarshipRepository->delete($scholarship);
    }

    public function setBudgets(int $scholarshipId, array $budgets): bool
    {
        $scholarship = $this->scholarshipRepository->find($scholarshipId);
        if (!$scholarship) {
            return false;
        }

        ScholarshipBudget::where('scholarship_id', $scholarshipId)->delete();

        foreach ($budgets as $budget) {
            ScholarshipBudget::updateOrCreate([
                'scholarship_id' => $scholarshipId,
                'cost_category_id' => $budget['cost_category_id']
            ], [
                'planned_amount' => $budget['planned_amount']
            ]);
        }

        return true;
    }

    public function getBudgets(int $scholarshipId)
    {
        $scholarship = $this->scholarshipRepository->find($scholarshipId);
        if (!$scholarship) {
            return null;
        }

        return ScholarshipBudget::with('costCategory')
            ->where('scholarship_id', $scholarshipId)
            ->get();
    }

    public function getActiveScholarships()
    {
        return $this->scholarshipRepository->getActiveScholarships();
    }
}