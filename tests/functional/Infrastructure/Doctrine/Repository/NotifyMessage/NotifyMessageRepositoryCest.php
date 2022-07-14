<?php


namespace App\Tests\functional\Infrastructure\Doctrine\Repository\NotifyMessage;


use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Infrastructure\Doctrine\Repository\NotifyMessage\NotifyMessageRepository;
use App\Tests\FunctionalTester;

// todo  create negative cases
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
        $I->assertEquals(NotifyMessage::STATUS_IN_QUEUE, $entity->getStatus());
        $I->assertEquals(NotifyMessage::EMAIL_TYPE, $entity->getType());
        $I->assertEquals('in queue', $entity->getTextStatus());
        $I->assertEquals(['test' => 'execute'], $entity->getBody());
    }

    private function createEntity(): NotifyMessage
    {
        $notify = new NotifyMessage(NotifyMessage::EMAIL_TYPE, ['test' => 'execute'], NotifyMessage::STATUS_IN_QUEUE);

        $this->repository->save($notify);

        return $notify;
    }
}