<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Project;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends BaseApiController
{
    public function __construct(private readonly TaskService $taskService,)
    {
    }

    public function index(Request $request,Project $project): JsonResponse
    {
        $this->authorize('view', $project);

        $tasks = $this->taskService->index(
            project: $project,
            filters: $request->only([
                'status',
                'priority',
                'search',
                'per_page',
            ]),
        );

        return $this->paginatedResponse(
            TaskResource::collection($tasks),
            'Tasks fetched successfully.',
        );
    }

    public function store(
        StoreTaskRequest $request,
        Project $project
    ): JsonResponse {
        $this->authorize('update', $project);

        $task = $this->taskService->store(
            $project,
            $request->validated()
        );

        return $this->successResponse(
            data: new TaskResource($task),
            message: 'Task created successfully.',
            status: Response::HTTP_CREATED,
        );
    }

    public function update(
        UpdateTaskRequest $request,
        Project $project,
        Task $task
    ): JsonResponse {
        $this->authorize('update', $task);

        $task = $this->taskService->update(
            task: $task,
            data: $request->validated(),
        );

        return $this->successResponse(
            data: new TaskResource($task),
            message: 'Task updated successfully.',
        );
    }

    public function destroy(
        Project $project,
        Task $task
    ): JsonResponse {
        $this->authorize('delete', $task);

        $this->taskService->delete($task);

        return $this->successResponse(
            message: 'Task deleted successfully.',
            status: Response::HTTP_OK,
        );
    }
}