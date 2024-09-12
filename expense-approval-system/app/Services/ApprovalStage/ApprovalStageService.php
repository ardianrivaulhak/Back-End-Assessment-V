<?php

namespace App\Services\ApprovalStage;

use App\Repositories\ApprovalStage\ApprovalStageRepositoryInterface;

class ApprovalStageService
{
    protected $approvalStageRepository;

    public function __construct(ApprovalStageRepositoryInterface $approvalStageRepository)
    {
        $this->approvalStageRepository = $approvalStageRepository;
    }

    public function store(array $data)
    {
        return $this->approvalStageRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->approvalStageRepository->update($id, $data);
    }
}
