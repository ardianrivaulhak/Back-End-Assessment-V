<?php

namespace Tests\Feature;

use App\Models\Approver;
use App\Models\ApprovalStage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class ApprovalStageTest extends TestCase
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
    public function it_can_create_an_approval_stage()
    {
        $approver = Approver::factory()->create();

        $response = $this->postJson('/api/approval-stages', [
            'approver_id' => $approver->id
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'approver_id',
                'created_at',
                'updated_at'
            ]);

        $this->assertDatabaseHas('approval_stages', ['approver_id' => $approver->id]);
    }

    /** @test */
    public function it_requires_valid_approver_id_to_create_approval_stage()
    {
        $response = $this->postJson('/api/approval-stages', [
            'approver_id' => 999 // Invalid approver_id
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('approver_id');
    }
}
