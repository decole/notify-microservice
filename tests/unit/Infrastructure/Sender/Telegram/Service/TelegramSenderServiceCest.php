<?php


namespace App\Tests\unit\Infrastructure\Sender\Telegram\Service;


use App\Infrastructure\Sender\Telegram\Exception\TelegramServiceException;
use App\Infrastructure\Sender\Telegram\Service\TelegramSenderService;
use App\Tests\UnitTester;
use Faker\Factory;
use Faker\Generator;
use Psr\Log\NullLogger;

class TelegramSenderServiceCest
{
    private Generator $faker;

    public function setUp(): void
    {
        $this->faker = Factory::create();
    }

    public function positiveStart(UnitTester $I): void
    {
        $logger = $I->grabService(NullLogger::class);
        $token = $this->faker->word;

        try {
            $service = new TelegramSenderService($logger, $token);
        } catch (\Throwable $exception) {}

        $I->assertEquals(true, isset($service));
        $I->assertInstanceOf(TelegramSenderService::class, $service);
        $I->assertEquals(false, isset($exception));
    }

    public function negativeCreate(UnitTester $I): void
    {
        $logger = $I->grabService(NullLogger::class);

        try {
            $service = new TelegramSenderService($logger);
        } catch (TelegramServiceException $exception) {}

        $I->assertEquals(false, isset($service));
        $I->assertEquals(true, isset($exception));
        $I->assertEquals('Please configure telegram bot Token', $exception->getMessage());
    }
}