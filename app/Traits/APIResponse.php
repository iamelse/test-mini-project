<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait APIResponse
{
    /**
     * Response sukses standar
     */
    protected function success(string $message, $data = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'status'  => 'success',
            'message' => $message,
            'data'    => $data
        ], $code);
    }

    /**
     * Response error standar
     */
    protected function error(string $message, int $code = 500, $errors = null): JsonResponse
    {
        return response()->json([
            'status'  => 'error',
            'message' => $message,
            'errors'  => $errors
        ], $code);
    }
}