<?php

namespace App\Services;

use App\Models\Project;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProjectService
{
    public function __construct(
        private readonly ProjectRepositoryInterface $repository,
    ) {
    }

    public function index(int $userId, array $filters = []): LengthAwarePaginator
    {
        return $this->repository->paginateByUser($userId, $filters);
    }

    public function store(int $userId, array $data): Project
    {
        $data['user_id'] = $userId;
    
        return $this->repository->create($data);
    }

    public function update(Project $project, array $data): Project
    {
        return $this->repository->update($project, $data);
    }

    public function delete(Project $project): bool
    {
        return $this->repository->delete($project);
    }

    public function show(int $id): Project
    {
        return $this->repository->findById($id);
    }
}