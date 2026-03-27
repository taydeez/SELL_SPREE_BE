<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\AbstractPaginator;

class ApiResponse
{
    public static function success(
        mixed $data = null,
        string $message = 'Success.',
        int $status = JsonResponse::HTTP_OK,
    ): JsonResponse {
        $payload = [
            'success' => true,
            'message' => $message,
            'data'    => null,
        ];

        if ($data instanceof AbstractPaginator) {
            $payload['data'] = $data->items();
            $payload['meta'] = [
                'current_page' => $data->currentPage(),
                'last_page'    => $data->lastPage(),
                'per_page'     => $data->perPage(),
                'total'        => $data->total(),
            ];
        } elseif ($data instanceof ResourceCollection) {
            $response = $data->response()->getData(true);
            $payload['data'] = $response['data'] ?? $data;
            if (isset($response['meta'])) {
                $payload['meta'] = $response['meta'];
            }
        } elseif ($data instanceof JsonResource) {
            $payload['data'] = $data->resolve();
        } else {
            $payload['data'] = $data;
        }

        return response()->json($payload, $status);
    }

    public static function created(
        mixed $data = null,
        string $message = 'Created successfully.',
    ): JsonResponse {
        return self::success($data, $message, JsonResponse::HTTP_CREATED);
    }

    public static function error(
        string $message,
        mixed $errors = null,
        int $status = JsonResponse::HTTP_BAD_REQUEST,
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data'    => null,
            'errors'  => $errors,
        ], $status);
    }

    public static function noContent(): JsonResponse
    {
        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
