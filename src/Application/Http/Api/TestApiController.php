<?php


namespace App\Application\Http\Api;


use App\Application\Helper\StringHelper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TestApiController
{
    #[Route('/')]
    public function number(): JsonResponse
    {
        $number = random_int(0, 100);

        $result = StringHelper::sanitize($number);

        return new JsonResponse([
            'api' => $result,
        ]);
    }
}