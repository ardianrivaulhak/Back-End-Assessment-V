<?php

namespace App\Services\Expense;

use App\Repositories\Expense\ExpenseRepositoryInterface;

class ExpenseService
{
    protected $expenseRepository;

    public function __construct(ExpenseRepositoryInterface $expenseRepository)
    {
        $this->expenseRepository = $expenseRepository;
    }

    public function createExpense(array $data)
    {
        if ($this->expenseRepository->findByApproverId($data['approver_id'])) {
            return response()->json([
                'message' => 'Approver ID has been used',
            ], 400);
        }

        return $this->expenseRepository->create($data);
    }

    public function getExpense($id)
    {
        return $this->expenseRepository->find($id);
    }

    public function updateExpense($id, array $data)
    {
        return $this->expenseRepository->update($id, $data);
    }
}
