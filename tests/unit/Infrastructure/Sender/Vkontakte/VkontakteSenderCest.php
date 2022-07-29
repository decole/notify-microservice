<?php


namespace App\Tests\unit\Infrastructure\Sender\Vkontakte;


use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Domain\Doctrine\NotifyMessage\Enum\NotifyStatusEnum;
use App\Domain\Doctrine\NotifyMessage\Enum\NotifyTypeEnum;
use App\Infrastructure\Sender\Vkontakte\Service\VkontakteSenderService;
use App\Infrastructure\Sender\Vkontakte\VkontakteSender;
use App\Tests\UnitTester;
use Codeception\Stub;
use Codeception\Stub\Expected;
use Faker\Factory;
use Faker\Generator;
use Psr\Log\NullLogger;
use Throwable;

class VkontakteSenderCest
{
    private Generator $faker;

    public function setUp(): void
    {
        $this->faker = Factory::create();
    }

    public function positiveSend(UnitTester $I): void
    {
        $service = Stub::makeEmpty(VkontakteSenderService::class, [
            'send' => Expected::once(),
        ]);
        $logger = $I->grabService(NullLogger::class);
        $sender = new VkontakteSender($service, $logger);

        try {
            $sender->send($this->getNotify());
        } catch (Throwable $exception) {}

        $I->assertEquals(false, isset($exception));
    }

    public function negativeSend(UnitTester $I): void
    {
        $service = Stub::makeEmpty(VkontakteSenderService::class, [
            'send' => Expected::once(),
        ]);
        $logger = $I->grabService(NullLogger::class);
        $sender = new VkontakteSender($service, $logger);

        try {
            $sender->send($this->getWrongNotify());
        } catch (Throwable $exception) {}

        $I->assertEquals('Undefined array key "message"', $exception->getMessage());
    }

    private function getNotify(): NotifyMessage
    {
        return new NotifyMessage(
            NotifyTypeEnum::VKONTAKTE->value,
            [
                'message' => $this->faker->text,
                'test' => 'execute',
            ],
            NotifyStatusEnum::IN_QUEUE->value
        );
    }

    private function getWrongNotify(): NotifyMessage
    {
        return new NotifyMessage(
            NotifyTypeEnum::VKONTAKTE->value,
            [
                'test' => 'execute',
            ],
            NotifyStatusEnum::IN_QUEUE->value
        );
    }
}