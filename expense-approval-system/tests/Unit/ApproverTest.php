<?php

namespace Tests\Feature;

use App\Models\Approver;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApproverTest extends TestCase
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
    public function it_can_create_an_approver()
    {
        $response = $this->postJson('/api/approvers', [
            'name' => 'Approver A'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'name',
                'created_at',
                'updated_at'
            ]);

        $this->assertDatabaseHas('approvers', ['name' => 'Approver A']);
    }

    /** @test */
    public function it_requires_name_to_create_an_approver()
    {
        $response = $this->postJson('/api/approvers', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('name');
    }
}
