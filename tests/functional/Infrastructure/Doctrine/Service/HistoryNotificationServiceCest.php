<?php


namespace App\Tests\functional\Infrastructure\Doctrine\Service;


use App\Domain\Doctrine\HistoryNotification\Entity\HistoryNotification;
use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Infrastructure\Doctrine\Repository\NotifyMessage\NotifyMessageRepository;
use App\Infrastructure\Doctrine\Service\HistoryNotificationService;
use App\Tests\FunctionalTester;

// todo  create negative cases
class HistoryNotificationServiceCest
{
    private HistoryNotificationService $service;

    public function setUp(FunctionalTester $I): void
    {
        $this->service = $I->grabService(HistoryNotificationService::class);
        $this->notifyRepository = $I->grabService(NotifyMessageRepository::class);
    }

    public function create(FunctionalTester $I): void
    {
        $notify = new NotifyMessage(NotifyMessage::EMAIL_TYPE, ['test' => 'execute'], NotifyMessage::STATUS_IN_QUEUE);

        $this->notifyRepository->save($notify);

        $entity = new HistoryNotification($notify, NotifyMessage::STATUS_ACTIVE, 'info message');

        $savedEntity = $this->service->create($entity);

        $I->assertInstanceOf(HistoryNotification::class, $savedEntity);
        $I->assertEquals(NotifyMessage::STATUS_ACTIVE, $savedEntity->getStatus());
        $I->assertEquals('info message', $savedEntity->getInfo());
    }

    public function findByNotifyMessage(FunctionalTester $I): void
    {
        $notify = new NotifyMessage(NotifyMessage::EMAIL_TYPE, ['test' => 'execute'], NotifyMessage::STATUS_IN_QUEUE);

        $this->notifyRepository->save($notify);

        $info = 'info message';
        $entity = new HistoryNotification($notify, NotifyMessage::STATUS_ACTIVE, $info);

        $this->service->create($entity);

        $foundEntity = $this->service->findByNotifyMessage($notify);

        $I->assertIsArray($foundEntity);
        $I->assertEquals($entity->getId()->toString(), $foundEntity[0]['id']->toString());
        $I->assertEquals(NotifyMessage::STATUS_ACTIVE, $foundEntity[0]['status']);
        $I->assertEquals($info, $foundEntity[0]['info']);
    }
}