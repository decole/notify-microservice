<?php


namespace App\Application\Http\Api;


use Symfony\Component\HttpFoundation\JsonResponse;

final class ApiResponse
{
    public static function success(array $result): JsonResponse
    {
        return new JsonResponse($result);
    }

    public static function validationError(array $result): JsonResponse
    {
        return new JsonResponse($result, 400);
    }

    public static function error(array $result): JsonResponse
    {
        return new JsonResponse($result, 404);
    }
}