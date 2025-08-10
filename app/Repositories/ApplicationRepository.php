<?php

namespace App\Repositories;

use App\Models\Application;
use App\Models\ApplicationDocument;

class ApplicationRepository
{
    protected $model;

    public function __construct(Application $model)
    {
        $this->model = $model;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function findUserApplication($applicationId, $userId)
    {
        return $this->model->where('id', $applicationId)
            ->where('user_id', $userId)
            ->first();
    }

    public function getUserApplications($userId, array $filters = [])
    {
        $query = $this->model->where('user_id', $userId)
            ->with(['scholarship', 'reviewer']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        $query->orderBy('created_at', 'desc');

        return $query->paginate($filters['per_page'] ?? 15);
    }

    public function getUserApplicationWithDetails($applicationId, $userId)
    {
        return $this->model->where('id', $applicationId)
            ->where('user_id', $userId)
            ->with(['scholarship', 'user', 'reviewer', 'applicationDocuments', 'applicationDocuments.documents'])
            ->first();
    }

    public function getExistingApplication($userId, $scholarshipId)
    {
        return $this->model->where('user_id', $userId)
            ->where('scholarship_id', $scholarshipId)
            ->first();
    }

    public function getApplicationDocuments($applicationId)
    {
        return ApplicationDocument::where('application_id', $applicationId)
            ->with('documents')
            ->get();
    }
}