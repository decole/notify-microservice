<?php


namespace App\Application\Presenter\Api;


use Symfony\Component\HttpFoundation\JsonResponse;

abstract class AbstractPresenter implements PresenterInterface
{
    protected const SERVER_HTTP_CODE = 200;

    final public function present(): JsonResponse
    {
        return new JsonResponse($this->getResult(), static::SERVER_HTTP_CODE);
    }

    public function getResult(): array
    {
        return [];
    }
}