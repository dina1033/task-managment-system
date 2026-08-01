<?php

namespace App\Exceptions;

use Throwable;
use App\Helpers\ApiResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\JsonResponse;

class ApiExceptionHandler
{
    public static function render(Throwable $e): JsonResponse
    {
        return match (true) {

            $e instanceof ValidationException => ApiResponse::error(
                message: 'Validation failed.',
                errors: $e->errors(),
                status: Response::HTTP_UNPROCESSABLE_ENTITY,
            ),

            $e instanceof AuthenticationException => ApiResponse::error(
                message: 'Unauthenticated.',
                status: Response::HTTP_UNAUTHORIZED,
            ),

            $e instanceof AuthorizationException => ApiResponse::error(
                message: $e->getMessage() ?: 'Forbidden.',
                status: Response::HTTP_FORBIDDEN,
            ),

            $e instanceof ModelNotFoundException => ApiResponse::error(
                message: 'Resource not found.',
                status: Response::HTTP_NOT_FOUND,
            ),

            $e instanceof NotFoundHttpException => ApiResponse::error(
                message: 'Endpoint not found.',
                status: Response::HTTP_NOT_FOUND,
            ),

            default => ApiResponse::error(
                message: config('app.debug')
                    ? $e->getMessage()
                    : 'Server Error.',
                status: Response::HTTP_INTERNAL_SERVER_ERROR,
            ),
        };
    }
}