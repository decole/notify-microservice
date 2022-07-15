<?php

namespace App\Tests\unit\Infrastructure\RabbitMq\Producer\Email;

use App\Infrastructure\Doctrine\Repository\NotifyMessage\NotifyMessageRepository;
use App\Infrastructure\Doctrine\Service\HistoryNotificationService;
use App\Tests\FunctionalTester;

class EmailProducerCССest
{
    // мокнуть продюсер сервис и посмотреть чтобы вызвано было один раз
    private HistoryNotificationService $service;

    public function setUp(FunctionalTester $I): void
    {
        $this->service = $I->grabService(HistoryNotificationService::class);
        $this->notifyRepository = $I->grabService(NotifyMessageRepository::class);
    }
}