<?php


namespace App\Tests\functional\Infrastructure\Doctrine\Service;


use App\Domain\Doctrine\HistoryNotification\Entity\HistoryNotification;
use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Domain\Doctrine\NotifyMessage\Enum\NotifyStatusEnum;
use App\Domain\Doctrine\NotifyMessage\Enum\NotifyTypeEnum;
use App\Infrastructure\Doctrine\Repository\NotifyMessage\NotifyMessageRepository;
use App\Infrastructure\Doctrine\Service\HistoryNotificationService;
use App\Tests\FunctionalTester;

class HistoryNotificationServiceCest
{
    private HistoryNotificationService $service;
    private NotifyMessageRepository $notifyRepository;

    public function setUp(FunctionalTester $I): void
    {
        $this->service = $I->grabService(HistoryNotificationService::class);
        $this->notifyRepository = $I->grabService(NotifyMessageRepository::class);
    }

    public function create(FunctionalTester $I): void
    {
        $notify = new NotifyMessage(NotifyTypeEnum::EMAIL->value, ['test' => 'execute'], NotifyStatusEnum::IN_QUEUE->value);

        $this->notifyRepository->save($notify);

        $entity = new HistoryNotification($notify, NotifyStatusEnum::ACTIVE->value, 'info message');

        $savedEntity = $this->service->create($entity);

        $I->assertInstanceOf(HistoryNotification::class, $savedEntity);
        $I->assertEquals(NotifyStatusEnum::ACTIVE->value, $savedEntity->getStatus());
        $I->assertEquals('info message', $savedEntity->getInfo());
    }

    public function negativeCreate(FunctionalTester $I): void
    {
        try {
            $this->notifyRepository->save(null);
        } catch (\Throwable $exception) {
        }

        $I->assertEquals(true, str_contains(
            $exception->getMessage(),
            'App\Infrastructure\Doctrine\Repository\BaseDoctrineRepository::save(): Argument #1 ($entity) must be of type App\Infrastructure\Doctrine\Interfaces\EntityInterface, null given, called in /var/www/tests/functional/Infrastructure/Doctrine/Service/HistoryNotificationServiceCest.php'
        ));
    }

    public function findByNotifyMessage(FunctionalTester $I): void
    {
        $notify = new NotifyMessage(NotifyTypeEnum::EMAIL->value, ['test' => 'execute'], NotifyStatusEnum::IN_QUEUE->value);

        $this->notifyRepository->save($notify);

        $info = 'info message';
        $entity = new HistoryNotification($notify, NotifyStatusEnum::ACTIVE->value, $info);

        $this->service->create($entity);

        $foundEntity = $this->service->findByNotifyMessage($notify);

        $I->assertIsArray($foundEntity);
        $I->assertEquals($entity->getId()->toString(), $foundEntity[0]['id']->toString());
        $I->assertEquals(NotifyStatusEnum::ACTIVE->value, $foundEntity[0]['status']);
        $I->assertEquals($info, $foundEntity[0]['info']);
    }

    public function negativeFindByNotifyMessage(FunctionalTester $I): void
    {
        try {
            $notify = new NotifyMessage(NotifyTypeEnum::EMAIL->value, ['test' => 'execute'], NotifyStatusEnum::IN_QUEUE->value);
            $foundEntity = $this->service->findByNotifyMessage($notify);
        } catch (\Throwable $exception) {
            dd($exception->getMessage());
        }

        $I->assertEquals(0, count($foundEntity));
    }
}