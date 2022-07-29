<?php


namespace App\Tests\unit\Domain\Doctrine\HistoryNotification\Entity;


use App\Domain\Doctrine\HistoryNotification\Entity\HistoryNotification;
use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Domain\Doctrine\NotifyMessage\Enum\NotifyStatusEnum;
use App\Domain\Doctrine\NotifyMessage\Enum\NotifyTypeEnum;
use App\Tests\UnitTester;

class HistoryNotificationCest
{
    public function getStatus(UnitTester $I): void
    {
        $message = new NotifyMessage(NotifyTypeEnum::EMAIL->value, ['test' => 'execute'], NotifyStatusEnum::IN_QUEUE->value);
        $entity = new HistoryNotification($message, NotifyStatusEnum::ACTIVE->value, 'info message');

        $I->assertEquals(NotifyStatusEnum::ACTIVE->value, $entity->getStatus());
    }

    public function positiveSetStatus(UnitTester $I): void
    {
        $newStatus = NotifyStatusEnum::ERROR->value;
        $message = new NotifyMessage(NotifyTypeEnum::EMAIL->value, ['test' => 'execute'], NotifyStatusEnum::IN_QUEUE->value);
        $entity = new HistoryNotification($message, NotifyStatusEnum::ACTIVE->value, 'info message');
        $entity->setStatus($newStatus);

        $I->assertEquals($newStatus, $entity->getStatus());
    }

    public function negativeSetStatus(UnitTester $I): void
    {
        $newStatus = 'error status';
        $message = new NotifyMessage(NotifyTypeEnum::EMAIL->value, ['test' => 'execute'], NotifyStatusEnum::IN_QUEUE->value);
        $entity = new HistoryNotification($message, NotifyStatusEnum::ACTIVE->value, 'info message');

        try {
            $entity->setStatus($newStatus);
        } catch (\Throwable $exception) {}

        $I->assertEquals(true, str_contains(
            $exception->getMessage(),
            'App\Domain\Doctrine\HistoryNotification\Entity\HistoryNotification::setStatus(): Argument #1 ($status) must be of type int, string given, called in /var/www/tests/unit/Domain/Doctrine/HistoryNotification/Entity/HistoryNotificationCest.php'
        ));
    }

    public function notIsSetChangeStatus(UnitTester $I): void
    {
        $newStatus = 9999;
        $message = new NotifyMessage(NotifyTypeEnum::EMAIL->value, ['test' => 'execute'], NotifyStatusEnum::IN_QUEUE->value);
        $entity = new HistoryNotification($message, NotifyStatusEnum::ACTIVE->value, 'info message');

        try {
            $entity->setStatus($newStatus);
        } catch (\Throwable $exception) {}


        $I->assertNotEmpty($exception->getMessage());
    }

    public function positiveSetInfo(UnitTester $I): void
    {
        $info = 'info message';
        $message = new NotifyMessage(NotifyTypeEnum::EMAIL->value, ['test' => 'execute'], NotifyStatusEnum::IN_QUEUE->value);
        $entity = new HistoryNotification($message, NotifyStatusEnum::ACTIVE->value, $info);

        $I->assertEquals($info, $entity->getInfo());
    }

    public function checkIsNullInfo(UnitTester $I): void
    {
        $info = null;
        $message = new NotifyMessage(NotifyTypeEnum::EMAIL->value, ['test' => 'execute'], NotifyStatusEnum::IN_QUEUE->value);
        $entity = new HistoryNotification($message, NotifyStatusEnum::ACTIVE->value, $info);

        $I->assertEquals($info, $entity->getInfo());
    }

    public function checkId(UnitTester $I): void
    {
        $message = new NotifyMessage(NotifyTypeEnum::EMAIL->value, ['test' => 'execute'], NotifyStatusEnum::IN_QUEUE->value);
        $entity = new HistoryNotification($message, NotifyStatusEnum::ACTIVE->value, null);

        $I->assertNotNull($entity->getId()->toString());
    }

    public function checkCreatedAt(UnitTester $I): void
    {
        $message = new NotifyMessage(NotifyTypeEnum::EMAIL->value, ['test' => 'execute'], NotifyStatusEnum::IN_QUEUE->value);
        $entity = new HistoryNotification($message, NotifyStatusEnum::ACTIVE->value, null);

        $I->assertNotNull($entity->getCreatedAt());
    }
}