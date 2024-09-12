<?php

namespace App\Services\Approver;

use App\Repositories\Approver\ApproverRepositoryInterface;

class ApproverService
{
    protected $approverRepository;

    /**
     * Inject ApproverRepositoryInterface into the service.
     *
     * @param \App\Repositories\Approver\ApproverRepositoryInterface $approverRepository
     */
    public function __construct(ApproverRepositoryInterface $approverRepository)
    {
        $this->approverRepository = $approverRepository;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param array $data
     * @return \App\Models\Approver
     */
    public function store(array $data)
    {
        return $this->approverRepository->create($data);
    }
}
