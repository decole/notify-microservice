<?php

namespace App\Tests\unit\Infrastructure\Sender\Email;

use App\Infrastructure\Doctrine\Repository\NotifyMessage\NotifyMessageRepository;
use App\Infrastructure\Doctrine\Service\HistoryNotificationService;
use App\Tests\FunctionalTester;

class EmailSenderСССуые
{
    // мокнуть имейл сервис и посмотреть чтобы отправка была вызвана один раз
    private HistoryNotificationService $service;

    public function setUp(FunctionalTester $I): void
    {
        $this->service = $I->grabService(HistoryNotificationService::class);
        $this->notifyRepository = $I->grabService(NotifyMessageRepository::class);
    }
}