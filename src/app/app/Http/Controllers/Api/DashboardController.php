<?php

namespace App\Http\Controllers\Api;

use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends BaseApiController
{
    public function __construct(
        private readonly DashboardService $dashboardService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        return $this->successResponse(
            data: $this->dashboardService->statistics(
                $request->user()->id
            ),
            message: 'Dashboard retrieved successfully.',
        );
    }
}