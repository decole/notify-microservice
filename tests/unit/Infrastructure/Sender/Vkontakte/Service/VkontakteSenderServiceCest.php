<?php


namespace App\Tests\unit\Infrastructure\Sender\Vkontakte\Service;


use App\Infrastructure\Sender\Vkontakte\Exception\VkontakteServiceException;
use App\Infrastructure\Sender\Vkontakte\Service\VkontakteSenderService;
use App\Tests\UnitTester;
use Faker\Factory;
use Faker\Generator;
use Throwable;

class VkontakteSenderServiceCest
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
            $service = new VkontakteSenderService($param, $param, $param);
        } catch (Throwable $exception) {}

        $I->assertEquals(true, isset($service));
        $I->assertInstanceOf(VkontakteSenderService::class, $service);
        $I->assertEquals(false, isset($exception));
    }

    public function negativeCreate(UnitTester $I): void
    {
        try {
            $service = new VkontakteSenderService();
        } catch (Throwable $exception) {}

        $I->assertEquals(false, isset($service));
        $I->assertEquals(true, isset($exception));
        $I->assertInstanceOf(VkontakteServiceException::class, $exception);
        $I->assertEquals('Please configure vkontakte access key', $exception->getMessage());
    }
}