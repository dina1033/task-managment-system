<?php

namespace App\Repositories\Interfaces;

interface DashboardRepositoryInterface
{
    public function statistics(int $userId): array;
}