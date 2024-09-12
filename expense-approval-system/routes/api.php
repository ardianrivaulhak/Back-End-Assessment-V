<?php

use App\Http\Controllers\ApprovalStage\ApprovalStageController;
use App\Http\Controllers\Approver\ApproverController;
use App\Http\Controllers\Expense\ExpenseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|----------------------------------------------------------------------
| API Routes
|----------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route untuk mendapatkan data pengguna yang terautentikasi
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Definisikan grup route dengan middleware api
Route::middleware('api')->group(function () {
    // Route untuk Approver
    Route::post('/approvers', [ApproverController::class, 'store']);

    // Route untuk Approval Stage
    Route::post('/approval-stages', [ApprovalStageController::class, 'store']);
    Route::put('/approval-stages/{id}', [ApprovalStageController::class, 'update']);

    // Route untuk Expense
    Route::post('/expenses', [ExpenseController::class, 'store']);  // Pastikan nama endpoint konsisten (plural atau singular)
    Route::patch('/expenses/{id}/approve', [ExpenseController::class, 'approve']);
    Route::get('/expenses/{id}', [ExpenseController::class, 'show']);
});
