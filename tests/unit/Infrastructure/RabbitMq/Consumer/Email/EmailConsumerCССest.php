<?php

namespace App\Tests\unit\Infrastructure\RabbitMq\Consumer\Email;

use App\Infrastructure\Doctrine\Repository\NotifyMessage\NotifyMessageRepository;
use App\Infrastructure\Doctrine\Service\HistoryNotificationService;
use App\Tests\FunctionalTester;

class EmailConsumerCССest
{
    // проверить чтобы сендер у консюмера был вызван один раз - execute с ожидаемым ответом
    private HistoryNotificationService $service;

    public function setUp(FunctionalTester $I): void
    {
        $this->service = $I->grabService(HistoryNotificationService::class);
        $this->notifyRepository = $I->grabService(NotifyMessageRepository::class);
    }
}