<?php

namespace App\Services;

use App\Models\Task;
use App\Models\Project;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaskService
{
    public function __construct(
        private readonly TaskRepositoryInterface $repository,
    ) {
    }

    public function index(
        Project $project,
        array $filters = []
    ): LengthAwarePaginator {
        return $this->repository->paginateByProject(
            $project,
            $filters
        );
    }

    public function store(Project $project, array $data): Task
    {
        $data['project_id'] = $project->id;
    
        return $this->repository->create($data);
    }

    public function update(
        Task $task,
        array $data
    ): Task {
        return $this->repository->update($task, $data);
    }

    public function delete(Task $task): bool
    {
        return $this->repository->delete($task);
    }
}