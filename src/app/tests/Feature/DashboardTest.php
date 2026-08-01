<?php

namespace Tests\Feature;

use App\Enums\ProjectStatus;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;


    public function test_authenticated_user_can_get_dashboard_statistics(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);


        // Projects
        Project::factory()->create([
            'user_id' => $user->id,
            'status' => ProjectStatus::ACTIVE->value,
        ]);

        Project::factory()->create([
            'user_id' => $user->id,
            'status' => ProjectStatus::COMPLETED->value,
        ]);

        Project::factory()->create([
            'user_id' => $user->id,
            'status' => ProjectStatus::ARCHIVED->value,
        ]);


        $activeProject = Project::factory()->create([
            'user_id' => $user->id,
            'status' => ProjectStatus::ACTIVE->value,
        ]);


        // Tasks
        Task::factory()->create([
            'project_id' => $activeProject->id,
            'status' => TaskStatus::DONE->value,
            'due_date' => now()->addDays(2),
        ]);


        Task::factory()->create([
            'project_id' => $activeProject->id,
            'status' => TaskStatus::TODO->value,
            'due_date' => now()->addDays(3),
        ]);


        Task::factory()->create([
            'project_id' => $activeProject->id,
            'status' => TaskStatus::TODO->value,
            'due_date' => Carbon::yesterday(),
        ]);


        $response = $this->getJson('/api/dashboard');


        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'data' => [
                    'total_projects' => 4,
                    'active_projects' => 2,
                    'total_tasks' => 3,
                    'completed_tasks' => 1,
                    'pending_tasks' => 2,
                    'overdue_tasks' => 1,
                ],
            ]);
    }


    public function test_guest_cannot_access_dashboard(): void
    {
        $this->getJson('/api/dashboard')
            ->assertUnauthorized();
    }
}