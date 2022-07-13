<?php


namespace App\Tests\unit\Domain\Doctrine\HistoryNotification\Entity;


use App\Domain\Doctrine\HistoryNotification\Entity\HistoryNotification;
use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Tests\UnitTester;

class HistoryNotificationCest
{
    public function getStatus(UnitTester $I): void
    {
        $message = new NotifyMessage(NotifyMessage::EMAIL_TYPE, ['test' => 'execute'], NotifyMessage::STATUS_IN_QUEUE);
        $entity = new HistoryNotification($message, NotifyMessage::STATUS_ACTIVE, 'info message');

        $I->assertEquals(NotifyMessage::STATUS_ACTIVE, $entity->getStatus());
    }

    public function positiveSetStatus(UnitTester $I): void
    {
        $newStatus = NotifyMessage::STATUS_ERROR;
        $message = new NotifyMessage(NotifyMessage::EMAIL_TYPE, ['test' => 'execute'], NotifyMessage::STATUS_IN_QUEUE);
        $entity = new HistoryNotification($message, NotifyMessage::STATUS_ACTIVE, 'info message');
        $entity->setStatus($newStatus);

        $I->assertEquals($newStatus, $entity->getStatus());
    }

    public function negativeSetStatus(UnitTester $I): void
    {
        $newStatus = 'error status';
        $message = new NotifyMessage(NotifyMessage::EMAIL_TYPE, ['test' => 'execute'], NotifyMessage::STATUS_IN_QUEUE);
        $entity = new HistoryNotification($message, NotifyMessage::STATUS_ACTIVE, 'info message');

        try {
            $entity->setStatus($newStatus);
        } catch (\Throwable $exception) {}

        $I->assertEquals(
            'App\Domain\Doctrine\HistoryNotification\Entity\HistoryNotification::setStatus(): Argument #1 ($status) must be of type int, string given, called in /var/www/tests/unit/Domain/Doctrine/HistoryNotification/Entity/HistoryNotificationCest.php on line 38',
            $exception->getMessage()
        );
    }

    public function notIsSetChangeStatus(UnitTester $I): void
    {
        $newStatus = 9999;
        $message = new NotifyMessage(NotifyMessage::EMAIL_TYPE, ['test' => 'execute'], NotifyMessage::STATUS_IN_QUEUE);
        $entity = new HistoryNotification($message, NotifyMessage::STATUS_ACTIVE, 'info message');

        try {
            $entity->setStatus($newStatus);
        } catch (\Throwable $exception) {}


        $I->assertNotEmpty($exception->getMessage());
    }

    public function positiveSetInfo(UnitTester $I): void
    {
        $info = 'info message';
        $message = new NotifyMessage(NotifyMessage::EMAIL_TYPE, ['test' => 'execute'], NotifyMessage::STATUS_IN_QUEUE);
        $entity = new HistoryNotification($message, NotifyMessage::STATUS_ACTIVE, $info);

        $I->assertEquals($info, $entity->getInfo());
    }

    public function checkIsNullInfo(UnitTester $I): void
    {
        $info = null;
        $message = new NotifyMessage(NotifyMessage::EMAIL_TYPE, ['test' => 'execute'], NotifyMessage::STATUS_IN_QUEUE);
        $entity = new HistoryNotification($message, NotifyMessage::STATUS_ACTIVE, $info);

        $I->assertEquals($info, $entity->getInfo());
    }

    public function checkId(UnitTester $I): void
    {
        $message = new NotifyMessage(NotifyMessage::EMAIL_TYPE, ['test' => 'execute'], NotifyMessage::STATUS_IN_QUEUE);
        $entity = new HistoryNotification($message, NotifyMessage::STATUS_ACTIVE, null);

        $I->assertNotNull($entity->getId()->toString());
    }

    public function checkCreatedAt(UnitTester $I): void
    {
        $message = new NotifyMessage(NotifyMessage::EMAIL_TYPE, ['test' => 'execute'], NotifyMessage::STATUS_IN_QUEUE);
        $entity = new HistoryNotification($message, NotifyMessage::STATUS_ACTIVE, null);

        $I->assertNotNull($entity->getCreatedAt());
    }
}