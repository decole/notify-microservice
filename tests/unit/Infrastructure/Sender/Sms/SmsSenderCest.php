<?php


namespace App\Tests\unit\Infrastructure\Sender\Sms;


use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Domain\Doctrine\NotifyMessage\Enum\NotifyStatusEnum;
use App\Domain\Doctrine\NotifyMessage\Enum\NotifyTypeEnum;
use App\Infrastructure\Sender\Sms\Service\SmsProviderResolver;
use App\Infrastructure\Sender\Sms\SmsSender;
use App\Tests\UnitTester;
use Codeception\Stub;
use Codeception\Stub\Expected;
use Faker\Factory;
use Faker\Generator;
use Psr\Log\NullLogger;
use Throwable;

class SmsSenderCest
{
    private Generator $faker;

    public function setUp(): void
    {
        $this->faker = Factory::create();
    }

    public function positiveSend(UnitTester $I): void
    {
        $logger = $I->grabService(NullLogger::class);
        $service = Stub::constructEmpty(
            SmsSender::class,
            [
                'service' => new SmsProviderResolver('smspilot'),
                'logger' => $logger,
            ],
            [
                'send' => Expected::once(),
            ]
        );

        try {
            $service->send($this->getNotify());
        } catch (Throwable $exception) {}

        $I->assertEquals(false, isset($exception));
    }

    public function negativeSend(UnitTester $I): void
    {
        $logger = $I->grabService(NullLogger::class);
        $service = Stub::construct(
            SmsSender::class,
            [
                'service' => new SmsProviderResolver('smspilot'),
                'logger' => $logger,
            ]
        );

        try {
            $service->send($this->getWrongNotify());
        } catch (Throwable $exception) {}

        $I->assertEquals('Undefined array key "phone"', $exception->getMessage());
    }

    private function getNotify(): NotifyMessage
    {
        return new NotifyMessage(
            NotifyTypeEnum::SMS->value,
            [
                'message' => $this->faker->text,
                'phone' => $this->faker->phoneNumber,
                'test' => 'execute',
            ],
            NotifyStatusEnum::IN_QUEUE->value
        );
    }

    private function getWrongNotify(): NotifyMessage
    {
        return new NotifyMessage(
            NotifyTypeEnum::SMS->value,
            [
                'test' => 'execute',
            ],
            NotifyStatusEnum::IN_QUEUE->value
        );
    }
}