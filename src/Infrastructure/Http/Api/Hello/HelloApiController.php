<?php


namespace App\Infrastructure\Http\Api\Hello;


use App\Application\Presenter\Api\Hello\HelloApiPresenter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class HelloApiController extends AbstractController
{
    #[Route('/')]
    public function number(): JsonResponse
    {
        return (new HelloApiPresenter())->present();
    }
}