<?php

namespace App\Tests\unit\Infrastructure\RabbitMq\Producer\History;

use App\Infrastructure\Doctrine\Repository\NotifyMessage\NotifyMessageRepository;
use App\Infrastructure\Doctrine\Service\HistoryNotificationService;
use App\Tests\FunctionalTester;

class HistoryMessageProducerCССest
{
    // мокнуть продюсер сервис и посмотреть чтобы вызвано было один раз
    private HistoryNotificationService $service;

    public function setUp(FunctionalTester $I): void
    {
        $this->service = $I->grabService(HistoryNotificationService::class);
        $this->notifyRepository = $I->grabService(NotifyMessageRepository::class);
    }
}