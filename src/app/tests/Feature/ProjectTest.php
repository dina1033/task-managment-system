<?php

namespace Tests\Feature;

use App\Enums\ProjectStatus;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_project(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/projects', [
            'name' => 'Laravel Assessment',
            'description' => 'Project Description',
            'status' => ProjectStatus::ACTIVE->value,
        ]);

        $response
            ->assertCreated()
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('projects', [
            'user_id' => $user->id,
            'name' => 'Laravel Assessment',
        ]);
    }

    public function test_guest_cannot_create_project(): void
    {
        $this->postJson('/api/projects', [
            'name' => 'Project',
            'status' => ProjectStatus::ACTIVE->value,
        ])->assertUnauthorized();
    }

    public function test_user_can_list_only_his_projects(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Project::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        Project::factory()->count(2)->create([
            'user_id' => $otherUser->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/projects');
        $response
            ->assertOk()
            ->assertJsonCount(3, 'data');    }

    public function test_user_can_view_his_project(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $project = Project::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->getJson("/api/projects/{$project->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $project->id);
    }

    public function test_user_cannot_view_other_users_project(): void
    {
        $user = User::factory()->create();

        $project = Project::factory()->create();

        Sanctum::actingAs($user);

        $this->getJson("/api/projects/{$project->id}")
            ->assertForbidden();
    }

    public function test_user_can_update_his_project(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $project = Project::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->putJson("/api/projects/{$project->id}", [
            'name' => 'Updated Project',
            'status' => ProjectStatus::COMPLETED->value,
        ])
        ->assertOk();

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => 'Updated Project',
        ]);
    }

    public function test_user_cannot_update_other_users_project(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $project = Project::factory()->create();

        $this->putJson("/api/projects/{$project->id}", [
            'name' => 'Updated',
        ])->assertForbidden();
    }

    public function test_user_can_delete_his_project(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $project = Project::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->deleteJson("/api/projects/{$project->id}")
            ->assertOk();

        $this->assertSoftDeleted('projects', [
            'id' => $project->id,
        ]);
    }

    public function test_user_cannot_delete_other_users_project(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $project = Project::factory()->create();
        $response = $this->deleteJson("/api/projects/{$project->id}");

        $response->dump();

        $response->assertStatus(403);
    }

    public function test_project_creation_validation(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $this->postJson('/api/projects', [])
            ->assertStatus(422);
    }
}