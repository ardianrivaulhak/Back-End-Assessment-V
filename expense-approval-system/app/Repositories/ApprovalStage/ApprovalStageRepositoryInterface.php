<?php

namespace App\Repositories\ApprovalStage;

interface ApprovalStageRepositoryInterface
{
    public function create(array $data);

    public function update($id, array $data);

    public function findById($id);
}
