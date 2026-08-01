<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\NotificationResource;
class NotificationController extends BaseApiController
{
    public function index(Request $request): JsonResponse
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->paginate(
                $request->integer('per_page', 10)
            );

            return $this->paginatedResponse(
                NotificationResource::collection($notifications),
                'Notifications fetched successfully.'
            );
    }
}