<?php

namespace App\Repositories\Expense;

use App\Models\Expense;

class ExpenseRepository implements ExpenseRepositoryInterface
{
    public function create(array $data)
    {
        return Expense::create($data);
    }

    public function find($id)
    {
        return Expense::find($id);
    }

    public function update($id, array $data)
    {
        $expense = $this->find($id);
        if ($expense) {
            $expense->update($data);
            return $expense;
        }
        return null;
    }

    public function findByApproverId($approverId)
    {
        return Expense::where('approver_id', $approverId)->first();
    }
}
