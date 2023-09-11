<?php

namespace App\Services;
use Illuminate\Http\JsonResponse;

class ResponseService
{
    private function jsonResponse($message, $data, $code): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public static function successResponse(string $message, $data = [])
    {
        return self::jsonResponse($message, $data, 200);
    }

    public static function validationErrorResponse(string $message, $data = [])
    {
        return self::jsonResponse($message, $data, 422);
    }

    public static function errorResponse(string $message, $data = [])
    {
        return self::jsonResponse($message, $data, 500);
    }
}