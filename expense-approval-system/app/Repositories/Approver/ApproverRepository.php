<?php

namespace App\Repositories\Approver;

use App\Models\Approver;

class ApproverRepository implements ApproverRepositoryInterface
{
    protected $model;

    public function __construct(Approver $approver)
    {
        $this->model = $approver;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function update($id, array $data)
    {
        $approver = $this->model->find($id);
        if ($approver) {
            $approver->update($data);
            return $approver;
        }
        return null;
    }

    public function delete($id)
    {
        $approver = $this->model->find($id);
        if ($approver) {
            return $approver->delete();
        }
        return false;
    }
}
