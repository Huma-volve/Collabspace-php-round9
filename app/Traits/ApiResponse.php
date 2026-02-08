<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * ApiResponse Trait
 * 
 * Provides standardized JSON response methods for API controllers
 */
trait ApiResponse
{
    /**
     * Success response
     * 
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    protected function successResponse($data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];

        // Resource collection with CursorPaginator (MessageResource::collection($cursorPaginator))
        if ($data instanceof AnonymousResourceCollection && $data->resource instanceof \Illuminate\Pagination\CursorPaginator) {
            $paginator = $data->resource;
            $response['data'] = $data->resolve();
            $response['pagination'] = [
                'next_cursor' => $paginator->nextCursor()?->encode(),
                'prev_cursor' => $paginator->previousCursor()?->encode(),
                'has_more' => $paginator->nextCursor() !== null,
            ];

            return response()->json($response, $code);
        }

        // Resource collection with LengthAwarePaginator (MessageResource::collection($paginator))
        if ($data instanceof AnonymousResourceCollection && $data->resource instanceof LengthAwarePaginator) {
            $paginator = $data->resource;
            $response['data'] = $data->resolve();
            $response['pagination'] = [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'has_more'     => $paginator->hasMorePages(),
            ];

            return response()->json($response, $code);
        }

        // fallback: لو جاي array/collection/primitive — نسيبها كما هي
        return response()->json($response, $code);
    }
    
    /**
     * Error response
     * 
     * @param string $message
     * @param int $code
     * @param mixed $errors
     * @return JsonResponse
     */
    protected function errorResponse(string $message = 'Error', int $code = 400, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];
        
        if ($errors !== null) {
            $response['errors'] = $errors;
        }
        
        return response()->json($response, $code);
    }
    
    /**
     * Validation error response
     * 
     * @param array $errors
     * @param string $message
     * @return JsonResponse
     */
    protected function validationErrorResponse(array $errors, string $message = 'Validation failed'): JsonResponse
    {
        return $this->errorResponse($message, 422, $errors);
    }
    
    /**
     * Unauthorized response
     * 
     * @param string $message
     * @return JsonResponse
     */
    protected function unauthorizedResponse(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->errorResponse($message, 401);
    }
    
    /**
     * Forbidden response
     * 
     * @param string $message
     * @return JsonResponse
     */
    protected function forbiddenResponse(string $message = 'Forbidden'): JsonResponse
    {
        return $this->errorResponse($message, 403);
    }
    
    /**
     * Not found response
     * 
     * @param string $message
     * @return JsonResponse
     */
    protected function notFoundResponse(string $message = 'Not found'): JsonResponse
    {
        return $this->errorResponse($message, 404);
    }
}
