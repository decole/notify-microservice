<?php

namespace App\Tests\unit\Infrastructure\Sender\Slack;

use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Infrastructure\Sender\Slack\Service\SlackSenderService;
use App\Infrastructure\Sender\Slack\SlackSender;
use App\Tests\UnitTester;
use Codeception\Stub;
use Codeception\Stub\Expected;
use Faker\Factory;
use Faker\Generator;
use Psr\Log\NullLogger;
use Throwable;

class SlackSenderCest
{
    private Generator $faker;

    public function setUp(): void
    {
        $this->faker = Factory::create();
    }

    public function positiveSend(UnitTester $I): void
    {
        $service = Stub::makeEmpty(SlackSenderService::class, [
            'send' => Expected::once(),
        ]);
        $logger = $I->grabService(NullLogger::class);
        $sender = new SlackSender($service, $logger);

        try {
            $sender->send($this->getNotify());
        } catch (Throwable $exception) {}

        $I->assertEquals(false, isset($exception));
    }

    public function negativeSend(UnitTester $I): void
    {
        $service = Stub::makeEmpty(SlackSenderService::class, [
            'send' => Expected::once(),
        ]);
        $logger = $I->grabService(NullLogger::class);
        $sender = new SlackSender($service, $logger);

        try {
            $sender->send($this->getWrongNotify());
        } catch (Throwable $exception) {}

        $I->assertEquals('Undefined array key "message"', $exception->getMessage());
    }

    private function getNotify(): NotifyMessage
    {
        return new NotifyMessage(
            NotifyMessage::SLACK_TYPE,
            [
                'message' => $this->faker->text,
                'test' => 'execute',
            ],
            NotifyMessage::STATUS_IN_QUEUE
        );
    }

    private function getWrongNotify(): NotifyMessage
    {
        return new NotifyMessage(
            NotifyMessage::SLACK_TYPE,
            [
                'test' => 'execute',
            ],
            NotifyMessage::STATUS_IN_QUEUE
        );
    }
}