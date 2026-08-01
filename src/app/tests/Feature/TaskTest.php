<?php

namespace Tests\Feature;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;


    public function test_user_can_create_task_for_his_project(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $project = Project::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->postJson(
            "/api/projects/{$project->id}/tasks",
            [
                'title' => 'Finish Laravel Assessment',
                'description' => 'Complete API',
                'priority' => TaskPriority::HIGH->value,
                'status' => TaskStatus::TODO->value,
                'due_date' => now()->addDays(5)->format('Y-m-d'),
            ]
        );

        $response
            ->assertCreated();

        $this->assertDatabaseHas('tasks', [
            'project_id' => $project->id,
            'title' => 'Finish Laravel Assessment',
        ]);
    }


    public function test_user_cannot_create_task_for_other_users_project(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $project = Project::factory()->create();

        $response = $this->postJson(
            "/api/projects/{$project->id}/tasks",
            [
                'title' => 'Unauthorized Task',
                'priority' => TaskPriority::HIGH->value,
                'status' => TaskStatus::TODO->value,
            ]
        );

        $response->assertForbidden();
    }


    public function test_user_can_list_project_tasks(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $project = Project::factory()->create([
            'user_id' => $user->id,
        ]);

        Task::factory()
            ->count(3)
            ->create([
                'project_id' => $project->id,
            ]);


        $response = $this->getJson(
            "/api/projects/{$project->id}/tasks"
        );


        $response
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }


    public function test_user_can_filter_tasks_by_status(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $project = Project::factory()->create([
            'user_id' => $user->id,
        ]);


        Task::factory()->create([
            'project_id' => $project->id,
            'status' => TaskStatus::DONE->value,
        ]);


        Task::factory()->create([
            'project_id' => $project->id,
            'status' => TaskStatus::TODO->value,
        ]);


        $response = $this->getJson(
            "/api/projects/{$project->id}/tasks?status=done"
        );


        $response
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }


    public function test_user_can_filter_tasks_by_priority(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $project = Project::factory()->create([
            'user_id' => $user->id,
        ]);


        Task::factory()->create([
            'project_id' => $project->id,
            'priority' => TaskPriority::HIGH->value,
        ]);


        Task::factory()->create([
            'project_id' => $project->id,
            'priority' => TaskPriority::LOW->value,
        ]);


        $response = $this->getJson(
            "/api/projects/{$project->id}/tasks?priority=high"
        );


        $response
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }


    public function test_user_can_search_tasks_by_title(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);


        $project = Project::factory()->create([
            'user_id' => $user->id,
        ]);


        Task::factory()->create([
            'project_id' => $project->id,
            'title' => 'Laravel API Task',
        ]);


        Task::factory()->create([
            'project_id' => $project->id,
            'title' => 'Frontend Task',
        ]);


        $response = $this->getJson(
            "/api/projects/{$project->id}/tasks?search=Laravel"
        );


        $response
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }


    public function test_user_can_update_task(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);


        $project = Project::factory()->create([
            'user_id' => $user->id,
        ]);


        $task = Task::factory()->create([
            'project_id' => $project->id,
        ]);


        $response = $this->putJson(
            "/api/projects/{$project->id}/tasks/{$task->id}",
            [
                'title' => 'Updated Task',
                'status' => TaskStatus::DONE->value,
            ]
        );


        $response->assertOk();


        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Task',
        ]);
    }


    public function test_user_can_delete_task(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);


        $project = Project::factory()->create([
            'user_id' => $user->id,
        ]);


        $task = Task::factory()->create([
            'project_id' => $project->id,
        ]);


        $response = $this->deleteJson(
            "/api/projects/{$project->id}/tasks/{$task->id}"
        );


        $response->assertOk();


        $this->assertSoftDeleted('tasks', [
            'id' => $task->id,
        ]);
    }


    public function test_task_validation_fails(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);


        $project = Project::factory()->create([
            'user_id' => $user->id,
        ]);


        $response = $this->postJson(
            "/api/projects/{$project->id}/tasks",
            []
        );


        $response->assertStatus(422);
    }
}