<?php

namespace App\Repositories\Eloquent;

use App\Enums\ProjectStatus;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use App\Repositories\Interfaces\DashboardRepositoryInterface;

class DashboardRepository implements DashboardRepositoryInterface
{
    public function statistics(int $userId): array
    {
        $tasks = Task::query()->whereHas('project', fn ($q) => $q->where('user_id', $userId));

        return [
            'total_projects' => Project::where('user_id', $userId)->count(),

            'active_projects' => Project::where('user_id', $userId)->where('status', ProjectStatus::ACTIVE)->count(),

            'total_tasks' => (clone $tasks)->count(),

            'completed_tasks' => (clone $tasks)->where('status', TaskStatus::DONE)->count(),

            'pending_tasks' => (clone $tasks)->where('status', TaskStatus::TODO)->count(),

            'overdue_tasks' => (clone $tasks)->whereDate('due_date', '<', today())->where('status', '!=', TaskStatus::DONE)->count(),
        ];
    }
}