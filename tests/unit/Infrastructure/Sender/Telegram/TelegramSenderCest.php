<?php


namespace App\Tests\unit\Infrastructure\Sender\Telegram;


use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Infrastructure\Sender\Telegram\Service\TelegramSenderService;
use App\Infrastructure\Sender\Telegram\TelegramSender;
use App\Tests\UnitTester;
use Codeception\Stub;
use Codeception\Stub\Expected;
use Faker\Factory;
use Faker\Generator;
use Psr\Log\NullLogger;
use Throwable;

class TelegramSenderCest
{
    private Generator $faker;

    public function setUp(): void
    {
        $this->faker = Factory::create();
    }

    public function positiveSend(UnitTester $I): void
    {
        $telegramSenderService = Stub::makeEmpty(TelegramSenderService::class, [
            'sendMessage' => Expected::once(),
        ]);
        $logger = $I->grabService(NullLogger::class);
        $sender = new TelegramSender($telegramSenderService, $logger);

        try {
            $sender->send($this->getNotify());
        } catch (Throwable $exception) {}

        $I->assertEquals(false, isset($exception));
    }

    public function negativeSend(UnitTester $I): void
    {
        $telegramSenderService = Stub::makeEmpty(TelegramSenderService::class, [
            'sendMessage' => '',
        ]);
        $logger = $I->grabService(NullLogger::class);
        $sender = new TelegramSender($telegramSenderService, $logger);

        try {
            $sender->send($this->getWrongNotify());
        } catch (Throwable $exception) {}

        $I->assertEquals('Undefined array key "userId"', $exception->getMessage());
    }

    public function negativeSendWithUserIdAndWithoutMessage(UnitTester $I): void
    {
        $telegramSenderService = Stub::makeEmpty(TelegramSenderService::class, [
            'sendMessage' => '',
        ]);
        $logger = $I->grabService(NullLogger::class);
        $sender = new TelegramSender($telegramSenderService, $logger);

        try {
            $sender->send($this->getWrongNotifyWithUserId());
        } catch (Throwable $exception) {}

        $I->assertEquals('Undefined array key "message"', $exception->getMessage());
    }

    private function getNotify(): NotifyMessage
    {
        return new NotifyMessage(
            NotifyMessage::TELEGRAM_TYPE,
            [
                'userId' => $this->faker->numberBetween(10000000, 99999999999),
                'message' => $this->faker->text,
                'test' => 'execute',
            ],
            NotifyMessage::STATUS_IN_QUEUE
        );
    }

    private function getWrongNotify(): NotifyMessage
    {
        return new NotifyMessage(
            NotifyMessage::TELEGRAM_TYPE,
            [
                'test' => 'execute',
            ],
            NotifyMessage::STATUS_IN_QUEUE
        );
    }

    private function getWrongNotifyWithUserId(): NotifyMessage
    {
        return new NotifyMessage(
            NotifyMessage::TELEGRAM_TYPE,
            [
                'userId' => $this->faker->numberBetween(10000000, 99999999999),
                $this->faker->word => $this->faker->word,
            ],
            NotifyMessage::STATUS_IN_QUEUE
        );
    }
}