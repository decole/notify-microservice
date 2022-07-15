<?php


namespace App\Tests\functional\Infrastructure\Doctrine\Repository\HistoryNotification;


use App\Domain\Doctrine\HistoryNotification\Entity\HistoryNotification;
use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Infrastructure\Doctrine\Repository\HistoryNotification\HistoryNotificationRepository;
use App\Infrastructure\Doctrine\Repository\NotifyMessage\NotifyMessageRepository;
use App\Tests\FunctionalTester;
use Ramsey\Uuid\Uuid;

class HistoryNotificationRepositoryCest
{
    private HistoryNotificationRepository $historyRepository;

    public function setUp(FunctionalTester $I): void
    {
        $this->notifyRepository = $I->grabService(NotifyMessageRepository::class);
        $this->historyRepository = $I->grabService(HistoryNotificationRepository::class);
    }

    public function findById(FunctionalTester $I): void
    {
        $entity = $this->createEntity();
        $id = $entity->getId()->toString();
        $foundEntity = $this->historyRepository->findById($id);

        $I->assertEquals($id, $foundEntity->getId()->toString());
        $I->assertInstanceOf(HistoryNotification::class, $foundEntity);
    }

    public function negativeFindById(FunctionalTester $I): void
    {
        $id = Uuid::uuid4()->toString();
        $foundEntity = $this->historyRepository->findById($id);

        $I->assertEquals(null, $foundEntity);
    }

    public function findByNotifyMessage(FunctionalTester $I): void
    {
        $notify = new NotifyMessage(NotifyMessage::EMAIL_TYPE, ['test' => 'execute'], NotifyMessage::STATUS_IN_QUEUE);

        $idOne = $this->createEntity($notify)->getId()->toString();
        $idTwo = $this->createEntity($notify)->getId()->toString();
        $idThree = $this->createEntity($notify)->getId()->toString();

        $foundEntityList = $this->historyRepository->findByNotifyMessage($notify->getId()->toString());

        $indexedArray = [];

        /** @var HistoryNotification $history */
        foreach ($foundEntityList as $history) {
            $indexedArray[$history['id']->toString()] = $history;
        }

        $I->assertIsArray($foundEntityList);
        $I->assertNotEmpty($foundEntityList);
        $I->assertArrayHasKey($idOne, $indexedArray);
        $I->assertArrayHasKey($idTwo, $indexedArray);
        $I->assertArrayHasKey($idThree, $indexedArray);
    }

    public function negativeFindByNotifyMessage(FunctionalTester $I): void
    {
        $id = Uuid::uuid4()->toString();
        $foundEntity = $this->historyRepository->findByNotifyMessage($id);

        $I->assertIsArray($foundEntity);
        $I->assertEquals(0, count($foundEntity));
    }

    private function createEntity(?NotifyMessage $notify = null): HistoryNotification
    {
        if ($notify === null) {
            $notify = new NotifyMessage(NotifyMessage::EMAIL_TYPE, ['test' => 'execute'], NotifyMessage::STATUS_IN_QUEUE);
        }

        $this->notifyRepository->save($notify);

        $entity = new HistoryNotification($notify, NotifyMessage::STATUS_ACTIVE, 'info message');

        $this->historyRepository->save($entity);

        return $entity;
    }
}