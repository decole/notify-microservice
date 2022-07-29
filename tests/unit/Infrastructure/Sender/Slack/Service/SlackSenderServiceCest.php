<?php


namespace App\Tests\unit\Infrastructure\Sender\Slack\Service;


use App\Infrastructure\Sender\Slack\Service\SlackSenderService;
use App\Tests\UnitTester;
use Faker\Factory;
use Faker\Generator;
use Throwable;

class SlackSenderServiceCest
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
            $service = new SlackSenderService($param, $param, $param);
        } catch (Throwable $exception) {}

        $I->assertEquals(true, isset($service));
        $I->assertInstanceOf(SlackSenderService::class, $service);
        $I->assertEquals(false, isset($exception));
    }

    public function negativeCreate(UnitTester $I): void
    {
        try {
            $service = new SlackSenderService();
        } catch (Throwable $exception) {}

        $I->assertEquals(false, isset($service));
        $I->assertEquals(true, isset($exception));
    }
}