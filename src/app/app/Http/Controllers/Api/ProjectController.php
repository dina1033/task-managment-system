<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Project Management APIs.
 */
class ProjectController extends BaseApiController
{
    public function __construct(
        private readonly ProjectService $projectService,
    ) {
    }

    /**
     * List all authenticated user's projects.
     */
    public function index(Request $request): JsonResponse
    {
        $projects = $this->projectService->index(
            userId: $request->user()->id,
            filters: $request->only('status', 'per_page'),
        );

        return $this->paginatedResponse(
            ProjectResource::collection($projects),
            'Projects fetched successfully.'
        );
    }

    public function store(StoreProjectRequest $request): JsonResponse
    {
        $project = $this->projectService->store(
            $request->user()->id,
            $request->validated()
        );

        return $this->successResponse(
            data: new ProjectResource($project),
            message: 'Project created successfully.',
            status: Response::HTTP_CREATED,
        );
    }

    public function show(Project $project): JsonResponse
    {
        $this->authorize('view', $project);

        return $this->successResponse(
            data: new ProjectResource($project),
        );
    }

    public function update(UpdateProjectRequest $request, Project $project): JsonResponse
    {
        $this->authorize('update', $project);

        $project = $this->projectService->update(
            $project,
            $request->validated()
        );

        return $this->successResponse(
            data: new ProjectResource($project),
            message: 'Project updated successfully.',
        );
    }

    public function destroy(Project $project): JsonResponse
    {    
        $this->authorize('delete', $project);

        $this->projectService->delete($project);
    
        return $this->successResponse(
            message: 'Project deleted successfully.',
            status: Response::HTTP_OK,
        );
    }
}