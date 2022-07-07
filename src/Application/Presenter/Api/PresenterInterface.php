<?php

namespace App\Application\Presenter\Api;

use Symfony\Component\HttpFoundation\JsonResponse;

interface PresenterInterface
{
    public function present(): JsonResponse;
}