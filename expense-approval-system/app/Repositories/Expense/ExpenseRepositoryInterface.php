<?php

namespace App\Repositories\Expense;

interface ExpenseRepositoryInterface
{
    public function create(array $data);
    public function find($id);
    public function update($id, array $data);

    public function findByApproverId($approverId);
}
