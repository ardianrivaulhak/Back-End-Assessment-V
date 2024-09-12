<?php

namespace App\Http\Controllers\Expense;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExpenseApprovalRequest;
use App\Http\Requests\ExpenseRequest;
use App\Models\Approver;
use App\Models\Expense;
use App\Models\Status;
use App\Services\Expense\ExpenseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Swagger\Annotations as SWG;

/**
 * @SWG\Tag(
 *     name="Expenses",
 *     description="Manage expenses"
 * )
 */
class ExpenseController extends Controller
{
    protected $expenseService;

    public function __construct(ExpenseService $expenseService)
    {
        $this->expenseService = $expenseService;
    }

    /**
     * @SWG\Post(
     *     path="/expenses",
     *     summary="Create a new expense",
     *     tags={"Expenses"},
     *     @SWG\Parameter(
     *         name="amount",
     *         in="formData",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         name="status_id",
     *         in="formData",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         name="approver_id",
     *         in="formData",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Expense created successfully",
     *         @SWG\Schema(ref="#/definitions/Expense")
     *     ),
     *     @SWG\Response(
     *         response=500,
     *         description="An error occurred while saving expenses"
     *     )
     * )
     */
    public function store(ExpenseRequest $request): JsonResponse
    {
        try {
            $data = $request->only(['amount', 'status_id', 'approver_id']);
            $expense = $this->expenseService->createExpense($data);

            if ($expense instanceof JsonResponse) {
                return $expense;
            }

            return response()->json($expense, 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while saving expenses',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @SWG\Put(
     *     path="/expenses/{id}/approve",
     *     summary="Approve an expense",
     *     tags={"Expenses"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         name="approver_id",
     *         in="formData",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Expense approved successfully",
     *         @SWG\Schema(ref="#/definitions/Expense")
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Expense not found"
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Withdrawals are no longer in the stage of waiting for approval"
     *     ),
     *     @SWG\Response(
     *         response=500,
     *         description="An error occurred while approving an expense"
     *     )
     * )
     */
    public function approve(ExpenseApprovalRequest $request, $id): JsonResponse
    {
        try {
            $expense = $this->expenseService->getExpense($id);

            if (!$expense) {
                return response()->json(['error' => 'Expense not found'], 404);
            }

            if ($expense->status->name !== 'menunggu persetujuan') {
                return response()->json([
                    'message' => 'Withdrawals are no longer in the stage of waiting for approval',
                ], 400);
            }

            $approvedStatus = Status::where('name', 'disetujui')->firstOrFail();
            $expense->status_id = $approvedStatus->id;
            $expense->approver_id = $request->approver_id;
            $expense->save();

            return response()->json([
                'message' => 'Expense approved successfully',
                'expense' => $expense,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while approving an expense',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @SWG\Get(
     *     path="/expenses/{id}",
     *     summary="Get details of an expense",
     *     tags={"Expenses"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Expense details retrieved successfully",
     *         @SWG\Schema(ref="#/definitions/Expense")
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Expense not found"
     *     ),
     *     @SWG\Response(
     *         response=500,
     *         description="An error occurred while retrieving expenses"
     *     )
     * )
     */
    public function show($id): JsonResponse
    {
        try {
            $expense = $this->expenseService->getExpense($id);

            if (!$expense) {
                return response()->json(['error' => 'Expense not found'], 404);
            }

            $approvers = Approver::with('status')->get();

            $response = [
                'id' => $expense->id,
                'amount' => $expense->amount,
                'status' => $expense->status,
                'approvers' => $approvers->map(function ($approver) {
                    return [
                        'id' => $approver->id,
                        'name' => $approver->name,
                        'status' => $approver->status,
                    ];
                }),
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving expenses',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
