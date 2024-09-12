<?php

namespace App\Services\Expense;

use App\Repositories\Expense\ExpenseRepositoryInterface;
use App\Models\Approver;
use Illuminate\Support\Facades\DB;

class ExpenseService
{
    protected $expenseRepository;

    public function __construct(ExpenseRepositoryInterface $expenseRepository)
    {
        $this->expenseRepository = $expenseRepository;
    }

    /**
     * Create an expense and update the status_id of the associated approver.
     *
     * @param array $data
     * @return mixed
     */
    public function createExpenseAndUpdateApprover(array $data)
    {
        DB::beginTransaction();

        try {
            $approver = Approver::find($data['approver_id']);
            if (!$approver) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Approver not found',
                ], 404);
            }

            if ($this->expenseRepository->findByApproverId($data['approver_id'])) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Approver ID has been used',
                ], 400);
            }

            $expense = $this->expenseRepository->create($data);

            $approver->status_id = $data['status_id'];
            $approver->save();

            DB::commit();
            return $expense;
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'An error occurred while saving expenses',
                'error' => $e->getMessage(),
            ], 500);
        }
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
