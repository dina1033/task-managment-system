<?php

namespace App\Repositories\Interfaces;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TaskRepositoryInterface
{
    public function create(array $data): Task;

    public function update(Task $task, array $data): Task;

    public function delete(Task $task): bool;

    public function paginateByProject(Project $project,array $filters = []): LengthAwarePaginator;
}