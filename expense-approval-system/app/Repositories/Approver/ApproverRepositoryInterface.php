<?php

namespace App\Repositories\Approver;

interface ApproverRepositoryInterface
{
    public function create(array $data);
    public function find($id);
    public function update($id, array $data);
    public function delete($id);
}
