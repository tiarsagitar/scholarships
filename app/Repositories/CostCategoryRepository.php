<?php

namespace App\Repositories;

use App\Models\CostCategory;

class CostCategoryRepository
{
    protected $model;

    public function __construct(CostCategory $model)
    {
        $this->model = $model;
    }

    public function paginate($perPage = 15)
    {
        return $this->model->paginate($perPage);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($scholarship, array $data)
    {
        $scholarship->update($data);
        return $scholarship;
    }

    public function delete($scholarship)
    {
        return $scholarship->delete();
    }

    public function getActiveCostCategorys()
    {
        return $this->model->active()->withinDeadline()->get();
    }

    public function getCostCategoryWithBudgets($id)
    {
        return $this->model->with(['budgets.costCategory'])->find($id);
    }
}