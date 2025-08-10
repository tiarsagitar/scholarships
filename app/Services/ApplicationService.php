<?php

namespace App\Services;

use App\Repositories\ApplicationRepository;
use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;

class ApplicationService
{
    protected $applicationRepository;

    public function __construct(ApplicationRepository $applicationRepository)
    {
        $this->applicationRepository = $applicationRepository;
    }

    public function create(array $data): Application
    {
        return $this->applicationRepository->create($data);
    }

    public function find(int $id): ?Application
    {
        return $this->applicationRepository->find($id);
    }

    public function getExistingApplication(int $userId, int $scholarshipId): ?Application
    {
        return $this->applicationRepository->getExistingApplication($userId, $scholarshipId);
    }

    public function getUserApplications(int $userId, array $filters = [])
    {
        return $this->applicationRepository->getUserApplications($userId, $filters);
    }

    public function getApplicationDetails(int $applicationId, int $userId): ?array
    {
        $application = $this->applicationRepository->getUserApplicationWithDetails($applicationId, $userId);
        
        if (!$application) {
            return null;
        }

        $documents = $this->applicationRepository->getApplicationDocuments($applicationId);

        return [
            'application' => $application,
            'documents' => $documents
        ];
    }

    public function uploadDocuments(int $applicationId, int $userId, array $files, array $names): ?array
    {
        $application = $this->applicationRepository->findUserApplication($applicationId, $userId);
        
        if (!$application) {
            return null;
        }

        $uploadedDocuments = [];

        foreach ($files as $index => $file) {
            $path = $file->store('application-documents', 'public');

            $applicationDocument = ApplicationDocument::create([
                'application_id' => $application->id,
                'name' => $names[$index],
            ]);

            $document = $applicationDocument->documents()->create([
                'name'   => $file->getClientOriginalName(),
                'file_path'       => $path,
                'size'       => $file->getSize(),
                'file_type' => $file->getClientMimeType(),
                'user_id'    => $userId,
            ]);

            $uploadedDocuments[] = [
                'id'       => $applicationDocument->id,
                'name'     => $applicationDocument->name,
                'filename' => $document->name,
                'size'     => $document->size,
            ];
        }

        return $uploadedDocuments;
    }
}