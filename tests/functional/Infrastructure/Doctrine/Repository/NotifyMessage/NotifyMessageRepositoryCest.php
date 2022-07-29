<?php


namespace App\Tests\functional\Infrastructure\Doctrine\Repository\NotifyMessage;


use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Domain\Doctrine\NotifyMessage\Enum\NotifyStatusEnum;
use App\Domain\Doctrine\NotifyMessage\Enum\NotifyTypeEnum;
use App\Infrastructure\Doctrine\Repository\NotifyMessage\NotifyMessageRepository;
use App\Tests\FunctionalTester;
use Ramsey\Uuid\Uuid;

class NotifyMessageRepositoryCest
{
    private NotifyMessageRepository $repository;

    public function setUp(FunctionalTester $I): void
    {
        $this->repository = $I->grabService(NotifyMessageRepository::class);
    }

    public function findById(FunctionalTester $I): void
    {
        $id = $this->createEntity()->getId()->toString();

        $entity = $this->repository->findById($id);

        $I->assertInstanceOf(NotifyMessage::class, $entity);
        $I->assertEquals($id, $entity->getId()->toString());
        $I->assertEquals(NotifyStatusEnum::IN_QUEUE->value, $entity->getStatus());
        $I->assertEquals(NotifyTypeEnum::EMAIL->value, $entity->getType());
        $I->assertEquals('in queue', $entity->getTextStatus());
        $I->assertEquals(['test' => 'execute'], $entity->getBody());
    }

    public function negativeFindById(FunctionalTester $I): void
    {
        $id = Uuid::uuid4()->toString();
        $entity = $this->repository->findById($id);

        $I->assertEquals(null, $entity);
    }

    private function createEntity(): NotifyMessage
    {
        $notify = new NotifyMessage(NotifyTypeEnum::EMAIL->value, ['test' => 'execute'], NotifyStatusEnum::IN_QUEUE->value);

        $this->repository->save($notify);

        return $notify;
    }
}