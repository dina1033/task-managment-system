<?php

namespace App\Services;

use App\Repositories\Interfaces\DashboardRepositoryInterface;

class DashboardService
{
    public function __construct(
        private readonly DashboardRepositoryInterface $repository
    ) {
    }

    public function statistics(int $userId): array
    {
        return $this->repository->statistics($userId);
    }
}