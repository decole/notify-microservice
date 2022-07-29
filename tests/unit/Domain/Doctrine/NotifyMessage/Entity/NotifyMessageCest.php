<?php


namespace App\Tests\unit\Domain\Doctrine\NotifyMessage\Entity;


use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Domain\Doctrine\NotifyMessage\Enum\NotifyStatusEnum;
use App\Domain\Doctrine\NotifyMessage\Enum\NotifyTypeEnum;
use App\Tests\UnitTester;

class NotifyMessageCest
{
    public function getEmailType(UnitTester $I): void
    {
        $message = new NotifyMessage(NotifyTypeEnum::EMAIL->value, ['test' => 'execute'], NotifyStatusEnum::IN_QUEUE->value);

        $I->assertEquals(NotifyTypeEnum::EMAIL->value, $message->getType());
    }

    public function getTelegramType(UnitTester $I): void
    {
        $message = new NotifyMessage(NotifyTypeEnum::TELEGRAM->value, ['test' => 'execute'], NotifyStatusEnum::IN_QUEUE->value);

        $I->assertEquals(NotifyTypeEnum::TELEGRAM->value, $message->getType());
    }

    public function getVkontakteType(UnitTester $I): void
    {
        $message = new NotifyMessage(NotifyTypeEnum::VKONTAKTE->value, ['test' => 'execute'], NotifyStatusEnum::IN_QUEUE->value);

        $I->assertEquals(NotifyTypeEnum::VKONTAKTE->value, $message->getType());
    }

    public function getSlackType(UnitTester $I): void
    {
        $message = new NotifyMessage(NotifyTypeEnum::SLACK->value, ['test' => 'execute'], NotifyStatusEnum::IN_QUEUE->value);

        $I->assertEquals(NotifyTypeEnum::SLACK->value, $message->getType());
    }

    public function getSmsType(UnitTester $I): void
    {
        $message = new NotifyMessage(NotifyTypeEnum::SMS->value, ['test' => 'execute'], NotifyStatusEnum::IN_QUEUE->value);

        $I->assertEquals(NotifyTypeEnum::SMS->value, $message->getType());
    }

    public function getStatus(UnitTester $I): void
    {
        $message = new NotifyMessage(NotifyTypeEnum::EMAIL->value, ['test' => 'execute'], NotifyStatusEnum::IN_QUEUE->value);

        $I->assertEquals(NotifyStatusEnum::IN_QUEUE->value, $message->getStatus());
    }

    public function positiveSetStatus(UnitTester $I): void
    {
        $newStatus = NotifyStatusEnum::ERROR->value;
        $message = new NotifyMessage(NotifyTypeEnum::EMAIL->value, ['test' => 'execute'], NotifyStatusEnum::IN_QUEUE->value);
        $message->setStatus($newStatus);

        $I->assertEquals($newStatus, $message->getStatus());
    }

    public function negativeSetStatus(UnitTester $I): void
    {
        $newStatus = 'error status';
        $message = new NotifyMessage(NotifyTypeEnum::EMAIL->value, ['test' => 'execute'], NotifyStatusEnum::IN_QUEUE->value);

        try {
            $message->setStatus($newStatus);
        } catch (\Throwable $exception) {}

        $I->assertEquals(true, str_contains(
            $exception->getMessage(),
            'App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage::setStatus(): Argument #1 ($status) must be of type int, string given, called in /var/www/tests/unit/Domain/Doctrine/NotifyMessage/Entity/NotifyMessageCest.php'
        ));
    }

    public function notIsSetEmailChangeStatus(UnitTester $I): void
    {
        $newStatus = 9999;
        $message = new NotifyMessage(NotifyTypeEnum::EMAIL->value, ['test' => 'execute'], NotifyStatusEnum::IN_QUEUE->value);

        try {
            $message->setStatus($newStatus);
        } catch (\Throwable $exception) {}

        $I->assertNotEmpty($exception->getMessage());
    }

    public function notIsSetTelegramChangeStatus(UnitTester $I): void
    {
        $newStatus = 9999;
        $message = new NotifyMessage(NotifyTypeEnum::TELEGRAM->value, ['test' => 'execute'], NotifyStatusEnum::IN_QUEUE->value);

        try {
            $message->setStatus($newStatus);
        } catch (\Throwable $exception) {}

        $I->assertNotEmpty($exception->getMessage());
    }

    public function getTextStatusInQueues(UnitTester $I): void
    {
        $message = new NotifyMessage(NotifyTypeEnum::EMAIL->value, ['test' => 'execute'], NotifyStatusEnum::IN_QUEUE->value);

        $I->assertEquals('in queue', $message->getTextStatus());
    }

    public function getTextStatusActive(UnitTester $I): void
    {
        $message = new NotifyMessage(NotifyTypeEnum::EMAIL->value, ['test' => 'execute'], NotifyStatusEnum::ACTIVE->value);

        $I->assertEquals('sending', $message->getTextStatus());
    }

    public function getTextStatusError(UnitTester $I): void
    {
        $message = new NotifyMessage(NotifyTypeEnum::EMAIL->value, ['test' => 'execute'], NotifyStatusEnum::ERROR->value);

        $I->assertEquals('error', $message->getTextStatus());
    }

    public function getTextStatusDone(UnitTester $I): void
    {
        $message = new NotifyMessage(NotifyTypeEnum::EMAIL->value, ['test' => 'execute'], NotifyStatusEnum::DONE->value);

        $I->assertEquals('sent', $message->getTextStatus());
    }

    public function checkUpdatedAtEmpty(UnitTester $I): void
    {
        $message = new NotifyMessage(NotifyTypeEnum::EMAIL->value, ['test' => 'execute'], NotifyStatusEnum::DONE->value);

        $I->assertEquals(null, $message->getUpdatedAt());
    }

    public function checkUpdatedAtIsSet(UnitTester $I): void
    {
        $message = new NotifyMessage(NotifyTypeEnum::EMAIL->value, ['test' => 'execute'], NotifyStatusEnum::DONE->value);
        $message->setUpdatedAt();

        $I->assertNotNull($message->getUpdatedAt());
    }

    public function getBody(UnitTester $I): void
    {
        $body = ['test' => 'execute'];
        $message = new NotifyMessage(NotifyTypeEnum::EMAIL->value, $body, NotifyStatusEnum::DONE->value);

        $I->assertEquals($body, $message->getBody());
    }

    public function getEmptyBody(UnitTester $I): void
    {
        $body = [];
        $message = new NotifyMessage(NotifyTypeEnum::EMAIL->value, $body, NotifyStatusEnum::DONE->value);

        $I->assertEquals($body, $message->getBody());
    }

    public function NullBody(UnitTester $I): void
    {
        $body = null;

        try {
            new NotifyMessage(NotifyTypeEnum::EMAIL->value, $body, NotifyStatusEnum::DONE->value);
        } catch (\Throwable $exception) {}

        $I->assertEquals(true, str_contains(
            $exception->getMessage(),
            'App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage::__construct(): Argument #2 ($message) must be of type array, null given, called in /var/www/tests/unit/Domain/Doctrine/NotifyMessage/Entity/NotifyMessageCest.php'
        ));
    }
}