<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\Approver;
use App\Models\ApprovalStage;
use App\Models\Approval;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpenseTest extends TestCase
{
    use RefreshDatabase;


    public function test_it_can_create_an_approval_stage()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $approver = Approver::factory()->create();

        $this->actingAs($user)->postJson('/api/approval-stages', [
            'approver_id' => $approver->id
        ])->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'approver_id',
                'created_at',
            ]);
    }

    /** @test */
    public function it_can_create_an_expense()
    {
        $response = $this->postJson('/api/expenses', [
            'amount' => 1000
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'amount',
                'status_id',
                'created_at',
                'updated_at'
            ]);

        $this->assertDatabaseHas('expenses', ['amount' => 1000]);
    }

    /** @test */
    public function it_requires_valid_amount_to_create_expense()
    {
        $response = $this->postJson('/api/expenses', [
            'amount' => -1000 // Invalid amount
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('amount');
    }

    /** @test */
    public function it_can_approve_an_expense()
    {
        $approver = Approver::factory()->create();
        $expense = Expense::factory()->create();
        ApprovalStage::factory()->create(['approver_id' => $approver->id]);

        $response = $this->patchJson("/api/expenses/{$expense->id}/approve", [
            'approver_id' => $approver->id
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $expense->id,
                'status_id' => 2 // Assume 2 is for "disetujui"
            ]);

        $this->assertDatabaseHas('approvals', [
            'expense_id' => $expense->id,
            'approver_id' => $approver->id,
            'status_id' => 2
        ]);
    }

    /** @test */
    public function it_fails_to_approve_an_expense_with_wrong_approver()
    {
        $approverA = Approver::factory()->create();
        $approverB = Approver::factory()->create();
        $expense = Expense::factory()->create();

        ApprovalStage::factory()->create(['approver_id' => $approverA->id]);

        $response = $this->patchJson("/api/expenses/{$expense->id}/approve", [
            'approver_id' => $approverB->id // Wrong approver
        ]);

        $response->assertStatus(403)
            ->assertJson(['error' => 'Unauthorized approval']);
    }
}
