<?php


namespace App\Tests\unit\Domain\Doctrine\NotifyMessage\Entity;


use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Tests\UnitTester;

class NotifyMessageCest
{
    public function getEmailType(UnitTester $I): void
    {
        $message = new NotifyMessage(NotifyMessage::EMAIL_TYPE, ['test' => 'execute'], NotifyMessage::STATUS_IN_QUEUE);

        $I->assertEquals(NotifyMessage::EMAIL_TYPE, $message->getType());
    }

    public function getTelegramType(UnitTester $I): void
    {
        $message = new NotifyMessage(NotifyMessage::TELEGRAM_TYPE, ['test' => 'execute'], NotifyMessage::STATUS_IN_QUEUE);

        $I->assertEquals(NotifyMessage::TELEGRAM_TYPE, $message->getType());
    }

    public function getStatus(UnitTester $I): void
    {
        $message = new NotifyMessage(NotifyMessage::EMAIL_TYPE, ['test' => 'execute'], NotifyMessage::STATUS_IN_QUEUE);

        $I->assertEquals(NotifyMessage::STATUS_IN_QUEUE, $message->getStatus());
    }

    public function positiveSetStatus(UnitTester $I): void
    {
        $newStatus = NotifyMessage::STATUS_ERROR;
        $message = new NotifyMessage(NotifyMessage::EMAIL_TYPE, ['test' => 'execute'], NotifyMessage::STATUS_IN_QUEUE);
        $message->setStatus($newStatus);

        $I->assertEquals($newStatus, $message->getStatus());
    }

    public function negativeSetStatus(UnitTester $I): void
    {
        $newStatus = 'error status';
        $message = new NotifyMessage(NotifyMessage::EMAIL_TYPE, ['test' => 'execute'], NotifyMessage::STATUS_IN_QUEUE);

        try {
            $message->setStatus($newStatus);
        } catch (\Throwable $exception) {}

        $I->assertEquals(
            'App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage::setStatus(): Argument #1 ($status) must be of type int, string given, called in /var/www/tests/unit/Domain/Doctrine/NotifyMessage/Entity/NotifyMessageCest.php on line 48',
            $exception->getMessage()
        );
    }

    public function notIsSetEmailChangeStatus(UnitTester $I): void
    {
        $newStatus = 9999;
        $message = new NotifyMessage(NotifyMessage::EMAIL_TYPE, ['test' => 'execute'], NotifyMessage::STATUS_IN_QUEUE);

        try {
            $message->setStatus($newStatus);
        } catch (\Throwable $exception) {}

        $I->assertNotEmpty($exception->getMessage());
    }

    public function notIsSetTelegramChangeStatus(UnitTester $I): void
    {
        $newStatus = 9999;
        $message = new NotifyMessage(NotifyMessage::TELEGRAM_TYPE, ['test' => 'execute'], NotifyMessage::STATUS_IN_QUEUE);

        try {
            $message->setStatus($newStatus);
        } catch (\Throwable $exception) {}

        $I->assertNotEmpty($exception->getMessage());
    }

    public function getTextStatusInQueues(UnitTester $I): void
    {
        $message = new NotifyMessage(NotifyMessage::EMAIL_TYPE, ['test' => 'execute'], NotifyMessage::STATUS_IN_QUEUE);

        $I->assertEquals('in queue', $message->getTextStatus());
    }

    public function getTextStatusActive(UnitTester $I): void
    {
        $message = new NotifyMessage(NotifyMessage::EMAIL_TYPE, ['test' => 'execute'], NotifyMessage::STATUS_ACTIVE);

        $I->assertEquals('sending', $message->getTextStatus());
    }

    public function getTextStatusError(UnitTester $I): void
    {
        $message = new NotifyMessage(NotifyMessage::EMAIL_TYPE, ['test' => 'execute'], NotifyMessage::STATUS_ERROR);

        $I->assertEquals('error', $message->getTextStatus());
    }

    public function getTextStatusDone(UnitTester $I): void
    {
        $message = new NotifyMessage(NotifyMessage::EMAIL_TYPE, ['test' => 'execute'], NotifyMessage::STATUS_DONE);

        $I->assertEquals('sent', $message->getTextStatus());
    }

    public function checkUpdatedAtEmpty(UnitTester $I): void
    {
        $message = new NotifyMessage(NotifyMessage::EMAIL_TYPE, ['test' => 'execute'], NotifyMessage::STATUS_DONE);

        $I->assertEquals(null, $message->getUpdatedAt());
    }

    public function checkUpdatedAtIsSet(UnitTester $I): void
    {
        $message = new NotifyMessage(NotifyMessage::EMAIL_TYPE, ['test' => 'execute'], NotifyMessage::STATUS_DONE);
        $message->setUpdatedAt();

        $I->assertNotNull($message->getUpdatedAt());
    }

    public function getBody(UnitTester $I): void
    {
        $body = ['test' => 'execute'];
        $message = new NotifyMessage(NotifyMessage::EMAIL_TYPE, $body, NotifyMessage::STATUS_DONE);

        $I->assertEquals($body, $message->getBody());
    }

    public function getEmptyBody(UnitTester $I): void
    {
        $body = [];
        $message = new NotifyMessage(NotifyMessage::EMAIL_TYPE, $body, NotifyMessage::STATUS_DONE);

        $I->assertEquals($body, $message->getBody());
    }

    public function NullBody(UnitTester $I): void
    {
        $body = null;

        try {
            new NotifyMessage(NotifyMessage::EMAIL_TYPE, $body, NotifyMessage::STATUS_DONE);
        } catch (\Throwable $exception) {}

        $I->assertEquals(
            'App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage::__construct(): Argument #2 ($message) must be of type array, null given, called in /var/www/tests/unit/Domain/Doctrine/NotifyMessage/Entity/NotifyMessageCest.php on line 145',
            $exception->getMessage()
        );
    }
}