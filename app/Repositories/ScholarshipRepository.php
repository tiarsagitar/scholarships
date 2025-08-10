<?php

namespace App\Repositories;

use App\Models\Scholarship;

class ScholarshipRepository
{
    protected $model;

    public function __construct(Scholarship $model)
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

    public function getActiveScholarships()
    {
        return $this->model->active()->withinDeadline()->get();
    }

    public function getScholarshipWithBudgets($id)
    {
        return $this->model->with(['budgets.costCategory'])->find($id);
    }
}