<?php


namespace App\Application\Http\Api;


use App\Application\Helper\StringHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class TestApiController extends AbstractController
{
    #[Route('/')]
    public function number(): JsonResponse
    {
        $number = random_int(0, 100);

        $result = StringHelper::sanitize($number);

        return new JsonResponse([
            'hello' => 'world',
            'api' => $result,
        ]);
    }
}