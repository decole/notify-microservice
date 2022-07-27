<?php


namespace App\Tests\unit\Infrastructure\Sender\Discord\Service;


use App\Infrastructure\Sender\Discord\Exception\DiscordServiceNullWebhookException;
use App\Infrastructure\Sender\Discord\Service\DiscordSenderService;
use App\Tests\UnitTester;
use Faker\Factory;
use Faker\Generator;
use Throwable;

class DiscordSenderServiceCest
{
    private Generator $faker;

    public function setUp(): void
    {
        $this->faker = Factory::create();
    }

    public function positiveStart(UnitTester $I): void
    {
        $param = $this->faker->word;

        try {
            $service = new DiscordSenderService($param);
        } catch (Throwable $exception) {}

        $I->assertEquals(true, isset($service));
        $I->assertInstanceOf(DiscordSenderService::class, $service);
        $I->assertEquals(false, isset($exception));
    }

    public function negativeCreate(UnitTester $I): void
    {
        try {
            $service = new DiscordSenderService();
        } catch (Throwable $exception) {}

        $I->assertEquals(false, isset($service));
        $I->assertEquals(true, isset($exception));
        $I->assertInstanceOf(DiscordServiceNullWebhookException::class, $exception);
        $I->assertEquals('Please configure discord webhook', $exception->getMessage());
    }
}
