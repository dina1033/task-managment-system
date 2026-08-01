<?php

namespace App\Repositories\Eloquent;

use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Project;

class TaskRepository implements TaskRepositoryInterface
{
    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(Task $task, array $data): Task
    {
        $task->update($data);

        return $task->refresh();
    }

    public function delete(Task $task): bool
    {
        return $task->delete();
    }

    public function paginateByProject(
        Project $project,
        array $filters = []
    ): LengthAwarePaginator {
        return Task::query()
            ->whereBelongsTo($project)
            ->when(
                $filters['status'] ?? null,
                fn ($query, $status) => $query->where('status', $status)
            )
            ->when(
                $filters['priority'] ?? null,
                fn ($query, $priority) => $query->where('priority', $priority)
            )
            ->when(
                $filters['search'] ?? null,
                fn ($query, $search) =>
                    $query->where('title', 'like', "%{$search}%")
            )
            ->latest()
            ->paginate(
                $filters['per_page'] ?? 10
            );
    }
}