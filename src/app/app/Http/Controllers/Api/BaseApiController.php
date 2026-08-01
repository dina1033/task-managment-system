<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\ApiResponse;

abstract class BaseApiController extends Controller
{
    protected function successResponse(
        mixed $data = null,
        string $message = 'Success',
        int $status = Response::HTTP_OK,
        array $meta = []
    ): JsonResponse {
        return ApiResponse::success(
            data: $data,
            message: $message,
            status: $status,
            meta: $meta,
        );
    }

    protected function paginatedResponse(
        ResourceCollection $resource,
        string $message = 'Data fetched successfully.'
    ): JsonResponse {
        $response = $resource->response()->getData(true);

        return ApiResponse::success(
            data: $response['data'],
            message: $message,
            meta: [
                'current_page' => $response['meta']['current_page'],
                'last_page' => $response['meta']['last_page'],
                'per_page' => $response['meta']['per_page'],
                'total' => $response['meta']['total'],
            ],
        );
    }

    protected function errorResponse(
        string $message = 'Something went wrong.',
        mixed $errors = null,
        int $status = Response::HTTP_BAD_REQUEST,
    ): JsonResponse {
        return ApiResponse::error(
            message: $message,
            errors: $errors,
            status: $status,
        );
    }
}